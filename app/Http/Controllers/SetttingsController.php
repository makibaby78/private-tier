<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Cloudinary\Laravel\CloudinaryEngine;
use Illuminate\Support\Facades\Storage;


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
        ]);

        $user = $request->user();

        // Delete old image from Cloudinary (optional if stored as public_id)
        if ($user->cloudinary_public_id) {
            Storage::disk('cloudinary')->delete($user->cloudinary_public_id);
        }

        // Upload new image to Cloudinary
        $publicId = Storage::disk('cloudinary')->putFile('profile-photos', $request->file('photo'));

        $user->profile_photo_path = $publicId;
        $user->save();

        return back()->with('success', 'Profile photo updated!');
    }
}
