<?php

namespace App\Controllers;

class Pages extends BaseController
{
    public function login(): string
    {
        return view('login/index');
    }
}