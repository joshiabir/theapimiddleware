<?php

namespace App\Livewire\Apis;

use Livewire\Component;
use App\Models\apis;
use App\Models\domain_routing as Router;
use Illuminate\Support\Facades\Auth;
use App\Models\logger;
use Illuminate\Support\Carbon;

class SimpleList extends Component
{

    public $apis = [];
    public $host = '';
    public $key;
    public $domain = '';
    public $start_time;
    public $end_time;
    public $list;
    public $last_connection;
    public $api;

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

        $list = Apis::where('host', $this->host)->orderByRaw('url asc')->get();
        $this->list = $list;
        $this->last_connection = logger::where('host', '=', $this->host)
            ->where('url', '=', $this->key)->orderby('created_at', 'desc')->limit(1)->get();
        if (count($this->last_connection) > 0) {
            $this->last_connection = Carbon::parse($this->last_connection[0]->created_at)->diffForHumans();
        }
        return view('livewire.apis.simple-list');
    }
}
