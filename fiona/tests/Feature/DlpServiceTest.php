<?php

use App\Services\dlp_service;
use Illuminate\Support\Facades\DB;

beforeEach(function () {
    $this->createSharedSchema();
});

// ── Helper ──────────────────────────────────────────────────────────────────

function seedDlpPolicy(string $host, string $apiPath, string $type, string $value, string $action = 'redact'): void
{
    $policyId = DB::table('cluster_policies')->insertGetId([
        'name' => 'dlp-policy', 'owner' => 'test', 'host' => $host,
        'description' => 'test', 'status' => 'Active',
    ]);

    $clusterId = DB::table('clusters')->insertGetId([
        'name' => 'c', 'description' => 'd', 'policy_id' => $policyId,
        'host' => $host, 'owner' => 'test', 'status' => 'Active',
    ]);

    $apiId = 'api-' . uniqid();
    DB::table('apis')->insert(['api_id' => $apiId, 'host' => $host, 'url' => $apiPath]);
    DB::table('cluster_apis')->insert(['cluster_id' => $clusterId, 'api_id' => $apiId, 'status' => 'Active']);

    DB::table('dlp_policies')->insert([
        'domain' => $host, 'cluster_policy_id' => $policyId, 'type' => $type, 'value' => $value,
    ]);

    DB::table('cluster_policy_lists')->insert([
        'policy_id' => $policyId, 'host' => $host, 'name' => 'pii_dlp', 'value' => $action,
    ]);
}

// ── Tests ───────────────────────────────────────────────────────────────────

it('returns body unchanged when no DLP policies exist', function () {
    $body   = '{"name":"Alice","email":"alice@example.com"}';
    $result = app(dlp_service::class)->dlp($body, 'unknown.com', '/api/users');

    expect($result->body)->toBe($body)
        ->and($result->count)->toBe('0');
});

it('redacts a keyword match when action is redact', function () {
    seedDlpPolicy('api.example.com', '/v1/data', 'Keyword', 'secret', 'redact');

    $result = app(dlp_service::class)->dlp('my secret value', 'api.example.com', '/v1/data');

    expect($result->body)->toBe('my [redacted] value')
        ->and($result->count)->toBe(1)
        ->and($result->replacements[0]['type'])->toBe('keyword');
});

it('redacts a regex pattern match when action is redact', function () {
    seedDlpPolicy('api.example.com', '/v1/data', 'Pattern', '\d{3}-\d{2}-\d{4}', 'redact');

    $result = app(dlp_service::class)->dlp('SSN: 123-45-6789', 'api.example.com', '/v1/data');

    expect($result->body)->toBe('SSN: [redacted]')
        ->and($result->count)->toBe(1);
});

it('returns Blocked when action is block and there is a match', function () {
    seedDlpPolicy('api.example.com', '/v1/data', 'Keyword', 'classified', 'block');

    $result = app(dlp_service::class)->dlp('this is classified data', 'api.example.com', '/v1/data');

    expect($result)->toBe('Blocked');
});

it('does not block when action is block but there is no match', function () {
    seedDlpPolicy('api.example.com', '/v1/data', 'Keyword', 'classified', 'block');

    $result = app(dlp_service::class)->dlp('this is safe data', 'api.example.com', '/v1/data');

    expect($result->body)->toBe('this is safe data')
        ->and($result->count)->toBe(0);
});

it('skips an invalid regex pattern without throwing', function () {
    seedDlpPolicy('api.example.com', '/v1/data', 'Pattern', '[invalid(regex', 'redact');

    $result = app(dlp_service::class)->dlp('some body text', 'api.example.com', '/v1/data');

    expect($result->count)->toBe(0);
});

it('chains multiple policies and counts all matches', function () {
    $policyId = DB::table('cluster_policies')->insertGetId([
        'name' => 'multi', 'owner' => 'test', 'host' => 'api.example.com',
        'description' => 'test', 'status' => 'Active',
    ]);
    $clusterId = DB::table('clusters')->insertGetId([
        'name' => 'c', 'description' => 'd', 'policy_id' => $policyId,
        'host' => 'api.example.com', 'owner' => 'test', 'status' => 'Active',
    ]);
    $apiId = 'api-chain';
    DB::table('apis')->insert(['api_id' => $apiId, 'host' => 'api.example.com', 'url' => '/v1/chain']);
    DB::table('cluster_apis')->insert(['cluster_id' => $clusterId, 'api_id' => $apiId, 'status' => 'Active']);

    DB::table('dlp_policies')->insert([
        ['domain' => 'api.example.com', 'cluster_policy_id' => $policyId, 'type' => 'Keyword', 'value' => 'foo'],
        ['domain' => 'api.example.com', 'cluster_policy_id' => $policyId, 'type' => 'Keyword', 'value' => 'bar'],
    ]);
    DB::table('cluster_policy_lists')->insert([
        'policy_id' => $policyId, 'host' => 'api.example.com', 'name' => 'pii_dlp', 'value' => 'redact',
    ]);

    $result = app(dlp_service::class)->dlp('foo and bar', 'api.example.com', '/v1/chain');

    expect($result->body)->toBe('[redacted] and [redacted]')
        ->and($result->count)->toBe(2);
});
