<?php

namespace App\Livewire\Apis\Cluster\Edit;

use Livewire\Component;
use App\Models\cluster_api;

class ApiTable extends Component
{
    public $api_id;
    public $domain;
    public $start_time;
    public $end_time;
    public $update;
    public $status;
    public $url;
    public $checked = '';
    public $warning ='';

    public function mount()
    {
        $this->api_id;
        if ($this->status == 'Active') {
            $this->checked = 'checked';
            $this->warning= "wire:confirm='Are you sure you want to disable the API $this->url'";
        }
    }
    public function manage()
    {
        if ($this->status == 'Active')
            cluster_api::where('api_id', '=', $this->api_id)->update([
                'status' => 'Disabled'
            ]);
        if ($this->status == 'Disabled') {
            cluster_api::where('api_id', '=', $this->api_id)->update([
                'status' => 'Active'
            ]);
        }
    }
    public function render()
    {
        return view('livewire.apis.cluster.edit.api-table');
    }
}
