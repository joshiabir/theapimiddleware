<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use App\Models\domain_routing as Router;
use Illuminate\Support\Facades\Auth;

class AddDomain extends Component
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

    public function new_domain()
    {
        $this->add_new_domain = '';
    }
    public function save_new()
    {
        $existing = Router::where('host', '=', $this->host_new)->get();
        if (count($existing) > 0) {
            $this->update = 'false';
        } else {
            Router::create([
                'user_id' => Auth::user()->id,
                'host' => $this->host_new,
                'ip' => $this->ip_new,
                'protocol' => $this->protocol_new,
                'status' => 'Active',
                'policy' => 'Default',
                'rate_limit' => '60',
                'client_policy' => 'NULL',
            ]);
            $this->update = 'true';
        }

        return redirect('settings',['domain'=>$this->domain]);
    }
    public function render()
    {
        return view('livewire.settings.add-domain');
    }
}
