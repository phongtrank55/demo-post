<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestSocialController extends Controller
{
    public function index(){
        return view('test_social.index');
    }
}
