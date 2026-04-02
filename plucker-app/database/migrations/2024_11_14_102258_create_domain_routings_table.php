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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('domain_routings');
    }
};
