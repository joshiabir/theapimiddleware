<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loggers');
    }
};
