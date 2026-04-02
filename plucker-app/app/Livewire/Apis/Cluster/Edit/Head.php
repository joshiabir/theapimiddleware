<?php

namespace App\Livewire\Apis\Cluster\Edit;

use Livewire\Component;
use App\Models\cluster_policy;
use App\Models\cluster;
use Illuminate\Support\Facades\Auth;

class Head extends Component
{

    public $cluster_p;
    public $name;
    public $description;
    public $policy_id;
    public $policy;
    public $domain;
    public $policy_list = [];
    public $cluster_id;
    public $error = [];
    public $update;

    public function mount()
    {
        $this->name;
        $this->domain;
        $this->policy = 'None';
        $this->name;
        $this->description;
        $this->policy_id;
        $policy = cluster_policy::select('name')->where('id', '=', $this->policy_id)->get();
        if (count($policy) > 0) {
            $this->policy = $policy[0]->name;
        }
        $policy_list = cluster_policy::select('name', 'id', 'created_at', 'owner')->where('host', '=', $this->domain)->where('status','=','Active')->get();
        if (count($policy_list) > 0) {
            $this->policy_list = $policy_list;
        }
        $cluster = cluster::where('name', '=', $this->name)->where('host', '=', $this->domain)->get();
        foreach ($cluster as $cl) {
            $this->description = $cl->description;
        }
    }

    public function save()
    {
        if ($this->cluster_id == 'new') {
            cluster::create([
                    'name' => $this->name,
                    'policy_id' => $this->policy_id,
                    'description' => $this->description ?? '',
                    'host' => $this->domain,
                    'status' => 'Active',
                    'owner' => auth::user()->id
                ]);
            return redirect(route('api_cluster', ['domain' => base64_encode($this->domain)]));
        }

        cluster::where('id', '=', $this->cluster_id)->where('host', '=', $this->domain)
            ->update([
                'name' => $this->name,
                'policy_id' => $this->policy_id,
                'description' => $this->description ?? '',
            ]);
        // dd($this->policy_id);
        $this->update = TRUE;
    }
    public function render()
    {

        return view('livewire.apis.cluster.edit.head');
    }
}
