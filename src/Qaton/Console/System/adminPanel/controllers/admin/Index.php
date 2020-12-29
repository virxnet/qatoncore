<?php

namespace App\Controllers\Admin;

use VirX\Qaton\HttpHeaders;

class Index
{
    public function __construct()
    {
        HttpHeaders::redirect('/admin/panel');
    }

    public function index()
    {
        //
    }
}
