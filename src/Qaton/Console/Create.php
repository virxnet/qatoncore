<?php

// creates Controllers, Views, Models, Pages, Migrations and more

namespace VirX\Qaton\Console;

use VirX\Qaton\Console;

class Create extends Console
{
    public const DEFAULT_RESOURCE_TEMPLATE = 'default' . parent::PHP_EXT;
    public const DEFAULT_PAGE_TEMPLATE = 'default';

    public $resource_template = self::DEFAULT_RESOURCE_TEMPLATE;

    public function __construct($system, array $options = [])
    {
        $this->system = $system;
        $this->config = $this->system->config;
        $this->options = $options;
        $this->setHelp(__CLASS__);

        Console::output();
        Console::label(__CLASS__);
        Console::output();
    }

    public function blueprint(array $args)
    {
        //
    }
    
    public function migration(array $args)
    {
        if (!isset($this->options['template'])) {
            $this->setResourceTemplate($this->config['APP_DATABASE_TYPE']);
        }

        $table = false;
        if (!isset($this->options['table']) && $args == 1) {
            $table = mb_strtolower($this->options['table']);
        }

        $this->checkCommonRequirements($args, 'migration', false);

        $template_file = $this->getTemplateFile('migration');

        foreach ($args as $arg) {
            $migration = mb_strtolower(time() . '_' . $arg);

            Console::output("Creating Migration {$migration}...", 2, 1);

            switch ($this->config['APP_DATABASE_TYPE']) {
                case 'FileDatabase':
                    if ($table === false) {
                        $table = mb_strtolower($arg) . 's'; // TODO: implement proper pluralization
                    }
                    $template_data = array(
                        '/***MigrationDescription***/' => 'a migration for ' . $arg,
                        '/***MigrationNamespace***/' => 'App\\Database\\' . $this->config['APP_DATABASE_TYPE']
                                                        . '\\Migrations',
                        '/***MigrationClassName***/' => $this->getMigrationClassName($migration),
                        '/***MigrationTableName***/' => $table
                    );
                    $migration_file = $this->config['APP_PATHS']['FILEDATABASE_MIGRATIONS']
                                    . $migration . parent::PHP_EXT;
                    break;
            }

            $this->makeFromTemplate($template_file, $migration_file, $template_data, 'migration');
        }

        Console::outputNotice('Migration Creation Complete', 2, 1);
    }

    public function model(array $args)
    {
        if (!isset($this->options['template'])) {
            $this->setResourceTemplate($this->config['APP_DATABASE_TYPE']);
        }

        $table = false;
        if (!isset($this->options['table']) && $args == 1) {
            $table = mb_strtolower($this->options['table']);
        }

        $this->checkCommonRequirements($args, 'model', false);
        $template_file = $this->getTemplateFile('model');

        foreach ($args as $model) {
            $namespace = $this->getNamespaceByPath($model);
            $model_class = $this->getPascalCaseName((basename($model)));
            $model_path = pathinfo($model)['dirname'];
            if ($model_path === '.') {
                $model_path = null;
            } else {
                $model_path .= '/';
            }
            $model_file = $this->config['APP_PATHS']['MODELS']
                            . $model_path . $model_class . parent::PHP_EXT;

            Console::output("Creating Model {$model_file}...", 2, 1);

            if ($table === false) {
                $table = mb_strtolower($model) . 's'; // TODO: implement proper pluralization
            }

            $template_data = array(
                '/***ModelsBaseNamespace***/' => $this->config['BASE_NAMESPACE_MODELS'],
                '/***ModelNamespace***/' => $namespace,
                '/***ModelClassName***/' => $model_class,
                '/***ModelTableName***/' => $table
            );

            if (isset($this->options['migration'])) {
                $this->migration([$model]);
            }

            $this->makeFromTemplate($template_file, $model_file, $template_data, 'model');
        }

        Console::outputNotice('Model Creation Complete', 2, 1);
    }

    public function page(array $args)
    {
        $this->checkCommonRequirements($args, 'page', false);

        $page_template_name = $this->getPageTemplateName();

        if ($page_template = $this->getPageTemplate($page_template_name)) {
            foreach ($args as $page) {
                $this->setupPageViews('common', $page_template);
                $this->setupPageViews('layouts', $page_template);
                $this->setupPageViewSections($page, $page_template);
                $this->setupPageController($page, $page_template);
                $this->setupPublicResources($page, $page_template);
            }
        }

        Console::outputNotice('Page Creation Complete', 2, 1);
    }

    private function setupPublicResources($page, $page_template)
    {
        $this->cloneDir($page_template . 'public', $this->config['APP_PUBLIC_PATH']
                        . $this->config['APP_PUBLIC_ASSETS_PATH']);
    }

    public function view(array $args)
    {
        $this->checkCommonRequirements($args, 'view');

        foreach ($args as $view) {
            $view_file = $this->config['APP_PATHS']['VIEWS'] . $view . parent::PHP_EXT;

            Console::output("Creating View {$view_file}...", 2, 1);

            $template_data = array(
                'ViewName' => $view
            );

            $template_file = $this->getTemplateFile('view');

            $this->makeFromTemplate($template_file, $view_file, $template_data, 'view');
        }

        Console::outputNotice('View Creation Complete', 2, 1);
    }

    public function controller(array $args)
    {
        $this->checkCommonRequirements($args, 'controller');
        $template_file = $this->getTemplateFile('controller');

        foreach ($args as $controller) {
            $namespace = $this->getNamespaceByPath($controller);
            $controller_class = $this->getPascalCaseName(basename($controller));
            $controller_path = pathinfo($controller)['dirname'];
            if ($controller_path === '.') {
                $controller_path = null;
            } else {
                $controller_path .= '/';
            }
            $controller_file = $this->config['APP_PATHS']['CONTROLLERS']
                                . $controller_path . $controller_class . parent::PHP_EXT;

            Console::output("Creating Controller {$controller_file}...", 2, 1);

            $methods = $this->buildMethods($controller_class);

            $template_data = array(
                '/***ControllersBaseNamespace***/' => $this->config['BASE_NAMESPACE_CONTROLLERS'],
                '/***ControllerNamespace***/' => $namespace,
                '/***ControllerClassName***/' => $controller_class,
                '/***ControllerMethods***/' => $methods
            );

            if (isset($this->options['view'])) {
                $template_data['/***ViewData***/'] = '$data = [];';
                $template_data['/***ViewRender***/'] = "\n        " // new line and indent
                                . '$this->view->render("' . $controller_path . DIRECTORY_SEPARATOR
                                . mb_strtolower($controller_class) . '", $data);';
                if (isset($this->options['section']) && isset($this->options['layout'])) {
                    $template_data['/***ViewSection***/'] = "\n        " // new line and indent
                                . '$this->view->section("' . $this->options['section']
                                . '", "' . $this->options['layout'] . '", $data);';
                } else {
                    $this->view([$controller]);
                    $template_data['/***ViewSection***/'] = null;
                }
            } else {
                $template_data['/***ViewData***/'] = '//';
                $template_data['/***ViewRender***/'] = null;
                $template_data['/***ViewSection***/'] = null;
            }

            $this->makeFromTemplate($template_file, $controller_file, $template_data, 'controller');
        }

        Console::outputNotice('Controller Creation Complete', 2, 1);
    }

    private function checkCommonRequirements($args, $resource, $write_test = true)
    {
        if (empty($args[0])) {
            Console::outputError('You must specify a ' . $resource . ' name (path optional)');
            return false;
        }

        if ($write_test === true) {
            if (!$this->isWritable($this->config['APP_PATHS'][strtoupper($resource) . 'S'])) {
                return false;
            }
        }

        if (isset($this->options['template']) && (!is_string($this->options['template']))) {
            Console::outputError('You must specify a ' . $resource
                                . ' template (template=[/path/template]) with the --template option');
            return false;
        }
    }

    private function setupPageController($page, $page_template)
    {
        $this->options['view'] = true;
        $this->options['section'] = 'sections' . DIRECTORY_SEPARATOR . $page;
        $this->options['layout'] = 'layouts' . DIRECTORY_SEPARATOR . 'default';
        $this->options['template_file'] = $page_template . 'controller'
                                            . DIRECTORY_SEPARATOR . 'default' . parent::PHP_EXT;
        $this->controller([$page]);
    }

    private function setupPageViews($type, $page_template)
    {
        $views_template_base = $page_template . 'view' . DIRECTORY_SEPARATOR;
        foreach (glob($views_template_base . $type . DIRECTORY_SEPARATOR . '*') as $template) {
            $template_info = pathinfo($template);
            $this->options['template_file'] = $template;
            $this->view([$type . DIRECTORY_SEPARATOR . $template_info['filename']]);
        }
    }

    private function setupPageViewSections($page, $page_template)
    {
        $views_template_base = $page_template . 'view' . DIRECTORY_SEPARATOR . 'sections' . DIRECTORY_SEPARATOR;
        foreach (glob($views_template_base . '*') as $sections) {
            foreach (glob($sections . DIRECTORY_SEPARATOR . '*') as $section) {
                $section_info = pathinfo($section);
                $this->options['template_file'] = $section;
                $view = 'sections' . DIRECTORY_SEPARATOR . $page
                        //. mb_substr($section_info['dirname'], mb_strlen($views_template_base))
                        . DIRECTORY_SEPARATOR . $section_info['filename'];
                $this->view([$view]);
            }
        }
    }

    private function setResourceTemplate($template)
    {
        $this->resource_template = $template . parent::PHP_EXT;
    }

    private function getPageTemplate($page_template)
    {
        $page_template_path = $this->config['APP_PATHS']['TEMPLATES']
                    . 'page' . DIRECTORY_SEPARATOR . $page_template;

        if (!$this->isWritable($this->config['APP_PATHS']['TEMPLATES'])) {
            return false;
        }

        $system_page_template = __DIR__ . DIRECTORY_SEPARATOR . 'Create' . DIRECTORY_SEPARATOR
                                . 'page' . DIRECTORY_SEPARATOR . self::DEFAULT_PAGE_TEMPLATE;

        if (!realpath($page_template_path) && !is_dir($page_template_path)) {
            Console::outputNotice('Template does not exist. Creating ' . $page_template_path);
        }

        $this->cloneDir($system_page_template, $page_template_path);

        return $page_template_path . DIRECTORY_SEPARATOR;
        //
    }

    private function getPageTemplateName()
    {
        $page_template = self::DEFAULT_PAGE_TEMPLATE;
        if (isset($this->options['template'])) {
            $page_template = $this->options['template'];
        }

        return $page_template;
    }

    private function getTemplateFile($resource)
    {
        $template_file = __DIR__ . DIRECTORY_SEPARATOR . 'Create' . DIRECTORY_SEPARATOR
                            . $resource . DIRECTORY_SEPARATOR . $this->resource_template;
        if (isset($this->options['template'])) {
            $file = $this->config['APP_PATHS']['TEMPLATES'] . $resource
                            . DIRECTORY_SEPARATOR . $this->options['template'] . parent::PHP_EXT;
            if (file_exists($file)) {
                $template_file = $file;
            } else {
                $template_file = __DIR__ . DIRECTORY_SEPARATOR . 'Create' . DIRECTORY_SEPARATOR
                            . $resource . DIRECTORY_SEPARATOR . $this->options['template'] . parent::PHP_EXT;
            }
        }
        if (isset($this->options['template_file'])) {
            $template_file = $this->options['template_file'];
        }
        return $template_file;
    }

    private function makeFromTemplate($template, $target, $data, $type)
    {
        $type_label = ucfirst($type);

        switch ($type) {
            case 'controller':
            case 'model':
                //
                break;
            default:
                $target = pathinfo($target)['dirname'] . DIRECTORY_SEPARATOR
                            . mb_strtolower(pathinfo($target)['basename']);
        }

        if (realpath($target)) {
            Console::outputError($type_label . ' Already Exists ' . $target);
            return false;
        }

        if (realpath($template)) {
            Console::outputNotice($type_label . ' Using Template ' . $template);
            $template = file_get_contents(($template));
            $target_dir = pathinfo($target)['dirname'];
            foreach ($data as $find => $replace) {
                $template = str_replace($find, $replace, $template);
            }
            if (!realpath($target_dir)) {
                if (mkdir($target_dir, octdec($this->config['APP_DEV_CHMOD']), true)) {
                    chown($target_dir, $this->config['APP_SERVER_USER']);
                    chgrp($target_dir, $this->config['APP_SERVER_GROUP']);
                } else {
                    Console::outputError('Unable to create ' . $target_dir);
                    return false;
                }
            }
            if (is_writable($target_dir)) {
                if (file_put_contents($target, $template)) {
                    chmod($target, octdec($this->config['APP_DEV_CHMOD']));
                    chown($target, $this->config['APP_SERVER_USER']);
                    chgrp($target, $this->config['APP_SERVER_GROUP']);
                    Console::outputSuccess($type_label . ' Created ' . $target);
                } else {
                    Console::outputError($type_label . ' Not Created ' . $target);
                }
            } else {
                Console::outputError($type_label . ' Not Writable ' . $target_dir);
            }
        } else {
            Console::outputError($type_label . ' Template Not Found ' . $template);
        }
    }

    private function isWritable($path)
    {
        if (!is_writable($path)) {
            Console::outputError("Path {$path} is not writable as user `"
                    . @posix_getpwuid(@posix_geteuid())['name'] . '`');
            return false;
        } else {
            return true;
        }
    }

    private function buildMethods($class)
    {
        if (isset($this->options['methods'])) {
            if ($class === 'Index') {
                Console::outputWarn('Methods ignored because Index');
                return null;
            } else {
                // TODO: Parse : --methods:abc=a,b,c:xyz=x,y,z
                /*
                array(2) {
                    [0]=>
                    string(9) "abc=a,b,c"
                    [1]=>
                    string(9) "xyz=x,y,z"
                }
                */
                Console::outputWarn('Methods ignored because Not Supported Yet');
            }
        } else {
            Console::outputNotice('No Methods Defined');
            return null;
        }
    }

    private function getMigrationClassName(string $migration)
    {
        switch ($this->config['APP_DATABASE_TYPE']) {
            case 'FileDatabase':
                $migration = array_map('ucfirst', explode('_', $migration));
                return $this->config['APP_DEFAULT_MIGRATION_CLASS'] . implode('', $migration);
        }
    }

    private function getPascalCaseName(string $string)
    {
        return implode('', array_map('ucfirst', explode('_', $string)));
    }

    private function getNamespaceByPath($path)
    {
        $path_arr = explode('/', pathinfo($path)['dirname']);
        if ($path_arr[0] !== '.') {
            $path_arr = array_map('ucfirst', $path_arr);
            return '\\' . implode('\\', $path_arr);
        } else {
            return null;
        }
    }

    public function cloneDir($source, $dest)
    {
        Console::output("Cloning {$source} to {$dest}", 2, 1);

        if (!realpath($dest)) {
            Console::outputNotice($dest . ' Does Not Exist ');
            if (mkdir($dest, octdec($this->config['APP_DEV_CHMOD']), true)) {
                chown($dest, $this->config['APP_SERVER_USER']);
                chgrp($dest, $this->config['APP_SERVER_GROUP']);
                Console::outputSuccess('Created ' . $dest);
            } else {
                Console::outputError('Unable to create ' . $dest);
            }
        } else {
            Console::outputNotice($dest . ' Already Exists ');
        }

        foreach (
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::SELF_FIRST
            ) as $item
        ) {
            $sub_dest = $dest . DIRECTORY_SEPARATOR . $iterator->getSubPathName();
            if ($item->isDir()) {
                if (!realpath($sub_dest)) {
                    if (mkdir($sub_dest, octdec($this->config['APP_DEV_CHMOD']), true)) {
                        chown($dest, $this->config['APP_SERVER_USER']);
                        chgrp($dest, $this->config['APP_SERVER_GROUP']);
                        Console::outputSuccess('Created ' . $sub_dest);
                    } else {
                        Console::outputError('Unable to create ' . $dest);
                    }
                } else {
                    Console::outputNotice($sub_dest . ' Already Exists ');
                }
            } else {
                if (!realpath($sub_dest)) {
                    if (copy($item, $sub_dest)) {
                        chown($dest, $this->config['APP_SERVER_USER']);
                        chgrp($dest, $this->config['APP_SERVER_GROUP']);
                        Console::outputSuccess('Created ' . $sub_dest);
                    } else {
                        Console::outputError('Unable to create ' . $sub_dest);
                    }
                } else {
                    Console::outputNotice($sub_dest . ' Already Exists ');
                }
            }
        }
    }
}
