<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Models\User;

class HomeController extends Controller
{
    public function authCheck(){
      return JWTAuth::parseToken()->authenticate();
    }

    public function index() {
        return User::all();
    }
}
