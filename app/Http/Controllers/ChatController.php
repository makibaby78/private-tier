<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;

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

}
