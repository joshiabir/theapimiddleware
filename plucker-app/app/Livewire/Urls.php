<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\logger;
use App\Models\domain_routing as Router;
use Illuminate\Support\Facades\Auth;
use App\Models\apis;

class Urls extends Component
{
    public $apis;
    public $start_time;
    public $end_time;
    public $host;

    public function mount()
    {
        // $this->start_time = date('Y-m-d H:i:s', strtotime('-12 hours'));
        // $this->end_time = date('Y-m-d H:i:s');
        $this->apis = [];
        $route = Router::where('user_id', '=', Auth::user()->id)->get();
        $this->host = $route[0]['host'];
    }

    public function render()
    {
        // Fetch all URLs for the given host
        $apis = [];
        $apis = Apis::where('host', $this->host)->orderByRaw('LENGTH(url) ASC')->get();


        $tree = [];

        foreach ($apis as $api) {
            $slug = rtrim($api->url, '/'); // Remove trailing '/'
            $segments = explode('/', trim($slug, '/')); // Split into segments

            $current = &$tree; // Reference the root of the tree

            foreach ($segments as $segment) {
                if (!isset($current[$segment])) {
                    $current[$segment] = [];
                }
                $current = &$current[$segment]; // Move reference deeper
            }
        }
        $this->apis = $tree;
        return view('livewire.urls');
    }
}
