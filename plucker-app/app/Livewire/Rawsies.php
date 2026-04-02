<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\logger;
use App\Models\domain_routing as Router;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

class Rawsies extends Component
{
    public $rawsies;
    public $start_time;
    public $end_time;

    public function mount()
    {
        // $this->start_time = date('Y-m-d H:i:s', strtotime('-12 hours'));
        // $this->end_time = date('Y-m-d H:i:s');
    }
    
    #[On('time-updated')]
    public function updatePostList($start_time, $end_time)
    {
        $this->start_time = $start_time;
        $this->end_time = $end_time;
    }


    public function render()
    {
        $this->rawsies = logger::where('created_at', '>=', $this->start_time)
            ->where('created_at', '<=', $this->end_time)
            ->get();
        return view('livewire.rawsies');
    }
}
