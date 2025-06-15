<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class ProfilePhoto extends Component
{
    public ?string $path;
    public string $alt;
    public int $width;
    public int $height;
    public string $class;

    /**
     * Create a new component instance.
     */
    public function __construct($path = null, $alt = 'Profile Photo', $width = 50, $height = 50, $class = '')
    {
        $this->path = $path;
        $this->alt = $alt;
        $this->width = $width;
        $this->height = $height;
        $this->class = $class;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.profile-photo');
    }
}
