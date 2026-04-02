<?php

namespace App\Livewire\Config;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\protocol as Method;
use App\Models\domain_routing as Router;
use Illuminate\Support\Facades\Auth;

class ProtocolFiltering extends Component
{
    public $update;
    public $list;
    public $host;
    public $methods;
    public $domain;

    public function mount()
    {
        $this->domain;
        $route = Router::where('user_id', '=', auth::user()->id)->where('host', '=', $this->domain)->get();
        if (count($route) > 0) {
            $this->update = '';

            if (count($route) > 0) {
                $this->host = $route[0]['host'];
                $this->methods = Method::where('host', '=', $this->host)->get();
            } else {
                $this->host = 'Not Configured';
                $this->methods = 'Not Configured';
            }
        }
    }

    public function save()
    {   
        if($this->host =='') return '<div>403</div>';
        $methods = array_filter(array_map('trim', explode("\n", $this->list)));
        DB::table('protocols')->where('host', '=', $this->host)->delete();
        foreach ($methods as $method) {

            if ($method != '') {
                Method::create([
                    'host' => $this->host,
                    'method' => $method,
                ]);
            }
        }

        $this->update = 'true';
    }


    public function render()
    {   
        if($this->host =='') return '<div>403</div>';
        return view('livewire.config.protocol-filtering');
    }
}
