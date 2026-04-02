<?php

use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\DB;

beforeEach(function () {
    Queue::fake();
    $this->createSharedSchema();
});

// ── Tests ───────────────────────────────────────────────────────────────────

it('returns 403 when the domain is not registered', function () {
    $this->withoutExceptionHandling()
         ->getJson('http://notregistered.com/v1/users')
         ->assertStatus(403)
         ->assertJson(['Error' => 'Domain not found or decativated']);
})->throws(\Symfony\Component\HttpKernel\Exception\HttpException::class);

it('returns 403 when domain exists but is inactive', function () {
    DB::table('domain_routings')->insert([
        'user_id' => '1', 'host' => 'inactive.example.com', 'ip' => '10.0.0.1',
        'protocol' => 'http', 'status' => 'Inactive', 'policy' => 'Strict',
        'client_policy' => 'default', 'rate_limit' => '100',
    ]);

    $this->getJson('http://inactive.example.com/v1/test')
         ->assertStatus(403);
});

it('returns 403 when the URL is not in any cluster', function () {
    $this->seedGatewayDomain('api.example.com', '/v1/known');

    $this->getJson('http://api.example.com/v1/unknown')
         ->assertStatus(403)
         ->assertJson(['message' => 'Invalid Request URL']);
});

it('returns 403 when the cluster is disabled', function () {
    $data = $this->seedGatewayDomain('api.example.com', '/v1/users');

    DB::table('clusters')->where('id', $data['clusterId'])->update(['status' => 'Disabled']);

    $this->getJson('http://api.example.com/v1/users')
         ->assertStatus(403);
});

it('returns 403 when the API endpoint is disabled', function () {
    $data = $this->seedGatewayDomain('api.example.com', '/v1/users');

    DB::table('cluster_apis')->where('api_id', $data['apiId'])->update(['status' => 'Disabled']);

    $this->getJson('http://api.example.com/v1/users')
         ->assertStatus(403);
});

it('returns 403 when IP is blacklisted', function () {
    $this->seedGatewayDomain('api.example.com', '/v1/users', 'Strict', [
        'access_type' => 'black',
        'users'       => json_encode(['127.0.0.1']),
    ]);

    $this->getJson('http://api.example.com/v1/users')
         ->assertStatus(403)
         ->assertJson(['Error' => 'Forbidden Access']);
});

it('returns 403 when IP is not on the whitelist', function () {
    $this->seedGatewayDomain('api.example.com', '/v1/users', 'Strict', [
        'access_type' => 'white',
        'users'       => json_encode(['10.10.10.10']),
    ]);

    $this->getJson('http://api.example.com/v1/users')
         ->assertStatus(403)
         ->assertJson(['Error' => 'Forbidden Access']);
});

it('returns 403 when auth is required but no credentials are provided', function () {
    $this->seedGatewayDomain('api.example.com', '/v1/users', 'Strict', [
        'required_auth' => '1',
    ]);

    $this->getJson('http://api.example.com/v1/users')
         ->assertStatus(403)
         ->assertJson(['Error' => 'Forbidden Access - Requires Authentication']);
});

it('passes policy check when auth is required and an Authorization header is present', function () {
    $this->seedGatewayDomain('api.example.com', '/v1/users', 'Strict', [
        'required_auth' => '1',
    ]);

    \Illuminate\Support\Facades\Http::fake(['*' => \Illuminate\Support\Facades\Http::response('ok', 200)]);

    $this->getJson('http://api.example.com/v1/users', ['Authorization' => 'Bearer test-token'])
         ->assertStatus(200);
});

it('allows Discovery-mode domain to bypass the cluster check for unknown URLs', function () {
    DB::table('domain_routings')->insert([
        'user_id' => '1', 'host' => 'discovery.example.com', 'ip' => '10.0.0.1',
        'protocol' => 'http', 'status' => 'Active', 'policy' => 'Discovery',
        'client_policy' => 'default', 'rate_limit' => '100',
    ]);

    \Illuminate\Support\Facades\Http::fake(['*' => \Illuminate\Support\Facades\Http::response('ok', 200)]);

    $this->getJson('http://discovery.example.com/any/path')
         ->assertStatus(200);
});
