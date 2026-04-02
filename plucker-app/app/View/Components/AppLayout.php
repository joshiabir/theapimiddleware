<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;
use App\Models\apis;
use App\Models\domain_routing as Router;
use Illuminate\Support\Facades\Auth;
use App\Models\logger;
use Illuminate\Support\Facades\DB;


class AppLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public $domain;
    public $start_time;
    public $end_time;
    public $breadcrumb;

    public function __construct($data)
    {
        $this->domain = $data[0];
        $this->start_time = $data[1];
        $this->end_time = $data[2];
    }
    public function render(): View
    {
        return view('layouts.app');
    }
}
