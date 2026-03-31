<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('forum_topics', function (Blueprint $table): void {
            $table->longText('body')->nullable()->after('summary');
            $table->string('image')->nullable()->after('body');
        });
    }

    public function down(): void
    {
        Schema::table('forum_topics', function (Blueprint $table): void {
            $table->dropColumn(['body', 'image']);
        });
    }
};
