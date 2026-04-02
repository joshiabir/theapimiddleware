<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;

beforeEach(function () {
    Queue::fake();
    $this->createSharedSchema();
    $this->seedGatewayDomain('api.example.com', '/v1/users');
});

// ── Tests ───────────────────────────────────────────────────────────────────

it('proxies a GET request and returns the backend response', function () {
    Http::fake(['*' => Http::response('{"id":1}', 200, ['Content-Type' => 'application/json'])]);

    $this->getJson('http://api.example.com/v1/users')
         ->assertStatus(200)
         ->assertSee('{"id":1}');
});

it('proxies a POST request and forwards the body', function () {
    Http::fake(['*' => Http::response('{"created":true}', 201)]);

    $this->postJson('http://api.example.com/v1/users', ['name' => 'Alice'])
         ->assertStatus(201);

    Http::assertSent(fn ($request) => str_contains($request->url(), '/v1/users'));
});

it('returns 404 when the domain has no IP mapping', function () {
    // No domain_routing row for this host
    $this->getJson('http://unknown.example.com/v1/users')
         ->assertStatus(403); // caught by policyControl before reaching the proxy
});

it('blocks the request when DLP detects a blocked keyword in the request body', function () {
    \Illuminate\Support\Facades\DB::table('cluster_policies')->get()->each(function ($p) {
        \Illuminate\Support\Facades\DB::table('dlp_policies')->insert([
            'domain'            => 'api.example.com',
            'cluster_policy_id' => $p->id,
            'type'              => 'Keyword',
            'value'             => 'classified',
        ]);
        \Illuminate\Support\Facades\DB::table('cluster_policy_lists')->insert([
            'policy_id' => $p->id,
            'host'      => 'api.example.com',
            'name'      => 'pii_dlp',
            'value'     => 'block',
        ]);
    });

    Http::fake(['*' => Http::response('ok', 200)]);

    $this->postJson('http://api.example.com/v1/users', ['note' => 'classified'])
         ->assertStatus(403)
         ->assertJson(['error' => 'Request blocked by DLP policy.']);
});

it('dispatches a log job after a successful proxy', function () {
    Http::fake(['*' => Http::response('ok', 200)]);

    $this->getJson('http://api.example.com/v1/users');

    Queue::assertPushed(\App\Jobs\logProcessor::class);
});
