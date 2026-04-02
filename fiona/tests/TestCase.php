<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

abstract class TestCase extends BaseTestCase
{
    /**
     * Create the tables that live in plucker-app's migrations but are shared
     * with fiona at runtime. Call this in beforeEach() for tests that need them.
     */
    protected function createSharedSchema(): void
    {
        Schema::create('domain_routings', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('host');
            $table->string('ip');
            $table->string('protocol');
            $table->string('status');
            $table->string('policy');
            $table->string('client_policy');
            $table->string('rate_limit');
            $table->timestamps();
        });

        Schema::create('cluster_policies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('owner');
            $table->string('host');
            $table->string('description');
            $table->string('status');
            $table->timestamps();
        });

        Schema::create('clusters', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->string('policy_id');
            $table->string('host');
            $table->string('owner');
            $table->string('status');
            $table->timestamps();
        });

        Schema::create('apis', function (Blueprint $table) {
            $table->id();
            $table->string('api_id')->unique();
            $table->string('host');
            $table->string('url');
            $table->timestamps();
        });

        Schema::create('cluster_apis', function (Blueprint $table) {
            $table->id();
            $table->string('cluster_id');
            $table->string('api_id');
            $table->string('status');
            $table->timestamps();
        });

        Schema::create('cluster_policy_lists', function (Blueprint $table) {
            $table->id();
            $table->string('policy_id');
            $table->string('host');
            $table->string('name');
            $table->string('value');
            $table->timestamps();
        });

        Schema::create('dlp_policies', function (Blueprint $table) {
            $table->id();
            $table->string('domain');
            $table->string('cluster_policy_id');
            $table->string('type');
            $table->string('value');
            $table->timestamps();
        });

        Schema::create('dlp_bypasses', function (Blueprint $table) {
            $table->id();
            $table->string('domain');
            $table->string('url');
            $table->timestamps();
        });

        Schema::create('response_times', function (Blueprint $table) {
            $table->id();
            $table->string('log_id')->nullable();
            $table->string('host')->nullable();
            $table->string('url')->nullable();
            $table->string('response_time')->nullable();
            $table->timestamps();
        });

        Schema::create('dlp_logs', function (Blueprint $table) {
            $table->id();
            $table->string('log_id')->nullable();
            $table->string('host')->nullable();
            $table->string('value')->nullable();
            $table->string('count')->nullable();
            $table->timestamps();
        });

        // inteli_worker references these tables
        Schema::create('api_methods', function (Blueprint $table) {
            $table->id();
            $table->string('api_id')->nullable();
            $table->string('host')->nullable();
            $table->string('method')->nullable();
            $table->string('count')->nullable();
            $table->timestamps();
        });

        Schema::create('request_keys', function (Blueprint $table) {
            $table->id();
            $table->string('api_id')->nullable();
            $table->string('key')->nullable();
            $table->timestamps();
        });

        Schema::create('response_keys', function (Blueprint $table) {
            $table->id();
            $table->string('api_id')->nullable();
            $table->string('key')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Seed a minimal domain+cluster+API setup used across gateway tests.
     * Returns the seeded data as an array for use in assertions.
     */
    protected function seedGatewayDomain(
        string $host = 'api.example.com',
        string $apiPath = '/v1/users',
        string $policy = 'Strict',
        array  $policyRules = []
    ): array {
        $policyRow = \Illuminate\Support\Facades\DB::table('cluster_policies')->insertGetId([
            'name'        => 'default',
            'owner'       => 'test',
            'host'        => $host,
            'description' => 'test policy',
            'status'      => 'Active',
        ]);

        $clusterId = \Illuminate\Support\Facades\DB::table('clusters')->insertGetId([
            'name'        => 'test-cluster',
            'description' => 'test',
            'policy_id'   => $policyRow,
            'host'        => $host,
            'owner'       => 'test',
            'status'      => 'Active',
        ]);

        $apiId = 'api-' . uniqid();
        \Illuminate\Support\Facades\DB::table('apis')->insert([
            'api_id' => $apiId,
            'host'   => $host,
            'url'    => $apiPath,
        ]);

        \Illuminate\Support\Facades\DB::table('cluster_apis')->insert([
            'cluster_id' => $clusterId,
            'api_id'     => $apiId,
            'status'     => 'Active',
        ]);

        $defaultRules = [
            'required_auth'  => '0',
            'encryption'     => 'none',
            'global_rpm'     => '1000',
            'user_rpm'       => '100',
            'honey_pots'     => '0',
            'access_type'    => 'none',
            'users'          => '[]',
            'logging_http'   => '1',
            'logging_gdpr'   => '0',
            'redact_auth'    => '0',
        ];

        foreach (array_merge($defaultRules, $policyRules) as $name => $value) {
            \Illuminate\Support\Facades\DB::table('cluster_policy_lists')->insert([
                'policy_id' => $policyRow,
                'host'      => $host,
                'name'      => $name,
                'value'     => $value,
            ]);
        }

        \Illuminate\Support\Facades\DB::table('domain_routings')->insert([
            'user_id'       => '1',
            'host'          => $host,
            'ip'            => '127.0.0.1',
            'protocol'      => 'http',
            'status'        => 'Active',
            'policy'        => $policy,
            'client_policy' => 'default',
            'rate_limit'    => '100',
        ]);

        return compact('host', 'apiPath', 'policyRow', 'clusterId', 'apiId');
    }
}
