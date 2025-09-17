<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class ConnectLayout extends Component
{
    public function __construct()
    {
        if (!Auth::check()) {
            abort(404);
        }
    }

    public function render(): View
    {
        return view('layouts.connect');
    }
}
