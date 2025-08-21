<?php
namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        return view('pages/index', ['title' => 'Home']);
    }

    public function about()
    {
        return view('pages/about', ['title' => 'About']);
    }

    public function contact()
    {
        return view('pages/contact', ['title' => 'Contact']);
    }
}
