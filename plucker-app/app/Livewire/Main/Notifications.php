<?php

namespace App\Livewire\Main;

use Livewire\Component;
use App\Models\domain_routing as Router;
use Illuminate\Support\Facades\Auth;
use App\Models\license;
use Illuminate\Support\Carbon;

class Notifications extends Component
{
    public $end_date;
    public $host;
    public $notifications;
    public $domain;


    public function mount()
    {
        $this->domain='';
        $route = $route = Router::where('user_id', '=', auth::user()->id)->where('host', '=', $this->domain)->get();
        if (count($route) > 0) {
            $this->host = $route[0]['host'];
            $this->start_time;
            $this->end_time;
            $this->key;
        }
    }
    public function render()
    {

        $notifications = [];
      //  return dd(auth::user()->email);
        $end_date = license::where('email', '=', auth::user()->email)->get();
        if (count($end_date) > 0) {
            $start_time = Carbon::parse(time());
            $end_time = Carbon::parse($end_date[0]->end_date);
           
            // Calculate the difference in days
            $diffInDays = $start_time->diffInDays($end_time);

            if($diffInDays < 30)
            {
                $end_date = [
                    'heading'=>'License Expiring',
                    'message'=>"Your license is expiring in ". number_format($diffInDays,0).' Days',
                ];

                array_push($notifications,$end_date);
            }

        }

        $this->notifications = $notifications;
        
        return view('livewire.main.notifications');
    }
}
