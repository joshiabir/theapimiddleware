<?php

namespace App\Livewire\Apis;

use App\Models\domain_routing as Router;
use App\Models\logger;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class Methods extends Component
{


    public $host = '';
    public $ip;
    public $last_modified;
    public $error_responses;
    public $start_time;
    public $end_time;
    public $time_frame;
    public $reqs;
    public $error_rate;
    public $url;
    public $domain;
    public $request_method;

    public function mount()
    {
        $this->domain;
        $route = Router::where('user_id', '=', auth::user()->id)->where('host', '=', $this->domain)->get();
        if (count($route) > 0) {
            $this->host = $route[0]['host'];
            $this->ip = $route[0]['ip'];
            $this->time_frame = [];
            $this->reqs = [];
            $this->url;
        }
    }

    public function render()
    {
        $request_method = Logger::select('request_method', 'response_status', DB::raw('count(id) as count'))
            ->where('created_at', '>=', $this->start_time . ' 00:00:00')
            ->where('created_at', '<=', $this->end_time . ' 23:59:59')
            ->where('host', $this->host)
            ->where('url', $this->url)
            ->groupBy('request_method', 'response_status') // ✅ added 'response_status'
            ->orderBy('response_status', 'asc')
            ->get();


        $this->request_method = $request_method;
        return view('livewire.apis.methods');
    }
}
