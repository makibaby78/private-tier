<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PostMedia;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

    
class UserController extends Controller
{
    private function resolveProfile(string $username, string $activeTab = 'posts'): array
    {
        $user = User::where('username', $username)->firstOrFail();
        $isOwnProfile = Auth::check() && Auth::id() === $user->id;
    
        return compact('user', 'isOwnProfile', 'activeTab');
    }

    public function index(string $username)
    {
        return view('profile.index', $this->resolveProfile($username, 'posts'));
    }
    
    public function about(string $username)
    {
        return view('profile.about.index', $this->resolveProfile($username, 'about'));
    }
    
    public function friends(string $username)
    {
        return view('profile.friends.index', $this->resolveProfile($username, 'friends'));
    }
    
    public function photos(string $username)
    {
        return view('profile.photos.index', $this->resolveProfile($username, 'photos'));
    }

    public function albums(string $username)
    {
        $data = $this->resolveProfile($username, 'photos');
        $data['photoTab'] = 'albums';
    
        return view('profile.photos.albums.index', $data);
    }
    
    public function videos(string $username)
    {
        return view('profile.videos.index', $this->resolveProfile($username, 'videos'));
    }
    
    public function reels(string $username)
    {
        return view('profile.reels.index', $this->resolveProfile($username, 'reels'));
    }

    public function showMedia(Request $request, string $username, int $mediaId)
    {
        $user = User::where('username', $username)->firstOrFail();
    
        $media = PostMedia::with('post.user', 'post.media')->findOrFail($mediaId);
    
        $from = $request->query('from');

        $back = $request->query('back');

        $mediaList = [];
    
        if ($from === 'photos') {

            $mediaList = PostMedia::whereHas('post', fn($q) => $q->where('user_id', $user->id))
                ->where('type', 'image')
                ->orderBy('id', 'desc')
                ->pluck('id')
                ->toArray();

        } else if($from === 'posts' || $from === 'feed') {

            $mediaList = PostMedia::where('post_id', $media->post_id)
                ->orderBy('id', 'desc')
                ->pluck('id')
                ->toArray();
        }
    
        $currentIndex = array_search($media->id, $mediaList);
        $prevId = $mediaList[$currentIndex - 1] ?? null;
        $nextId = $mediaList[$currentIndex + 1] ?? null;

        $backUrl = match ($back) {
            'feed'   => url('/'),
            default  => $back ? url($back) : url()->previous(),
        };
    
        return view('profile.photos.media.index', compact('user', 'media', 'prevId', 'nextId', 'backUrl'));
    }
}
