<?php

namespace App\Livewire;

use Livewire\Component;

class AppSwitcher extends Component
{
    public function render()
    {
        return view('livewire.app-switcher')->layout('components.layouts.app')->title('Pilih Modul');
    }
}
