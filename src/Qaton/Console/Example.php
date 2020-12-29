<?php

// demonstrates the capabilities of Qaton CLI command modules

namespace VirX\Qaton\Console;

use VirX\Qaton\Console;

class Example extends Console
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

    public function greet(array $args)
    {
        Console::output();
        Console::output("Hello! You said: ", false);

        if (empty($args)) {
            Console::output("Nothing");
        } else {
            if (is_array($args)) {
                $words = $args;
                if (array_key_exists('extra', $this->options)) {
                    foreach ($words as $key => $word) {
                        unset($words[$key]);
                        foreach ($this->options['extra'] as $extra) {
                            $words[] = "{$word} {$extra}";
                        }
                    }
                }
                foreach ($words as $word) {
                    if (array_key_exists('lines', $this->options)) {
                        Console::output();
                        Console::output("{$word} ", false);
                    } else {
                        Console::output("{$word} ", false);
                    }
                }
            } else {
                Console::output($args, false);
            }
        }

        Console::output("\n");
    }

    public function __destruct()
    {
        Console::setColor('reset');
        /*
        Console::label('DEBUG');
        Console::output();
        var_dump($this);
        */
    }

}
