<?php

namespace App\Livewire;

use Livewire\Component;

class LocalTime extends Component
{
    
    public $action;

    public function mount($action){
        $this->action = $action;
    }
    
    public function render()
    {
        return view('livewire.local-time');
    }
}
