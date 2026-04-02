<?php

namespace App\Livewire;

use Livewire\Component;

class GlobalTime extends Component
{   
    public $start_time;
    public $end_time;
    public $action;

    public function mount()
    {
       //$this->start_time = datetime('',$this->start_time);

        $this->action;
    }

    // public function update_time()
    // {
    //     $this->dispatch('time-updated', start_time:$this->start_time, end_time:$this->end_time);
    // }
    public function render()
    {
        return view('livewire.global-time');
    }
}
