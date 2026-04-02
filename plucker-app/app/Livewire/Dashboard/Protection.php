<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\domain_routing as Router;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Protection extends Component
{
    public $domain;
    public $start_time;
    public $end_time;
    public $host = '';

    public $clean        = 0;
    public $blocked      = 0;
    public $dlp          = 0;
    public $rate_limited = 0;
    public $errors       = 0;

    public function mount()
    {
        $route = Router::where('user_id', Auth::user()->id)->where('host', $this->domain)->first();
        if ($route) $this->host = $route->host;
    }

    public function render()
    {
        if ($this->host !== '') {
            $start = $this->start_time . ' 00:00:00';
            $end   = $this->end_time   . ' 23:59:59';

            $stats = DB::selectOne(
                "SELECT
                    SUM(CASE WHEN analysis = 'PASS' THEN 1 ELSE 0 END) as clean,
                    SUM(CASE WHEN analysis = 'DLP'  THEN 1 ELSE 0 END) as dlp,
                    SUM(CASE WHEN analysis = 'REQF' THEN 1 ELSE 0 END) as errors,
                    SUM(CASE WHEN analysis IN ('SRVRL','DGRL','DURL','CGRL','CURL') THEN 1 ELSE 0 END) as rate_limited,
                    SUM(CASE WHEN analysis IN ('DNF','HTPSF','BADACS','IPWLF','IPBLF','AUTHVF') THEN 1 ELSE 0 END) as blocked
                FROM loggers
                WHERE host = ? AND created_at BETWEEN ? AND ?",
                [$this->host, $start, $end]
            );

            $this->clean        = (int) ($stats->clean ?? 0);
            $this->dlp          = (int) ($stats->dlp ?? 0);
            $this->errors       = (int) ($stats->errors ?? 0);
            $this->rate_limited = (int) ($stats->rate_limited ?? 0);
            $this->blocked      = (int) ($stats->blocked ?? 0);
        }
        return view('livewire.dashboard.protection');
    }
}
