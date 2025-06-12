<?php

use App\Http\Controllers\SetttingsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ChatController;
use App\Events\MessageSent;


Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/settings', [SetttingsController::class, 'edit'])->name('settings.edit');
    Route::patch('/settings', [SetttingsController::class, 'update'])->name('settings.update');
    Route::delete('/settings', [SetttingsController::class, 'destroy'])->name('settings.destroy');

    Route::prefix('profile')->name('profile.')->group(function () {

        Route::get('/', function () {
            return view('profile.index');
        })->name('index');
    });

    // Route::get('/chat', [ChatController::class, 'index'])->name('chat');
    // Route::post('/messages', [ChatController::class, 'store']);
    // Route::get('/messages', [ChatController::class, 'fetch']);
    // Route::post('/send-message', [ChatController::class, 'send']);

    Route::get('/send-test', function () {
        return view('send-test');
    });

    Route::get('/test-broadcast', function () {
        broadcast(new MessageSent('Hello from Laravel + Pusher!'));
        return 'Broadcasted!';
    });

    Route::get('/trigger-broadcast', function () {
        broadcast(new MessageSent('Hello from Laravel!'));
        return 'Message broadcasted';
    });

});

require __DIR__.'/auth.php';
