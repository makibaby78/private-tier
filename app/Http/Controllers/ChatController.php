<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        return view('chat');
    }

    public function fetch()
    {
        return Message::with('user')->latest()->take(50)->get()->reverse()->values();
    }

    public function store(Request $request)
    {
        $message = auth()->user()->messages()->create([
            'message' => $request->message,
        ]);

        broadcast(new \App\Events\MessageSent($message->load('user')))->toOthers();

        return ['status' => 'Message Sent!'];
    }

    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $message = Message::create([
            'user_id' => auth()->id(),
            'message' => $request->message,
        ]);
    
        broadcast(new MessageSent($message))->toOthers();

        event(new \App\Events\MessageSent($message));
    
        return response()->json(['status' => 'Message sent!']);
    }
    
}
