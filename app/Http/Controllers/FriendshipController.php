<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendshipController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return view('profile.index', compact('user'));
    }
    
    public function sendRequest($id)
    {
        $user = Auth::user();

        // Prevent duplicates
        if ($user->friendsOfMine()->where('friend_id', $id)->exists() ||
            $user->friendOf()->where('user_id', $id)->exists()) {
            return back()->with('error', 'Request already exists.');
        }

        $user->friendsOfMine()->attach($id, ['status' => 'pending']);
        return back()->with('success', 'Friend request sent!');
    }

    public function acceptRequest($id)
    {
        $user = Auth::user();

        $user->friendOf()->updateExistingPivot($id, ['status' => 'accepted']);
        return back()->with('success', 'Friend request accepted!');
    }

    public function cancelRequest($id)
    {
        Auth::user()->friendsOfMine()->detach($id);
        return back()->with('info', 'Friend request canceled.');
    }

    public function unfriend($id)
    {
        $user = Auth::user();

        $user->friendsOfMine()->detach($id);
        $user->friendOf()->detach($id);

        return back()->with('info', 'Unfriended.');
    }
}
