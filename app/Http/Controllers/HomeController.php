<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;


class HomeController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            return view('dashboard'); // view for logged-in users
        }

        return view('welcome'); // default view for guests
    }
}