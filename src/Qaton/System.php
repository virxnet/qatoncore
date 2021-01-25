<?php

namespace VirX\Qaton;

use VirX\Qaton\Error;
use VirX\Qaton\Http;
use VirX\Qaton\Request;
use VirX\Qaton\Console;

final class System
{

    /**
     * Framework Package Name
     *
     * @var string
     */
    public const PACKAGE = 'VirX Qaton';

    /**
     * Framework Package Description
     *
     * @var string
     */
    public const DESCRIPTION = 'The Elemental PHP MVC Framework';

    /**
     * Framework Package Version
     *
     * @var string
     */
    public const VERSION = '1.1.4';

    /**
     * Framework Package Release Date
     *
     * @var string
     */
    public const RELEASE_DATE = '2021-01-24';

    /**
     * Framework Package Author
     *
     * @var string
     */
    public const AUTHOR = 'Antony Shan Peiris <asp@virx.net>';

    /**
     * Framework Package Website
     *
     * @var string
     */
    public const WEBSITE = 'http://qaton.virx.net';

    /**
     * Environment Config Key
     *
     * @var string
     */
    private const ENV_CONFIG_KEY = 'QATON_CONFIG';

    public const PHP_EXT = '.php';

    /**
     * Config Defaults
     *
     * @var array
     */
    public const CONFIG_SYSTEM_DEFAULTS = [
        'APP_PATH'                          => null,
        'BASE_PATH'                         => null,
        'APP_PATH_RELATIVE_TO_BASE'         => true,
        'APP_PATHS'                         => [
            'CONTROLLERS'                       => 'Controllers' . DIRECTORY_SEPARATOR,
            'VIEWS'                             => 'Views' . DIRECTORY_SEPARATOR,
            'DATABASE'                          => 'Database' . DIRECTORY_SEPARATOR,
            'FILEDATABASE'                      => 'Database'  . DIRECTORY_SEPARATOR .  'FileDatabase'
                                                    . DIRECTORY_SEPARATOR .  'Databases' . DIRECTORY_SEPARATOR,
            'FILEDATABASE_MIGRATIONS'           => 'Database'  . DIRECTORY_SEPARATOR .  'FileDatabase'
                                                    . DIRECTORY_SEPARATOR .  'Migrations' . DIRECTORY_SEPARATOR,
            'TEMPLATES'                         => 'Templates' . DIRECTORY_SEPARATOR,
            'BLUEPRINTS'                        => 'Blueprints' . DIRECTORY_SEPARATOR,
            'CONFIG'                            => 'Config' . DIRECTORY_SEPARATOR,
            'MODELS'                            => 'Models' . DIRECTORY_SEPARATOR,
            'HELPERS'                           => 'Helpers' . DIRECTORY_SEPARATOR,
            'STORAGE'                           => 'Storage' . DIRECTORY_SEPARATOR,
            'CACHE'                             => 'Storage'  . DIRECTORY_SEPARATOR .  'Cache' . DIRECTORY_SEPARATOR,
        ],
        'APP_VIEWS_SPECIAL_PATHS'            => [
            'COMMON'                      => 'common' . DIRECTORY_SEPARATOR,
            'LAYOUTS'                     => 'layouts' . DIRECTORY_SEPARATOR,
            'SECTIONS'                    => 'sections' . DIRECTORY_SEPARATOR,
        ],
        'APP_PUBLIC_PATH'                   => 'public' . DIRECTORY_SEPARATOR,
        'APP_PUBLIC_ASSETS_PATH'            => 'assets' . DIRECTORY_SEPARATOR,
        'APP_DATABASE_TYPE'                 => 'FileDatabase',
        'APP_DATABASE'                      => [
            'NAME'                              => 'default_database',
            'MIGRATIONS'                        => 'filedatabase_migrations'
        ],
        'APP_DATABASE_OPTIONS'              => [],
        'APP_AUTO_USE_DATABASE'             => true,
        'APP_AUTH_TABLE'                    => 'users',
        'APP_AUTH'                          => [
            'USERS_TABLE'                       => 'users',
            'USER_MODEL'                        => 'user',
            'ACTIVE_USERS_TABLE'                => 'activeusers',
            'ACTIVE_USER_MODEL'                 => 'ActiveUser',
            'INITIAL_USER_DEFAULTS' => [
                'LEVEL'                             => 1,
                'USERNAME'                          => 'admin',
                'EMAIL'                             => 'admin@example.com',
                'PASSWORD'                          => 'password',
                'FIRSTNAME'                         => 'Admin',
                'LASTNAME'                          => 'Prime'
            ],
            'SESSION_NAME'                      => 'QatonActiveUser',
            'COOKIE_NAME'                       => 'QatonUserAuthenticationSession',
            'COOKIE_EXPIRY'                     => 3600 * 48
        ],
        'APP_DASHBOARD_NAME'                => 'admin',
        'APP_DEFAULT_CONTROLLER'            => 'index',
        'APP_DEFAULT_MIGRATION_CLASS'       => 'AppMigration',
        'APP_DEFAULT_METHOD'                => 'index',
        'APP_FALLBACK_CONTROLLER'           => 'Errors/Error404',
        'APP_PROTOCOL'                      => 'auto',
        'APP_URL_SUB_DIR'                   => '/',
        'APP_SERVER_USER'                   => 'www-data',
        'APP_SERVER_GROUP'                  => 'www-data',
        'APP_DEV_CHMOD'                     => '777',
        'APP_DEBUG'                         => true,
        'APP_PRODUCTION_MODE'               => false,
        'APP_OUTPUT_MODE'                   => null,
    ];

    /**
     * System Helpers
     *
     * @var array
     */
    private const SYSTEM_HELPERS = [
        'env',
        'debug',
        'number',
        'common'
    ];

    public $appPath;
    public $basePath;
    public $httpProtocol;
    public $baseUrl;
    public $outputMode;
    public $config;
    public $configOverrides;

    public $request;
    public $router;

    private $start_time;
    private $start_memory;
    private $end_memory;
    private $end_time;

    public function __construct()
    {
        $this->start_time = microtime(true);
        $this->start_memory = memory_get_usage();
        $this->loadHelpers();
    }

    public function boot()
    {
        $this->initConfig();
        __clear_debug(1); // TODO: clean this up
        __clear_debug(2);
        __clear_debug(3);
        $this->setOutputMode();

        if ($this->outputMode === 'cli') {
            $this->getCliResource();
        } else {
            $this->getHttpResource();
        }
    }

    public function setConfigOverrides(array $config)
    {
        $this->configOverrides = $config;
    }

    public function getHttpResource()
    {
        //
        $this->setBaseUrl();
        $this->request = new Request($this->config['APP_URL_SUB_DIR']);
        $this->router = new Router($this->request, $this->config);
        $this->attachControllerMagicProperties();
        $this->response = new Response($this->router);
        $this->response->send($this->outputMode);

        $this->end_memory = memory_get_usage();
    }

    public function attachControllerMagicProperties()
    {
        $this->router->controller['instance']->request = $this->request;
 
        $this->router->controller['instance']->view = new View();
        $this->router->controller['instance']->view->setBaseUrl($this->baseUrl);
        $this->router->controller['instance']->view->setPagePath($this->request->path);
        $this->router->controller['instance']->view->setPageName($this->router->class);
        $this->router->controller['instance']->view->setViewsPath($this->config['APP_PATHS']['VIEWS']);

        if ($this->config['APP_AUTO_USE_DATABASE'] === true) {
            $this->router->controller['instance']->db = Db::init($this->config);
        }
    }

    public function initConfig()
    {
        $_ENV[self::ENV_CONFIG_KEY] = self::CONFIG_SYSTEM_DEFAULTS;
        $this->config = $_ENV[self::ENV_CONFIG_KEY];
        $this->setConfig('APP_PATH', $this->appPath);
        $this->setConfig('BASE_PATH', $this->basePath);
        $this->setConfig('BASE_NAMESPACE_CONTROLLERS', $this->getBaseNamespaceControllers());
        $this->setConfig('BASE_NAMESPACE_MODELS', $this->getBaseNamespaceModels());

        if (!empty($this->configOverrides)) {
            foreach ($this->configOverrides as $key => $value) {
                $this->setConfig($key, $value);
            }
        }

        $this->setConfig('APP_PUBLIC_PATH', $this->setBasePublicPath());

        $this->parseConfigAppPaths();
        $this->initAppConfigs();
    }

    public function initAppConfigs()
    {
        //
    }

    public function parseConfigAppPaths()
    {
        if (!empty($this->config['APP_PATHS'])) {
            if ($this->config['APP_PATH_RELATIVE_TO_BASE'] === true) {
                foreach ($this->config['APP_PATHS'] as $key => $path) {
                    $this->config['APP_PATHS'][$key] = $this->appPath . $path;
                }
            }
        }
        if (!empty($this->config['APP_VIEWS_SPECIAL_PATHS'])) {
            if ($this->config['APP_PATH_RELATIVE_TO_BASE'] === true) {
                foreach ($this->config['APP_VIEWS_SPECIAL_PATHS'] as $key => $path) {
                    $this->config['APP_PATHS']['VIEWS_' . $key] = $this->config['APP_PATHS']['VIEWS'] . $path;
                }
            }
        }
        $_ENV[self::ENV_CONFIG_KEY] = $this->config;
    }

    public function setConfig($key, $value)
    {
        if (is_array($value)) {
            foreach ($value as $sub_key => $sub_value) {
                $_ENV[self::ENV_CONFIG_KEY][$key][$sub_key] = $sub_value;
            }
        } else {
            $_ENV[self::ENV_CONFIG_KEY][$key] = $value;
        }

        $this->config = $_ENV[self::ENV_CONFIG_KEY];
    }

    public function getCliResource()
    {
        new Console($this);
    }

    public function setOutputMode()
    {
        if (php_sapi_name() === 'cli') {
            $this->outputMode = 'cli';
        } else {
            $headers = Http::getHeaders();
            if (isset($headers['Accept'])) {
                switch ($headers['Accept']) {
                    case 'application/json':
                        $this->outputMode = 'json';
                        break;
                    case 'text/plain':
                        $this->outputMode = 'text';
                        break;
                    default:
                        $this->outputMode = 'html';
                }
            } else {
                $this->outputMode = 'html';
            }
        }

        $this->setConfig('APP_OUTPUT_MODE', $this->outputMode);
    }

    public function getBaseNamespaceControllers()
    {
        return basename($this->config['APP_PATH']) . "\\"
                . basename($this->config['APP_PATHS']['CONTROLLERS']);
    }

    public function getBaseNamespaceModels()
    {
        return basename($this->config['APP_PATH']) . "\\"
                . basename($this->config['APP_PATHS']['MODELS']);
    }

    public function setBaseUrl()
    {
        if ($this->config['APP_PROTOCOL'] == 'auto') {
            switch (Http::server('REQUEST_SCHEME')) {

                case 'http':
                    $this->httpProtocol = 'http://';
                    break;
    
                case 'https':
                    $this->httpProtocol = 'https://';
                    break;
    
                default:
                    $this->httpProtocol = (!empty(Http::server('HTTPS')) && Http::server('HTTPS') !== 'off'
                        || Http::server('SERVER_PORT') == 443) ? "https://" : "http://";
                        
            }
        } else {
            $this->httpProtocol = $this->config['APP_PROTOCOL'];
        }
        
        $this->baseUrl = $this->httpProtocol
                        . Http::server('HTTP_HOST')
                        . $this->config['APP_URL_SUB_DIR'];
        $this->setConfig('BASE_URL', $this->baseUrl);
    }

    public function setAppPath($path)
    {
        if (!$this->appPath = realpath($path)) {
            throw new Error(null, ['path' => $path], 1001, __LINE__, __METHOD__, __CLASS__, __FILE__);
        }
        $this->appPath .= DIRECTORY_SEPARATOR;
    }

    public function setBasePath($path)
    {
        if (!$this->basePath = realpath($path)) {
            throw new Error(null, ['path' => $path], 1001, __LINE__, __METHOD__, __CLASS__, __FILE__);
        }
        $this->basePath .= DIRECTORY_SEPARATOR;
    }

    public function setBasePublicPath()
    {
        if ($public = realpath($this->basePath . DIRECTORY_SEPARATOR . $this->config['APP_PUBLIC_PATH'])) {
            return $public . DIRECTORY_SEPARATOR;
        } else {
            return $this->config['APP_PUBLIC_PATH'];
        }
    }

    public function loadHelpers()
    {
        foreach (self::SYSTEM_HELPERS as $helper) {
            include_once __DIR__ . DIRECTORY_SEPARATOR 
                        . 'Helpers' . DIRECTORY_SEPARATOR 
                        . $helper . self::PHP_EXT ;
        }
    }


    public static function appAutoload ($class_Name) 
    {
        $file = $_ENV['QATON_CONFIG']['APP_PATH'] 
        . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR
        . str_replace("\\", DIRECTORY_SEPARATOR, $class_Name)
        . self::PHP_EXT;
        if (realpath($file)) {
            include_once $file;
        }
        
    }


    public function __destruct()
    {
        $this->end_time = microtime(true);
        $banner =   ' ! ! ! ! ! '
                    . self::PACKAGE . ' v' . self::VERSION
                    . ' by ' . self::AUTHOR
                    . ' ' . self::WEBSITE
                    . ' ! ! ! ! ! ';
        __debug($banner, 'QATON', 1);
        __debug($this, 'SYSTEM @' .  __METHOD__, 1);
        __debug(($this->end_time - $this->start_time), 'EXECUTION TIME', 1);
        __debug(_formatBytes(($this->end_memory - $this->start_memory)), 'MEMORY USAGE', 1);
        __debug(
            [
                'start' => $this->start_memory,
                'end' => $this->end_memory,
                'usage' => ($this->end_memory - $this->start_memory)
            ],
            'MEMORY USAGE DETAIL',
            1
        );
        __debug(debug_backtrace(), 'BACKTRACE', 2);
        __debug(@$_SESSION, 'SESSION', 2);
        __debug($_SERVER, 'SERVER', 2);
        __debug($_ENV, 'ENV', 2);
        __debug($banner, 'QATON', 2);
        __print_debug(1);
        __print_debug(2);
    }
}
