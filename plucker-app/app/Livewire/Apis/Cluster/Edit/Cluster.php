<?php

namespace App\Livewire\Apis\Cluster\Edit;

use Livewire\Component;
use App\Models\cluster as clusties;

class Cluster extends Component
{
    public $cluster_id;
    public $update;
    public $status;
    public $url;
    public $name;
    public $checked = '';
    public $warning = '';
    public function mount()
    {
        if ($this->status == 'Active') {
            $this->checked = 'checked';
            $this->warning = "wire:confirm='Are you sure you want to Turn Off the Cluster $this->name'";
        }
    }
    public function manage()
    {
        if ($this->status == 'Active')
            clusties::where('id', '=', $this->cluster_id)->update([
                'status' => 'Disabled'
            ]);
        if ($this->status == 'Disabled') {
            clusties::where('id', '=', $this->cluster_id)->update([
                'status' => 'Active'
            ]);
        }
    }
    public function render()
    {
        return view('livewire.apis.cluster.edit.cluster');
    }
}
