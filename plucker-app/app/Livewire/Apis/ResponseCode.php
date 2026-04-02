<?php

namespace App\Livewire\Apis;

use Livewire\Component;
use App\Models\logger;
use Illuminate\Support\Facades\Auth;
use App\Models\domain_routing as Router;
use Illuminate\Support\Facades\DB;

class ResponseCode extends Component
{


    public $start_time;
    public $end_time;
    public $url;
    public $host;
    public $responses;
    public $reqs = [];
    public $response_status = [];
    public $domain;

    public function mount()
    {
        $this->domain;
        $route = Router::where('user_id', '=', auth::user()->id)->where('host', '=', $this->domain)->get();
        if (count($route) > 0) {
            $route = Router::where('user_id', '=', Auth::user()->id)->get();
            $this->host = $route[0]['host'];
            $this->url;
            $this->end_time;
            $this->start_time;
        }
    }

    public function render()
    {
        if ($this->host != '') {
        $this->responses = Logger::select('response_status', DB::raw('count(id) as count'))
            ->where('created_at', '>=', $this->start_time . ' 00:00:00')
            ->where('created_at', '<=', $this->end_time . ' 23:59:59')
            ->where('host', '=', $this->host)
            ->where('url', '=', $this->url)
            ->groupBy('response_status')
            ->orderBy('response_status', 'asc')
            ->get();

    
        return view('livewire.apis.response-code');
    }
    return '<div>403</div>';
    }
}
