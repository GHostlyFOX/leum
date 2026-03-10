<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.custom-app')]
class ForgotPassword extends Component
{
    public function render()
    {
        return view('livewire.forgot-password');
    }
}
