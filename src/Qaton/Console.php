<?php

namespace VirX\Qaton;

class Console
{
    public const CONSOLE_MODULES_PATH = __DIR__ . DIRECTORY_SEPARATOR . 'Console' . DIRECTORY_SEPARATOR;
    public const CONSOLE_NAMESPACE = __NAMESPACE__ . '\\Console\\';
    public const MODULE_HELP_FILE = 'help.txt';
    public const PHP_EXT = '.php';

    public $system;
    public $config;
    public $args;
    public $argc;
    public $modules;
    public $options;
    public $module_file;
    public $modile_name;
    public $module;
    public $module_instance;
    public $method;
    public $help;

    public function __construct($system)
    {
        $this->system = $system;
        $this->config = $this->system->config;
        $this->welcome();
        $this->setArgs();
        $this->setModules();

        if (!empty($this->args)) {
            $this->runModule();
        } else {
            $this->listModules();
        }
    }

    public function runModule()
    {
        $this->setOptions();
        if ($this->setModule() !== false) {
            $this->module_instance = new $this->module($this->system, $this->options);
            $this->runMethod();
        }
    }

    public function runMethod()
    {
        $this->setMethod();

        if ($this->method) {
            $this->setOptions();
            call_user_func_array(array($this->module_instance, $this->method), array_values(array($this->args)));
        }

        if (isset($this->options['help'])) {
            $this->module_instance->printHelp($this->module);
        }
    }

    public function setMethod()
    {
        if (!empty($this->args)) {
            if (is_null($this->args[0]) || trim($this->args[0]) == '') {
                return;
            }
            if (method_exists($this->module_instance, $this->args[0])) {
                $this->method = array_shift($this->args);
                return;
            } else {
                self::outputError("Unknown Argument: {$this->args[0]}", 2, 1);
            }
        }
        $this->method = false;
    }

    public function setModule()
    {
        if (!$this->module_file = realpath(self::CONSOLE_MODULES_PATH . $this->args[0] . self::PHP_EXT)) {
            self::outputError("Qaton Command Module `{$this->args[0]}` Not Found", 2, 1);
            return false;
        }
        $this->module_name = array_shift($this->args);
        require_once($this->module_file);
        $this->module = self::CONSOLE_NAMESPACE . $this->module_name;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function setOptions()
    {
        // auto parsing instead of using getopt()
        if (!empty($this->args)) {
            foreach ($this->args as $key => $arg) {
                if (mb_substr($arg, 0, 2) == '--') {
                    unset($this->args[$key]);
                    // --option:...
                    $option = explode(':', $arg);
                    if (count($option) > 1) {
                        $opt_key = str_replace('--', '', array_shift($option));
                        $this->options[$opt_key] = $option;
                        foreach ($option as $key => $value) {
                            $sub_option = explode('=', $value);
                            if (count($sub_option) > 1) {
                                // --option:key1=val1:key2=val2:key3=val3
                                /* array(1) {
                                ["option"]=>
                                array(3) {
                                    ["key1"]=>
                                    string(4) "val1"
                                    ["key2"]=>
                                    string(4) "val2"
                                    ["key3"]=>
                                    string(4) "val3"
                                }
                                } */
                                $this->options[$opt_key][array_shift($sub_option)] = implode('=', $sub_option);
                                unset($this->options[$opt_key][$key]);
                            } else {
                                // --option:value1:value2:value3
                                /* array(1) {
                                ["option"]=>
                                array(3) {
                                    [0]=>
                                    string(6) "value1"
                                    [1]=>
                                    string(6) "value2"
                                    [2]=>
                                    string(6) "value3"
                                }
                                } */
                                $this->options[$opt_key][$key] = $sub_option[0];
                            }
                        }
                    } else {
                        $option = explode('=', str_replace('--', '', $arg));
                        if (count($option) > 1) {
                            // --option=value
                            /* array(1) {
                            ["option"]=>
                            string(5) "value"
                            } */
                            $this->options[array_shift($option)] = implode('=', $option);
                        } else {
                            // --option
                            /* array(1) {
                            ["option"]=>
                            string(5) "value"
                            } */
                            $this->options[array_shift($option)] = true;
                        }
                    }
                }
            }
        }

        if (!is_array($this->options)) {
            $this->options = [];
        }
    }

    public function setArgs()
    {
        $this->args = $this->server('argv');
        $this->argc = $this->server('argc');
        array_shift($this->args);
    }

    public function setModules()
    {
        $this->modules = glob(self::CONSOLE_MODULES_PATH . '*' . self::PHP_EXT);
    }

    public function listModules()
    {
        $this->label("Available Command Modules");
        self::output();
        foreach ($this->modules as $module) {
            self::output(self::setColor('yellow') . " - " . pathinfo($module)['filename'] . "\t: ", 0, 0);
            //self::output(self::setColor('light_cyan') . str_replace('<?php // ', '', fgets(fopen($module, 'r'))));
            self::output(self::setColor('light_cyan') 
                    . str_replace('// ', '', array_slice(file($module), 2, 3)[0]), 0, 0);
            self::setColor('default');
        }
        self::output();
    }

    public function printHelp($for)
    {
        Console::label("Help For " . $for);
        if (realpath($this->help)) {
            self::setColor('white');
            self::output(file_get_contents($this->help));
        } else {
            self::output("No Help File");
        }

    }

    public function setHelp(string $class)
    {
        $class = explode('\\', $class);
        $class = array_pop($class);
        $this->help = self::CONSOLE_MODULES_PATH . $class . DIRECTORY_SEPARATOR . self::MODULE_HELP_FILE;
    }

    public function welcome()
    {
        $this::banner([
            $this->system::PACKAGE . ' v' . $this->system::VERSION,
            ' ',
            $this->system::DESCRIPTION,
            $this->system::WEBSITE,
            ' ',
            $this->system::AUTHOR,
        ]);

        self::output(' Help Usage: qpm [ModuleName] --help', 1, 1);
    }

    public static function banner(array $strings = [], string $color = 'green', string $border_color = 'magenta')
    {
        $lengths = [];
        $max = 0;
        $pad = 0;
        foreach ($strings as $key => $string) {
            $lengths[$key] = mb_strlen($string) + 4;
            if ($lengths[$key] > $max) {
                $max = $lengths[$key];
            }
        }
        if ($max % 2 == 0) {
            $pad = 1;
        }
        self::setColor($border_color);
        echo "\n *" . str_repeat(' *', $max / 2) . " *\n";
        echo " *" . str_repeat(' ', $max + $pad) . "*\n";
        foreach ($strings as $key => $string) {
            $space = str_repeat(' ', ($pad + ($max - $lengths[$key])));
            echo " * ";
            self::setColor($color);
            echo "{$string}{$space}";
            self::setColor($border_color);
            echo "   *\n";
        }
        echo " *" . str_repeat(' ', $max + $pad) . "*\n";
        echo " *" . str_repeat(' *', $max / 2) . " *\n";
        self::setColor('reset');
    }

    public static function label(string $string = null, string $color = 'green')
    {
        $len = mb_strlen($string) + 2;
        self::setColor($color);
        echo "\n +" . str_repeat('-', $len) . "+\n";
        echo " | {$string} |\n";
        echo " +" . str_repeat('-', $len) . "+\n";
        self::setColor('reset');
    }

    public static function outputError(string $string = '', int $breaks = 1, int $top_breaks = 0)
    {
        self::output("\e[91m_[ERROR]_   $string\e[39m", $breaks, $top_breaks);
    }

    public static function outputSuccess(string $string = '', int $breaks = 1, int $top_breaks = 0)
    {
        self::output("\e[92m_[SUCCESS]_ $string\e[39m", $breaks, $top_breaks);
    }

    public static function outputWarn(string $string = '', int $breaks = 1, int $top_breaks = 0)
    {
        self::output("\e[93m_[WARNING]_ $string\e[39m", $breaks, $top_breaks);
    }

    public static function outputNotice(string $string = '', int $breaks = 1, int $top_breaks = 0)
    {
        self::output("\e[96m_[NOTICE]_  $string\e[39m", $breaks, $top_breaks);
    }

    public static function output(string $string = '', int $breaks = 1, int $top_breaks = 0)
    {
        if (is_int($breaks)) {
            echo str_repeat("\n", $top_breaks) . "{$string}" . str_repeat("\n", $breaks);
        } else {
            echo $string;
        }
    }

    public static function input(string $string = '')
    {
        return readline("{$string}: ");
    }

    public static function server(string $key)
    {
        if (isset($_SERVER[$key])) {
            return $_SERVER[$key];
        }
    }

    public static function setColor(string $color = "default")
    {
        switch ($color) {
            case 'black':
                echo "\e[30m";
                break;

            case 'red':
                echo "\e[31m";
                break;

            case 'green':
                echo "\e[32m";
                break;

            case 'yellow':
                echo "\e[33m";
                break;

            case 'blue':
                echo "\e[34m";
                break;

            case 'magenta':
                echo "\e[35m";
                break;

            case 'cyan':
                echo "\e[36m";
                break;

            case 'light_gray':
                echo "\e[37m";
                break;

            case 'dark_gray':
                echo "\e[90m";
                break;

            case 'light_red':
                echo "\e[91m";
                break;

            case 'light_green':
                echo "\e[92m";
                break;

            case 'light_yellow':
                echo "\e[93m";
                break;

            case 'light_blue':
                echo "\e[94m";
                break;

            case 'light_magenta':
                echo "\e[95m";
                break;

            case 'light_cyan':
                echo "\e[96m";
                break;

            case 'white':
                echo "\e[97m";
                break;

            case 'default':
            case 'reset':
            default:
                echo "\e[39m";
        }
    }
}
