<?php

namespace App\Livewire\Apis\Cluster\Edit;

use Livewire\Component;
use App\Models\cluster_api;

class RemoveApi extends Component
{
    public $api_id;
    public $cluster_id;
    public $domain;
    public $start_date;
    public $end_date;
    public $update;
    public $status;
    public $url;
    public $checked = '';
    public $warning = '';

    public function mount()
    {
        $this->api_id;
        if ($this->status == 'Active') {
            $this->checked = 'checked';
            $this->warning = "wire:confirm='Are you sure you want to remove the API $this->url from the cluster. The API will be unaccessiable unless assigned to a different Cluster.'";
        }
    }
    public function manage()
    {
            cluster_api::where('api_id', '=', $this->api_id)->update([
                'cluster_id' => 'Free'
            ]);
        return redirect(route('api_cluster_edit',['domain'=>base64_encode($this->domain),'cluster'=>$this->cluster_id,'start_datetime'=>$this->start_date,'end_datetime'=>$this->end_date]));
    }
    public function render()
    {
        return view('livewire.apis.cluster.edit.remove-api');
    }
}
