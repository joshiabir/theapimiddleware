<?php

namespace App\Livewire\Apis\Cluster\Edit;

use Livewire\Component;
use App\Models\cluster_policy as policies;
class Policy extends Component
{
    public $policy_id;
    public $update;
    public $status;
    public $url;
    public $name;
    public $checked = '';
    public $warning = '';
    public $linked_cluster;
    public function mount()
    {
        if ($this->status == 'Active') {
            $this->checked = 'checked';
            $this->warning = "wire:confirm='Are you sure you want to Turn Off the Policy $this->name, all the linked clusters will '";
        }
    }
    public function manage()
    {
        if ($this->status == 'Active')
            if($this->linked_cluster == 0)
            {
                policies::where('id', '=', $this->policy_id)->update([
                    'status' => 'Disabled'
                ]);
            }else{
                echo"alert('There are more than one clsuter linked to the Polciy, please remove the policy from all clusters to disable the policy')";
            }
           
        if ($this->status == 'Disabled') {
            policies::where('id', '=', $this->policy_id)->update([
                'status' => 'Active'
            ]);
        }
    }
    public function render()
    {
        return view('livewire.apis.cluster.edit.policy');
    }
}
