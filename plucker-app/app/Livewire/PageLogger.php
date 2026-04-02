<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Http\Request;
use Livewire\Attributes\On; 
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;


class PageLogger extends Component
{
    public $email;
    public $currentUrl;
    public $tg='none';
    protected $listeners = ['loadComponent' => 'load'];

    public function mount()
    {
        $this->email;
        $this->currentUrl = request()->url();
        $this->tg = request()->query('tg', '');
    }
    function slack_hook($message)
    {
        $url = config('services.slack.webhook_url');
        if ($url) Http::withoutVerifying()->post($url, ['text' => $message]);
    }

    #[On('page-loaded')]
    public function load(Request $request)
    {
        $user = 'Guest';
        if (Auth::user() !== '') $user = Auth::user();
        $this->slack_hook("User $user in on $this->currentUrl by tag - $this->tg with " . implode(',', $request->ips())." and ".$request->userAgent());
    }

    public function render()
    {
        return view('livewire.page-logger');
    }
}
