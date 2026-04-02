<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\apis;
use App\Models\domain_routing as Router;
use Illuminate\Support\Facades\Auth;
use App\Models\logger;
use Illuminate\Support\Facades\DB;


class ApiList extends Component
{

    public $apis = [];
    public $host = '';
    public $key;
    public $domain = '';
    public $start_time;
    public $end_time;
    public $list;

    public function mount()
    {
        $this->start_time;
        $this->end_time;
        $this->domain;
        $route = Router::where('user_id', '=', auth::user()->id)->where('host', '=', $this->domain)->get();
        if (count($route) > 0) {

            $this->host = $route[0]['host'];
        }
    }
    public function render()
    {
        $startTime = $this->start_time . ' 00:00:00';
        $endTime = $this->end_time . ' 23:59:59';

        if ($this->host != '') {
            $apis = [];
            $this->apis = DB::select("SELECT 
                                            apis.api_id,
                                            apis.url AS api_url,
                                            apis.host AS api_host,
                                            clusters.name AS cluster_name,
                                            apis.url as url,
                                            COUNT(DISTINCT loggers.client) AS clients,

                                            COUNT(loggers.key) AS total_logs,

                                            COUNT(CASE 
                                                WHEN loggers.analysis IS NOT NULL AND loggers.analysis != 'Passed' THEN 1 
                                            END) AS blocked_logs

                                        FROM apis

                                        -- Join to cluster_apis to find associated cluster
                                        LEFT JOIN cluster_apis ON cluster_apis.api_id = apis.api_id

                                        -- Join to clusters to get cluster name
                                        LEFT JOIN clusters ON clusters.id = cluster_apis.cluster_id

                                        -- Left join to loggers, match logs by URL and date range
                                        LEFT JOIN loggers ON loggers.url = apis.url
                                            AND loggers.created_at BETWEEN ? AND ?  -- Replace with your dates
                                        WHERE loggers.host = ?
                                        GROUP BY 
                                            apis.api_id, apis.url, apis.host, clusters.name

                                        ORDER BY total_logs DESC;
                                        ", [$startTime, $endTime, $this->domain]);


            return view('livewire.dashboard.api-list', ['domain' => $this->host]);
        }
        return '<div>403</div>';
    }
}
