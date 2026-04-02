<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\domain_routing as Router;
use Illuminate\Support\Facades\Auth;
use App\Models\logger;
use Illuminate\Support\Facades\DB;

class TopApiUse extends Component
{
    public $host ='';
    public $start_time;
    public $end_time;
    public $api_data;
    public $class;
    public $domain;
    public $time_fram_array =[];
    public $reqs_array = [];

    public function mount()
    {
        $this->domain;
        $route = Router::where('user_id', '=', auth::user()->id)->where('host', '=', $this->domain)->get();
        if (count($route) > 0) {
            $this->host = $route[0]['host'];
            $this->start_time;
            $this->end_time;
            $this->class;
        }
    }

    public function render()
    {
        if($this->host != ''){
        $request_method = Logger::select('url', DB::raw('count(id) as count'))
            ->where('created_at', '>=', $this->start_time . ' 00:00:00')
            ->where('created_at', '<=', $this->end_time . ' 23:59:59')
            ->where('host', '=', $this->host)
            ->where('analysis','!=','Failed')
            ->groupBy('url')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get();

        $this->api_data = $request_method;
        return view('livewire.dashboard.top-api-use');
        }
        return '<div>403</div>';
    }
}
