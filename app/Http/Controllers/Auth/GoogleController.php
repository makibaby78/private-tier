<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $firstName = $googleUser->user['given_name'] ?? '';
        $lastName = $googleUser->user['family_name'] ?? '';
        $email = $googleUser->email;

        // Generate a base username from email (before @)
        $baseUsername = Str::slug(Str::before($email, '@'));

        // Ensure uniqueness by checking for existing usernames
        $username = $baseUsername;
        $counter = 1;

        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }

        $user = User::updateOrCreate(
            ['google_id' => $googleUser->id],
            [
                'firstname' => $firstName,
                'lastname' => $lastName,
                'email' => $email,
                'username' => $username,
            ]
        );

        Auth::login($user);

        return redirect()->intended('/');
    }
}
