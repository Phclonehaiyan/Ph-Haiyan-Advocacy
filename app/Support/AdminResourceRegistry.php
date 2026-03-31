<?php

namespace App\Support;

use App\Models\Activity;
use App\Models\Event;
use App\Models\ForumTopic;
use App\Models\GalleryItem;
use App\Models\Letter;
use App\Models\NewsPost;
use App\Models\Page;
use App\Models\Project;
use App\Models\Video;
use Illuminate\Validation\Rule;

class AdminResourceRegistry
{
    public static function all(): array
    {
        return [
            'pages' => [
                'label' => 'Pages',
                'singular' => 'Page',
                'model' => Page::class,
                'description' => 'Core page records, hero content, and structured section data.',
                'searchable' => ['title', 'slug', 'meta_title'],
                'default_sort' => ['slug' => 'asc'],
                'index_columns' => ['title', 'slug', 'is_published', 'published_at'],
                'fields' => [
                    'slug' => self::textField('Slug', fn ($id) => ['required', 'max:255', Rule::unique('pages', 'slug')->ignore($id)]),
                    'title' => self::textField('Title', ['required', 'max:255']),
                    'subtitle' => self::textareaField('Subtitle', ['nullable', 'string']),
                    'hero_eyebrow' => self::textField('Hero Eyebrow', ['nullable', 'max:255']),
                    'hero_title' => self::textField('Hero Title', ['nullable', 'max:255']),
                    'hero_subtitle' => self::textareaField('Hero Subtitle', ['nullable', 'string']),
                    'hero_image' => self::imageField('Hero Image', ['nullable', 'string', 'max:2048']),
                    'content' => self::jsonField('Structured Content (JSON)', ['nullable', 'string']),
                    'meta_title' => self::textField('Meta Title', ['nullable', 'max:255']),
                    'meta_description' => self::textareaField('Meta Description', ['nullable', 'string']),
                    'is_published' => self::checkboxField('Published'),
                    'published_at' => self::datetimeField('Published At', ['nullable', 'date']),
                ],
            ],
            'projects' => [
                'label' => 'Projects',
                'singular' => 'Project',
                'model' => Project::class,
                'description' => 'Project records used across the homepage, What We Do page, search results, and the dedicated Projects archive.',
                'searchable' => ['title', 'slug', 'category', 'year', 'summary'],
                'default_sort' => ['sort_order' => 'asc', 'title' => 'asc'],
                'index_columns' => ['title', 'category', 'year', 'sort_order', 'is_featured'],
                'fields' => [
                    'title' => self::textField('Title', ['required', 'max:255']),
                    'slug' => self::textField('Slug', fn ($id) => ['required', 'max:255', Rule::unique('projects', 'slug')->ignore($id)]),
                    'category' => self::textField('Category', ['required', 'max:255']),
                    'year' => self::textField('Year', ['nullable', 'max:20']),
                    'summary' => self::textareaField('Summary', ['required', 'string']),
                    'description' => self::textareaField('Full Description', ['required', 'string'], rows: 14),
                    'image' => self::imageField('Preview Image', ['nullable', 'string', 'max:2048']),
                    'image_alt' => self::textField('Image Alt Text', ['nullable', 'max:255'], 'Describe the image naturally for accessibility and search.'),
                    'sort_order' => self::numberField('Sort Order', ['nullable', 'integer', 'min:0']),
                    'is_featured' => self::checkboxField('Featured'),
                ],
            ],
            'news' => [
                'label' => 'News',
                'singular' => 'News Post',
                'model' => NewsPost::class,
                'description' => 'Public updates, campaign stories, and archive articles.',
                'searchable' => ['title', 'slug', 'category', 'excerpt'],
                'default_sort' => ['published_at' => 'desc', 'id' => 'desc'],
                'index_columns' => ['title', 'category', 'is_featured', 'is_published', 'published_at'],
                'fields' => [
                    'title' => self::textField('Title', ['required', 'max:255']),
                    'slug' => self::textField('Slug', fn ($id) => ['required', 'max:255', Rule::unique('news_posts', 'slug')->ignore($id)]),
                    'category' => self::textField('Category', ['required', 'max:255']),
                    'excerpt' => self::textareaField('Excerpt', ['required', 'string']),
                    'content' => self::textareaField('Full Story / HTML', ['required', 'string'], rows: 16),
                    'image' => self::imageField('Preview Image', ['nullable', 'string', 'max:2048']),
                    'image_alt' => self::textField('Image Alt Text', ['nullable', 'max:255'], 'Describe the image naturally for accessibility and search.'),
                    'meta_title' => self::textField('Meta Title', ['nullable', 'max:255']),
                    'meta_description' => self::textareaField('Meta Description', ['nullable', 'string']),
                    'og_image' => self::imageField('Social Share Image', ['nullable', 'string', 'max:2048']),
                    'reading_time' => self::numberField('Reading Time (minutes)', ['nullable', 'integer', 'min:1']),
                    'is_featured' => self::checkboxField('Featured'),
                    'is_published' => self::checkboxField('Published'),
                    'published_at' => self::datetimeField('Published At', ['nullable', 'date']),
                ],
            ],
            'events' => [
                'label' => 'Events',
                'singular' => 'Event',
                'model' => Event::class,
                'description' => 'Forums, meetings, and public event archive records.',
                'searchable' => ['title', 'slug', 'category', 'location', 'venue'],
                'default_sort' => ['start_at' => 'desc', 'id' => 'desc'],
                'index_columns' => ['title', 'category', 'location', 'is_featured', 'is_published', 'start_at'],
                'fields' => [
                    'title' => self::textField('Title', ['required', 'max:255']),
                    'slug' => self::textField('Slug', fn ($id) => ['required', 'max:255', Rule::unique('events', 'slug')->ignore($id)]),
                    'category' => self::textField('Category', ['required', 'max:255']),
                    'summary' => self::textareaField('Summary', ['required', 'string']),
                    'description' => self::textareaField('Description', ['nullable', 'string'], rows: 14),
                    'location' => self::textField('Location', ['nullable', 'max:255']),
                    'venue' => self::textField('Venue', ['nullable', 'max:255']),
                    'image' => self::imageField('Image', ['nullable', 'string', 'max:2048']),
                    'image_alt' => self::textField('Image Alt Text', ['nullable', 'max:255'], 'Describe the image naturally for accessibility and search.'),
                    'is_featured' => self::checkboxField('Featured'),
                    'is_published' => self::checkboxField('Published'),
                    'start_at' => self::datetimeField('Start At', ['nullable', 'date']),
                    'end_at' => self::datetimeField('End At', ['nullable', 'date', 'after_or_equal:start_at']),
                ],
            ],
            'activities' => [
                'label' => 'Activities',
                'singular' => 'Activity',
                'model' => Activity::class,
                'description' => 'Field activity records used on the homepage and archive sections.',
                'searchable' => ['title', 'slug', 'category', 'location', 'summary'],
                'default_sort' => ['activity_date' => 'desc', 'id' => 'desc'],
                'index_columns' => ['title', 'category', 'location', 'is_featured', 'activity_date'],
                'fields' => [
                    'title' => self::textField('Title', ['required', 'max:255']),
                    'slug' => self::textField('Slug', fn ($id) => ['required', 'max:255', Rule::unique('activities', 'slug')->ignore($id)]),
                    'category' => self::textField('Category', ['required', 'max:255']),
                    'summary' => self::textareaField('Summary', ['required', 'string']),
                    'content' => [
                        'label' => 'Structured Content (JSON)',
                        'type' => 'json',
                        'rules' => ['nullable', 'string'],
                        'help' => 'Use valid JSON. Example: {"highlights":["First key takeaway","Second key takeaway"]}',
                        'template' => [
                            'highlights' => [
                                'First key takeaway',
                                'Second key takeaway',
                            ],
                        ],
                    ],
                    'location' => self::textField('Location', ['nullable', 'max:255']),
                    'image' => self::imageField('Image', ['nullable', 'string', 'max:2048']),
                    'image_alt' => self::textField('Image Alt Text', ['nullable', 'max:255'], 'Describe the image naturally for accessibility and search.'),
                    'is_featured' => self::checkboxField('Featured'),
                    'activity_date' => self::datetimeField('Activity Date', ['nullable', 'date']),
                ],
            ],
            'videos' => [
                'label' => 'Videos',
                'singular' => 'Video',
                'model' => Video::class,
                'description' => 'Homepage and archive video stories from Facebook and partner platforms.',
                'searchable' => ['title', 'slug', 'summary', 'platform'],
                'default_sort' => ['published_at' => 'desc', 'id' => 'desc'],
                'index_columns' => ['title', 'platform', 'view_count_label', 'is_featured', 'published_at'],
                'fields' => [
                    'title' => self::textField('Title', ['required', 'max:255']),
                    'slug' => self::textField('Slug', fn ($id) => ['required', 'max:255', Rule::unique('videos', 'slug')->ignore($id)]),
                    'summary' => self::textareaField('Summary', ['required', 'string']),
                    'thumbnail' => self::imageField('Thumbnail', ['nullable', 'string', 'max:2048']),
                    'video_url' => self::textField('Video URL', ['required', 'url', 'max:2048']),
                    'platform' => self::textField('Platform', ['nullable', 'max:100']),
                    'view_count_label' => self::textField('View Count Label', ['nullable', 'max:100']),
                    'duration' => self::textField('Duration', ['nullable', 'max:100']),
                    'is_featured' => self::checkboxField('Featured'),
                    'published_at' => self::datetimeField('Published At', ['nullable', 'date']),
                ],
            ],
            'gallery' => [
                'label' => 'Gallery',
                'singular' => 'Gallery Item',
                'model' => GalleryItem::class,
                'description' => 'Photo gallery records used across the public gallery experience.',
                'searchable' => ['title', 'slug', 'category', 'summary'],
                'default_sort' => ['sort_order' => 'asc', 'taken_at' => 'desc', 'id' => 'desc'],
                'index_columns' => ['title', 'category', 'sort_order', 'is_featured', 'taken_at'],
                'fields' => [
                    'title' => self::textField('Title', ['required', 'max:255']),
                    'slug' => self::textField('Slug', fn ($id) => ['required', 'max:255', Rule::unique('gallery_items', 'slug')->ignore($id)]),
                    'category' => self::textField('Category', ['required', 'max:255']),
                    'summary' => self::textareaField('Summary', ['nullable', 'string']),
                    'image' => self::imageField('Image', ['required', 'string', 'max:2048']),
                    'image_alt' => self::textField('Image Alt Text', ['nullable', 'max:255'], 'Describe the image naturally for accessibility and search.'),
                    'is_featured' => self::checkboxField('Featured'),
                    'sort_order' => self::numberField('Sort Order', ['nullable', 'integer', 'min:0']),
                    'taken_at' => self::datetimeField('Taken At', ['nullable', 'date']),
                ],
            ],
            'letters' => [
                'label' => 'Letters',
                'singular' => 'Letter',
                'model' => Letter::class,
                'description' => 'Official public correspondence, supporting files, and story-backed record pages.',
                'searchable' => ['title', 'slug', 'category', 'topic', 'summary'],
                'default_sort' => ['published_at' => 'desc', 'id' => 'desc'],
                'index_columns' => ['title', 'category', 'topic', 'is_featured', 'published_at'],
                'fields' => [
                    'title' => self::textField('Title', ['required', 'max:255']),
                    'slug' => self::textField('Slug', fn ($id) => ['required', 'max:255', Rule::unique('letters', 'slug')->ignore($id)]),
                    'category' => self::textField('Category', ['required', 'max:255']),
                    'topic' => self::textField('Topic', ['nullable', 'max:255']),
                    'summary' => self::textareaField('Summary', ['required', 'string']),
                    'body' => self::textareaField('Story Body / HTML', ['nullable', 'string'], rows: 18),
                    'key_takeaways' => self::jsonField('Key Takeaways (JSON)', ['nullable', 'string']),
                    'attachments' => self::jsonField('Attachments (JSON)', ['nullable', 'string']),
                    'source_url' => self::textField('Source URL', ['nullable', 'max:2048']),
                    'document_url' => self::fileField('Document URL / PDF', ['nullable', 'string', 'max:2048'], uploadRules: ['nullable', 'file', 'mimes:pdf,doc,docx']),
                    'image' => self::imageField('Preview Image', ['nullable', 'string', 'max:2048']),
                    'image_alt' => self::textField('Image Alt Text', ['nullable', 'max:255'], 'Describe the image naturally for accessibility and search.'),
                    'is_featured' => self::checkboxField('Featured'),
                    'published_at' => self::datetimeField('Published At', ['nullable', 'date']),
                ],
            ],
            'forums' => [
                'label' => 'Forums',
                'singular' => 'Forum Topic',
                'model' => ForumTopic::class,
                'description' => 'Forum topics, long-form body content, and discussion metadata.',
                'searchable' => ['title', 'slug', 'category', 'summary', 'starter_name'],
                'default_sort' => ['last_activity_at' => 'desc', 'id' => 'desc'],
                'index_columns' => ['title', 'category', 'starter_name', 'status', 'is_pinned', 'last_activity_at'],
                'fields' => [
                    'title' => self::textField('Title', ['required', 'max:255']),
                    'slug' => self::textField('Slug', fn ($id) => ['required', 'max:255', Rule::unique('forum_topics', 'slug')->ignore($id)]),
                    'category' => self::textField('Category', ['required', 'max:255']),
                    'summary' => self::textareaField('Summary', ['required', 'string']),
                    'body' => self::textareaField('Body / HTML', ['nullable', 'string'], rows: 18),
                    'image' => self::imageField('Image', ['nullable', 'string', 'max:2048']),
                    'starter_name' => self::textField('Starter Name', ['nullable', 'max:255']),
                    'status' => self::selectField('Status', ['nullable', 'max:100'], [
                        'Open' => 'Open',
                        'Archived' => 'Archived',
                        'Closed' => 'Closed',
                    ]),
                    'tags' => self::jsonField('Tags (JSON)', ['nullable', 'string']),
                    'replies_count' => self::numberField('Replies Count', ['nullable', 'integer', 'min:0']),
                    'views_count' => self::numberField('Views Count', ['nullable', 'integer', 'min:0']),
                    'is_featured' => self::checkboxField('Featured'),
                    'is_pinned' => self::checkboxField('Pinned'),
                    'last_activity_at' => self::datetimeField('Last Activity At', ['nullable', 'date']),
                ],
            ],
        ];
    }

    public static function get(string $resource): array
    {
        $definitions = self::all();

        abort_unless(isset($definitions[$resource]), 404);

        return $definitions[$resource];
    }

    private static function textField(string $label, array|\Closure $rules, ?string $help = null): array
    {
        return ['label' => $label, 'type' => 'text', 'rules' => $rules, 'help' => $help];
    }

    private static function textareaField(string $label, array|\Closure $rules, ?string $help = null, int $rows = 5): array
    {
        return ['label' => $label, 'type' => 'textarea', 'rules' => $rules, 'help' => $help, 'rows' => $rows];
    }

    private static function jsonField(string $label, array|\Closure $rules, ?string $help = null): array
    {
        return ['label' => $label, 'type' => 'json', 'rules' => $rules, 'help' => $help ?? 'Enter valid JSON.'];
    }

    private static function checkboxField(string $label): array
    {
        return ['label' => $label, 'type' => 'checkbox', 'rules' => ['nullable', 'boolean']];
    }

    private static function datetimeField(string $label, array|\Closure $rules): array
    {
        return ['label' => $label, 'type' => 'datetime', 'rules' => $rules];
    }

    private static function numberField(string $label, array|\Closure $rules): array
    {
        return ['label' => $label, 'type' => 'number', 'rules' => $rules];
    }

    private static function imageField(string $label, array|\Closure $rules): array
    {
        return [
            'label' => $label,
            'type' => 'image',
            'rules' => $rules,
            'upload_rules' => ['nullable', 'image', 'max:5120'],
            'help' => 'Use an existing public path or upload a replacement image.',
        ];
    }

    private static function fileField(string $label, array|\Closure $rules, ?string $help = null, array $uploadRules = ['nullable', 'file', 'max:10240']): array
    {
        return [
            'label' => $label,
            'type' => 'file',
            'rules' => $rules,
            'upload_rules' => $uploadRules,
            'help' => $help ?? 'Use an existing public path or upload a replacement file.',
        ];
    }

    private static function selectField(string $label, array|\Closure $rules, array $options): array
    {
        return [
            'label' => $label,
            'type' => 'select',
            'rules' => $rules,
            'options' => $options,
        ];
    }
}
