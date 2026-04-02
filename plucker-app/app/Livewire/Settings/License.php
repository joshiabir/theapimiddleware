<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\license as lice;
use App\Models\domain_routing as dnr;
use Illuminate\Support\Facades\DB;

class License extends Component
{


   public $email;
   public $rpm;
   public $end_date;
   public $domains;
   public $existing;

    public function render()
    {
        $license = lice::where('email', '=', Auth::user()->email)->get();
        $this->email = $license[0]->email;
        $this->rpm = $license[0]->rpm;
        $this->end_date = $license[0]->end_date;
        $this->domains = $license[0]->domains;

        $existing = dnr::select(DB::RAW('count(id) as count'))->where('user_id','=',auth::user()->id)->get();
        if(count($existing) > 0)
        {
            $this->existing = $existing[0]->count;
        }else{
            $this->existing = '0';
        }
        return view('livewire.settings.license');
    }
}
