<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class ConnectLayout extends Component
{
    public $user;
    
    public function __construct()
    {
        // Check if user is logged in, else 404
        if (!Auth::check()) {
            abort(404);
        }

        // Store the logged-in user
        $this->user = Auth::user();
    }

    public function render(): View
    {
        return view('layouts.connect', [
            'user' => $this->user,
        ]);
    }
}
