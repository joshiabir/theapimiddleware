<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use App\Models\domain_routing as Router;
use Illuminate\Support\Facades\Auth;

class Domain extends Component
{
    public $host;
    public $ip;
    public $host_new;
    public $ip_new;
    public $last_modified;
    public $protocol;
    public $protocol_new;
    public $update;
    public $add_new_domain;
    public $domain;
    public $discover;


    public function mount()
    {
        $this->domain;
        $route = Router::where('user_id', '=', Auth::user()->id)
            ->where('host', '=', $this->domain)->get();
        if (count($route) > 0) {
            $this->host = $route[0]['host'];
            $this->ip = $route[0]['ip'];
            $this->last_modified = $route[0]['updated_at'];
            $this->protocol = $route[0]['protocol'];
            $this->discover = $route[0]['policy'];

        } else {
            $this->host = 'Not Configured';
            $this->ip = 'Not Configured';
            $this->last_modified = '0';
            $this->protocol = 'Not Configured';
            $this->discover = 'Active';
        }
        $this->add_new_domain = '';
        $this->update = '';
    }

    public function new_domain()
    {
        $this->add_new_domain = '';
    }

    public function save()
    {
        
        Router::where('user_id', '=', Auth::user()->id)
            ->where('host', '=', $this->host)
            ->update(['ip' => $this->ip, 'protocol' => $this->protocol,'policy'=>$this->discover]);

        $this->update = 'true';
    }
    public function delete()
    {
        Router::where('user_id', '=', Auth::user()->id)
            ->where('host', '=', $this->host)
            ->delete();

        $this->update = 'true';
    }
    public function render()
    {
        return view('livewire.settings.domain');
    }
}
