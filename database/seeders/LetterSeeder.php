<?php

namespace Database\Seeders;

use App\Models\Letter;
use Illuminate\Database\Seeder;

class LetterSeeder extends Seeder
{
    public function run(): void
    {
        $remote = static function (string $path): string {
            $segments = array_map('rawurlencode', explode('/', ltrim($path, '/')));

            return '/'.implode('/', $segments);
        };

        $archiveArticle = static function (string $relativePath) use ($remote): string {
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
            ];

            $skipTags = ['script', 'style', 'footer', 'h1'];

            $serialize = static function (\DOMNode $node) use (&$serialize, $remote, $skipClasses, $skipIds, $skipTags): string {
                if ($node instanceof \DOMText) {
                    if (trim($node->nodeValue ?? '') === '') {
                        return '';
                    }

                    return htmlspecialchars($node->nodeValue ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
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

                if ($tag === 'a' && array_intersect(['back-btn', 'print-btn'], $classList)) {
                    return '';
                }

                if ($tag === 'div' || $tag === 'section') {
                    $inner = '';

                    foreach ($node->childNodes as $child) {
                        $inner .= $serialize($child);
                    }

                    return $inner;
                }

                $allowedTags = ['h2', 'h3', 'p', 'ul', 'ol', 'li', 'strong', 'em', 'br', 'a'];

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
                        $href = $remote($href);
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

            return trim($output);
        };

        $articleStory = static fn (string $file): string => $archiveArticle('old-letters-articles/'.$file);

        $escape = static fn (string $value): string => htmlspecialchars($value, ENT_QUOTES, 'UTF-8');

        $body = static function (array $sections) use ($escape): string {
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

        $record = static function (string $context, array $asks, string $why) {
            return [
                [
                    'title' => 'Context',
                    'paragraphs' => [$context],
                ],
                [
                    'title' => 'What the Letter Covered',
                    'bullets' => $asks,
                ],
                [
                    'title' => 'Why It Matters',
                    'paragraphs' => [$why],
                ],
            ];
        };

        $letters = [
            [
                'title' => 'Water Accountability in Tacloban',
                'slug' => 'water-accountability-in-tacloban',
                'category' => 'Water Governance',
                'topic' => 'LMWD-PrimeWater agreement',
                'summary' => 'PH Haiyan called for decisive resolution of the LMWD-PrimeWater agreement after confirmed breach notices, unresolved pre-termination, and continuing service failures affecting Tacloban consumers.',
                'document_url' => $remote('assets/letters/LMWD Letter Reply to Haiyan.pdf'),
                'image' => $remote('assets/letters/img/CALL FOR ACTION.png'),
                'is_featured' => true,
                'published_at' => '2026-03-18 00:00:00',
                'source_url' => null,
                'attachments' => [],
                'key_takeaways' => [
                    'LMWD had already issued breach and pre-termination notices in 2025.',
                    'Consumers were still dealing with intermittent supply, weak pressure, and limited service.',
                    'PH Haiyan called for a clear and transparent public resolution.',
                ],
                'body' => $articleStory('article-Water-Accountability.php'),
            ],
            [
                'title' => 'Request for Clarification on PrimeWater Concession Arrangement, Reported Change in Ownership, and Regulatory Compliance',
                'slug' => 'request-clarification-primewater-concession-arrangement',
                'category' => 'Water Governance',
                'topic' => 'PrimeWater ownership transition',
                'summary' => 'PH Haiyan asked LMWD and the city government to clarify the reported PrimeWater ownership transition and disclose its implications for governance, audits, environmental compliance, and consumer protection.',
                'document_url' => $remote('assets/letters/LMWD LETTER.pdf'),
                'image' => $remote('assets/letters/img/LMWD.png'),
                'is_featured' => false,
                'published_at' => '2026-02-18 00:00:00',
                'source_url' => null,
                'attachments' => [
                    [
                        'label' => 'Open companion letter to the City Mayor',
                        'url' => $remote('assets/letters/LETTER TO MAYOR (LMWD).pdf'),
                    ],
                ],
                'key_takeaways' => [
                    'The letters raised governance, audit, wastewater, and rate-governance issues.',
                    'PH Haiyan treated transparency as central to consumer protection.',
                    'The old story framed the issue as public utility accountability, not private restructuring alone.',
                ],
                'body' => $articleStory('article-lmwd.php'),
            ],
            [
                'title' => 'Inquiry on the Maintenance and Landscaping of the Access Road Beside the New Tacloban Terminal',
                'slug' => 'inquiry-maintenance-landscaping-access-road-new-tacloban-terminal',
                'category' => 'Urban Landscape',
                'topic' => 'Tacloban DZR Airport access road',
                'summary' => 'PH Haiyan framed the airport frontage and access road as a climate-resilient public-space issue, urging better maintenance, landscaping, and interagency coordination around Tacloban DZR Airport.',
                'document_url' => $remote('assets/letters/Oct 17 Letter.pdf'),
                'image' => $remote('assets/letters/img/Airport.png'),
                'is_featured' => false,
                'published_at' => '2026-02-06 00:00:00',
                'source_url' => null,
                'attachments' => [
                    [
                        'label' => 'Open November response',
                        'url' => $remote('assets/letters/Nov 13 response.pdf'),
                    ],
                    [
                        'label' => 'Open January follow-up letter',
                        'url' => $remote('assets/letters/Jan 27 Letter.pdf'),
                    ],
                ],
                'key_takeaways' => [
                    'The airport frontage was framed as a city-building and climate-adaptation issue.',
                    'PH Haiyan documented formal letters, response, and follow-up.',
                    'The old story treated landscaping as protection and public dignity, not decoration alone.',
                ],
                'body' => $articleStory('article-airport-letter.php'),
            ],
            [
                'title' => 'Calls for Immediate Action, Accountability, and Enforcement of Solid Waste Laws',
                'slug' => 'calls-for-immediate-action-accountability-solid-waste-laws',
                'category' => 'Solid Waste',
                'topic' => 'Tacloban garbage collection and enforcement',
                'summary' => 'PH Haiyan raised urgent concerns over uncollected garbage in Tacloban and called for immediate action, public accountability, and enforcement of existing solid waste laws.',
                'document_url' => $remote('assets/letters/Solid Waste.pdf'),
                'image' => $remote('assets/letters/img/Waste.png'),
                'is_featured' => false,
                'published_at' => '2026-02-03 00:00:00',
                'source_url' => null,
                'attachments' => [],
                'key_takeaways' => [
                    'PH Haiyan linked garbage buildup to health, drainage, and flood risk.',
                    'The article cited both national and local waste-management mandates.',
                    'The letter asked for investigation, remedial action, and public disclosure.',
                ],
                'body' => $articleStory('article-waste-management.php'),
            ],
            [
                'title' => 'From Warnings to Flooding: A Documented Timeline of PH Haiyan\'s Call for a Flood-Free Tacloban',
                'slug' => 'from-warnings-to-flooding-documented-timeline',
                'category' => 'Flood Control',
                'topic' => 'Flood-free Tacloban public record',
                'summary' => 'This letter-story preserved PH Haiyan\'s documented timeline of warnings, forum outcomes, formal letters, and institutional follow-through related to Tacloban flood planning before the January 2026 flooding.',
                'document_url' => $remote('assets/letters/all letters.pdf'),
                'image' => $remote('assets/letters/img/from-warning-to-flooding.png'),
                'is_featured' => false,
                'published_at' => '2026-01-16 00:00:00',
                'source_url' => null,
                'attachments' => [
                    ['label' => 'Open 24 March 2025 letter', 'url' => $remote('assets/letters/24 March 2025 Letter.pdf')],
                    ['label' => 'Open 15 April 2025 letter', 'url' => $remote('assets/letters/15 April 2025 Letter.pdf')],
                    ['label' => 'Open 25 April 2025 letter', 'url' => $remote('assets/letters/25 April 2025 Letter.pdf')],
                    ['label' => 'Open 28 May 2025 letter', 'url' => $remote('assets/letters/28 May 2025 Letter.pdf')],
                    ['label' => 'Open 24 June 2025 letter to Mayor', 'url' => $remote('assets/letters/24 June 2025 Letter to Mayor.pdf')],
                    ['label' => 'Open 24 June 2025 letter to City DILG', 'url' => $remote('assets/letters/24 June 2025 DILG-CITY.pdf')],
                    ['label' => 'Open 24 June 2025 letter to DILG Region VIII', 'url' => $remote('assets/letters/24 June 2025 DILG-REGION.pdf')],
                    ['label' => 'Open Executive Order reference', 'url' => $remote('assets/letters/Executive Order for CFCMP.pdf')],
                ],
                'key_takeaways' => [
                    'The article documented repeated notice before the January 2026 flooding.',
                    'Forum outputs, letters, and DILG escalation were part of the public record.',
                    'PH Haiyan framed the issue as one of transparency, notice, and accountability.',
                ],
                'body' => $articleStory('article-flood-letter.php'),
            ],
            [
                'title' => 'Request for Presidential Intervention to Repeal DPWH Department Order No. 73, s. 2014, and Reinstate Tree Planting in Road Right-of-Way Areas',
                'slug' => 'request-presidential-intervention-repeal-dpwh-do-73',
                'category' => 'Roadside Greening',
                'topic' => 'Repeal of DPWH DO No. 73',
                'summary' => 'PH Haiyan elevated its roadside greening advocacy to the national level, requesting presidential intervention so climate-resilient tree planting along national road right-of-way areas could be restored lawfully.',
                'document_url' => $remote('assets/letters/repeal.pdf'),
                'image' => $remote('assets/letters/img/repeal.png'),
                'is_featured' => false,
                'published_at' => '2025-09-25 00:00:00',
                'source_url' => null,
                'attachments' => [
                    ['label' => 'Open official advisory on the postponed kickoff', 'url' => $remote('assets/letters/ol.pdf')],
                ],
                'key_takeaways' => [
                    'The roadside greening issue had already moved into national policy review.',
                    'PH Haiyan postponed the kickoff while still pushing for policy reform.',
                    'The old story linked tree planting to resilience, not ornament alone.',
                ],
                'body' => $articleStory('article-repeal.php'),
            ],
            [
                'title' => 'DPWH Backs PH Haiyan Advocacy Inc.\'s Proposal to Integrate Fruit-Bearing Trees into Tacloban\'s National Roads',
                'slug' => 'proposal-fruit-bearing-trees-tacloban-national-roads',
                'category' => 'Roadside Greening',
                'topic' => 'Fruit-bearing roadside trees',
                'summary' => 'PH Haiyan proposed the integration of fruit-bearing trees into Tacloban\'s national roads as a food security, tourism, and climate-resilience measure, and the proposal received a favorable DPWH response.',
                'document_url' => $remote('assets/letters/PROPOSAL TO DPWH.pdf'),
                'image' => $remote('assets/letters/img/A.png'),
                'is_featured' => false,
                'published_at' => '2025-09-16 00:00:00',
                'source_url' => null,
                'attachments' => [],
                'key_takeaways' => [
                    'The proposal connected roads to food security, tourism, and climate resilience.',
                    'DPWH central and regional offices responded positively.',
                    'The old story presented the idea as infrastructure for well-being.',
                ],
                'body' => $articleStory('article-dpwh-fruit-trees.php'),
            ],
            [
                'title' => 'Appeal for the Immediate Withdrawal of DPWH Department Order No. 73, Series of 2014, and Submission of Position Paper on Strengthened Inter-Agency Coordination in Tacloban City',
                'slug' => 'appeal-withdrawal-dpwh-do-73-series-2014',
                'category' => 'Policy Advocacy',
                'topic' => 'Position paper on roadside tree planting',
                'summary' => 'This appeal and position paper challenged DPWH Department Order No. 73 and argued for stronger inter-agency coordination so climate-resilient roadside greening could proceed lawfully in Tacloban.',
                'document_url' => $remote('assets/letters/7.pdf'),
                'image' => $remote('assets/letters/img/Appeal.png'),
                'is_featured' => false,
                'published_at' => '2025-09-02 00:00:00',
                'source_url' => null,
                'attachments' => [
                    ['label' => 'Open consolidated appeal and position paper', 'url' => $remote('assets/letters/APPEAL.pdf')],
                    ['label' => 'Open letter packet 1', 'url' => $remote('assets/letters/1.pdf')],
                    ['label' => 'Open letter packet 2', 'url' => $remote('assets/letters/2.pdf')],
                    ['label' => 'Open letter packet 3', 'url' => $remote('assets/letters/3.pdf')],
                    ['label' => 'Open letter packet 4', 'url' => $remote('assets/letters/4.pdf')],
                    ['label' => 'Open letter packet 5', 'url' => $remote('assets/letters/5.pdf')],
                    ['label' => 'Open letter packet 6', 'url' => $remote('assets/letters/6.pdf')],
                ],
                'key_takeaways' => [
                    'The appeal traced a conflict between older DPWH greening directives and DO No. 73.',
                    'PH Haiyan turned a local roadside issue into a national policy question.',
                    'The old story treated inter-agency coordination as the missing public-service ingredient.',
                ],
                'body' => $articleStory('article-appeal.php'),
            ],
            [
                'title' => 'Request for Shapefile of NGP Projects within Tigbao Watershed and Copy of Foreshore Development Plan of Tacloban City',
                'slug' => 'request-shapefile-ngp-projects-tigbao-watershed',
                'category' => 'Watershed Planning',
                'topic' => 'Tigbao watershed and foreshore planning',
                'summary' => 'PH Haiyan requested NGP shapefiles and Tacloban foreshore planning documents to support watershed regeneration, landscape mapping, and sustainable livelihood planning around the Tigbao area.',
                'document_url' => $remote('assets/letters/CENRO_04-10-2025.pdf'),
                'image' => $remote('assets/letters/img/CENRO_04-10-2025.png'),
                'is_featured' => false,
                'published_at' => '2025-04-10 00:00:00',
                'source_url' => null,
                'attachments' => [],
                'key_takeaways' => null,
                'body' => $body($record(
                    'This letter supported PH Haiyan\'s watershed and livelihood planning work by asking for technical and planning records connected to the Tigbao Watershed and Tacloban foreshore areas.',
                    [
                        'Requested shapefiles of National Greening Program projects within the Tigbao Watershed.',
                        'Requested a copy of Tacloban City\'s foreshore development plan.',
                        'Placed the request inside a broader landscape-regeneration proposal.',
                    ],
                    'The record shows that PH Haiyan pursued evidence, mapping, and planning intelligence alongside public advocacy.'
                )),
            ],
            [
                'title' => 'Inquiry on Compliance with Section 20 of R.A. 9275 (Clean Water Act of 2004), Water Quality Monitoring of Cancabato Bay, and Legal Basis of Reclamation Activity near Tacloban Astrodome',
                'slug' => 'inquiry-compliance-clean-water-act-cancabato-bay',
                'category' => 'Cancabato Bay',
                'topic' => 'Water quality and reclamation oversight',
                'summary' => 'PH Haiyan sought clarification on Clean Water Act compliance, water quality monitoring, and the legal basis of reclamation-related activity near Cancabato Bay and the Tacloban Astrodome.',
                'document_url' => $remote('assets/letters/Cancabato Bay Letter to Mayor 2025.pdf'),
                'image' => $remote('assets/letters/img/Cancabato Bay Letter to Mayor 2025.png'),
                'is_featured' => false,
                'published_at' => '2025-04-30 00:00:00',
                'source_url' => null,
                'attachments' => [],
                'key_takeaways' => null,
                'body' => $body($record(
                    'This letter connected Cancabato Bay protection, legal compliance, and public disclosure around water quality and reclamation activity near the Astrodome area.',
                    [
                        'Asked about Clean Water Act compliance under Section 20 of R.A. 9275.',
                        'Requested information on water-quality monitoring in Cancabato Bay.',
                        'Asked for the legal basis of reclamation-related activity near the Astrodome.',
                    ],
                    'It preserved PH Haiyan\'s effort to ground bay advocacy in official records rather than speculation.'
                )),
            ],
            [
                'title' => 'Update on SEC Registration of Paraclete Integrated Agro-Forestry Producers and Developers Association (PIAFDA)',
                'slug' => 'update-sec-registration-piafda',
                'category' => 'Organizational Records',
                'topic' => 'PIAFDA registration update',
                'summary' => 'This record tracked the SEC registration update of PIAFDA, a step relevant to agro-forestry organization, livelihood coordination, and longer-term environmental work linked to PH Haiyan\'s advocacy network.',
                'document_url' => $remote('assets/letters/CENRO Handover SEC.pdf'),
                'image' => $remote('assets/letters/img/CENRO Handover SEC.png'),
                'is_featured' => false,
                'published_at' => '2025-05-28 00:00:00',
                'source_url' => null,
                'attachments' => [],
                'key_takeaways' => null,
                'body' => $body($record(
                    'The old archive preserved this administrative update because institution-building and agro-forestry implementation also depend on formal organizational standing.',
                    [
                        'Tracked the SEC registration status of PIAFDA.',
                        'Linked the update to agro-forestry and livelihood coordination.',
                        'Showed PH Haiyan preserving operational as well as advocacy records.',
                    ],
                    'Even administrative documents matter when environmental programs require recognized community partners and accountable structures.'
                )),
            ],
            [
                'title' => 'Inquiry on the Current Environmental Condition and Existing Rehabilitation Programs for Cancabato Bay, Tacloban City',
                'slug' => 'inquiry-environmental-condition-cancabato-bay',
                'category' => 'Cancabato Bay',
                'topic' => 'Environmental rehabilitation of Cancabato Bay',
                'summary' => 'PH Haiyan requested information on the environmental condition of Cancabato Bay and on existing rehabilitation programs, studies, and monitoring efforts affecting the bay.',
                'document_url' => $remote('assets/letters/Letter to EMB 03-06-2025.pdf'),
                'image' => $remote('assets/letters/img/Letter to EMB 03-06-2025.png'),
                'is_featured' => false,
                'published_at' => '2025-03-06 00:00:00',
                'source_url' => null,
                'attachments' => [],
                'key_takeaways' => null,
                'body' => $body($record(
                    'This inquiry asked for actual environmental studies and rehabilitation records related to Cancabato Bay so public discussion could rest on documented evidence.',
                    [
                        'Requested water-quality monitoring and biodiversity assessments.',
                        'Asked for pollution-source inventories and similar studies.',
                        'Asked about existing rehabilitation programs for the bay.',
                    ],
                    'The letter shows PH Haiyan pairing ecological concern with documentary requests that make environmental accountability measurable.'
                )),
            ],
            [
                'title' => 'Mayor Alfred S. Romualdez Invitation to a Meeting',
                'slug' => 'mayor-alfred-romualdez-invitation-to-a-meeting',
                'category' => 'Flood Control',
                'topic' => 'Comprehensive flood control master plan',
                'summary' => 'This record related to the endorsement and urgent formulation of a comprehensive flood control master plan for Tacloban City and the effort to convene the needed institutional discussion.',
                'document_url' => $remote('assets/letters/Letter to PENRO 04-10-2025.pdf'),
                'image' => $remote('assets/letters/img/Letter to PENRO 04-10-2025.png'),
                'is_featured' => false,
                'published_at' => '2025-04-10 00:00:00',
                'source_url' => null,
                'attachments' => [],
                'key_takeaways' => null,
                'body' => $body($record(
                    'The old archive grouped this record with PH Haiyan\'s efforts to move flood-control concerns into formal meetings and structured institutional engagement.',
                    [
                        'Preserved a meeting-related record tied to the flood-control issue.',
                        'Linked the communication to the urgent formulation of a comprehensive flood control master plan.',
                        'Showed that PH Haiyan pursued formal engagement channels early.',
                    ],
                    'This matters because the later flood timeline was built on precisely these kinds of documented institutional approaches.'
                )),
            ],
            [
                'title' => 'Letter to DENR Request for Indigenous Trees in Bypass Road Tree Planting',
                'slug' => 'request-indigenous-tree-species-bypass-road',
                'category' => 'Roadside Greening',
                'topic' => 'Indigenous species for bypass road tree planting',
                'summary' => 'PH Haiyan requested help identifying indigenous tree species appropriate for the Tacloban Bypass Road tree-planting initiative, aligning roadside greening with ecological suitability.',
                'document_url' => $remote('assets/letters/Letter to DENR 06-11-2025.pdf'),
                'image' => $remote('assets/letters/img/Request for the Identification.png'),
                'is_featured' => false,
                'published_at' => '2025-06-11 00:00:00',
                'source_url' => null,
                'attachments' => [],
                'key_takeaways' => null,
                'body' => $body($record(
                    'This letter shows that PH Haiyan treated roadside greening as ecological restoration, not just a symbolic planting activity.',
                    [
                        'Requested identification of indigenous tree species for the Tacloban Bypass Road.',
                        'Sought DENR guidance to improve species fit and ecological credibility.',
                        'Connected species selection to the broader bypass-road initiative.',
                    ],
                    'The record matters because it shows attention to ecological quality, survivability, and long-term restoration value.'
                )),
            ],
            [
                'title' => 'Letter to City DILG',
                'slug' => 'letter-to-city-dilg',
                'category' => 'Flood Control',
                'topic' => 'DILG city intervention on flood planning',
                'summary' => 'PH Haiyan transmitted its flood-control concerns to City DILG and requested intervention and enforcement action regarding Tacloban\'s continued failure to formulate a legally mandated comprehensive flood control master plan.',
                'document_url' => $remote('assets/letters/Letter to City DILG.pdf'),
                'image' => $remote('assets/letters/img/Letter to City DILG.png'),
                'is_featured' => false,
                'published_at' => '2025-06-24 00:00:00',
                'source_url' => null,
                'attachments' => [],
                'key_takeaways' => null,
                'body' => $body($record(
                    'This letter pushed the flood-control issue into City DILG oversight and treated the missing comprehensive flood control master plan as a compliance issue, not just a policy preference.',
                    [
                        'Transmitted PH Haiyan\'s flood-control concerns to City DILG.',
                        'Requested intervention and enforcement action.',
                        'Documented escalation beyond city executive channels.',
                    ],
                    'It forms part of the paper trail showing that PH Haiyan preserved notice, escalation, and accountability around flood planning.'
                )),
            ],
            [
                'title' => 'Letter to DILG Region VIII',
                'slug' => 'letter-to-dilg-region-viii',
                'category' => 'Flood Control',
                'topic' => 'Regional oversight on Tacloban flood planning',
                'summary' => 'PH Haiyan elevated the flood-control issue to DILG Region VIII and requested regional oversight over Tacloban\'s noncompliance with the comprehensive flood control master plan mandate.',
                'document_url' => $remote('assets/letters/Letter to DILG Region VIII.pdf'),
                'image' => $remote('assets/letters/img/Letter to DILG Region VIII.png'),
                'is_featured' => false,
                'published_at' => '2025-06-24 00:00:00',
                'source_url' => null,
                'attachments' => [],
                'key_takeaways' => null,
                'body' => $body($record(
                    'This regional DILG letter extended the flood-control issue beyond the city level and asked for higher-level oversight on Tacloban\'s planning gap.',
                    [
                        'Escalated the matter to DILG Region VIII.',
                        'Requested regional oversight on the flood-control planning mandate.',
                        'Expanded the formal record of institutional notice.',
                    ],
                    'Regional escalation broadened accountability and strengthened the documentary trail PH Haiyan later summarized in its flood timeline.'
                )),
            ],
            [
                'title' => 'Appreciation and Update on the Identification of Tree-Planting Sites along the Tacloban Bypass Road',
                'slug' => 'appreciation-update-identification-tree-planting-sites-bypass-road',
                'category' => 'Roadside Greening',
                'topic' => 'Tacloban Bypass Road tree-planting sites',
                'summary' => 'PH Haiyan documented appreciation and provided an update on the identification of planting sites along the Tacloban Bypass Road as part of its wider roadside greening initiative.',
                'document_url' => $remote('assets/letters/Letter to DPWH Appreciation Bypass Actual Survey.pdf'),
                'image' => $remote('assets/letters/img/Letter to DPWH Appreciation Bypass Actual Survey.png'),
                'is_featured' => false,
                'published_at' => '2025-06-24 00:00:00',
                'source_url' => null,
                'attachments' => [],
                'key_takeaways' => null,
                'body' => $body($record(
                    'This record captured actual progress in identifying sites for the Tacloban Bypass Road tree-planting initiative and acknowledged participating partners.',
                    [
                        'Recorded progress on site identification and actual survey work.',
                        'Preserved appreciation and coordination around the bypass-road effort.',
                        'Showed that the initiative had moved into field-based implementation steps.',
                    ],
                    'It matters because it shows the project was already operational before later policy conflicts complicated rollout.'
                )),
            ],
            [
                'title' => 'Tacloban City\'s Plan to Create a Tarsier Sanctuary in Barangay Salvacion Where Balugo Falls Is Located',
                'slug' => 'tacloban-city-plan-tarsier-sanctuary-balugo-falls',
                'category' => 'Watershed Planning',
                'topic' => 'Balugo Falls and tarsier sanctuary',
                'summary' => 'This record supported PH Haiyan\'s watershed and landscape advocacy by tying Balugo Falls, Barangay Salvacion, and the idea of a tarsier sanctuary to broader regeneration and eco-tourism planning.',
                'document_url' => $remote('assets/letters/Letter to PENRO.pdf'),
                'image' => $remote('assets/letters/img/Letter to PENRO.png'),
                'is_featured' => false,
                'published_at' => '2025-04-10 00:00:00',
                'source_url' => null,
                'attachments' => [],
                'key_takeaways' => null,
                'body' => $body($record(
                    'The old archive linked this record to PH Haiyan\'s watershed, biodiversity, and eco-tourism vision around Barangay Salvacion and Balugo Falls.',
                    [
                        'Documented correspondence tied to Balugo Falls and environmental planning.',
                        'Connected habitat protection and sanctuary ideas to broader watershed advocacy.',
                        'Reinforced PH Haiyan\'s long-term land and biodiversity concerns.',
                    ],
                    'This record helps show that PH Haiyan\'s resilience work includes upstream landscapes and ecological stewardship, not only urban issues.'
                )),
            ],
            [
                'title' => 'Resilient Leadership for a Climate-Ready Future',
                'slug' => 'resilient-leadership-climate-ready-future',
                'category' => 'Public Leadership',
                'topic' => 'San Juanico Bridge and climate-ready leadership',
                'summary' => 'This letter framed the San Juanico Bridge issue in the language of resilience and public leadership, calling for decisions that match the demands of a climate-ready future.',
                'document_url' => $remote('assets/letters/Letter to Speaker Romualdez.pdf'),
                'image' => $remote('assets/letters/img/Letter to Speaker Romualdez.png'),
                'is_featured' => false,
                'published_at' => '2025-06-05 00:00:00',
                'source_url' => null,
                'attachments' => [],
                'key_takeaways' => null,
                'body' => $body($record(
                    'The old archive described this record as a call for leadership on a public infrastructure issue through the lens of resilience and long-term readiness.',
                    [
                        'Connected the San Juanico Bridge issue to climate-ready leadership.',
                        'Framed resilience as a leadership standard, not only a technical concept.',
                        'Preserved a public-facing record directed to national leadership.',
                    ],
                    'The letter broadened PH Haiyan\'s archive from environmental management into the language of public leadership and institutional responsibility.'
                )),
            ],
        ];

        foreach ($letters as $letter) {
            Letter::query()->updateOrCreate(
                ['slug' => $letter['slug']],
                $letter
            );
        }

        Letter::query()
            ->whereNotIn('slug', array_column($letters, 'slug'))
            ->delete();
    }
}
