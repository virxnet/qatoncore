<?php

namespace VirX\Qaton;

use VirX\Qaton\System;

final class App
{

    public $configOverrides;
    protected $system;

    /**
     * Construct new Qaton application
     *
     * @param string|null  $appPath
     * @param string|null  $basePath
     * @return void
     */
    public function __construct(string $appPath, string $basePath)
    {
        $this->system = new System();
        $this->system->setAppPath($appPath);
        $this->system->setBasePath($basePath);
        spl_autoload_register("\VirX\Qaton\System::appAutoload");
    }

    public function setConfig(array $config)
    {
        $this->configOverrides = $config;
    }

    public function run()
    {
        $this->system->setConfigOverrides($this->configOverrides);
        $this->system->boot();
    }
}
