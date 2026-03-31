<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class PageEditorController extends Controller
{
    public function edit(string $pageKey): View
    {
        [$definition, $page] = $this->resolvePage($pageKey);

        return view('admin.page-editors.'.$pageKey, [
            'pageKey' => $pageKey,
            'definition' => $definition,
            'page' => $page,
            'values' => $this->valuesFor($pageKey, $page),
            'iconOptions' => $this->iconOptions(),
        ]);
    }

    public function update(Request $request, string $pageKey): RedirectResponse
    {
        [$definition, $page] = $this->resolvePage($pageKey);

        $validated = $request->validate($this->rulesFor($pageKey));
        $content = $page->content ?? [];

        if ($pageKey === 'home') {
            $content['hero_title_lines'] = [
                trim((string) $validated['hero_title_line_1']),
                trim((string) $validated['hero_title_line_2']),
                trim((string) $validated['hero_title_line_3']),
            ];
            $content['hero_intro'] = trim((string) $validated['hero_intro']);
            $content['hero_chips'] = [
                trim((string) $validated['hero_chip_1']),
                trim((string) $validated['hero_chip_2']),
                trim((string) $validated['hero_chip_3']),
            ];
            $content['mission_pillars'] = collect(range(1, 3))->map(function (int $index) use ($validated): array {
                return [
                    'icon' => $validated["pillar_{$index}_icon"],
                    'title' => trim((string) $validated["pillar_{$index}_title"]),
                    'description' => trim((string) $validated["pillar_{$index}_description"]),
                ];
            })->all();
            $content['about_preview'] = [
                'heading' => trim((string) $validated['about_heading']),
                'image' => $this->storePageImage(
                    $request,
                    'about_image_upload',
                    'about_image',
                    $pageKey,
                    'about-preview',
                    data_get($content, 'about_preview.image')
                ),
                'body' => [
                    trim((string) $validated['about_body_1']),
                    trim((string) $validated['about_body_2']),
                    trim((string) $validated['about_body_3']),
                ],
            ];
            $content['impact_cta'] = [
                'title' => trim((string) $validated['impact_title']),
                'description' => trim((string) $validated['impact_description']),
                'background_image' => $this->storePageImage(
                    $request,
                    'impact_background_image_upload',
                    'impact_background_image',
                    $pageKey,
                    'impact-cta',
                    data_get($content, 'impact_cta.background_image')
                ),
            ];
        }

        if ($pageKey === 'about') {
            $content['who_we_are'] = [
                trim((string) $validated['who_we_are_1']),
                trim((string) $validated['who_we_are_2']),
                trim((string) $validated['who_we_are_3']),
            ];
            $content['who_we_are_image'] = $this->storePageImage(
                $request,
                'who_we_are_image_upload',
                'who_we_are_image',
                $pageKey,
                'who-we-are',
                data_get($content, 'who_we_are_image')
            );
            $content['logo'] = [
                trim((string) $validated['logo_paragraph_1']),
                trim((string) $validated['logo_paragraph_2']),
                trim((string) $validated['logo_paragraph_3']),
                trim((string) $validated['logo_paragraph_4']),
            ];
            $content['ceo'] = [
                'name' => trim((string) $validated['ceo_name']),
                'role' => trim((string) $validated['ceo_role']),
                'description' => trim((string) $validated['ceo_description']),
                'image' => $this->storePageImage(
                    $request,
                    'ceo_image_upload',
                    'ceo_image',
                    $pageKey,
                    'ceo',
                    data_get($content, 'ceo.image')
                ),
                'highlights' => [
                    trim((string) $validated['ceo_highlight_1']),
                    trim((string) $validated['ceo_highlight_2']),
                    trim((string) $validated['ceo_highlight_3']),
                ],
            ];
            $content['mission_vision'] = [
                'eyebrow' => trim((string) $validated['mission_vision_eyebrow']),
                'heading' => trim((string) $validated['mission_vision_heading']),
                'description' => trim((string) $validated['mission_vision_description']),
                'badge' => trim((string) $validated['mission_vision_badge']),
                'background_image' => $this->storePageImage(
                    $request,
                    'mission_vision_background_image_upload',
                    'mission_vision_background_image',
                    $pageKey,
                    'mission-vision',
                    data_get($content, 'mission_vision.background_image')
                ),
                'mission_label' => trim((string) $validated['mission_label']),
                'mission_title' => trim((string) $validated['mission_title']),
                'mission_quote' => trim((string) $validated['mission_quote']),
                'vision_label' => trim((string) $validated['vision_label']),
                'vision_title' => trim((string) $validated['vision_title']),
                'vision_quote' => trim((string) $validated['vision_quote']),
            ];
            $content['story'] = [
                trim((string) $validated['story_1']),
                trim((string) $validated['story_2']),
                trim((string) $validated['story_3']),
                trim((string) $validated['story_4']),
                trim((string) $validated['story_5']),
            ];
            $content['why_resilience'] = [
                'heading' => trim((string) $validated['why_heading']),
                'paragraphs' => [
                    trim((string) $validated['why_paragraph_1']),
                    trim((string) $validated['why_paragraph_2']),
                    trim((string) $validated['why_paragraph_3']),
                    trim((string) $validated['why_paragraph_4']),
                ],
            ];
            $content['cta'] = [
                'heading' => trim((string) $validated['cta_heading']),
                'description' => trim((string) $validated['cta_description']),
            ];
        }

        if ($pageKey === 'what-we-do') {
            $content['overview_image'] = $this->storePageImage(
                $request,
                'overview_image_upload',
                'overview_image',
                $pageKey,
                'overview',
                data_get($content, 'overview_image')
            );
            $content['intro'] = [
                trim((string) $validated['intro_1']),
                trim((string) $validated['intro_2']),
                trim((string) $validated['intro_3']),
            ];
            $content['initiatives'] = collect(range(1, 6))->map(function (int $index) use ($validated): array {
                return [
                    'icon' => $validated["initiative_{$index}_icon"],
                    'title' => trim((string) $validated["initiative_{$index}_title"]),
                    'description' => trim((string) $validated["initiative_{$index}_description"]),
                ];
            })->all();
            $content['stories'] = collect(range(1, 3))->map(function (int $index) use ($validated): array {
                return [
                    'title' => trim((string) $validated["story_title_{$index}"]),
                    'description' => trim((string) $validated["story_description_{$index}"]),
                ];
            })->all();
        }

        $page->update([
            'title' => trim((string) $validated['title']),
            'subtitle' => trim((string) $validated['subtitle']),
            'hero_eyebrow' => trim((string) $validated['hero_eyebrow']),
            'hero_title' => trim((string) ($validated['hero_title'] ?? $page->hero_title)),
            'hero_subtitle' => trim((string) ($validated['hero_subtitle'] ?? $page->hero_subtitle)),
            'hero_image' => $this->storePageImage(
                $request,
                'hero_image_upload',
                'hero_image',
                $pageKey,
                'hero',
                $page->hero_image
            ),
            'meta_title' => trim((string) $validated['meta_title']),
            'meta_description' => trim((string) $validated['meta_description']),
            'content' => $content,
        ]);

        return redirect()
            ->route('admin.page-editors.edit', $pageKey)
            ->with('status', $definition['label'].' updated successfully.');
    }

    private function resolvePage(string $pageKey): array
    {
        $definitions = $this->definitions();
        abort_unless(isset($definitions[$pageKey]), 404);

        $definition = $definitions[$pageKey];
        $page = Page::query()->where('slug', $definition['slug'])->firstOrFail();

        return [$definition, $page];
    }

    private function definitions(): array
    {
        return [
            'home' => [
                'slug' => 'home',
                'label' => 'Home Page',
                'description' => 'Edit the homepage hero, mission pillars, about preview, and impact call-to-action.',
            ],
            'about' => [
                'slug' => 'about-ph-haiyan',
                'label' => 'About Page',
                'description' => 'Edit the About page story, logo meaning, resilience narrative, and closing call-to-action.',
            ],
            'what-we-do' => [
                'slug' => 'what-we-do',
                'label' => 'What We Do Page',
                'description' => 'Edit the program overview, signature initiative cards, and milestone story blocks.',
            ],
        ];
    }

    private function valuesFor(string $pageKey, Page $page): array
    {
        $content = $page->content ?? [];
        $values = [
            'title' => $page->title,
            'subtitle' => $page->subtitle,
            'hero_eyebrow' => $page->hero_eyebrow,
            'hero_title' => $page->hero_title,
            'hero_subtitle' => $page->hero_subtitle,
            'hero_image' => $page->hero_image,
            'meta_title' => $page->meta_title,
            'meta_description' => $page->meta_description,
        ];

        if ($pageKey === 'home') {
            $values += [
                'hero_title_line_1' => data_get($content, 'hero_title_lines.0', 'Building'),
                'hero_title_line_2' => data_get($content, 'hero_title_lines.1', 'Resilient'),
                'hero_title_line_3' => data_get($content, 'hero_title_lines.2', 'Communities'),
                'hero_intro' => data_get($content, 'hero_intro'),
                'hero_chip_1' => data_get($content, 'hero_chips.0', 'Envision'),
                'hero_chip_2' => data_get($content, 'hero_chips.1', 'Engage'),
                'hero_chip_3' => data_get($content, 'hero_chips.2', 'Empower'),
                'about_heading' => data_get($content, 'about_preview.heading'),
                'about_image' => data_get($content, 'about_preview.image'),
                'about_body_1' => data_get($content, 'about_preview.body.0'),
                'about_body_2' => data_get($content, 'about_preview.body.1'),
                'about_body_3' => data_get($content, 'about_preview.body.2'),
                'impact_title' => data_get($content, 'impact_cta.title'),
                'impact_description' => data_get($content, 'impact_cta.description'),
                'impact_background_image' => data_get($content, 'impact_cta.background_image', '/images/hero/mangrove-river-bright.jpg'),
            ];

            foreach (range(1, 3) as $index) {
                $values["pillar_{$index}_icon"] = data_get($content, 'mission_pillars.'.($index - 1).'.icon', 'spark');
                $values["pillar_{$index}_title"] = data_get($content, 'mission_pillars.'.($index - 1).'.title');
                $values["pillar_{$index}_description"] = data_get($content, 'mission_pillars.'.($index - 1).'.description');
            }
        }

        if ($pageKey === 'about') {
            $values += [
                'who_we_are_1' => data_get($content, 'who_we_are.0'),
                'who_we_are_2' => data_get($content, 'who_we_are.1'),
                'who_we_are_3' => data_get($content, 'who_we_are.2'),
                'who_we_are_image' => data_get($content, 'who_we_are_image', '/images/imported/floodcontrol/building-resilience.jpg'),
                'logo_paragraph_1' => data_get($content, 'logo.0'),
                'logo_paragraph_2' => data_get($content, 'logo.1'),
                'logo_paragraph_3' => data_get($content, 'logo.2'),
                'logo_paragraph_4' => data_get($content, 'logo.3'),
                'ceo_name' => data_get($content, 'ceo.name', 'Pete L. Ilagan'),
                'ceo_role' => data_get($content, 'ceo.role', 'CEO, PH Haiyan Advocacy Inc.'),
                'ceo_description' => data_get($content, 'ceo.description', 'Leading PH Haiyan with a civic, environmental, and resilience-centered vision for Tacloban and Eastern Visayas.'),
                'ceo_image' => data_get($content, 'ceo.image', '/uploads/about/ceo-pete-ilagan.png'),
                'ceo_highlight_1' => data_get($content, 'ceo.highlights.0', 'Pete L. Ilagan helped shape PH Haiyan from a citizen-led response into a long-view climate resilience advocacy rooted in Tacloban.'),
                'ceo_highlight_2' => data_get($content, 'ceo.highlights.1', 'As a survivor of Super Typhoon Haiyan and a long-time conservation advocate, he pushed for practical environmental rehabilitation when local climate-response plans were still missing.'),
                'ceo_highlight_3' => data_get($content, 'ceo.highlights.2', 'His leadership helped gather professionals, advocates, and community partners around a shared goal: building systems that make Tacloban and Eastern Visayas more prepared for future climate threats.'),
                'mission_vision_eyebrow' => data_get($content, 'mission_vision.eyebrow', 'Mission and Vision'),
                'mission_vision_heading' => data_get($content, 'mission_vision.heading', "The principles guiding PH Haiyan's work in Tacloban and Eastern Visayas."),
                'mission_vision_description' => data_get($content, 'mission_vision.description', "These statements define the organization's present responsibility and the future it is working to help shape."),
                'mission_vision_badge' => data_get($content, 'mission_vision.badge', 'Core Direction'),
                'mission_vision_background_image' => data_get($content, 'mission_vision.background_image', '/images/imported/events/event-balugo-watershed.jpg'),
                'mission_label' => data_get($content, 'mission_vision.mission_label', 'Mission'),
                'mission_title' => data_get($content, 'mission_vision.mission_title', 'What PH Haiyan is called to do now.'),
                'mission_quote' => data_get($content, 'mission_vision.mission_quote', 'Overcoming climate threats by building resilient interdependent systems in Tacloban City and across Eastern Visayas, Philippines.'),
                'vision_label' => data_get($content, 'mission_vision.vision_label', 'Vision'),
                'vision_title' => data_get($content, 'mission_vision.vision_title', 'What PH Haiyan is working toward.'),
                'vision_quote' => data_get($content, 'mission_vision.vision_quote', 'A future where Tacloban City stands as a model for climate resilience and climate-smart development in the Philippines.'),
                'story_1' => data_get($content, 'story.0'),
                'story_2' => data_get($content, 'story.1'),
                'story_3' => data_get($content, 'story.2'),
                'story_4' => data_get($content, 'story.3'),
                'story_5' => data_get($content, 'story.4'),
                'why_heading' => data_get($content, 'why_resilience.heading'),
                'why_paragraph_1' => data_get($content, 'why_resilience.paragraphs.0'),
                'why_paragraph_2' => data_get($content, 'why_resilience.paragraphs.1'),
                'why_paragraph_3' => data_get($content, 'why_resilience.paragraphs.2'),
                'why_paragraph_4' => data_get($content, 'why_resilience.paragraphs.3'),
                'cta_heading' => data_get($content, 'cta.heading'),
                'cta_description' => data_get($content, 'cta.description'),
            ];
        }

        if ($pageKey === 'what-we-do') {
            $values += [
                'overview_image' => data_get($content, 'overview_image', '/images/imported/gallery/first-interagency-meeting.jpg'),
                'intro_1' => data_get($content, 'intro.0'),
                'intro_2' => data_get($content, 'intro.1'),
                'intro_3' => data_get($content, 'intro.2'),
            ];

            foreach (range(1, 6) as $index) {
                $values["initiative_{$index}_icon"] = data_get($content, 'initiatives.'.($index - 1).'.icon', 'spark');
                $values["initiative_{$index}_title"] = data_get($content, 'initiatives.'.($index - 1).'.title');
                $values["initiative_{$index}_description"] = data_get($content, 'initiatives.'.($index - 1).'.description');
            }

            foreach (range(1, 3) as $index) {
                $values["story_title_{$index}"] = data_get($content, 'stories.'.($index - 1).'.title');
                $values["story_description_{$index}"] = data_get($content, 'stories.'.($index - 1).'.description');
            }
        }

        return $values;
    }

    private function rulesFor(string $pageKey): array
    {
        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string'],
            'hero_eyebrow' => ['nullable', 'string', 'max:255'],
            'hero_title' => ['nullable', 'string', 'max:255'],
            'hero_subtitle' => ['nullable', 'string'],
            'hero_image' => ['nullable', 'string', 'max:2048'],
            'hero_image_upload' => ['nullable', 'image', 'max:5120'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
        ];

        if ($pageKey === 'home') {
            $rules += [
                'hero_title_line_1' => ['required', 'string', 'max:255'],
                'hero_title_line_2' => ['required', 'string', 'max:255'],
                'hero_title_line_3' => ['required', 'string', 'max:255'],
                'hero_intro' => ['required', 'string'],
                'hero_chip_1' => ['required', 'string', 'max:255'],
                'hero_chip_2' => ['required', 'string', 'max:255'],
                'hero_chip_3' => ['required', 'string', 'max:255'],
                'about_heading' => ['required', 'string', 'max:255'],
                'about_image' => ['nullable', 'string', 'max:2048'],
                'about_image_upload' => ['nullable', 'image', 'max:5120'],
                'about_body_1' => ['required', 'string'],
                'about_body_2' => ['required', 'string'],
                'about_body_3' => ['required', 'string'],
                'impact_title' => ['required', 'string', 'max:255'],
                'impact_description' => ['required', 'string'],
                'impact_background_image' => ['nullable', 'string', 'max:2048'],
                'impact_background_image_upload' => ['nullable', 'image', 'max:5120'],
            ];

            foreach (range(1, 3) as $index) {
                $rules["pillar_{$index}_icon"] = ['required', 'string', 'max:100'];
                $rules["pillar_{$index}_title"] = ['required', 'string', 'max:255'];
                $rules["pillar_{$index}_description"] = ['required', 'string'];
            }
        }

        if ($pageKey === 'about') {
            $rules += [
                'who_we_are_1' => ['required', 'string'],
                'who_we_are_2' => ['required', 'string'],
                'who_we_are_3' => ['required', 'string'],
                'who_we_are_image' => ['nullable', 'string', 'max:2048'],
                'who_we_are_image_upload' => ['nullable', 'image', 'max:5120'],
                'logo_paragraph_1' => ['required', 'string'],
                'logo_paragraph_2' => ['required', 'string'],
                'logo_paragraph_3' => ['required', 'string'],
                'logo_paragraph_4' => ['required', 'string'],
                'ceo_name' => ['required', 'string', 'max:255'],
                'ceo_role' => ['required', 'string', 'max:255'],
                'ceo_description' => ['required', 'string'],
                'ceo_image' => ['nullable', 'string', 'max:2048'],
                'ceo_image_upload' => ['nullable', 'image', 'max:5120'],
                'ceo_highlight_1' => ['required', 'string'],
                'ceo_highlight_2' => ['required', 'string'],
                'ceo_highlight_3' => ['required', 'string'],
                'mission_vision_eyebrow' => ['required', 'string', 'max:255'],
                'mission_vision_heading' => ['required', 'string', 'max:255'],
                'mission_vision_description' => ['required', 'string'],
                'mission_vision_badge' => ['required', 'string', 'max:255'],
                'mission_vision_background_image' => ['nullable', 'string', 'max:2048'],
                'mission_vision_background_image_upload' => ['nullable', 'image', 'max:5120'],
                'mission_label' => ['required', 'string', 'max:255'],
                'mission_title' => ['required', 'string', 'max:255'],
                'mission_quote' => ['required', 'string'],
                'vision_label' => ['required', 'string', 'max:255'],
                'vision_title' => ['required', 'string', 'max:255'],
                'vision_quote' => ['required', 'string'],
                'story_1' => ['required', 'string'],
                'story_2' => ['required', 'string'],
                'story_3' => ['required', 'string'],
                'story_4' => ['required', 'string'],
                'story_5' => ['required', 'string'],
                'why_heading' => ['required', 'string', 'max:255'],
                'why_paragraph_1' => ['required', 'string'],
                'why_paragraph_2' => ['required', 'string'],
                'why_paragraph_3' => ['required', 'string'],
                'why_paragraph_4' => ['required', 'string'],
                'cta_heading' => ['required', 'string', 'max:255'],
                'cta_description' => ['required', 'string'],
            ];
        }

        if ($pageKey === 'what-we-do') {
            $rules += [
                'overview_image' => ['nullable', 'string', 'max:2048'],
                'overview_image_upload' => ['nullable', 'image', 'max:5120'],
                'intro_1' => ['required', 'string'],
                'intro_2' => ['required', 'string'],
                'intro_3' => ['required', 'string'],
            ];

            foreach (range(1, 6) as $index) {
                $rules["initiative_{$index}_icon"] = ['required', 'string', 'max:100'];
                $rules["initiative_{$index}_title"] = ['required', 'string', 'max:255'];
                $rules["initiative_{$index}_description"] = ['required', 'string'];
            }

            foreach (range(1, 3) as $index) {
                $rules["story_title_{$index}"] = ['required', 'string', 'max:255'];
                $rules["story_description_{$index}"] = ['required', 'string'];
            }
        }

        return $rules;
    }

    private function storePageImage(Request $request, string $uploadField, string $pathField, string $pageKey, string $slot, ?string $current): ?string
    {
        if ($request->hasFile($uploadField)) {
            return $this->storeUpload($request->file($uploadField), $pageKey, $slot);
        }

        $value = trim((string) $request->input($pathField, ''));

        return $value !== '' ? $value : $current;
    }

    private function storeUpload(UploadedFile $file, string $pageKey, string $slot): string
    {
        $directory = public_path('uploads/admin/page-editors/'.$pageKey.'/'.$slot);

        if (! File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $filename = now()->format('YmdHis').'-'.Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $extension = $file->getClientOriginalExtension();
        $file->move($directory, $filename.'.'.$extension);

        return '/uploads/admin/page-editors/'.$pageKey.'/'.$slot.'/'.$filename.'.'.$extension;
    }

    private function iconOptions(): array
    {
        return [
            'eye' => 'Eye',
            'users' => 'Users',
            'sprout' => 'Sprout',
            'leaf' => 'Leaf',
            'shield' => 'Shield',
            'megaphone' => 'Megaphone',
            'spark' => 'Spark',
            'speaker' => 'Speaker',
            'heart' => 'Heart',
        ];
    }
}
