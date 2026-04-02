<?php

namespace App\Livewire\Config;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\domain_routing as Router;
use App\Models\policy;
use Illuminate\Support\Facades\DB;

class UrlFiltering extends Component
{
    public $host;
    public $apis;
    public $type;
    public $update;
    public $list;
    public $domain;

    public function mount()
    {
        $this->domain;
        $route = Router::where('user_id', '=', auth::user()->id)->where('host', '=', $this->domain)->get();
        if (count($route) > 0) {
            $this->host = $route[0]['host'];
            $this->update = '';
            $this->apis = policy::where('host', '=', $this->host)->get();
        }
    }

    public function save()
    {   
        if($this->host =='') return '<div>403</div>';
        Router::where('user_id', '=', Auth::user()->id)
            ->where('host', '=', $this->host)
            ->update(['policy' => $this->type]);


        $apis = array_filter(array_map('trim', explode("\n", $this->list)));

        DB::table('policies')->where('host', '=', $this->host)->delete();
        foreach ($apis as $api) {
            policy::create([
                'host' => $this->host,
                'url' => $api,
                'type' => $this->type,
            ]);
        }

        $this->update = 'true';
    }

    public function render()
    {   
        if($this->host =='') return '<div>403</div>';
        $this->type = Router::where('host', '=', $this->host)->get();
        $this->type = $this->type[0]['policy'];

        return view('livewire.config.url-filtering');
    }
}
