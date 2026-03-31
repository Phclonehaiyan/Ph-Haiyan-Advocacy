<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $projectsPage = Page::query()->where('slug', 'projects')->first();
        $whatWeDoPage = Page::query()->where('slug', 'what-we-do')->first();

        if (! $projectsPage) {
            return;
        }

        $archiveOverrides = collect(data_get($whatWeDoPage, 'content.project_archive', []))
            ->keyBy('slug');

        collect(data_get($projectsPage, 'content.projects', []))
            ->values()
            ->each(function (array $project, int $index) use ($archiveOverrides): void {
                $override = $archiveOverrides->get($project['slug'] ?? '', []);

                Project::query()->updateOrCreate(
                    ['slug' => $project['slug']],
                    [
                        'title' => $project['title'],
                        'category' => $project['category'] ?? 'Project Archive',
                        'year' => $project['year'] ?? null,
                        'summary' => $override['summary'] ?? $project['summary'],
                        'description' => $override['description'] ?? $project['description'],
                        'image' => $project['image'] ?? $override['image'] ?? null,
                        'sort_order' => $index + 1,
                        'is_featured' => $index < 4,
                    ],
                );
            });
    }
}
