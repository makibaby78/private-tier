<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use App\Models\Post;
use App\Models\PostMedia;
use App\Models\ProfilePicture;


class SetttingsController extends Controller
{
    /**
     * Display the user's settings form.
     */
    public function edit(Request $request): View
    {
        return view('settings.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's settings information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('settings.edit')->with('status', 'settings-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|max:2048',
            'body' => 'nullable|string|max:1000',
        ]);

        $user = $request->user();

        // 🔁 Step 1: Mark previous profile pictures as NOT current
        ProfilePicture::whereIn('post_id', $user->posts()->pluck('id'))
            ->update(['is_current' => false]);

        // Upload new image to Cloudinary
        $publicId = Storage::disk('cloudinary')->putFile('profile-photos', $request->file('photo'));
        $url = Storage::disk('cloudinary')->url($publicId);

        // ✅ 3. Create the Post
        $post = Post::create([
            'user_id' => auth()->id(),
            'type' => 'media',
            'body' => $request->input('body', null),
        ]);

        // ✅ 4. Store the media in post_media
        PostMedia::create([
            'post_id' => $post->id,
            'url' => $url,
            'type' => 'image',
            'public_id' => $publicId,
        ]);

        ProfilePicture::create([
            'post_id' => $post->id,
            'is_current' => true,
        ]);

        return back()->with('success', 'Profile photo updated!');
    }
}
