<?php

namespace App\View\Components;

use App\Models\Shelter;
use Illuminate\View\Component;
use Illuminate\View\View;

class ShelterLayout extends Component
{
    public function __construct(
        public Shelter $shelter
    ) {
    }

    public function render() : View
    {
        return view('layouts.shelter');
    }
}
