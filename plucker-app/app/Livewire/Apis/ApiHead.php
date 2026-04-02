<?php

namespace App\Livewire\Apis;

use Livewire\Component;
use App\Models\domain_routing as Router;
use Illuminate\Support\Facades\Auth;
use App\Models\response_keys;
use App\Models\api_methods;
use App\Models\logger;
use App\Models\request_keys;
use Illuminate\Support\Carbon;

class ApiHead extends Component
{   

    public $action = '/APIs';
    public $host = '';
    public $start_time;
    public $end_time;
    public $api;
    public $key;
    public $response_keys;
    public $methods;
    public $none_res;
    public $none_req;
    public $request_keys;
    public $last_connection;
    public $domain = '';


    public function mount()
    {
        $this->domain;
        $route = Router::where('user_id', '=', auth::user()->id)->where('host', '=', $this->domain)->get();
        if (count($route) > 0) {
            $this->host = $route[0]['host'];
            $this->start_time;
            $this->end_time;
            $this->key;
            $this->api = $this->key;
        }
    }

    public function render()
    {
        if ($this->host!= '') {
            $this->response_keys = response_keys::where('host', '=', $this->host)
                ->where('url', '=', $this->api)->get();
            $this->request_keys = request_keys::where('host', '=', $this->host)
                ->where('url', '=', $this->api)->get();

            $this->methods = api_methods::where('host', '=', $this->host)
                ->where('url', '=', $this->api)->get();

            if (count($this->response_keys) < 1 && count($this->methods) < 1) {
                $this->none_res = TRUE;
            } else {
                $this->none_res = FALSE;
            }
            if (count($this->request_keys) < 1 && count($this->methods) < 1) {
                $this->none_req = TRUE;
            } else {
                $this->none_req = FALSE;
            }
            $this->last_connection = logger::where('host', '=', $this->host)
                ->where('url', '=', $this->api)->orderby('created_at', 'desc')->limit(1)->get();
            if (count($this->last_connection) > 0) {
                $this->last_connection = Carbon::parse($this->last_connection[0]->created_at)->diffForHumans();
            }
            return view('livewire.apis.api-head');
        }

        return '<div>403</div>';
    }
}
