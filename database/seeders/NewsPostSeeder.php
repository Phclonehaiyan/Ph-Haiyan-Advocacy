<?php

namespace Database\Seeders;

use App\Models\NewsPost;
use Illuminate\Database\Seeder;

class NewsPostSeeder extends Seeder
{
    public function run(): void
    {
        $normalize = static function (?string $value): string {
            return trim(strtr($value ?? '', [
                'â€™' => '’',
                'â€œ' => '“',
                'â€' => '”',
                'â€”' => '—',
                'â€“' => '–',
                'â€¢' => '•',
                'â€¦' => '…',
                'Ã±' => 'ñ',
                'Â ' => ' ',
                'Â' => '',
            ]));
        };

        $escape = static fn (string $value): string => htmlspecialchars($normalize($value), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

        $story = static function (array $sections) use ($escape): string {
            $html = '';

            foreach ($sections as $section) {
                $html .= '<section>';

                if (! empty($section['title'])) {
                    $html .= '<h2>'.$escape($section['title']).'</h2>';
                }

                foreach ($section['paragraphs'] ?? [] as $paragraph) {
                    $html .= '<p>'.$escape($paragraph).'</p>';
                }

                if (! empty($section['bullets'])) {
                    $html .= '<ul>';

                    foreach ($section['bullets'] as $bullet) {
                        $html .= '<li>'.$escape($bullet).'</li>';
                    }

                    $html .= '</ul>';
                }

                $html .= '</section>';
            }

            return $html;
        };

        $archiveArticle = static function (string $relativePath) use ($normalize): string {
            $fullPath = storage_path('app/legacy-source/'.ltrim($relativePath, '/'));

            if (! is_file($fullPath)) {
                return '';
            }

            $html = file_get_contents($fullPath) ?: '';

            libxml_use_internal_errors(true);

            $dom = new \DOMDocument('1.0', 'UTF-8');
            $dom->loadHTML('<?xml encoding="UTF-8">'.$html, LIBXML_NOERROR | LIBXML_NOWARNING);

            libxml_clear_errors();

            $body = $dom->getElementsByTagName('body')->item(0);

            if (! $body instanceof \DOMElement) {
                return '';
            }

            $skipClasses = [
                'key-takeaways',
                'share-bar',
                'toc',
                'related-articles',
                'feedback-section',
                'attachments',
                'image-single',
                'image-row',
                'doc-card',
                'comments-list',
                'modal-inner',
                'meta',
                'caption',
            ];

            $skipIds = [
                'imageModal',
                'feedbackForm',
                'feedbackList',
                'commentsSection',
                'commentsList',
                'showLettersModal',
                'showLettersOverlay',
                'backToTop',
                'top',
            ];

            $skipTags = ['script', 'style', 'footer', 'h1'];

            $serialize = static function (\DOMNode $node) use (&$serialize, $normalize, $skipClasses, $skipIds, $skipTags): string {
                if ($node instanceof \DOMText) {
                    $text = $normalize($node->nodeValue ?? '');

                    if ($text === '') {
                        return '';
                    }

                    return htmlspecialchars($text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                }

                if (! $node instanceof \DOMElement) {
                    return '';
                }

                $tag = strtolower($node->tagName);

                if (in_array($tag, $skipTags, true)) {
                    return '';
                }

                $classList = preg_split('/\s+/', trim($node->getAttribute('class'))) ?: [];

                if (array_intersect($skipClasses, $classList)) {
                    return '';
                }

                if (in_array($node->getAttribute('id'), $skipIds, true)) {
                    return '';
                }

                if ($tag === 'a' && array_intersect(['back-btn', 'print-btn', 'btn', 'btn-view', 'btn-download'], $classList)) {
                    return '';
                }

                if ($tag === 'div' || $tag === 'section') {
                    $inner = '';

                    foreach ($node->childNodes as $child) {
                        $inner .= $serialize($child);
                    }

                    return $inner;
                }

                $allowedTags = ['h2', 'h3', 'p', 'ul', 'ol', 'li', 'strong', 'em', 'br', 'a', 'blockquote'];

                if (! in_array($tag, $allowedTags, true)) {
                    $inner = '';

                    foreach ($node->childNodes as $child) {
                        $inner .= $serialize($child);
                    }

                    return $inner;
                }

                $attributes = [];

                if ($tag === 'a') {
                    $href = trim($node->getAttribute('href'));

                    if ($href === '' || $href === '#') {
                        return '';
                    }

                    if (str_starts_with($href, 'assets/')) {
                        $segments = array_map('rawurlencode', explode('/', ltrim($href, '/')));
                        $href = '/'.implode('/', $segments);
                    }

                    $attributes[] = 'href="'.htmlspecialchars($href, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8').'"';
                    $attributes[] = 'target="_blank"';
                    $attributes[] = 'rel="noreferrer"';
                }

                $inner = '';

                foreach ($node->childNodes as $child) {
                    $inner .= $serialize($child);
                }

                return '<'.$tag.($attributes ? ' '.implode(' ', $attributes) : '').'>'.$inner.'</'.$tag.'>';
            };

            $output = '';

            foreach ($body->childNodes as $child) {
                $output .= $serialize($child);
            }

            return trim($normalize($output));
        };

        $articleStory = static fn (string $file): string => $archiveArticle('old-letters-articles/'.$file);

        $posts = [
            [
                'title' => 'Official statement from PH Haiyan Advocacy Inc.',
                'slug' => 'official-statement-adopt-a-tree-postponement',
                'category' => 'Latest News',
                'image' => '/images/imported/news/official-statement.jpg',
                'excerpt' => 'Official statement on the indefinite postponement of the Adopt-a-Tree kick-off along the Tacloban Bypass Road.',
                'content' => $story([
                    [
                        'title' => 'Why the statement was issued',
                        'paragraphs' => [
                            'PH Haiyan Advocacy Inc. released this official statement after ongoing coordination around the Adopt-a-Tree project along the Tacloban Bypass Road raised questions that required further legal and policy review. The organization had already invested in public preparation, partner coordination, and field activity around the planned kickoff, but it chose to put the clarification process first.',
                            'The statement made clear that the postponement was not a retreat from the advocacy. It was a public acknowledgement that environmental action must move in step with road-safety policy, inter-agency coordination, and formal review processes when work is taking place along a national-road corridor.',
                        ],
                    ],
                    [
                        'title' => 'What the statement placed on record',
                        'bullets' => [
                            'The Adopt-a-Tree kickoff was postponed indefinitely while policy and coordination questions were being addressed.',
                            'PH Haiyan remained committed to greener public infrastructure and climate-resilient roadside planting in Tacloban.',
                            'The organization chose transparency with supporters, volunteers, and partner agencies instead of proceeding with an event while key issues were unresolved.',
                        ],
                    ],
                    [
                        'title' => 'Why the update matters',
                        'paragraphs' => [
                            'This statement captured an important part of PH Haiyan’s public record: the ability to treat a delay as a matter of accountability rather than confusion. By informing the public directly, the organization kept the campaign credible and made clear that responsible advocacy includes compliance, documentation, and public notice.',
                            'It also marked the transition from a public launch effort to a deeper policy conversation about what kind of roadside greening is possible, lawful, and sustainable in Tacloban.',
                        ],
                    ],
                    [
                        'title' => 'What comes next',
                        'paragraphs' => [
                            'PH Haiyan’s position, as reflected in the statement and related public updates, was that coordination would continue until agencies could reach a clearer path forward. Future updates would depend on the outcome of policy review, inter-agency consultation, and the organization’s ongoing push for greener and safer public roads.',
                        ],
                    ],
                ]),
                'reading_time' => 4,
                'is_featured' => true,
                'is_published' => true,
                'published_at' => '2025-11-05 09:00:00',
            ],
            [
                'title' => 'PH Haiyan Advocacy Inc. Pushes for Policy Reform on Roadside Tree Planting',
                'slug' => 'policy-reform-on-roadside-tree-planting',
                'category' => 'Policy',
                'image' => '/images/imported/news/policy-reform.png',
                'excerpt' => 'PH Haiyan pushed for policy reform on roadside tree planting and paused the Adopt-a-Tree launch pending DPWH review.',
                'content' => $articleStory('article-repeal.php'),
                'reading_time' => 8,
                'is_featured' => false,
                'is_published' => true,
                'published_at' => '2025-10-14 09:00:00',
            ],
            [
                'title' => 'Important Announcement: Adopt-a-Tree kickoff rescheduled',
                'slug' => 'important-announcement-adopt-a-tree-kickoff-rescheduled',
                'category' => 'News',
                'image' => '/images/imported/news/important-announcement.png',
                'excerpt' => 'The kick-off of the Adopt-a-Tree Project along the Tacloban Bypass Road was moved from August 16, 2025 to November 8, 2025.',
                'content' => $story([
                    [
                        'title' => 'What changed in the schedule',
                        'paragraphs' => [
                            'PH Haiyan informed sponsors, partner agencies, volunteers, and supporters that the kickoff of the Adopt-a-Tree project along the Tacloban Bypass Road would move from August 16, 2025 to November 8, 2025. The announcement was part of the organization’s effort to keep the public updated while the project shifted from campaign messaging into coordinated field preparation.',
                        ],
                    ],
                    [
                        'title' => 'Why the reset mattered',
                        'paragraphs' => [
                            'By the time the announcement was issued, PH Haiyan and partner offices were already dealing with right-of-way, encroachment, and field-readiness questions. The schedule change created space for more coordinated site work, inter-agency meetings, and practical problem-solving before a public kickoff could proceed.',
                        ],
                        'bullets' => [
                            'The first inter-agency coordination meeting took place on August 7, 2025.',
                            'The project was moving from public invitation into actual field validation and coordination.',
                            'The postponement kept supporters informed instead of leaving the project timeline unclear.',
                        ],
                    ],
                    [
                        'title' => 'Why this public notice mattered',
                        'paragraphs' => [
                            'The update showed that PH Haiyan treated announcements as part of public accountability. Rather than letting confusion grow around the schedule, the organization preserved a visible record of the change, helping volunteers and partners understand that timing adjustments were part of a responsible rollout process.',
                        ],
                    ],
                    [
                        'title' => 'What followed',
                        'paragraphs' => [
                            'This rescheduling notice became part of a longer story. In the weeks that followed, PH Haiyan’s roadside greening work intersected more directly with policy and legal review, eventually leading to the later official statement on the indefinite postponement of the kickoff while the broader issue was being elevated and clarified.',
                        ],
                    ],
                ]),
                'reading_time' => 4,
                'is_featured' => false,
                'is_published' => true,
                'published_at' => '2025-08-07 09:00:00',
            ],
            [
                'title' => 'Grow More Trees',
                'slug' => 'grow-more-trees',
                'category' => 'Campaign',
                'image' => '/images/imported/news/grow-more-trees.png',
                'excerpt' => 'A call to support the Adopt-a-Tree campaign and join the tree-planting effort for a greener Tacloban.',
                'content' => $story([
                    [
                        'title' => 'A public call for greener roads',
                        'paragraphs' => [
                            '“Grow More Trees” worked as a public-facing campaign message inside PH Haiyan’s wider Adopt-a-Tree effort. The update invited residents, supporters, and partner groups to see roadside greening as something practical and visible — not as an abstract climate issue, but as a shared local action that could change how Tacloban’s roads feel, function, and endure.',
                        ],
                    ],
                    [
                        'title' => 'What the campaign was trying to build',
                        'bullets' => [
                            'Public support for tree-growing and Banaba planting along the Tacloban Bypass Road.',
                            'A stronger sense that climate resilience can be made visible in everyday public infrastructure.',
                            'Momentum around a campaign that linked environmental care, public participation, and civic pride.',
                        ],
                    ],
                    [
                        'title' => 'Why this kind of update mattered',
                        'paragraphs' => [
                            'Campaign posts like this helped translate technical environmental work into language the public could immediately understand. They made the advocacy legible to people who might join as volunteers, supporters, or observers long before they read a policy paper or a formal letter.',
                            'That role matters in an archive. It shows that PH Haiyan’s work is not only about writing to agencies, but also about keeping climate action visible, understandable, and open to public participation.',
                        ],
                    ],
                ]),
                'reading_time' => 3,
                'is_featured' => false,
                'is_published' => true,
                'published_at' => '2025-08-06 09:00:00',
            ],
            [
                'title' => 'Flooding Threat Prompts PH Haiyan to Seek City Action',
                'slug' => 'flooding-threat-prompts-city-action',
                'category' => 'Flood Control',
                'image' => '/images/imported/news/flooding-threat.png',
                'excerpt' => 'PH Haiyan urged the Tacloban City government to prioritize a comprehensive flood control master plan.',
                'content' => $articleStory('article-flood-letter.php'),
                'reading_time' => 7,
                'is_featured' => false,
                'is_published' => true,
                'published_at' => '2025-07-11 09:00:00',
            ],
            [
                'title' => 'Morning Tree Guard Installation with DPWH',
                'slug' => 'morning-tree-guard-installation-with-dpwh',
                'category' => 'Tree Planting',
                'image' => '/images/imported/news/tree-guard-installation.png',
                'excerpt' => 'PH Haiyan continued the installation of tree guards along the Tacloban Bypass Road in partnership with DPWH.',
                'content' => $story([
                    [
                        'title' => 'A field activity focused on protection',
                        'paragraphs' => [
                            'This update documented one of the practical maintenance steps behind PH Haiyan’s roadside greening work: the installation of tree guards for Banaba trees along the Tacloban Bypass Road. Conducted in coordination with DPWH, the activity showed that tree planting was being treated as a long-term public effort rather than a one-day symbolic event.',
                        ],
                    ],
                    [
                        'title' => 'Why tree guards mattered',
                        'bullets' => [
                            'They helped protect newly planted trees from roadside disturbance and early damage.',
                            'They signaled that maintenance and survivability matter as much as planting itself.',
                            'They made the greening effort more visible to agencies and the public using the bypass road.',
                        ],
                    ],
                    [
                        'title' => 'What this field record says about the advocacy',
                        'paragraphs' => [
                            'PH Haiyan’s archive is strongest when it shows actual follow-through. This update belongs in that record because it demonstrates the practical side of resilience work: once a project is visible in public, it also needs care, protection, and agency coordination to survive.',
                            'That is why the activity matters beyond the photo. It captures the day-to-day stewardship required to make environmental advocacy durable in the field.',
                        ],
                    ],
                ]),
                'reading_time' => 4,
                'is_featured' => false,
                'is_published' => true,
                'published_at' => '2025-07-08 09:00:00',
            ],
        ];

        foreach ($posts as $post) {
            NewsPost::query()->updateOrCreate(
                ['slug' => $post['slug']],
                $post,
            );
        }
    }
}
