<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class UserController extends Controller
{
    public function showByUsername($username)
    {
        $user = User::where('username', $username)->firstOrFail();
    
        $isOwnProfile = Auth::check() && Auth::user()->id === $user->id;
    
        return view('profile.index', compact('user', 'isOwnProfile'));
    }
}
