<?php

namespace App\Livewire\Apis\Cluster\Edit\Policy;

use App\Models\dlp_policy;
use Livewire\Component;
use App\Models\cluster_policy;
use App\Models\cluster_policy_list;
use Illuminate\Support\Facades\Auth;

class All extends Component
{
    public $policy_id;
    public $name;
    public $description;
    public $host;
    public $new_user;

    public $logging_http = TRUE;
    public $logging_gdpr = TRUE;
    public $redact_auth = FALSE;
    public $required_auth = FALSE;
    public $encryption = 'https';
    public $global_rpm = '60';
    public $user_rpm = '10';
    public $anomally_detection = FALSE;
    public $honey_pots = FALSE;
    public $pii_dlp = 'alert';
    public $access_type = 'black';
    public $users = [];
    public $dlp_list;
    public $new_list_type;
    public $new_list_value;
    public $update_list = [];

    public function mount()
    {
        $policy = cluster_policy::where('id', '=', $this->policy_id)->get();
        if (count($policy) > 0) {
            $this->name = $policy[0]->name;
            $this->description = $policy[0]->description;
        }
        if ($this->policy_id == 'new') {
            $this->name = 'My new Policy';
            $this->description = '';
        }

        $this->dlp_list = dlp_policy::where('cluster_policy_id', '=', $this->policy_id)
            ->get();

        $policy_list = cluster_policy_list::select('name', 'value')->where('policy_id', '=', $this->policy_id)->get();
        foreach ($policy_list as $p) {
            if ($p->name == 'logging_http' && $p->value == '0')
                $this->logging_http = FALSE;
            if ($p->name == 'logging_gdpr' && $p->value == '0')
                $this->logging_gdpr = FALSE;
            if ($p->name == 'redact_auth' && $p->value == '1')
                $this->redact_auth = TRUE;
            if ($p->name == 'required_auth' && $p->value == '1')
                $this->required_auth = TRUE;
            if ($p->name == 'encryption')
                $this->encryption = $p->value;
            if ($p->name == 'global_rpm')
                $this->global_rpm = $p->value;
            if ($p->name == 'user_rpm')
                $this->user_rpm = $p->value;
            if ($p->name == 'anomally_detection' && $p->value == '1')
                $this->anomally_detection = TRUE;
            if ($p->name == 'honey_pots' && $p->value == '1')
                $this->honey_pots = TRUE;
            if ($p->name == 'pii_dlp')
                $this->pii_dlp = $p->value;
            if ($p->name == 'access_type')
                $this->access_type = $p->value;
            if ($p->name == 'users')
                $this->users = json_decode($p->value, true);
        }


    }

    public function add_user()
    {
        $new_user = $this->new_user;
        $array = $this->users;
        $array[$new_user] = $new_user; //This is right do not change to array refrence
        $this->users = $array;
        $this->new_user = '';
    }

    public function remove_user($ip)
    {
        unset($this->users[$ip]);
    }

    public function add_list()
    {
        $new_list = [
            'value' => $this->new_list_value,
            'type' => $this->new_list_type,
            'id' => time()
        ];
        array_push($this->update_list, $new_list);
    }
    public function remove_list($id)
    {
        dlp_policy::where('id', $id)->delete();
    }
    public function save()
    {
        if ($this->policy_id == 'new') {
            $policy = cluster_policy::create([
                'name' => $this->name,
                'owner' => auth::user()->id,
                'host' => $this->host,
                'description' => $this->description,
                'status' => 'Active',
            ]);
            $this->policy_id = $policy->id;
        }



        $records = [
            [
                'policy_id' => $this->policy_id,
                'host' => $this->host,
                'name' => 'logging_http',
                'value' => $this->logging_http
            ],
            [
                'policy_id' => $this->policy_id,
                'host' => $this->host,
                'name' => 'logging_gdpr',
                'value' => $this->logging_gdpr
            ],
            [
                'policy_id' => $this->policy_id,
                'host' => $this->host,
                'name' => 'redact_auth',
                'value' => $this->redact_auth
            ],
            [
                'policy_id' => $this->policy_id,
                'host' => $this->host,
                'name' => 'required_auth',
                'value' => $this->required_auth
            ],

            [
                'policy_id' => $this->policy_id,
                'host' => $this->host,
                'name' => 'encryption',
                'value' => $this->encryption
            ],

            [
                'policy_id' => $this->policy_id,
                'host' => $this->host,
                'name' => 'global_rpm',
                'value' => $this->global_rpm
            ],
            [
                'policy_id' => $this->policy_id,
                'host' => $this->host,
                'name' => 'user_rpm',
                'value' => $this->user_rpm
            ],
            [
                'policy_id' => $this->policy_id,
                'host' => $this->host,
                'name' => 'anomally_detection',
                'value' => $this->anomally_detection
            ],
            [
                'policy_id' => $this->policy_id,
                'host' => $this->host,
                'name' => 'honey_pots',
                'value' => $this->honey_pots
            ],
            [
                'policy_id' => $this->policy_id,
                'host' => $this->host,
                'name' => 'pii_dlp',
                'value' => $this->pii_dlp
            ],
            [
                'policy_id' => $this->policy_id,
                'host' => $this->host,
                'name' => 'access_type',
                'value' => $this->access_type
            ],
            [
                'policy_id' => $this->policy_id,
                'host' => $this->host,
                'name' => 'users',
                'value' => json_encode($this->users)
            ],


        ];

        foreach ($records as $record) {
            cluster_policy_list::updateOrCreate(
                [
                    'policy_id' => $record['policy_id'],
                    'host' => $record['host'],
                    'name' => $record['name']
                ],
                ['value' => $record['value']]
            );
        }
        cluster_policy::where('id', '=', $this->policy_id)
            ->where('host', '=', $this->host)->update(
                [
                    'name' => $this->name,
                    'description' => $this->description
                ]
            );

        
        foreach ($this->update_list as $list) {

            if($list['type'] != '' || $list['value'] != '')
            dlp_policy::create([
                'type' => $list['type'],
                'value' => $list['value'],
                'cluster_policy_id' => $this->policy_id,
                'domain' => $this->host,
            ]);
        }

        $this->new_list_type = '';
        $this->new_list_value = '';


        session()->flash('status', 'Policy successfully updated.');
    }

    public function render()
    {
        return view('livewire.apis.cluster.edit.policy.all');
    }
}
