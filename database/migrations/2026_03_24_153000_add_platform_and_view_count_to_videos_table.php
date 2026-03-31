<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('videos', function (Blueprint $table): void {
            $table->string('platform')->nullable()->after('video_url');
            $table->string('view_count_label')->nullable()->after('platform');
        });
    }

    public function down(): void
    {
        Schema::table('videos', function (Blueprint $table): void {
            $table->dropColumn(['platform', 'view_count_label']);
        });
    }
};
