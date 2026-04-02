<?php

namespace App\Livewire\Logs;

use Livewire\Component;
use App\Models\logger;
use App\Models\dlp_log;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Details extends Component
{
    public $log;
    public $dlp;
    public $key;

    public function mount()
    {
        $this->log;
        $this->dlp;
        $this->key;
    }
    public function render()
    {

        return view('livewire.logs.details');
    }
}
