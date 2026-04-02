<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\logger;

class incident_responder implements ShouldQueue
{
    use Queueable;

    protected $key;
    /**
     * Create a new job instance.
     */
    public function __construct($key)
    {
        $this->key = $key;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $logs = logger::where('log_id', '=', $this->key)->get();

        foreach ($logs as $log) {
            
        }

    }
}
