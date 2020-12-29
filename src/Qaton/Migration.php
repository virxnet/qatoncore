<?php

namespace VirX\Qaton;

use VirX\Qaton\Db;

class Migration
{
    public $db;

    public function __construct(array $config)
    {
        $this->db = Db::init($config);
    }
}
