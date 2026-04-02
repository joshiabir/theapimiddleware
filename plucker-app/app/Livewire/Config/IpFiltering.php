<?php

namespace App\Livewire\Config;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\domain_routing as Router;
use App\Models\client;
use Illuminate\Support\Facades\DB;

class IpFiltering extends Component
{
    public $host;
    public $ips;
    public $type;
    public $update;
    public $list;
    public $domain;

    public function mount()
    {
        $this->domain;
        $route = Router::where('user_id', '=', auth::user()->id)->where('host', '=', $this->domain)->get();
        if (count($route) > 0) {
            $this->host = $route[0]['host'];
            $this->update = '';
            $this->ips = client::where('host', '=', $this->host)->get();
        }
    }

    public function save()
    {
        if ($this->host == '') return '<div>403</div>';
        Router::where('user_id', '=', Auth::user()->id)
            ->where('host', '=', $this->host)
            ->update(['client_policy' => $this->type]);


        $ips = array_filter(array_map('trim', explode("\n", $this->list)));

        DB::table('clients')->where('host', '=', $this->host)->delete();
        foreach ($ips as $ip) {
            client::create([
                'host' => $this->host,
                'ip' => $ip,
            ]);
        }

        $this->update = 'true';
    }

    public function render()
    {
        if ($this->host == '') return '<div>403</div>';
        $this->type = Router::where('host', '=', $this->host)->get();
        $this->type = $this->type[0]['client_policy'];
        return view('livewire.config.ip-filtering');
    }
}
