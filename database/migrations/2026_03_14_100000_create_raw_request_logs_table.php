<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Ham istek logu: her gelen istek için bir satır.
 * Admin ve skip_paths hariç tüm web istekleri buraya yazılır.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('raw_request_logs', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45)->index();
            $table->string('method', 10)->index();
            $table->text('full_url');
            $table->string('path', 2048)->index();
            $table->text('query_string')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('referer', 2048)->nullable();
            $table->unsignedSmallInteger('status_code')->nullable()->index();
            $table->unsignedInteger('response_time_ms')->nullable();
            $table->timestamp('visited_at')->index();
            $table->boolean('is_asset_request')->default(false)->index();
            $table->string('session_id', 255)->nullable()->index();
            $table->string('request_fingerprint', 64)->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('raw_request_logs');
    }
};
