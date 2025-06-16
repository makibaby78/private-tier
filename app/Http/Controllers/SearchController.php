<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{

    public function index(Request $request)
    {
        $query = $request->input('q');
    
        if (!$query || strlen(trim($query)) < 1) {
            return view('search.results', [
                'query' => '',
                'users' => collect(),
                'posts' => collect(),
            ]);
        }
    
        $users = User::where('firstname', 'like', "%{$query}%")
                     ->orWhere('lastname', 'like', "%{$query}%")
                     ->orWhere('username', 'like', "%{$query}%")
                     ->get();
    
        $authUser = auth()->user();
    
        if ($authUser) {
            $users->transform(function ($user) use ($authUser) {
                if ($user->id === $authUser->id) {
                    $user->friendship_label = 'Your Profile';
                } else {
                    $friendship = DB::table('friendships')
                        ->where(function ($q) use ($authUser, $user) {
                            $q->where('user_id', $authUser->id)
                              ->where('friend_id', $user->id);
                        })
                        ->orWhere(function ($q) use ($authUser, $user) {
                            $q->where('friend_id', $authUser->id)
                              ->where('user_id', $user->id);
                        })
                        ->first();
    
                    if (!$friendship) {
                        $user->friendship_label = 'Add Friend';
                    } elseif ($friendship->status === 'accepted') {
                        $user->friendship_label = 'Friends';
                    } elseif (
                        $friendship->status === 'pending' &&
                        $friendship->user_id == $authUser->id
                    ) {
                        $user->friendship_label = 'Cancel Request';
                    } elseif (
                        $friendship->status === 'pending' &&
                        $friendship->friend_id == $authUser->id
                    ) {
                        $user->friendship_label = 'Accept Request';
                    } else {
                        $user->friendship_label = 'Add Friend';
                    }
                }
    
                return $user;
            });
        } else {
            $users->each(function ($user) {
                $user->friendship_label = 'Login to Add';
            });
        }
    
        $posts = Post::where('body', 'like', "%{$query}%")->get();
    
        return view('search.results', compact('query', 'users', 'posts'));
    }
    
}
