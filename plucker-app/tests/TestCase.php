<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

abstract class TestCase extends BaseTestCase
{
    /**
     * Create tables owned by fiona's migrations that plucker reads at runtime.
     * Call this in beforeEach() for tests that query loggers or response_times.
     */
    protected function createFionaSchema(): void
    {
        Schema::create('loggers', function (Blueprint $table) {
            $table->id();
            $table->string('key');
            $table->string('host')->nullable();
            $table->string('domain_resolved')->nullable();
            $table->string('client')->nullable();
            $table->string('url')->nullable();
            $table->mediumText('prams')->nullable();
            $table->mediumText('request_headers')->nullable();
            $table->mediumText('request_body')->nullable();
            $table->string('request_method')->nullable();
            $table->string('response_status')->nullable();
            $table->mediumText('response_headers')->nullable();
            $table->mediumText('response_body')->nullable();
            $table->string('response_time')->nullable();
            $table->string('middleware_response')->nullable();
            $table->string('analysis')->nullable();
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
    }

    /**
     * Create a user + personal team (required by Jetstream).
     */
    protected function createUser(array $overrides = []): \App\Models\User
    {
        $user = \App\Models\User::factory()->create($overrides);
        $team = \App\Models\Team::factory()->create(['user_id' => $user->id, 'personal_team' => true]);
        $user->teams()->attach($team);
        $user->update(['current_team_id' => $team->id]);
        return $user;
    }
}
