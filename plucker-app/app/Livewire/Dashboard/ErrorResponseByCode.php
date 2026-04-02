<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\domain_routing as Router;
use App\Models\logger;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;

class ErrorResponseByCode extends Component
{
    public $host = '';
    public $ip;
    public $last_modified;
    public $error_responses;
    public $start_time;
    public $end_time;
    public $response_status;
    public $reqs;
    public $response_status_array =[];
    public $reqs_array = [];
    public $domain;

    public function mount()
    {
        $this->domain;
        $route = Router::where('user_id', '=', auth::user()->id)->where('host', '=', $this->domain)->get();
        if (count($route) > 0) {
            $this->host = $route[0]['host'];
            $this->ip = $route[0]['ip'];
            // $this->start_time = date('Y-m-d H:i:s', strtotime('-12 hours'));
            // $this->end_time = date('Y-m-d H:i:s');
            $this->reqs = [];
            $this->response_status = [];
        }
    }

    // #[On('time-updated')]
    // public function updatePostList($start_time, $end_time)
    // {
    //     $this->start_time = $start_time;
    //     $this->end_time = $end_time;
    // }

    public function render()
    {
        if($this->host !=''){
        $error_responses = Logger::select('response_status', DB::raw('count(id) as count'))
            ->where('response_status', '>', 299)
            ->where('created_at', '>=', $this->start_time . ' 00:00:00')
            ->where('created_at', '<=', $this->end_time . ' 23:59:59')
            ->where('host', '=', $this->host)
            ->groupBy('response_status')
            ->orderBy('response_status', 'asc')
            ->get();
        
        $this->error_responses = $error_responses;
        return view('livewire.dashboard.error-response-by-code');
    }
    return '<div>403</div>';
    }
}
