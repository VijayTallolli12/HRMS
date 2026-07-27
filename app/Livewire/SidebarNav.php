<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class SidebarNav extends Component
{
    #[On('branding-updated')]
    public function refreshBranding(): void {}

    public function render()
    {
        return view('livewire.sidebar-nav');
    }
}
