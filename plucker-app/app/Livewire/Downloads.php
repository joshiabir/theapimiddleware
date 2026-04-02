<?php

namespace App\Livewire;

use Livewire\Component;

class Downloads extends Component
{
    
    public $type;
    public $s_date;
    public $e_date;

    public function mount()
    {
        $this->type;
        $this->s_date;
        $this->e_date;
    }
    
    public function download()
    {
        if($this->type == 'gen_log')
        {
            //
        }
    }
    
    public function render()
    {
        return view('livewire.downloads');
    }
}
