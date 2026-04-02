<?php

use App\Livewire\Dashboard\Stats;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;

beforeEach(function () {
    $this->createFionaSchema();
    $this->user = $this->createUser();
});

// ── Tests ───────────────────────────────────────────────────────────────────

it('renders without errors', function () {
    $this->actingAs($this->user);

    Livewire::test(Stats::class, [
        'domain'     => '',
        'start_time' => now()->subDays(7)->toDateString(),
        'end_time'   => now()->toDateString(),
    ])->assertOk();
});

it('shows zeros when no domain is matched to the user', function () {
    $this->actingAs($this->user);

    $component = Livewire::test(Stats::class, [
        'domain'     => 'notmine.example.com',
        'start_time' => now()->subDays(7)->toDateString(),
        'end_time'   => now()->toDateString(),
    ]);

    $component->assertSet('total', 0)
              ->assertSet('blocked', 0)
              ->assertSet('dlp', 0);
});

it('counts total requests and blocked events for the user domain', function () {
    $this->actingAs($this->user);

    DB::table('domain_routings')->insert([
        'user_id' => $this->user->id, 'host' => 'mine.example.com', 'ip' => '10.0.0.1',
        'protocol' => 'http', 'status' => 'Active', 'policy' => 'Strict',
        'client_policy' => 'default', 'rate_limit' => '100',
    ]);

    $now = now()->toDateTimeString();
    DB::table('loggers')->insert([
        ['key' => '1.0', 'host' => 'mine.example.com', 'analysis' => 'PASS',     'created_at' => $now, 'updated_at' => $now],
        ['key' => '2.0', 'host' => 'mine.example.com', 'analysis' => 'DLP',      'created_at' => $now, 'updated_at' => $now],
        ['key' => '3.0', 'host' => 'mine.example.com', 'analysis' => 'IPBLF',    'created_at' => $now, 'updated_at' => $now],
        ['key' => '4.0', 'host' => 'other.example.com','analysis' => 'PASS',     'created_at' => $now, 'updated_at' => $now],
    ]);

    $component = Livewire::test(Stats::class, [
        'domain'     => 'mine.example.com',
        'start_time' => now()->subDay()->toDateString(),
        'end_time'   => now()->toDateString(),
    ]);

    $component->assertSet('total', 3)     // 3 rows for this host
              ->assertSet('blocked', 1)   // IPBLF is a block code
              ->assertSet('dlp', 1);      // 1 DLP row
});

it('respects the date range filter', function () {
    $this->actingAs($this->user);

    DB::table('domain_routings')->insert([
        'user_id' => $this->user->id, 'host' => 'mine.example.com', 'ip' => '10.0.0.1',
        'protocol' => 'http', 'status' => 'Active', 'policy' => 'Strict',
        'client_policy' => 'default', 'rate_limit' => '100',
    ]);

    DB::table('loggers')->insert([
        ['key' => '1.0', 'host' => 'mine.example.com', 'analysis' => 'PASS',
         'created_at' => now()->toDateTimeString(), 'updated_at' => now()->toDateTimeString()],
        ['key' => '2.0', 'host' => 'mine.example.com', 'analysis' => 'PASS',
         'created_at' => now()->subDays(30)->toDateTimeString(), 'updated_at' => now()->subDays(30)->toDateTimeString()],
    ]);

    $component = Livewire::test(Stats::class, [
        'domain'     => 'mine.example.com',
        'start_time' => now()->subDay()->toDateString(),
        'end_time'   => now()->toDateString(),
    ]);

    $component->assertSet('total', 1); // Only today's log is in range
});
