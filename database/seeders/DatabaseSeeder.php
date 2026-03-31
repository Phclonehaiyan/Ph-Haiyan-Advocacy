<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            SiteSettingSeeder::class,
            PageSeeder::class,
            ProjectSeeder::class,
            NewsPostSeeder::class,
            EventSeeder::class,
            ActivitySeeder::class,
            VideoSeeder::class,
            GalleryItemSeeder::class,
            LetterSeeder::class,
            ForumTopicSeeder::class,
        ]);
    }
}
