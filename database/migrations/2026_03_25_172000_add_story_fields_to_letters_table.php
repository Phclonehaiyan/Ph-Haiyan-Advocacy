<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('letters', function (Blueprint $table): void {
            $table->json('key_takeaways')->nullable()->after('body');
            $table->json('attachments')->nullable()->after('key_takeaways');
            $table->string('source_url')->nullable()->after('attachments');
        });
    }

    public function down(): void
    {
        Schema::table('letters', function (Blueprint $table): void {
            $table->dropColumn(['key_takeaways', 'attachments', 'source_url']);
        });
    }
};
