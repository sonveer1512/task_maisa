<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function user_proile()
    {
        return view('user_profile');
    }
}
