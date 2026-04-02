<?php

namespace App\Livewire\Main;

use Livewire\Component;

class NavigationMenu extends Component
{   
    public $domain;

    public function mount($domain)
    {
        $this->domain = $domain;
    }
    public function render()
    {
        return view('livewire.main.navigation-menu',['domain'=>$this->domain]);
    }
}
