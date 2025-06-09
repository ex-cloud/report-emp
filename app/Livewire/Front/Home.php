<?php

namespace App\Livewire\Front;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Home extends Component
{
    #[Title('Home')]
    public function render()
    {
        return view('livewire.front.home');
    }
}
