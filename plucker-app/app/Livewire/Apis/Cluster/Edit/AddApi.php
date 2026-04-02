<?php

namespace App\Livewire\Apis\Cluster\Edit;

use Livewire\Component;
use App\Models\cluster_api;

class AddApi extends Component
{
    public $url;
    public $api_array_f;
    public $api_array_o;
    public $api_id;
    public $created_at;
    public $status;
    public $cluster_id;
    public $api_ids = [];
    public $domain;
    public $start_date;
    public $end_date;

    public function add()
    {
        foreach ($this->api_ids as $id) {
            cluster_api::updateOrinsert(
                [
                    'api_id' => $id
                ],
                [
                    'cluster_id' => $this->cluster_id,
                    'status' => 'Active'
                ]
            );
        }

        return redirect(route('api_cluster_edit',['domain'=>base64_encode($this->domain),'cluster'=>$this->cluster_id,'start_datetime'=>$this->start_date,'end_datetime'=>$this->end_date]));
    }
    public function render()
    {
        return view('livewire.apis.cluster.edit.add-api');
    }
}
