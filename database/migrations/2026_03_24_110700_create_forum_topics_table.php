<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('forum_topics', function (Blueprint $table): void {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category');
            $table->string('starter_name')->nullable();
            $table->string('status')->default('open');
            $table->json('tags')->nullable();
            $table->text('summary');
            $table->unsignedInteger('replies_count')->default(0);
            $table->unsignedInteger('views_count')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_pinned')->default(false);
            $table->timestamp('last_activity_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forum_topics');
    }
};
