<?php

namespace App\Mcp\Tools;

use App\Mcp\Support\Activity;
use App\Mcp\Support\Html;
use App\Mcp\Support\Render;
use App\Mcp\Support\SiteIndex;
use App\Mcp\Support\Validate;
use App\Mcp\ToolError;
use App\Mcp\ToolSet;
use App\Models\Page;
use App\Models\PageSection;
use App\Models\Post;

class HeadingTools extends ToolSet
{
    /** component => [attr => heading level] for headings that live in component attrs */
    private const COMPONENT_HEADINGS = [
        'page-hero' => ['title' => 1],
        'home-hero' => ['title' => 1, 'title_b' => 1, 'form_title' => 2],
        'business-picker' => ['heading' => 2],
        'plans' => ['heading' => 2],
        'home-faq' => ['heading' => 2],
        'cta-band' => ['ctaTitle' => 2],
    ];

    public function tools(): array
    {
        return [
            $this->read('get_page_outline', 'The rendered H1-H6 tree of a page or post, including headings from components, header and footer (marked by region), plus words and headings per section.', self::ownerProps(), [], fn ($a) => $this->outline($this->findOwner($a))),

            $this->write('update_heading', 'Change one heading\'s text (or level). Finds it in the html sections or component texts (page-hero title = the H1). Give section_id when the same heading text appears twice. A page keeps exactly one H1.', self::ownerProps() + [
                'current_text' => self::s('The heading text as it is now (as in get_page_outline)'),
                'new_text' => self::s('New plain text'),
                'new_html' => self::s('Or new inner HTML, e.g. Retail <span class="text-gradient">POS</span>'),
                'new_level' => self::i('Change the level (1-6)'),
                'section_id' => self::i('Section that holds it'),
                'expected_updated_at' => self::s('updated_at of that section (or of the post)'),
            ], ['current_text', 'expected_updated_at'], fn ($a) => $this->update($a)),

            $this->read('audit_headings', 'Heading problems per page: missing or several H1, skipped levels, empty or duplicate headings, headings over 70 characters, and H1/H2 wording repeated across industry pages.', [
                'page_type' => self::e('Only this type (or "post")', array_merge(Page::TYPES, ['post'])),
            ], [], fn ($a) => $this->audit($a)),
        ];
    }

    private function outline($owner): array
    {
        $html = Render::full($owner);
        $mainStart = strpos($html, '<main id="content">');
        $mainEnd = strrpos($html, '</main>');
        $out = [];
        preg_match_all('#<h([1-6])\b[^>]*>(.*?)</h\1>#is', $html, $m, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);
        foreach ($m as $h) {
            $pos = $h[0][1];
            $region = $pos < $mainStart ? 'header' : ($pos > $mainEnd ? 'footer / widgets' : 'main');
            $level = (int) $h[1][0];
            $out[] = ['level' => $level, 'line' => str_repeat('  ', $level - 1).'h'.$level.' '.Html::text($h[2][0]).($region === 'main' ? '' : '   ['.$region.']')];
        }
        $sections = [];
        if ($owner instanceof Page) {
            foreach ($owner->sections as $s) {
                $render = $s->is_visible ? Render::section($s) : '';
                $sections[] = ['section_id' => $s->id, 'key' => $s->key, 'type' => $s->type, 'component' => $s->component, 'visible' => $s->is_visible,
                    'words' => Html::words(Html::text($render)), 'headings' => array_map(fn ($h) => 'h'.$h['level'].': '.$h['text'], Html::headings($render))];
            }
        } else {
            $body = \App\Cms\Placeholders::render($owner->content, '');
            $sections[] = ['post_id' => $owner->id, 'key' => 'post body', 'words' => Html::words(Html::text($body)), 'headings' => array_map(fn ($h) => 'h'.$h['level'].': '.$h['text'], Html::headings($body))];
        }

        return [
            'url' => $owner->path,
            'h1_count' => count(array_filter($out, fn ($h) => $h['level'] === 1)),
            'outline' => array_column($out, 'line'),
            'sections' => $sections,
        ];
    }

    private function update(array $a): array
    {
        $owner = $this->findOwner($a);
        $current = trim($a['current_text']);
        if (! isset($a['new_text']) && ! isset($a['new_html']) && ! isset($a['new_level'])) {
            throw new ToolError('Send new_text, new_html or new_level.');
        }
        if (isset($a['new_level']) && ($a['new_level'] < 1 || $a['new_level'] > 6)) {
            throw new ToolError('new_level must be 1-6.');
        }
        $inner = isset($a['new_html']) ? Validate::html($a['new_html'])[0] : (isset($a['new_text']) ? e(strip_tags($a['new_text'])) : null);

        if ($owner instanceof Post) {
            return $this->updatePostHeading($owner, $a, $current, $inner);
        }

        // find candidates
        $hits = [];
        foreach ($owner->sections as $s) {
            if (! empty($a['section_id']) && $s->id !== (int) $a['section_id']) {
                continue;
            }
            if ($s->type === 'html') {
                preg_match_all('#<h([1-6])\b[^>]*>(.*?)</h\1>#is', (string) $s->content, $m, PREG_SET_ORDER);
                foreach ($m as $h) {
                    if (Html::text($h[2]) === $current) {
                        $hits[] = [$s, 'html', $h];
                    }
                }
            } elseif (isset(self::COMPONENT_HEADINGS[$s->component])) {
                foreach (self::COMPONENT_HEADINGS[$s->component] as $attr => $level) {
                    if (isset($s->attrs[$attr]) && Html::text(\App\Cms\Placeholders::render($s->attrs[$attr], '', null, false)) === $current) {
                        $hits[] = [$s, 'attr', [$attr, $level]];
                    }
                }
            }
        }
        if (! $hits) {
            throw new ToolError("No editable heading with the text \"$current\" on this page. Headings from shared components (service-why, CTA band, widgets, footer) are code; see get_page_outline.");
        }
        if (count($hits) > 1) {
            throw new ToolError('That heading text appears '.count($hits).' times; give section_id.', ['section_ids' => array_map(fn ($h) => $h[0]->id, $hits)]);
        }
        [$section, $kind, $h] = $hits[0];
        $this->guard($section, $a, 'section');
        $before = PageTools::sectionOut($section);

        if ($kind === 'attr') {
            [$attr] = $h;
            if (isset($a['new_level'])) {
                throw new ToolError('This heading\'s level is fixed by its component.');
            }
            $attrs = $section->attrs;
            $attrs[$attr] = isset($a['new_html']) ? $inner : strip_tags((string) $a['new_text']);
            $fields = ['attrs' => $attrs];
        } else {
            $level = (int) ($a['new_level'] ?? $h[1]);
            $tagOpen = preg_replace('#^<h[1-6]#i', '<h'.$level, substr($h[0], 0, strpos($h[0], '>') + 1));
            $new = $tagOpen.($inner ?? $h[2]).'</h'.$level.'>';
            $fields = ['content' => preg_replace('/'.preg_quote($h[0], '/').'/', str_replace(['\\', '$'], ['\\\\', '\\$'], $new), $section->content, 1)];
        }
        $candidate = (clone $section)->fill($fields);
        Validate::h1($owner, $owner->sections()->get()->map(fn ($s) => $s->id === $section->id ? $candidate : $s));
        Activity::revision($section, 'before update_heading');
        $section->update($fields);
        $owner->touch();
        Activity::log('update', $section, $before, PageTools::sectionOut($section), 'heading "'.$current.'" on '.$owner->path, [$owner->path]);

        return ['section' => PageTools::sectionOut($section->fresh(), false), 'outline' => $this->outline($owner->fresh())['outline']];
    }

    private function updatePostHeading(Post $post, array $a, string $current, ?string $inner): array
    {
        $this->guard($post, $a, 'post');
        $before = BlogTools::postOut($post);
        if (Html::text(e($post->title)) === $current) {
            if (isset($a['new_level'])) {
                throw new ToolError('A post\'s H1 is its title; its level cannot change.');
            }
            $post->update(['title' => strip_tags(html_entity_decode((string) ($a['new_text'] ?? $a['new_html']), ENT_QUOTES | ENT_HTML5))]);
        } else {
            preg_match_all('#<h([1-6])\b[^>]*>(.*?)</h\1>#is', (string) $post->content, $m, PREG_SET_ORDER);
            $hits = array_values(array_filter($m, fn ($h) => Html::text($h[2]) === $current));
            if (count($hits) !== 1) {
                throw new ToolError(count($hits) ? 'That heading appears '.count($hits).' times in the post.' : 'Heading not found in the post.');
            }
            $h = $hits[0];
            $level = (int) ($a['new_level'] ?? $h[1]);
            if ($level === 1) {
                throw new ToolError('The post title is the H1; use h2-h6 in the body.');
            }
            $tagOpen = preg_replace('#^<h[1-6]#i', '<h'.$level, substr($h[0], 0, strpos($h[0], '>') + 1));
            Activity::revision($post, 'before update_heading');
            $post->update(['content' => preg_replace('/'.preg_quote($h[0], '/').'/', str_replace(['\\', '$'], ['\\\\', '\\$'], $tagOpen.($inner ?? $h[2]).'</h'.$level.'>'), $post->content, 1)]);
        }
        Activity::log('update', $post, $before, BlogTools::postOut($post), 'heading "'.$current.'" on '.$post->path, [$post->path]);

        return ['post' => BlogTools::postOut($post->fresh()), 'outline' => $this->outline($post->fresh())['outline']];
    }

    private function audit(array $a): array
    {
        $entries = array_filter(SiteIndex::all(), fn ($e) => empty($a['page_type']) || $e['page_type'] === $a['page_type']);
        $rows = [];
        $h2Use = [];
        $patterns = [];
        foreach ($entries as $path => $e) {
            $p = [];
            $h1 = array_filter($e['headings'], fn ($h) => $h['level'] === 1);
            if (count($h1) !== 1) {
                $p[] = count($h1) ? count($h1).' H1 headings' : 'no H1';
            }
            $prev = 0;
            $seen = [];
            foreach ($e['headings'] as $h) {
                if ($prev && $h['level'] > $prev + 1) {
                    $p[] = 'skipped level: h'.$prev.' → h'.$h['level'].' ("'.mb_strimwidth($h['text'], 0, 50, '…').'")';
                }
                $prev = $h['level'];
                if ($h['text'] === '') {
                    $p[] = 'empty h'.$h['level'];
                }
                if (mb_strlen($h['text']) > 70) {
                    $p[] = 'h'.$h['level'].' over 70 chars ('.mb_strlen($h['text']).'): "'.mb_strimwidth($h['text'], 0, 60, '…').'"';
                }
                $k = mb_strtolower($h['text']);
                if ($k !== '' && isset($seen[$k])) {
                    $p[] = 'duplicate h'.$h['level'].': "'.$h['text'].'"';
                }
                $seen[$k] = true;
                if ($e['page_type'] === 'industry' && $h['level'] <= 2 && $k !== '') {
                    $h2Use[$k][$path] = true;
                    $patterns[$this->pattern($h['text'], (string) $e['h1'])][$path] = 'h'.$h['level'].': '.$h['text'];
                }
            }
            if ($p) {
                $rows[] = ['url' => $path, 'type' => $e['page_type'], 'issues' => array_values(array_unique($p))];
            }
        }
        $repeated = [];
        foreach ($h2Use as $text => $pages) {
            if (count($pages) >= 3) {
                $repeated[] = ['heading' => $text, 'pages' => count($pages), 'urls' => array_keys($pages)];
            }
        }
        $templated = [];
        foreach ($patterns as $pattern => $pages) {
            if (count($pages) >= 3 && str_contains($pattern, '*')) {
                $templated[] = ['pattern' => $pattern, 'pages' => count($pages), 'examples' => array_slice($pages, 0, 5, true)];
            }
        }
        usort($repeated, fn ($x, $y) => $y['pages'] <=> $x['pages']);
        usort($templated, fn ($x, $y) => $y['pages'] <=> $x['pages']);

        return [
            'pages_checked' => count($entries),
            'pages_with_issues' => count($rows),
            'issues' => $rows,
            'industry_pages' => [
                'identical_h1_h2_used_on_3_or_more' => array_slice($repeated, 0, 30),
                'same_pattern_with_only_the_industry_word_changed' => array_slice($templated, 0, 30),
            ],
        ];
    }

    /** Heading with the page's own topic words replaced by * (to spot templated industry pages). */
    private function pattern(string $heading, string $h1): string
    {
        $stop = ['pos', 'software', 'system', 'systems', 'uae', 'in', 'the', 'for', 'and', 'dubai', 'abu', 'dhabi', 'sharjah', 'best', 'point', 'of', 'sale', 'management', 'multitech', 'your', 'our', 'with', 'a', 'to'];
        $topic = array_diff(preg_split('/\W+/u', mb_strtolower($h1), -1, PREG_SPLIT_NO_EMPTY), $stop);
        $words = preg_split('/\s+/u', mb_strtolower(trim($heading)));
        $words = array_map(fn ($w) => in_array(trim($w, '.,:;!?&'), $topic, true) ? '*' : $w, $words);

        return preg_replace('/(\* )+\*/', '*', implode(' ', $words));
    }
}
