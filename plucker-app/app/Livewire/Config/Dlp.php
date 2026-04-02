<?php

namespace App\Livewire\Config;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\domain_routing as Router;
use App\Models\dlp_policy;
use App\Models\dlp_bypass;
use Illuminate\Support\Facades\DB;

class Dlp extends Component
{


    public $keywords;
    public $patterns;
    public $keyword;
    public $pattern;
    public $list;
    public $apis;
    public $host;
    public $update;
    public $domain;


    public function mount()
    {
        $this->domain;
        $route = Router::where('user_id', '=', auth::user()->id)->where('host', '=', $this->domain)->get();
        if (count($route) > 0) {
            $this->host = $route[0]['host'];
            $this->update = '';
            $this->apis = dlp_bypass::where('domain', '=', $this->host)->get();
            $this->keywords = dlp_policy::where('domain', '=', $this->host)
                ->where('type', '=', 'Keyword')->get();
            $this->patterns = dlp_policy::where('domain', '=', $this->host)
                ->where('type', '=', 'Pattern')->get();
        }
    }
    public function save()
    {
        if($this->host =='') return '<div>403</div>';
        $apis = array_filter(array_map('trim', explode("\n", $this->list)));

        DB::table('dlp_bypasses')->where('domain', '=', $this->host)->delete();
        DB::table('dlp_policies')->where('domain', '=', $this->host)->delete();

        foreach ($apis as $api) {
            dlp_bypass::create([
                'domain' => $this->host,
                'url' => $api,
            ]);
        }

        $keywords = array_filter(array_map('trim', explode("\n", $this->keyword)));
        foreach ($keywords as $keyword) {
            dlp_policy::create([
                'domain' => $this->host,
                'type' => 'Keyword',
                'value' => $keyword,
            ]);
        }

        $patterns = array_filter(array_map('trim', explode("\n", $this->pattern)));
        foreach ($patterns as $pattern) {
            dlp_policy::create([
                'domain' => $this->host,
                'type' => 'Pattern',
                'value' => $pattern,
            ]);
        }

        $this->update = 'true';
    }

    public function render()
    {   
        if($this->host =='') return '<div>403</div>';
        return view('livewire.config.dlp');
    }
}
