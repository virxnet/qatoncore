<?php

namespace App\Controllers\Admin;

use VirX\Qaton\Auth;

class Login
{
    public function __construct()
    {
        //
    }

    public function logout()
    {
        Auth::logout('/admin/login');
    }

    public function index()
    {
        $data['login'] = Auth::login('panel');

        $this->view->section("sections/admin/login", "layouts/admin/login", $data);
    }
}
