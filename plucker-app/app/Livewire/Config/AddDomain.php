<?php

namespace App\Livewire\Config;

use App\Models\domain_routing as Router;

use Livewire\Component;

class AddDomain extends Component
{
    public $domain_a;
    public $domain_b;
    public $existing = 'NA';
    public $error;

    public function mount()
    {
        $this->error;
    }

    public function search()
    {
        $check = router::where('host', '=', $this->domain_b . '.plucker.app')->get();
        if (count($check) > 0) {
            $this->existing = 'True';
        } else {
            $this->existing = 'False';
        }
        if ($this->domain_a != '') {
            $this->existing = 'False_2';
            return $this->existing;
        }
        if ($this->domain_b == '') {
            return $this->existing;
        }
    }
    public function render()
    {
        return view('livewire.config.add-domain');
    }
}
