<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Sınıflandırılmış ziyaret logu: her istek için traffic_type, risk_level vb.
 * Analytics (insan hit, benzersiz ziyaretçi) bu tablodan hesaplanır.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('classified_visit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('raw_log_id')->nullable()->constrained('raw_request_logs')->nullOnDelete();
            $table->string('ip_address', 45)->index();
            $table->string('traffic_type', 32)->index(); // human, known_bot, suspicious_bot, monitoring, internal
            $table->text('suspicion_reason')->nullable();
            $table->string('bot_name', 128)->nullable();
            $table->string('risk_level', 16)->default('low')->index(); // low, medium, high
            $table->string('matched_rule', 255)->nullable();
            $table->timestamp('visited_at')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classified_visit_logs');
    }
};
