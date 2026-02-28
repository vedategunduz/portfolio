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
        Schema::create('page_history', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address')->index();
            $table->string('path');
            $table->string('method')->default('GET');
            $table->text('user_agent')->nullable();
            $table->string('referer')->nullable();
            $table->string('session_id')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_history');
    }
};
