<?php

namespace App\Livewire\Apis\Cluster;

use App\Models\cluster;
use Livewire\Component;
use App\Models\cluster_policy;
use App\Models\cluster_policy_list;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PolicyView extends Component
{

    public $cluster;
    public $cluster_policy;
    public $cluster_apis;
    public $user;
    public $domain;
    public $owner;
    public $linked_clusters;

    public function mount()
    {
        $this->cluster_policy;
        $this->domain;
        $this->linked_clusters = cluster::select(DB::raw('count(id) as count'))->where('policy_id','=',$this->cluster_policy->id)->get();
        $this->owner = user::select('name')->where('id','=',$this->cluster_policy->owner)->get();
    }

    public function render()
    {
        return view('livewire.apis.cluster.policy-view');
    }
}
