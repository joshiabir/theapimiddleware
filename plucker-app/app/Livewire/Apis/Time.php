<?php

namespace App\Livewire\Apis;

use Livewire\Component;

class Time extends Component
{   

    public $start_time;
    public $end_time;
    public $action;
    public $url;
    public $domain;

    public function mount()
    {
       //$this->start_time = datetime('',$this->start_time);

        $this->action;
        $this->url;
        $this->domain;
    }

    // public function update_time()
    // {
    //     $this->dispatch('time-updated', start_time:$this->start_time, end_time:$this->end_time);
    // }

    public function render()
    {
        return view('livewire.apis.time');
    }
}
