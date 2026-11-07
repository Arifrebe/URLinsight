<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        // Authentication logic here
    }

    public function register()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        // Registration logic here
    }

    public function logout(Request $request)
    {
        // Logout logic here
    }
}
