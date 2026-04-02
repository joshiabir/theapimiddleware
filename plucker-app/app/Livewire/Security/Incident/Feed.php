<?php

namespace App\Livewire\Security\Incident;

use Livewire\Component;
use App\Models\incidents;
use App\Models\logger;
use Illuminate\Support\Facades\DB;

class Feed extends Component
{

    public $domain;
    public $start_time;
    public $end_time;

    public function mount()
    {
        $this->domain;
        $this->start_time;
        $this->end_time;
    }

    public function render()
    {

        $inc = DB::table('incidents')
            ->join('loggers', 'incidents.log_key', '=', 'loggers.key')
            ->select('incidents.*', 'client', 'url')
            ->where('incidents.status', '!=', 'closed')
            ->where('incidents.host', '=', $this->domain)
            ->where('incidents.created_at', '>=', $this->start_time . ' 00:00:00')
            ->where('incidents.created_at', '<=', $this->end_time . ' 23:59:59')
            ->orderBy('incidents.created_at', 'desc')
            ->paginate(10);

        return view('livewire.security.incident.feed', ['inc' => $inc]);
    }
}
