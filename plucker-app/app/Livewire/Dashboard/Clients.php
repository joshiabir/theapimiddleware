<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\logger;
use App\Models\domain_routing as Router;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class Clients extends Component
{

    public $start_time;
    public $end_time;
    public $host;
    public $ip;
    public $time_frame;
    public $reqs;
    public $time_fram_array =[];
    public $reqs_array = [];

    public $domain;

    public function mount()
    {
        $this->domain;
        $route = Router::where('user_id', '=', auth::user()->id)->where('host', '=', $this->domain)->get();
        if (count($route) > 0) {
            $this->host = $route[0]['host'];
            $this->ip = $route[0]['ip'];
            $this->time_frame = [];
            $this->reqs = [];
            // $this->start_time = date('Y-m-d H:i:s', strtotime('-12 hours'));
            // $this->end_time = date('Y-m-d H:i:s');
        }
    }

    public function render()
    {
            
        if($this->host !=''){

      
        $startTime = $this->start_time . ' 00:00:00';
            $endTime = $this->end_time . ' 23:59:59';

            $clients = logger::select(DB::RAW("client"), DB::RAW("count(id) AS count"))
                ->where('host', '=', $this->host)
                ->where('created_at', '>=', $startTime)
                ->where('created_at', '<', $endTime)
                ->groupby('client')
                ->orderby('count','desc')
                ->get();

        return view('livewire.dashboard.clients', ['clients' => $clients]);
    }
    return '<div>403</div>';
    }
}
