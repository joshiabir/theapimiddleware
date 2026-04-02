<?php

namespace App\Livewire\Config;

use Livewire\Component;
use App\Models\domain_routing as Router;
use App\Models\license;
use Illuminate\Support\Facades\Auth;

class RateLimit extends Component
{
    public $limit;
    public $max_rpm;
    public $last_modified;
    public $update;
    public $domain;
    public $host = '';

    public function mount()
    {
        $route = Router::where('user_id', Auth::user()->id)->where('host', $this->domain)->first();
        if ($route) {
            $this->limit         = $route->rate_limit;
            $this->last_modified = $route->updated_at;
            $this->host          = $route->host;
        }

        $lic = license::where('email', Auth::user()->email)->first();
        $this->max_rpm = $lic ? (int) $lic->rpm : 60;

        $this->update = '';
    }

    public function save()
    {
        if ($this->host === '') return;

        $this->limit = min((int) $this->limit, $this->max_rpm);

        Router::where('user_id', Auth::user()->id)
            ->where('host', $this->host)
            ->update(['rate_limit' => $this->limit]);

        $this->update = 'true';
    }

    public function render()
    {
        if ($this->host === '') return '<div></div>';
        return view('livewire.config.rate-limit');
    }
}
