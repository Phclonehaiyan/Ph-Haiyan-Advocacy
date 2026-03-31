<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_views', function (Blueprint $table): void {
            $table->id();
            $table->string('visitor_key', 64)->index();
            $table->string('session_id', 128)->nullable()->index();
            $table->string('path', 500)->index();
            $table->string('route_name', 150)->nullable()->index();
            $table->string('page_label', 255)->nullable();
            $table->string('referrer_host', 255)->nullable()->index();
            $table->string('device_type', 32)->nullable()->index();
            $table->string('ip_hash', 64)->nullable()->index();
            $table->text('user_agent')->nullable();
            $table->timestamp('viewed_at')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_views');
    }
};
