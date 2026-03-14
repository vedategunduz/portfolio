<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Eski page_history tablosunu kaldırır (yeni sistem raw_request_logs + classified_visit_logs kullanıyor).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('page_history');
    }

    public function down(): void
    {
        Schema::create('page_history', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address')->index();
            $table->text('path');
            $table->string('method')->default('GET');
            $table->text('user_agent')->nullable();
            $table->string('referer')->nullable();
            $table->string('session_id')->nullable()->index();
            $table->unsignedInteger('response_time_ms')->nullable();
            $table->timestamps();
        });
    }
};
