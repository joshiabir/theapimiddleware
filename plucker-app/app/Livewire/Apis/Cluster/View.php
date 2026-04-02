<?php

namespace App\Livewire\Apis\Cluster;

use Livewire\Component;
use App\Models\cluster_api;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\cluster_policy;

class View extends Component
{

    public $cluster;
    public $api_count;
    public $user;
    public $domain;

    public function mount()
    {
        $this->cluster;
        $this->domain;
        $apis = cluster_api::select(DB::raw('count(id) as count'))->where('cluster_id', '=', $this->cluster->id)->get();
        $user = user::select('name')->where('id','=',$this->cluster->owner)->get();
        $policy = cluster_policy::select('name')->where('id','=',$this->cluster->policy_id)->get();
        if(count($policy) > 0)
        {
            $this->cluster->policy_id = $policy[0]->name;
        }
        $this->user = $user[0]->name;
        $this->api_count = $apis[0]->count;
    }
    public function render()
    {
        return view('livewire.apis.cluster.view');
    }
}
