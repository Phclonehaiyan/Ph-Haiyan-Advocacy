<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('news_posts', function (Blueprint $table): void {
            $table->string('meta_title')->nullable()->after('image');
            $table->text('meta_description')->nullable()->after('meta_title');
            $table->string('og_image')->nullable()->after('meta_description');
            $table->string('image_alt')->nullable()->after('og_image');
        });

        foreach (['gallery_items', 'projects', 'events', 'activities', 'letters'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table): void {
                $table->string('image_alt')->nullable()->after('image');
            });
        }
    }

    public function down(): void
    {
        Schema::table('news_posts', function (Blueprint $table): void {
            $table->dropColumn(['meta_title', 'meta_description', 'og_image', 'image_alt']);
        });

        foreach (['gallery_items', 'projects', 'events', 'activities', 'letters'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table): void {
                $table->dropColumn('image_alt');
            });
        }
    }
};
