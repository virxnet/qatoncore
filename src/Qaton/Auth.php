<?php

namespace VirX\Qaton;

use VirX\Qaton\Session;
use VirX\Qaton\Request;
use VirX\Qaton\HttpHeaders;

// TODO: this only supports FileDatabase, need to make it more versatile

final class Auth
{
    protected $db;

    public function __construct()
    {
        $this->config = $_ENV['QATON_CONFIG'];
    }

    public static function guard(string $redirect)
    {
        if (self::isAuthenticated() === false) {
            HttpHeaders::redirect($redirect);
        } else {
            return true;
        }
    }

    public static function isAuthenticated()
    {
        $user = Session::get($_ENV['QATON_CONFIG']['APP_AUTH']['SESSION_NAME']);
        if (!is_null($user)) {
            $user_key = $_COOKIE[$_ENV['QATON_CONFIG']['APP_AUTH']['COOKIE_NAME']];
            $db = Db::init($_ENV['QATON_CONFIG']);
            $active = $db->table($_ENV['QATON_CONFIG']['APP_AUTH']['ACTIVE_USERS_TABLE'])
                            ->where('user', $user['id'])
                            ->verifyHashed('key', $user_key)
                            ->first();
            if (is_array($active) && isset($active['key'])  && $active['key'] === true) {
                return true;
            }
        }

        return false;
    }

    public static function isAdmin()
    {
        $instance = new static();
        return $instance::restrictTo(1);
    }

    public static function restrictTo(int $max_user_level)
    {
        $instance = new static();
        $user = $instance::user();
        if ($user['level'] > $max_user_level) {
            return false;
        } else {
            return true;
        }
    }

    public static function user()
    {
        $instance = new static();
        return Session::get($instance->config['APP_AUTH']['SESSION_NAME']);
    }

    
    public static function decrypt(string $string, string $salt, string $hash)
    {
        return password_verify($string . $salt, $hash);
    }

    public static function encrypt(string $string, $salt = false)
    {
        if ($salt === false) {
            $salt = time() . uniqid(rand(1000000000000000, 9999999999999999));
        }
        $options = [
            'cost' => 12,
        ];
        return [
            'hash' => password_hash($string . $salt, PASSWORD_BCRYPT, $options),
            'salt' => $salt
        ];
    }

    public static function logout(string $redirect)
    {
        $instance = new static();
        if ($instance::isAuthenticated()) {
            $user = $instance::user();
            $instance->db = Db::init($instance->config);
            $instance->db->table($instance->config['APP_AUTH']['ACTIVE_USERS_TABLE'])
                            ->where('user', $user['id'])
                            ->purge();
            $logout = Session::unset($instance->config['APP_AUTH']['SESSION_NAME']);
        }

        if (is_null($redirect)) {
            return $logout;
        } else {
            HttpHeaders::redirect($redirect);
        }
    }

    public static function login(string $redirect = null)
    {
        $instance = new static();
        $request = new Request($instance->config['APP_URL_SUB_DIR']);

        if (!isset($request->post['username']) || !isset($request->post['password'])) {
            return false;
        }

        $instance->db = Db::init($instance->config);
        $instance->db->table($instance->config['APP_AUTH']['USERS_TABLE']);
        $user = $instance->db->select('*')
                            ->where('username', $request->post['username'])
                            ->verifyHashed('password', $request->post['password'])
                            ->first();
        
        if (empty($user)) {
            return false;
        }

        if (is_null($user['password']) || $user['password'] === false) {
            return false;
        }


        $rand = microtime(true) . rand(0, 9999999999);
        Session::set($instance->config['APP_AUTH']['SESSION_NAME'], $user);
        setcookie(
            $instance->config['APP_AUTH']['COOKIE_NAME'],
            $rand,
            time() + $instance->config['APP_AUTH']['COOKIE_EXPIRY']
        );
        $instance->db->table($instance->config['APP_AUTH']['ACTIVE_USERS_TABLE'])
                    ->where('user', $user['id'])
                    ->purge();
        // TODO: evaluate security 
        $instance->db->table($instance->config['APP_AUTH']['ACTIVE_USERS_TABLE'])
                    ->insert([
                        'user' => $user['id'],
                        'key' => $rand,
                        'since' => time()
                    ]);
        if (is_null($redirect)) {
            return true;
        } else {
            HttpHeaders::redirect($redirect);
        }
   

    }

    public function install()
    {
        $this->db = Db::init($this->config);
        if (
            $this->db->table($this->config['APP_AUTH']['USERS_TABLE'])
                        ->create([
                            'level' => ['type' => 'integer', 'null' => false],
                            'username' => ['type' => 'string', 'null' => false, 'unique' => true],
                            'email' => ['type' => 'string', 'unique' => true],
                            'password' => ['type' => 'hashed', 'null' => false],
                            'first_name' => ['type' => 'string'],
                            'last_name' => ['type' => 'string'],
                        ], [
                            'timestamps' => true
                        ])
        ) {
            $this->db->insert([
                'level' => $this->config['APP_AUTH']['INITIAL_USER_DEFAULTS']['LEVEL'],
                'username' => $this->config['APP_AUTH']['INITIAL_USER_DEFAULTS']['USERNAME'],
                'email' => $this->config['APP_AUTH']['INITIAL_USER_DEFAULTS']['EMAIL'],
                'password' => $this->config['APP_AUTH']['INITIAL_USER_DEFAULTS']['PASSWORD'],
                'first_name' => $this->config['APP_AUTH']['INITIAL_USER_DEFAULTS']['FIRSTNAME'],
                'last_name' => $this->config['APP_AUTH']['INITIAL_USER_DEFAULTS']['LASTNAME']
            ]);
            $this->db->table($this->config['APP_AUTH']['ACTIVE_USERS_TABLE'])
                    ->create([
                        'user' => [
                                    'type' => 'foreign',
                                    'foreign' => $this->config['APP_AUTH']['USERS_TABLE'],
                                    'key' => 'id'
                                ],
                        'key' => ['type' => 'hashed', 'null' => false],
                        'since' => ['type' => 'timestamp']
                    ]);
            return true;
        } else {
            return false;
        }
    }
}
