<?php

namespace App\Livewire;

use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.dashboard')
            #hereda el layout de Jetstream
            ->layout('layouts.app');
    }
}
