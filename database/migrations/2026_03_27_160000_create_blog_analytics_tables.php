<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('analytics_visitors', function (Blueprint $table) {
            $table->id();
            $table->uuid('visitor_uuid')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('first_seen_at')->nullable();
            $table->timestamp('last_seen_at')->nullable();
            $table->boolean('is_bot')->default(false)->index();
            $table->boolean('is_suspicious')->default(false)->index();
            $table->timestamps();
        });

        Schema::create('analytics_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('session_id', 100)->unique();
            $table->foreignId('visitor_id')->constrained('analytics_visitors')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('started_at')->nullable()->index();
            $table->timestamp('ended_at')->nullable();
            $table->string('landing_url')->nullable();
            $table->text('referrer')->nullable();
            $table->string('utm_source')->nullable()->index();
            $table->string('utm_medium')->nullable()->index();
            $table->string('utm_campaign')->nullable()->index();
            $table->string('utm_term')->nullable();
            $table->string('utm_content')->nullable();
            $table->string('ip_hash', 80)->nullable()->index();
            $table->text('user_agent')->nullable();
            $table->string('device_type')->nullable()->index();
            $table->string('browser')->nullable()->index();
            $table->string('os')->nullable()->index();
            $table->string('country', 8)->nullable()->index();
            $table->string('city')->nullable();
            $table->unsignedInteger('screen_width')->nullable();
            $table->unsignedInteger('screen_height')->nullable();
            $table->unsignedInteger('viewport_width')->nullable();
            $table->unsignedInteger('viewport_height')->nullable();
            $table->unsignedInteger('load_time_ms')->nullable();
            $table->unsignedInteger('dom_ready_ms')->nullable();
            $table->unsignedInteger('time_to_first_interaction_ms')->nullable();
            $table->boolean('is_bot')->default(false)->index();
            $table->boolean('is_suspicious')->default(false)->index();
            $table->timestamps();
        });

        Schema::create('analytics_post_views', function (Blueprint $table) {
            $table->id();
            $table->uuid('view_uuid')->unique();
            $table->foreignId('session_ref_id')->constrained('analytics_sessions')->cascadeOnDelete();
            $table->foreignId('visitor_id')->constrained('analytics_visitors')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('post_id')->constrained('posts')->cascadeOnDelete();
            $table->string('post_slug');
            $table->timestamp('view_started_at')->index();
            $table->timestamp('view_ended_at')->nullable()->index();
            $table->unsignedInteger('total_time_seconds')->default(0);
            $table->unsignedInteger('active_time_seconds')->default(0);
            $table->unsignedInteger('heartbeat_count')->default(0);
            $table->unsignedTinyInteger('max_scroll_percent')->default(0);
            $table->unsignedTinyInteger('reading_progress_percent')->default(0);
            $table->boolean('completed_read')->default(false)->index();
            $table->boolean('engaged_read')->default(false)->index();
            $table->timestamp('first_scroll_at')->nullable();
            $table->boolean('reached_25_percent')->default(false);
            $table->boolean('reached_50_percent')->default(false);
            $table->boolean('reached_75_percent')->default(false);
            $table->boolean('reached_90_percent')->default(false);
            $table->unsignedSmallInteger('toc_click_count')->default(0);
            $table->unsignedSmallInteger('internal_link_click_count')->default(0);
            $table->unsignedSmallInteger('external_link_click_count')->default(0);
            $table->unsignedSmallInteger('copy_count')->default(0);
            $table->unsignedSmallInteger('share_click_count')->default(0);
            $table->boolean('is_bot')->default(false)->index();
            $table->boolean('is_suspicious')->default(false)->index();
            $table->timestamps();

            $table->index(['post_id', 'view_started_at']);
            $table->index(['visitor_id', 'view_started_at']);
        });

        Schema::create('analytics_events', function (Blueprint $table) {
            $table->id();
            $table->uuid('event_uuid')->unique();
            $table->string('event_type', 60)->index();
            $table->dateTime('occurred_at')->index();
            $table->dateTime('received_at')->index();
            $table->string('session_id', 100)->nullable()->index();
            $table->foreignId('visitor_id')->nullable()->constrained('analytics_visitors')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('post_id')->nullable()->constrained('posts')->nullOnDelete();
            $table->foreignId('post_view_id')->nullable()->constrained('analytics_post_views')->nullOnDelete();
            $table->text('url')->nullable();
            $table->text('referrer')->nullable();
            $table->json('payload_json')->nullable();
            $table->boolean('is_bot')->default(false)->index();
            $table->boolean('is_suspicious')->default(false)->index();
            $table->string('ip_hash', 80)->nullable()->index();
            $table->text('user_agent')->nullable();
            $table->string('country', 8)->nullable();
            $table->string('city')->nullable();
            $table->timestamps();
        });

        Schema::create('analytics_post_daily_aggregates', function (Blueprint $table) {
            $table->id();
            $table->date('date')->index();
            $table->foreignId('post_id')->constrained('posts')->cascadeOnDelete();
            $table->unsignedInteger('total_views')->default(0);
            $table->unsignedInteger('unique_visitors')->default(0);
            $table->unsignedInteger('avg_total_time_seconds')->default(0);
            $table->unsignedInteger('avg_active_time_seconds')->default(0);
            $table->unsignedTinyInteger('avg_scroll_percent')->default(0);
            $table->unsignedInteger('completed_read_count')->default(0);
            $table->unsignedInteger('engaged_read_count')->default(0);
            $table->unsignedInteger('bounce_count')->default(0);
            $table->unsignedInteger('returning_visitor_count')->default(0);
            $table->unsignedInteger('bot_views')->default(0);
            $table->unsignedInteger('suspicious_views')->default(0);
            $table->timestamps();

            $table->unique(['date', 'post_id']);
        });

        Schema::create('analytics_source_daily_aggregates', function (Blueprint $table) {
            $table->id();
            $table->date('date')->index();
            $table->string('utm_source')->nullable();
            $table->string('utm_medium')->nullable();
            $table->string('utm_campaign')->nullable();
            $table->string('referrer_domain')->nullable();
            $table->unsignedInteger('views')->default(0);
            $table->unsignedInteger('unique_visitors')->default(0);
            $table->unsignedInteger('engaged_reads')->default(0);
            $table->timestamps();

            $table->index(['date', 'utm_source', 'utm_medium'], 'asa_date_src_med_idx');
        });

        Schema::create('analytics_device_daily_aggregates', function (Blueprint $table) {
            $table->id();
            $table->date('date')->index();
            $table->string('device_type')->nullable();
            $table->string('browser')->nullable();
            $table->string('os')->nullable();
            $table->unsignedInteger('views')->default(0);
            $table->unsignedInteger('unique_visitors')->default(0);
            $table->timestamps();

            $table->index(['date', 'device_type', 'browser'], 'ada_date_dev_brw_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('analytics_device_daily_aggregates');
        Schema::dropIfExists('analytics_source_daily_aggregates');
        Schema::dropIfExists('analytics_post_daily_aggregates');
        Schema::dropIfExists('analytics_events');
        Schema::dropIfExists('analytics_post_views');
        Schema::dropIfExists('analytics_sessions');
        Schema::dropIfExists('analytics_visitors');
    }
};
