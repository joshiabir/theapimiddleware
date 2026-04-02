<?php

namespace App\Livewire\Main;

use Livewire\Component;
use App\Models\domain_routing as Router;
use Illuminate\Support\Facades\Auth;

class Domains extends Component
{
    
    public $domain_selected;
    public $domains;
    public $start_time;
    public $end_time;
    public $domain;
    public $api;

    public function mount()
    {
        $this->start_time;
        $this->end_time;
        $this->domain;
        $this->api;
        
    }
    
    public function render()
    {   
        
        $this->domain_selected = Router::where('user_id','=',Auth::user()->id)
                            ->where('host','=',$this->domain)->get();

        $this->domains = Router::where('user_id','=',Auth::user()->id)->get();
        return view('livewire.main.domains');
    }
}
