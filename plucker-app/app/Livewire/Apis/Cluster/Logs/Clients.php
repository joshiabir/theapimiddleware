<?php

namespace App\Livewire\Apis\Cluster\Logs;

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
    public $time_fram_array = [];
    public $reqs_array = [];
    public $cluster_id;

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
            $this->cluster_id;
            // $this->start_time = date('Y-m-d H:i:s', strtotime('-12 hours'));
            // $this->end_time = date('Y-m-d H:i:s');
        }

    }

    public function render()
    {

        if ($this->host != '') {


            $startTime = $this->start_time . ' 00:00:00';
            $endTime = $this->end_time . ' 23:59:59';

            $clients = DB::select("select count(client) as count, client from loggers,cluster_apis,apis where 
            cluster_apis.cluster_id = ? and
            cluster_apis.api_id = apis.api_id and
            and created_at >= ?
            and created_at < ?
            apis.url = loggers.url
            group by client
            ",[$startTime,$endTime,$this->cluster_id]);

            return view('livewire.apis.cluster.logs.clients', ['clients' => $clients]);
        }
        return '<div>403</div>';
    }

}
