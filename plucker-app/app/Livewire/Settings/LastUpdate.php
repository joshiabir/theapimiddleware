<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use App\Models\domain_routing as Router;
use Illuminate\Support\Facades\Auth;

class LastUpdate extends Component
{

    public $last_modified;

    public function mount()
    {
        $route = Router::where('user_id', '=', Auth::user()->id)->get();
        if(count($route)>0){
            $this->last_modified = $route[0]['updated_at'];
        }else{
            $this->last_modified = '';
        }
       
    }
    public function render()
    {

        return view('livewire.settings.last-update');
    }
}
