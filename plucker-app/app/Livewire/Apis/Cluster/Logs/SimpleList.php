<?php

namespace App\Livewire\Apis\Cluster\Logs;

use Livewire\Component;
use App\Models\cluster;
use App\Models\domain_routing as Router;
use Illuminate\Support\Facades\Auth;
use App\Models\logger;
use Illuminate\Support\Carbon;

class SimpleList extends Component
{
    public $clusters = [];
    public $host = '';
    public $key;
    public $domain = '';
    public $start_time;
    public $end_time;
    public $list;
    public $last_connection;
    public $cluster;

    public function mount()
    {
        $this->start_time;
        $this->end_time;
        $this->domain;
        $route = Router::where('user_id', '=', auth::user()->id)->where('host', '=', $this->domain)->get();
        if (count($route) > 0) {

            $this->host = $route[0]['host'];
            $this->key;
        }
    }
    public function render()
    {
        if ($this->key != '') {
            $key = cluster::where('host', $this->host)->where('id', '=', $this->cluster_id)->orderByRaw('created_at desc')->get();
            $this->key = $key[0]['name'];
        }
        $this->list = cluster::where('host', $this->host)->orderByRaw('created_at desc')->get();
        return view('livewire.apis.cluster.logs.simple-list');
    }
}
