<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PostMedia;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\User;
use App\Models\ProfilePicture;
use Illuminate\Support\Facades\Storage;

    
class UserController extends Controller
{
    private function resolveProfile(string $username, string $activeTab = 'posts'): array
    {
        $user = User::where('username', $username)->firstOrFail();
        $isOwnProfile = Auth::check() && Auth::id() === $user->id;

        $profile_post_id = $user->getProfilePostIdAttribute();
    
        return compact('user', 'isOwnProfile', 'activeTab','profile_post_id');
    }

    public function index(string $username)
    {
        $profile = $this->resolveProfile($username, 'posts');
        $user = $profile['user'];

        $photos = $user->media()
            ->where('post_media.type', 'image')
            ->latest()
            ->take(9)
            ->get();

        return view('profile.index', array_merge($profile, ['photos' => $photos]));
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
    
        $user = $data['user'];

        $data['albums'] = Post::with('media')
            ->where('user_id', $user->id)
            ->where('type', 'album')
            ->latest()
            ->get();

        return view('profile.photos.albums.index', $data);
    }

    public function album(string $username, string $album)
    {
        $data = $this->resolveProfile($username, 'photos');

        $user = $data['user'];

        // Find the album by ID or slug (depending on your setup)
        $albumPost = Post::with('media')
        ->where('user_id', $user->id)
        ->where('type', 'album')
        ->where(function ($query) use ($album) {
            $query->where('id', $album)
                ->orWhere('body', $album); // if using title as identifier
        })
        ->firstOrFail();

        // Add album and media to view data
        $data['album'] = $albumPost;
        $data['mediaItems'] = $albumPost->media;
        $data['back'] = request()->path();

        return view('profile.photos.albums.album.index', $data);
    }
    
    public function pictures(string $username)
    {
        $data = $this->resolveProfile($username, 'photos');

        $user = $data['user'];

        $pictures = $user->profilePictures()->with('post.media')->get();

        $mediaItems = $pictures->flatMap(fn($pic) => $pic->post?->media ?? []);

        $data['album'] = (object) [
            'body' => 'Profile Pictures',
        ];
        $data['mediaItems'] = $mediaItems;
        $data['back'] = request()->path();

        return view('profile.photos.albums.album.index', $data);
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

    public function updatePicture(Request $request, $username)
    {
        // Ensure the user is authenticated
        if (!Auth::check()) {
            abort(403, 'You must be logged in to update your profile picture.');
        }
    
        // Validate the request input
        $validated = $request->validate([
            'body'      => 'nullable|string',
            'media'     => 'required|array|size:1',
            'media.*'   => 'file|mimetypes:image/*,video/*|max:102400', // Max 100MB per file
        ]);
    
        // Fetch the user by username
        $user = User::where('username', $username)->firstOrFail();
    
        // Authorize only the owner
        if (Auth::id() !== $user->id) {
            abort(403, 'Unauthorized access.');
        }
    
        // Create a new media-type post
        $post = Post::create([
            'user_id' => $user->id,
            'body'    => $validated['body'] ?? null,
            'type'    => 'media',
        ]);
    
        // Set all previous profile pictures to not current
        ProfilePicture::whereIn('post_id', $user->posts()->pluck('id'))
            ->update(['is_current' => false]);
    
        // Mark the new profile picture
        ProfilePicture::create([
            'post_id'    => $post->id,
            'is_current' => true,
        ]);
    
        // Upload the media file (only one is expected)
        $file = $validated['media'][0];
        $path = Storage::disk('cloudinary')->putFile('profile-photos', $file);
        $url = Storage::disk('cloudinary')->url($path);
        $publicId = pathinfo($path, PATHINFO_FILENAME);
    
        // Save media reference to the post
        $post->media()->create([
            'url'       => $url,
            'type'      => 'image', // optionally detect image/video by mime
            'public_id' => $path,
        ]);
    
        return back()->with('success', 'Profile picture updated successfully.');
    }
    
}
