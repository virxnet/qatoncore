<?php

// system utility tasks for development usage

namespace VirX\Qaton\Console;

use VirX\Qaton\Console;
use VirX\Qaton\Console\Create;
use VirX\Qaton\Auth;

class System extends Console
{
    public $system;
    public $config;
    public $options;

    public function __construct($system, array $options  = [])
    {
        $this->system = $system;
        $this->config = $this->system->config;
        $this->options = $options;
        $this->setHelp(__CLASS__);

        Console::output();
        Console::label(__CLASS__);
        Console::output();
    }

    public function config()
    {
        var_dump($this->config);
    }

    public function rebuildPaths()
    {
        Console::output('Rebuilding Paths...');

        foreach ($this->config['APP_PATHS'] as $path) {
            Console::outputNotice('Creating ' . $path);
            $this->mkDir($path);
        }

        Console::outputNotice('Completed Rebuilding Paths', 2, 1);
    }

    public function adminPanel()
    {

        Console::output('Installing Admin Panel...');

        $source = __DIR__ . DIRECTORY_SEPARATOR . 'System' . DIRECTORY_SEPARATOR
                        . 'adminPanel' . DIRECTORY_SEPARATOR;
        $res_map = [
            $source . 'controllers' . DIRECTORY_SEPARATOR . $this->config['APP_DASHBOARD_NAME'] . DIRECTORY_SEPARATOR =>
                $this->config['APP_PATHS']['CONTROLLERS'] . $this->config['APP_DASHBOARD_NAME'] . DIRECTORY_SEPARATOR,
            $source . 'views' . DIRECTORY_SEPARATOR . 'common'
                    . DIRECTORY_SEPARATOR . $this->config['APP_DASHBOARD_NAME'] . DIRECTORY_SEPARATOR =>
                $this->config['APP_PATHS']['VIEWS_COMMON'] . $this->config['APP_DASHBOARD_NAME'] . DIRECTORY_SEPARATOR,
            $source . 'views' . DIRECTORY_SEPARATOR . 'layouts'
                . DIRECTORY_SEPARATOR . $this->config['APP_DASHBOARD_NAME'] . DIRECTORY_SEPARATOR =>
                $this->config['APP_PATHS']['VIEWS_LAYOUTS'] . $this->config['APP_DASHBOARD_NAME'] . DIRECTORY_SEPARATOR,
            $source . 'views' . DIRECTORY_SEPARATOR . 'sections'
                . DIRECTORY_SEPARATOR . $this->config['APP_DASHBOARD_NAME'] . DIRECTORY_SEPARATOR =>
                $this->config['APP_PATHS']['VIEWS_SECTIONS'] . $this->config['APP_DASHBOARD_NAME'] . DIRECTORY_SEPARATOR,
            $source . 'public' . DIRECTORY_SEPARATOR . 'assets'
                . DIRECTORY_SEPARATOR . $this->config['APP_DASHBOARD_NAME'] . DIRECTORY_SEPARATOR =>
                $this->config['APP_PUBLIC_PATH'] . $this->config['APP_PUBLIC_ASSETS_PATH']
                . $this->config['APP_DASHBOARD_NAME'] . DIRECTORY_SEPARATOR
        ];

        if (isset($this->options['reinstall'])) {
                unset($this->options['reinstall']);
                $this->options['clean'] = true;
                $this->options['install'] = true;
                return $this->adminPanel();
        }

        if (isset($this->options['clean'])) {
            Console::outputWarn('!!! This will delete all your admin panel customizations. This can not be undone !!!');
            $clean = Console::input('Are you sure you want to delete the admin panel? (yes/no)');
            foreach ($res_map as $source => $target) {
                if ($clean === 'yes') {
                    Console::outputNotice('Deleting ' . $target);
                    $this->rmDir($target);
                }
            }

            Console::output();
        }

        if (isset($this->options['install'])) {
            Console::output('Installing Admin Control Panel...');
            $this->auth();
            Console::input('Press enter to install files');
            $create = new Create($this->system, []);
            $failed = false;
            foreach ($res_map as $source => $target) {
                if (realpath($target)) {
                    Console::outputWarn('Installation path is already in use for ' . $target);
                    $failed = true;
                } else {
                    $create->cloneDir($source, $target);
                }
            }
            if ($failed) {
                Console::outputNotice('To delete existing installation (files only) and reinstall, run again with --reinstall');
                Console::outputError('Installation Failed!', 2, 1);
            } else {
                Console::outputSuccess('Installation Completed!', 2, 1);
            }
        }

        return true;
    }

    public function auth()
    {
        if (isset($this->options['install'])) {
            Console::output('Installing Auth...');
            $auth = new Auth();
            if ($auth->install()) {
                Console::outputSuccess('Install Complete!', 2, 1);
            } else {
                Console::outputWarn('Installation Failed! Probably already installed or no database connection...', 2, 1);
            }
        }
    }

    public function install()
    {
        $install = true;
        foreach ($this->config['APP_PATHS'] as $path) {
            if (realpath($path)) {
                Console::outputWarn('App data already exists in ' . $path);
                $install = false;
            }
        }

        if ($install === false) {
            Console::outputError('Installation Failed! Run `System clean` to delete App data and auto reinstall', 2, 2);
            return false;
        }

        $this->rebuildPaths();

        Console::output('Installing Application...');

        require_once('Create.php');
        $create = new Create($this->system, $this->options);
        $create->options['view'] = true;
        $create->controller(['Errors/Error400']); // Bad Request
        $create->controller(['Errors/Error401']); // Unauthorized
        $create->controller(['Errors/Error403']); // Forbidden
        $create->controller(['Errors/Error404']); // Not Found
        $create->controller(['Errors/Error500']); // Internal Server Error

        Console::outputNotice('Completed Installing', 1, 0);
    }

    public function clean()
    {
        Console::outputWarn('!!! This will delete all application data and can not be undone !!!');
        $answer = Console::input('Are you sure you want to clean the application? (yes/no)');
        Console::output();

        if ($answer === 'yes') {
            Console::output('Cleaning Application...');
            foreach ($this->config['APP_PATHS'] as $path) {
                Console::outputNotice('Deleting ' . $path);
                $this->rmDir($path);
            }
            $other_paths = [
                $this->config['APP_PUBLIC_PATH'] . $this->config['APP_PUBLIC_ASSETS_PATH']
            ];
            foreach ($other_paths as $path) {
                Console::outputNotice('Deleting ' . $path);
                $this->rmDir($path);
            }
        }

        Console::outputNotice('Completed Cleaning', 1, 0);

        $this->install();

        Console::outputNotice('Completed Clean & Reinstall', 2, 1);
    }

    private function mkDir(string $directory)
    {
        Console::outputNotice('Creating ' . $directory);
        if (!@mkdir($directory, octdec($this->config['APP_DEV_CHMOD']), true)) {
            Console::outputError('Failed Creating ' . $directory);
        } else {
            Console::outputSuccess('Created ' . $directory);
        }
        @chmod($directory, octdec($this->config['APP_DEV_CHMOD']));
        @chown($directory, $this->config['APP_SERVER_USER']);
        @chgrp($directory, $this->config['APP_SERVER_GROUP']);
        return true;
    }

    private function rmDir(string $directory)
    {
        if (!realpath($directory)) {
            Console::outputSuccess('Already Deleted ' . $directory);
            return false;
        }

        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($files as $fileinfo) {
            $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
            $todo($fileinfo->getRealPath());
        }

        if (rmdir($directory)) {
            Console::outputSuccess('Deleted ' . $directory);
        } else {
            Console::outputError('Failed Deleting ' . $directory);
        }
    }

    public function __destruct()
    {
        Console::setColor('reset');
    }

}
