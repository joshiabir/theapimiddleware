<?php

use App\Livewire\Config\Dlp;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = $this->createUser();

    DB::table('domain_routings')->insert([
        'user_id'       => $this->user->id,
        'host'          => 'api.example.com',
        'ip'            => '10.0.0.1',
        'protocol'      => 'http',
        'status'        => 'Active',
        'policy'        => 'Strict',
        'client_policy' => 'default',
        'rate_limit'    => '100',
    ]);
});

// ── Tests ───────────────────────────────────────────────────────────────────

it('renders without errors for the user\'s domain', function () {
    $this->actingAs($this->user);

    Livewire::test(Dlp::class, ['domain' => 'api.example.com'])
            ->assertOk();
});

it('saves keyword DLP policies', function () {
    $this->actingAs($this->user);

    Livewire::test(Dlp::class, ['domain' => 'api.example.com'])
            ->set('keyword', "password\napikey")
            ->set('pattern', '')
            ->set('list', '')
            ->call('save');

    $keywords = DB::table('dlp_policies')
        ->where('domain', 'api.example.com')
        ->where('type', 'Keyword')
        ->pluck('value')
        ->toArray();

    expect($keywords)->toContain('password')->toContain('apikey');
});

it('saves pattern DLP policies', function () {
    $this->actingAs($this->user);

    Livewire::test(Dlp::class, ['domain' => 'api.example.com'])
            ->set('keyword', '')
            ->set('pattern', '\d{3}-\d{2}-\d{4}')
            ->set('list', '')
            ->call('save');

    $patterns = DB::table('dlp_policies')
        ->where('domain', 'api.example.com')
        ->where('type', 'Pattern')
        ->pluck('value')
        ->toArray();

    expect($patterns)->toContain('\d{3}-\d{2}-\d{4}');
});

it('saves bypass URLs', function () {
    $this->actingAs($this->user);

    Livewire::test(Dlp::class, ['domain' => 'api.example.com'])
            ->set('keyword', '')
            ->set('pattern', '')
            ->set('list', "/health\n/ping")
            ->call('save');

    $bypasses = DB::table('dlp_bypasses')
        ->where('domain', 'api.example.com')
        ->pluck('url')
        ->toArray();

    expect($bypasses)->toContain('/health')->toContain('/ping');
});

it('replaces existing policies on save instead of appending', function () {
    $this->actingAs($this->user);

    // First save
    Livewire::test(Dlp::class, ['domain' => 'api.example.com'])
            ->set('keyword', 'old-secret')
            ->set('pattern', '')
            ->set('list', '')
            ->call('save');

    // Second save with different value
    Livewire::test(Dlp::class, ['domain' => 'api.example.com'])
            ->set('keyword', 'new-secret')
            ->set('pattern', '')
            ->set('list', '')
            ->call('save');

    $count = DB::table('dlp_policies')
        ->where('domain', 'api.example.com')
        ->where('type', 'Keyword')
        ->count();

    expect($count)->toBe(1); // Only the new one should exist
});

it('does not save when the domain does not belong to the user', function () {
    $other = $this->createUser(['email' => 'other@example.com']);
    $this->actingAs($other);

    Livewire::test(Dlp::class, ['domain' => 'api.example.com'])
            ->set('keyword', 'injected')
            ->call('save');

    $count = DB::table('dlp_policies')
        ->where('domain', 'api.example.com')
        ->count();

    expect($count)->toBe(0);
});
