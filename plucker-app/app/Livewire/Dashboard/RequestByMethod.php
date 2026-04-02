<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\domain_routing as Router;
use App\Models\logger;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;

class RequestByMethod extends Component
{
    public $host = '';
    public $ip;
    public $last_modified;
    public $request_method;
    public $start_time;
    public $end_time;
    public $method;
    public $reqs;
    public $domain;
    public $method_array = [];
    public $reqs_array = [];

    public function mount()
    {
        $this->domain;
        $route = Router::where('user_id', '=', auth::user()->id)->where('host', '=', $this->domain)->get();
        if (count($route) > 0) {
            $this->host = $route[0]['host'];
            $this->ip = $route[0]['ip'];
            $this->method = [];
            $this->reqs = [];
        }
    }

    public function render()
    {
        if ($this->host != '') {

            $request_method = Logger::select('request_method', DB::raw('count(id) as count'))
                ->where('created_at', '>=', $this->start_time . ' 00:00:00')
                ->where('created_at', '<=', $this->end_time . ' 23:59:59')
                ->where('host', '=', $this->host)
                ->groupBy('request_method')
                ->get();

            $this->request_method = $request_method;
            return view('livewire.dashboard.request-by-method');
        }
        return '<div>403</div>';
    }
}
