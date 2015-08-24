<?php

namespace MKTests\Http\Controllers;

use Illuminate\Http\Request;

use MKTests\Http\Requests;
use MKTests\Http\Controllers\Controller;

class HomeController extends Controller
{

    public function __construct()
    {
        //$this->middleware('auth', ['except' => 'getLogin']);
    }

    public function getIndex()
    {
        return "Home";
    }

    public function getLogin()
    {
        return "Login";
    }

}
