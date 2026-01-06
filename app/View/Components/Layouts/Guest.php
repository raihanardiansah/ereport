<?php

namespace App\View\Components\Layouts;

use Illuminate\View\Component;
use Illuminate\View\View;

class Guest extends Component
{
    public string $title;

    public function __construct(string $title = 'e-Report')
    {
        $this->title = $title;
    }

    public function render(): View
    {
        return view('layouts.guest');
    }
}
