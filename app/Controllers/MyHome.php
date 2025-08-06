<?php

namespace App\Controllers;

class MyHome extends BaseController
{
    public function index()
    {
        $data['content'] = view('home'); 
        return view('template', $data);   
    }
}