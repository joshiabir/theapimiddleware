<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\domain_routing as Router;
use App\Models\response_time;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Stats extends Component
{
    public $domain;
    public $start_time;
    public $end_time;
    public $host = '';

    public $total          = 0;
    public $blocked        = 0;
    public $dlp            = 0;
    public $avg_rt         = 0;
    public $error_rate     = 0;
    public $unique_clients = 0;

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
                    COUNT(*) as total,
                    SUM(CASE WHEN analysis NOT IN ('PASS','DLP','REQF','Pending') THEN 1 ELSE 0 END) as blocked,
                    SUM(CASE WHEN analysis = 'DLP' THEN 1 ELSE 0 END) as dlp,
                    SUM(CASE WHEN CAST(response_status AS UNSIGNED) >= 400 THEN 1 ELSE 0 END) as errors,
                    COUNT(DISTINCT client) as unique_clients
                FROM loggers
                WHERE host = ? AND created_at BETWEEN ? AND ?",
                [$this->host, $start, $end]
            );

            $rt = response_time::where('host', $this->host)
                ->where('created_at', '>=', $start)
                ->where('created_at', '<=', $end)
                ->where('response_time', '>', 0)
                ->selectRaw('AVG(response_time) as avg_rt')
                ->first();

            $this->total          = (int) ($stats->total ?? 0);
            $this->blocked        = (int) ($stats->blocked ?? 0);
            $this->dlp            = (int) ($stats->dlp ?? 0);
            $this->unique_clients = (int) ($stats->unique_clients ?? 0);
            $this->error_rate     = $this->total > 0
                ? round(($stats->errors / $this->total) * 100, 1)
                : 0;
            $this->avg_rt = $rt && $rt->avg_rt
                ? round((float) $rt->avg_rt * 1000, 0)
                : 0;
        }
        return view('livewire.dashboard.stats');
    }
}
