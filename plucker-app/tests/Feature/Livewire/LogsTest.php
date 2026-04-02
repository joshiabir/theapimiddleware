<?php

use App\Livewire\Logs\Logs;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;

beforeEach(function () {
    $this->createFionaSchema();
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

    Livewire::test(Logs::class, ['domain' => 'api.example.com'])
            ->set('start_time', now()->subDays(7)->toDateString())
            ->set('end_time', now()->toDateString())
            ->assertOk();
});

it('shows logs for the user\'s domain within the date range', function () {
    $this->actingAs($this->user);

    $now = now()->toDateTimeString();
    DB::table('loggers')->insert([
        [
            'key' => '1.1', 'host' => 'api.example.com', 'url' => '/v1/users',
            'request_method' => 'GET', 'response_status' => '200', 'analysis' => 'PASS',
            'client' => '["127.0.0.1"]', 'created_at' => $now, 'updated_at' => $now,
        ],
        [
            'key' => '2.2', 'host' => 'other.com', 'url' => '/v1/data',
            'request_method' => 'POST', 'response_status' => '200', 'analysis' => 'PASS',
            'client' => '["10.0.0.1"]', 'created_at' => $now, 'updated_at' => $now,
        ],
    ]);

    $component = Livewire::test(Logs::class, ['domain' => 'api.example.com'])
        ->set('start_time', now()->subDay()->toDateString())
        ->set('end_time', now()->toDateString());

    // The view receives a paginator — assert the component rendered with data
    $component->assertSee('/v1/users')
              ->assertDontSee('/v1/data'); // other domain should not appear
});

it('returns 403 view when domain does not belong to user', function () {
    $other = $this->createUser(['email' => 'other@example.com']);
    $this->actingAs($other);

    $component = Livewire::test(Logs::class, ['domain' => 'api.example.com'])
        ->set('start_time', now()->subDay()->toDateString())
        ->set('end_time', now()->toDateString());

    $component->assertSee('403');
});

it('filters logs by request method', function () {
    $this->actingAs($this->user);

    $now = now()->toDateTimeString();
    DB::table('loggers')->insert([
        [
            'key' => '1.1', 'host' => 'api.example.com', 'url' => '/v1/a',
            'request_method' => 'GET', 'response_status' => '200', 'analysis' => 'PASS',
            'client' => '["127.0.0.1"]', 'created_at' => $now, 'updated_at' => $now,
        ],
        [
            'key' => '2.2', 'host' => 'api.example.com', 'url' => '/v1/b',
            'request_method' => 'POST', 'response_status' => '200', 'analysis' => 'PASS',
            'client' => '["127.0.0.1"]', 'created_at' => $now, 'updated_at' => $now,
        ],
    ]);

    $component = Livewire::test(Logs::class, ['domain' => 'api.example.com'])
        ->set('start_time', now()->subDay()->toDateString())
        ->set('end_time', now()->toDateString())
        ->set('method', 'GET');

    $component->assertSee('/v1/a')
              ->assertDontSee('/v1/b');
});
