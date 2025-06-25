<?php

use App\Http\Controllers\SetttingsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FriendshipController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SearchController;
use App\Livewire\SinglePostView;
use App\Livewire\SingleMediaView;
use Illuminate\Http\Request;


Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/settings', [SetttingsController::class, 'edit'])->name('settings.edit');
    Route::patch('/settings', [SetttingsController::class, 'update'])->name('settings.update');
    Route::delete('/settings', [SetttingsController::class, 'destroy'])->name('settings.destroy');
    Route::post('/settings/upload-photo', [SetttingsController::class, 'upload'])->name('settings.upload-photo');

    Route::post('/friend-request/send/{id}', [FriendshipController::class, 'sendRequest'])->name('friend.send');
    Route::post('/friend-request/accept/{id}', [FriendshipController::class, 'acceptRequest'])->name('friend.accept');
    Route::post('/friend-request/cancel/{id}', [FriendshipController::class, 'cancelRequest'])->name('friend.cancel');
    Route::delete('/friend/remove/{id}', [FriendshipController::class, 'unfriend'])->name('friend.remove');

    Route::get('/search', [SearchController::class, 'index'])->name('search.results');

    Route::prefix('friends')->name('friends.')->group(function () {
        Route::get('/', function () {
            return view('friends.index');
        })->name('index');

        Route::get('/requests', function () {
            return view('friends.requests.index');
        })->name('requests.index');

        Route::get('/suggestions', function () {
            return view('friends.suggestions.index');
        })->name('suggestions.index');

        Route::get('/all-friends', function () {
            return view('friends.all-friends.index');
        })->name('all-friends.index');

        Route::get('/birthdays', function () {
            return view('friends.birthdays.index');
        })->name('birthdays.index');
    });

    Route::prefix('{username}')
        ->as('profile.')
        ->group(function () {

        Route::get('/about', [UserController::class, 'about'])->name('about.index');

        Route::get('/friends', [UserController::class, 'friends'])->name('friends.index');

        Route::get('/photos', [UserController::class, 'photos'])->name('photos.index');

        Route::prefix('/photos')
            ->as('photos.')
            ->group(function () {

            Route::get('/albums', [UserController::class, 'albums'])->name('albums.index');

        });

        Route::get('/videos', [UserController::class, 'videos'])->name('videos.index');

        Route::get('/reels', [UserController::class, 'reels'])->name('reels.index');

        Route::get('/posts/{post}', SinglePostView::class)->name('posts.show');

    });
});

Route::get('/{username}', [UserController::class, 'index'])
->where('username', '^(?!login$|register$|admin$|dashboard$)[a-zA-Z0-9_]+$')
->name('profile.index');

require __DIR__.'/auth.php';
