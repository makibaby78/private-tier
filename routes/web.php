<?php

use App\Http\Controllers\SetttingsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FriendshipController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use App\Models\Message;


Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/settings', [SetttingsController::class, 'edit'])->name('settings.edit');
    Route::patch('/settings', [SetttingsController::class, 'update'])->name('settings.update');
    Route::delete('/settings', [SetttingsController::class, 'destroy'])->name('settings.destroy');
    Route::post('/settings/upload-photo', [SetttingsController::class, 'upload'])->name('settings.upload-photo');

    Route::get('/chat/{user}', function ($userId) {
        $messages = Message::where(function ($q) use ($userId) {
            $q->where('sender_id', auth()->id())
              ->where('receiver_id', $userId);
        })->orWhere(function ($q) use ($userId) {
            $q->where('sender_id', $userId)
              ->where('receiver_id', auth()->id());
        })->with('sender')->get();

        $receiver = \App\Models\User::findOrFail($userId);

        return view('chat', compact('messages', 'receiver'));
    });

    Route::post('/chat/send', function (Request $request) {
        $message = \App\Models\Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ]);
    
        broadcast(new \App\Events\MessageSent($message))->toOthers();
    
        return ['status' => 'sent'];
    });

    Route::post('/friend-request/send/{id}', [FriendshipController::class, 'sendRequest'])->name('friend.send');
    Route::post('/friend-request/accept/{id}', [FriendshipController::class, 'acceptRequest'])->name('friend.accept');
    Route::post('/friend-request/cancel/{id}', [FriendshipController::class, 'cancelRequest'])->name('friend.cancel');
    Route::delete('/friend/remove/{id}', [FriendshipController::class, 'unfriend'])->name('friend.remove');

});

Route::get('/{username}', [UserController::class, 'showByUsername'])
->where('username', '^(?!login$|register$|admin$|dashboard$)[a-zA-Z0-9_]+$')
->name('profile.index');

require __DIR__.'/auth.php';
