<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Register extends Component
{
    
    public $email = "";
    public $register = FALSE;

    public function register(){

       // DB::select("insert into users ('email','time') values (?,?)",[$this->email,time()]);

        $this->register = TRUE;
    }
    public function render()
    {
        return view('livewire.register');
    }
}
