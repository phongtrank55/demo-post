<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct(){
        $this->module_name = 'Home Page';
        parent::__construct();
    }
    public function index(){
        return view('home.index');
    }
}
