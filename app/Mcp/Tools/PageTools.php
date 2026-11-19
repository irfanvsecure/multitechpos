<?php

namespace App\Mcp\Tools;

use App\Cms\Components;
use App\Cms\Renderer;
use App\Mcp\Support\Activity;
use App\Mcp\Support\Guard;
use App\Mcp\Support\Html;
use App\Mcp\Support\Redirects;
use App\Mcp\Support\Validate;
use App\Mcp\ToolError;
use App\Mcp\ToolSet;
use App\Models\Faq;
use App\Models\Page;
use App\Models\PageSection;
use App\Models\Post;
use App\Models\SectionRevision;
use App\Models\SeoMeta;
use Illuminate\Support\Facades\DB;

class PageTools extends ToolSet
{
    public function tools(): array
    {
        $types = Page::TYPES;
        $sectionId = self::i('Section id (from get_page_sections)');

        return [
            $this->read('list_pages', 'List pages (not blog posts) with type, status, parent and SEO title.', [
                'page_type' => self::e('Filter', $types),
                'status' => self::e('Filter', ['published', 'draft']),
                'parent_id' => self::i('Only children of this page'),
                'search' => self::s('Text in the slug or name'),
            ], [], fn ($a) => $this->listPages($a)),

            $this->read('get_page', 'One page: fields, SEO, FAQs, children and a summary of its sections (use get_page_sections for full content).', self::pageRef(), [], fn ($a) => $this->getPage($this->findPage($a))),

            $this->write('create_page', 'Create a page (draft by default). Gives it a page-hero (the H1) and an FAQ schema section when hero_title is set. Do not copy another page\'s text: near-duplicate pages compete with each other in Google.', [
                'slug' => self::s('URL slug: lowercase, digits, hyphens (the page will be at /slug/)'),
                'name' => self::s('Name (usually the H1 text)'),
                'page_type' => self::e('Type', $types),
                'parent_id' => self::i('Parent page (hub), e.g. /by-industry/ for industry pages'),
                'status' => self::e('Default draft', ['draft', 'published']),
                'hero_title' => self::s('H1 shown in the page banner (page-hero component)'),
                'hero_text' => self::s('Intro under the H1 (defaults to the meta description)'),
                'hero_image' => self::s('Banner background image path'),
                'seo_title' => self::s('Meta title (50-60 characters)'),
                'seo_description' => self::s('Meta description (140-160 characters)'),
                'focus_keyword' => self::s('Focus keyword (reference only)'),
                'body_class' => self::s('CSS class on <body> (rarely needed)'),
                'show_cro' => self::b('Interactive widgets + call-back card (default true)'),
            ], ['slug', 'name', 'page_type'], fn ($a) => $this->createPage($a)),

            $this->write('update_page', 'Change page fields. Changing the slug moves the page and adds a 301 from the old URL. Publishing needs exactly one H1.', [
                'page_id' => self::i('Page id'),
                'expected_updated_at' => self::expected(),
                'name' => self::s('Name'),
                'slug' => self::s('New slug'),
                'page_type' => self::e('Type', $types),
                'parent_id' => self::i('Parent page id (0 = none)'),
                'status' => self::e('Status', ['published', 'draft']),
                'body_class' => self::s('CSS class on <body>'),
                'page_kind' => self::e('Which interactive widgets the page gets', ['home', 'landing', 'post', 'general']),
                'show_cro' => self::b('Interactive widgets + call-back card'),
                'sort_order' => self::i('Order in lists'),
            ], ['page_id', 'expected_updated_at'], fn ($a) => $this->updatePage($a)),

            $this->delete('delete_page', 'Move a page to the trash (restore_item undoes it). Give redirect_to (301) for pages with traffic or links, or gone=true for a 410. The home page cannot be deleted.', [
                'page_id' => self::i('Page id'),
                'expected_updated_at' => self::expected(),
                'redirect_to' => self::s('Send the old URL here with a 301, e.g. /pos-software-uae/'),
                'gone' => self::b('Answer the old URL with 410 Gone instead'),
            ], ['page_id', 'expected_updated_at'], fn ($a) => $this->deletePage($a)),

            $this->read('get_page_sections', 'The sections of a page in order, with their full content (html) or component attrs and updated_at.', self::pageRef() + [
                'section_id' => self::i('Only this section'),
                'include_content' => self::b('Default true'),
            ], [], fn ($a) => $this->getSections($a)),

            $this->write('add_page_section', 'Add a section: type html (content = HTML with placeholders; keep the site\'s classes, e.g. <section class="section"><div class="container">...) or type component (component + attrs). Placed at the end unless after_section_id or position is given.', [
                'page_id' => self::i('Page id'),
                'type' => self::e('Section type', ['html', 'component']),
                'content' => self::s('HTML (type html)'),
                'component' => self::s('Component name (type component), see list_components'),
                'attrs' => self::o('Component options'),
                'embeds' => self::a('Components placed with {{embed:N}} in the HTML: [{"component":"quote-form","attrs":{}}]', ['type' => 'object']),
                'key' => self::s('Short name for the section (default: from its heading)'),
                'after_section_id' => self::i('Insert after this section'),
                'position' => self::i('Or insert at this index (0 = first)'),
            ], ['page_id', 'type'], fn ($a) => $this->addSection($a)),

            $this->write('update_page_section', 'Change one section. For small edits pass find + replace (exact text, must occur once). Or pass the full content / attrs. A revision is kept.', [
                'section_id' => $sectionId,
                'expected_updated_at' => self::expected(),
                'content' => self::s('New full HTML (type html)'),
                'find' => self::s('Exact HTML/text to find (once) in the section'),
                'replace' => self::s('Replacement for find'),
                'attrs' => self::o('New component attrs (replaces them, or merges with merge_attrs)'),
                'merge_attrs' => self::b('Merge attrs into the existing ones (default false)'),
                'embeds' => self::a('New embeds list', ['type' => 'object']),
                'key' => self::s('New section key'),
            ], ['section_id', 'expected_updated_at'], fn ($a) => $this->updateSection($a)),

            $this->delete('delete_page_section', 'Remove a section (to the trash; restore_item brings it back).', [
                'section_id' => $sectionId,
                'expected_updated_at' => self::expected(),
            ], ['section_id', 'expected_updated_at'], fn ($a) => $this->deleteSection($a)),

            $this->write('reorder_page_sections', 'Set the order of a page\'s sections. Pass every section id of the page in the new order and the page\'s updated_at.', [
                'page_id' => self::i('Page id'),
                'section_ids' => self::a('All section ids in the new order', ['type' => 'integer']),
                'expected_updated_at' => self::expected(),
            ], ['page_id', 'section_ids', 'expected_updated_at'], fn ($a) => $this->reorder($a), true),

            $this->write('toggle_section_visibility', 'Show or hide a section without deleting it.', [
                'section_id' => $sectionId,
                'visible' => self::b('true = shown'),
                'expected_updated_at' => self::expected(),
            ], ['section_id', 'visible', 'expected_updated_at'], fn ($a) => $this->toggle($a), true),

            $this->read('list_section_revisions', 'Saved versions of a section (or of a blog post), newest first.', [
                'section_id' => $sectionId,
                'post_id' => self::i('Or a blog post id'),
                'limit' => self::i('Default 20'),
            ], [], fn ($a) => $this->revisions($a)),

            $this->write('restore_section_revision', 'Put a saved version back (the current version is saved as a revision first).', [
                'revision_id' => self::i('Revision id'),
                'expected_updated_at' => self::s('updated_at of the current section / post'),
            ], ['revision_id', 'expected_updated_at'], fn ($a) => $this->restoreRevision($a)),

            $this->read('search_content', 'Find text in page sections, component attrs, blog posts, FAQs and SEO fields.', [
                'query' => self::s('Text (or a regex with regex=true)'),
                'regex' => self::b('Treat query as a PCRE regex'),
                'case_sensitive' => self::b('Default false'),
                'in' => self::a('Where: sections, posts, faqs, seo (default all)'),
                'page_type' => self::e('Only pages of this type', Page::TYPES),
                'limit' => self::i('Default 50'),
            ], ['query'], fn ($a) => $this->search($a)),

            $this->write('replace_text', 'Replace text everywhere (text nodes only: never inside tags or attributes) in sections, component texts, blog posts and FAQs. dry_run defaults to true: it only lists the matches.', [
                'find' => self::s('Exact text'),
                'replace' => self::s('New text (may contain placeholders such as {{setting:stat_years}})'),
                'dry_run' => self::dryRun(),
                'case_sensitive' => self::b('Default true'),
                'page_id' => self::i('Only this page'),
                'post_id' => self::i('Only this post'),
                'scope' => self::e('Default all', ['all', 'pages', 'posts', 'faqs']),
            ], ['find', 'replace'], fn ($a) => $this->replaceText($a)),
        ];
    }

    private static function pageRef(): array
    {
        return ['page_id' => self::i('Page id'), 'path' => self::s('Or the URL path, e.g. /contact/')];
    }

    // ------------------------------------------------------------------ output

    public static function pageOut(Page $p, bool $brief = true): array
    {
        $out = [
            'id' => $p->id, 'path' => $p->path, 'slug' => $p->slug, 'name' => $p->name, 'page_type' => $p->page_type,
            'status' => $p->status, 'parent_id' => $p->parent_id, 'updated_at' => Guard::stamp($p),
        ];
        if (! $brief) {
            $out += ['body_class' => $p->body_class, 'page_kind' => $p->page_kind, 'show_cro' => $p->show_cro, 'sort_order' => $p->sort_order];
        }

        return $out;
    }

    public static function sectionOut(PageSection $s, bool $content = true): array
    {
        $out = [
            'id' => $s->id, 'page_id' => $s->page_id, 'key' => $s->key, 'type' => $s->type, 'sort_order' => $s->sort_order,
            'is_visible' => $s->is_visible, 'updated_at' => Guard::stamp($s),
        ];
        if ($s->type === 'component') {
            $out['component'] = $s->component;
            $out['attrs'] = $s->attrs ?? (object) [];
        } else {
            $out['words'] = Html::words(Html::text($s->content));
            $out['headings'] = array_map(fn ($h) => 'h'.$h['level'].': '.$h['text'], Html::headings((string) $s->content));
            if ($content) {
                $out['content'] = $s->content;
                if ($s->embeds) {
                    $out['embeds'] = $s->embeds;
                }
            } else {
                $out['preview'] = mb_strimwidth(Html::text($s->content), 0, 160, '…');
            }
        }

        return $out;
    }

    private function listPages(array $a): array
    {
        $pages = Page::query()->with('seo:id,seoable_id,seoable_type,title')
            ->when($a['page_type'] ?? null, fn ($q, $v) => $q->where('page_type', $v))
            ->when($a['status'] ?? null, fn ($q, $v) => $q->where('status', $v))
            ->when($a['parent_id'] ?? null, fn ($q, $v) => $q->where('parent_id', $v))
            ->when($a['search'] ?? null, fn ($q, $v) => $q->where(fn ($q) => $q->where('slug', 'like', "%$v%")->orWhere('name', 'like', "%$v%")))
            ->withCount('sections')->orderBy('sort_order')->get();

        return [
            'count' => $pages->count(),
            'pages' => $pages->map(fn ($p) => self::pageOut($p) + ['seo_title' => $p->seo?->title, 'sections' => $p->sections_count])->all(),
        ];
    }

    private function getPage(Page $page): array
    {
        $page->load(['sections', 'seo', 'faqs', 'children:id,parent_id,slug,name,page_type,status']);

        return self::pageOut($page, false) + [
            'parent' => $page->parent ? ['id' => $page->parent->id, 'path' => $page->parent->path, 'name' => $page->parent->name] : null,
            'children' => $page->children->map(fn ($c) => ['id' => $c->id, 'path' => $c->path, 'name' => $c->name, 'status' => $c->status])->all(),
            'h1_count' => Renderer::h1Count($page->sections),
            'seo' => SeoTools::seoOut($page),
            'faqs' => $page->faqs->map(fn ($f) => ['id' => $f->id, 'question' => $f->question, 'answer' => $f->answer, 'updated_at' => Guard::stamp($f)])->all(),
            'sections' => $page->sections->map(fn ($s) => self::sectionOut($s, false))->all(),
        ];
    }

    // ------------------------------------------------------------------ pages

    private function createPage(array $a): array
    {
        $slug = Validate::slug((string) $a['slug']);
        if (! empty($a['parent_id']) && ! Page::query()->find($a['parent_id'])) {
            throw new ToolError('No such parent page.');
        }
        $status = $a['status'] ?? 'draft';
        if ($status === 'published' && empty($a['hero_title'])) {
            throw new ToolError('A published page needs an H1: give hero_title, or create it as a draft, add sections, then publish.');
        }

        $page = DB::transaction(function () use ($a, $slug, $status) {
            $page = Page::query()->create([
                'slug' => $slug, 'path' => Page::pathFor($slug), 'name' => strip_tags($a['name']), 'page_type' => $a['page_type'],
                'parent_id' => $a['parent_id'] ?? null, 'status' => $status, 'body_class' => $a['body_class'] ?? null,
                'show_cro' => $a['show_cro'] ?? true, 'sort_order' => (int) Page::query()->max('sort_order') + 1,
            ]);
            $page->seo()->create(array_filter([
                'title' => $a['seo_title'] ?? null, 'description' => $a['seo_description'] ?? null, 'focus_keyword' => $a['focus_keyword'] ?? null,
            ]));
            if (! empty($a['hero_title'])) {
                $page->sections()->create(['key' => 'schema-faq', 'type' => 'component', 'component' => 'schema-faq', 'sort_order' => 0]);
                [$attrs] = Validate::component('page-hero', array_filter([
                    'title' => $a['hero_title'], 'text' => $a['hero_text'] ?? null, 'image' => $a['hero_image'] ?? null,
                    'parent' => ($parent = $page->parent) ? ['name' => $parent->name, 'url' => $parent->path] : null,
                ]));
                $page->sections()->create(['key' => 'page-hero', 'type' => 'component', 'component' => 'page-hero', 'attrs' => $attrs, 'sort_order' => 1]);
            }

            return $page;
        });
        Activity::log('create', $page, null, self::pageOut($page), 'page '.$page->path, [$page->path]);

        return ['created' => $this->getPage($page->fresh()), 'next' => 'Add content with add_page_section, then update_page status=published.'];
    }

    private function updatePage(array $a): array
    {
        $page = $this->guard(Page::query()->find($a['page_id']), $a, 'page');
        $before = self::pageOut($page, false);
        $fields = array_intersect_key($a, array_flip(['name', 'page_type', 'status', 'body_class', 'page_kind', 'show_cro', 'sort_order']));
        if (array_key_exists('parent_id', $a)) {
            $fields['parent_id'] = $a['parent_id'] ?: null;
            if ($fields['parent_id'] && ((int) $fields['parent_id'] === $page->id || ! Page::query()->find($fields['parent_id']))) {
                throw new ToolError('Invalid parent page.');
            }
        }
        $notes = [];
        $oldPath = $page->path;
        if (isset($a['slug']) && $a['slug'] !== $page->slug) {
            if ($page->slug === '') {
                throw new ToolError('The home page URL cannot change.');
            }
            $fields['slug'] = Validate::slug($a['slug'], $page);
            $fields['path'] = Page::pathFor($fields['slug']);
        }
        if (($fields['status'] ?? $page->status) === 'published' && Renderer::h1Count($page->sections) !== 1) {
            throw new ToolError('This page has '.Renderer::h1Count($page->sections).' H1 headings; it needs exactly one before it can be published.');
        }

        $page->update($fields);
        if (isset($fields['path'])) {
            $notes[] = Redirects::moved($oldPath, $page->path, 'slug changed (page #'.$page->id.')');
            $count = LinkTools::countLinksTo($oldPath);
            if ($count) {
                $notes[] = "$count internal links still point to $oldPath (they work through the 301). Update them with replace_link_sitewide.";
            }
        }
        Activity::log('update', $page, $before, self::pageOut($page, false), 'page '.$page->path, [$oldPath, $page->path]);

        return ['page' => self::pageOut($page->fresh(), false), 'notes' => $notes];
    }

    private function deletePage(array $a): array
    {
        $page = $this->guard(Page::query()->find($a['page_id']), $a, 'page');
        if ($page->slug === '') {
            throw new ToolError('The home page cannot be deleted.');
        }
        if (! empty($a['redirect_to']) && ! empty($a['gone'])) {
            throw new ToolError('Use redirect_to or gone, not both.');
        }
        $before = self::pageOut($page, false);
        $page->delete();
        $note = 'The URL now falls through to the site catch-all (301 to the home page). Prefer redirect_to or gone.';
        if (! empty($a['redirect_to'])) {
            $note = Redirects::moved($page->path, $a['redirect_to'], 'page #'.$page->id.' deleted');
        } elseif (! empty($a['gone'])) {
            Redirects::save($page->path, null, 410, 'page #'.$page->id.' deleted');
            $note = $page->path.' now answers 410 Gone.';
        }
        Activity::log('delete', $page, $before, null, 'page '.$page->path, [$page->path]);

        return ['deleted' => $before, 'note' => $note, 'restore' => 'restore_item type=page id='.$page->id];
    }

    // ------------------------------------------------------------------ sections

    private function getSections(array $a): array
    {
        if (! empty($a['section_id'])) {
            $s = PageSection::query()->find($a['section_id']) ?? throw new ToolError('No such section.');

            return self::sectionOut($s, $a['include_content'] ?? true);
        }
        $page = $this->findPage($a);

        return [
            'page' => self::pageOut($page),
            'h1_count' => Renderer::h1Count($page->sections),
            'sections' => $page->sections->map(fn ($s) => self::sectionOut($s, $a['include_content'] ?? true))->all(),
        ];
    }

    /** @return array{0: array, 1: list<string>} [fields, removed] */
    private function sectionFields(string $type, array $a, ?PageSection $existing = null): array
    {
        $removed = [];
        if ($type === 'html') {
            $embeds = array_key_exists('embeds', $a) ? Validate::embeds($a['embeds']) : ($existing->embeds ?? []);
            [$html, $removed] = Validate::html($a['content'] ?? '', count($embeds));
            if (trim($html) === '') {
                throw new ToolError('content is empty.');
            }

            return [['content' => $html, 'embeds' => $embeds ?: null], $removed];
        }
        $name = $a['component'] ?? $existing?->component;
        if (! $name) {
            throw new ToolError('component is required for a component section.');
        }
        $attrs = $a['attrs'] ?? null;
        if ($existing && ($a['merge_attrs'] ?? false)) {
            $attrs = array_replace_recursive($existing->attrs ?? [], $attrs ?? []);
        }
        if ($attrs === null && $existing) {
            $attrs = $existing->attrs;
        }
        [$attrs, $removed] = Validate::component($name, $attrs);

        return [['component' => $name, 'attrs' => $attrs ?: null], $removed];
    }

    private function addSection(array $a): array
    {
        $page = $this->findPage($a);
        [$fields, $removed] = $this->sectionFields($a['type'], $a);
        $sections = $page->sections()->get();

        $index = $sections->count();
        if (! empty($a['after_section_id'])) {
            $pos = $sections->search(fn ($s) => $s->id === (int) $a['after_section_id']);
            if ($pos === false) {
                throw new ToolError('after_section_id is not a section of this page.');
            }
            $index = $pos + 1;
        } elseif (isset($a['position'])) {
            $index = max(0, min($sections->count(), (int) $a['position']));
        }

        $new = new PageSection($fields + ['type' => $a['type'], 'page_id' => $page->id, 'is_visible' => true]);
        $after = $sections->all();
        array_splice($after, $index, 0, [$new]);
        Validate::h1($page, $after);

        $section = DB::transaction(function () use ($page, $new, $after, $a) {
            $new->key = $this->uniqueKey($page, $a['key'] ?? $this->keyFrom($new));
            $new->sort_order = 0;
            $new->save();
            foreach (array_values($after) as $i => $s) {
                PageSection::query()->whereKey($s->id ?? $new->id)->update(['sort_order' => $i]);
            }
            $page->touch();

            return $new->fresh();
        });
        Activity::log('create', $section, null, self::sectionOut($section), 'section '.$section->key.' on '.$page->path, [$page->path]);

        return ['section' => self::sectionOut($section), 'removed_by_sanitizer' => $removed];
    }

    private function updateSection(array $a): array
    {
        $section = $this->guard(PageSection::query()->find($a['section_id']), $a, 'section');
        $page = $section->page;
        $before = self::sectionOut($section);
        $input = $a;

        if (isset($a['find'])) {
            if ($section->type !== 'html') {
                throw new ToolError('find/replace works on html sections; for components send attrs.');
            }
            $n = substr_count((string) $section->content, $a['find']);
            if ($n !== 1) {
                throw new ToolError($n === 0 ? 'find was not found in this section (it must match the stored HTML exactly; read it with get_page_sections).' : "find occurs $n times; make it longer so it matches once.");
            }
            $input['content'] = str_replace($a['find'], (string) ($a['replace'] ?? ''), $section->content);
        } elseif ($section->type === 'html' && ! array_key_exists('content', $a) && array_key_exists('embeds', $a)) {
            $input['content'] = $section->content;
        }

        $removed = [];
        $fields = [];
        if ($section->type === 'html' && array_key_exists('content', $input)) {
            [$fields, $removed] = $this->sectionFields('html', $input, $section);
        } elseif ($section->type === 'component' && (array_key_exists('attrs', $a))) {
            [$fields, $removed] = $this->sectionFields('component', $a, $section);
        }
        if (isset($a['key'])) {
            $fields['key'] = $this->uniqueKey($page, $a['key'], $section->id);
        }
        if (! $fields) {
            throw new ToolError('Nothing to change: send content, find+replace, attrs, embeds or key.');
        }

        $candidate = (clone $section)->fill($fields);
        Validate::h1($page, $page->sections()->get()->map(fn ($s) => $s->id === $section->id ? $candidate : $s));

        Activity::revision($section, 'before update');
        $section->update($fields);
        $page->touch();
        Activity::log('update', $section, $before, self::sectionOut($section), 'section '.$section->key.' on '.$page->path, [$page->path]);

        return ['section' => self::sectionOut($section->fresh()), 'removed_by_sanitizer' => $removed];
    }

    private function deleteSection(array $a): array
    {
        $section = $this->guard(PageSection::query()->find($a['section_id']), $a, 'section');
        $page = $section->page;
        Validate::h1($page, $page->sections()->get()->reject(fn ($s) => $s->id === $section->id));
        $before = self::sectionOut($section);
        Activity::revision($section, 'before delete');
        $section->delete();
        $page->touch();
        Activity::log('delete', $section, $before, null, 'section '.$section->key.' on '.$page->path, [$page->path]);

        return ['deleted' => ['id' => $section->id, 'key' => $section->key], 'restore' => 'restore_item type=section id='.$section->id];
    }

    private function reorder(array $a): array
    {
        $page = $this->guard(Page::query()->find($a['page_id']), $a, 'page');
        $ids = array_map('intval', $a['section_ids']);
        $current = $page->sections()->pluck('id')->all();
        if (count($ids) !== count(array_unique($ids)) || array_diff($current, $ids) || array_diff($ids, $current)) {
            throw new ToolError('section_ids must contain every section id of this page exactly once.', ['current_order' => $current]);
        }
        DB::transaction(function () use ($ids) {
            foreach ($ids as $i => $id) {
                PageSection::query()->whereKey($id)->update(['sort_order' => $i]);
            }
        });
        $page->touch();
        Activity::log('reorder', $page, ['order' => $current], ['order' => $ids], 'sections of '.$page->path, [$page->path]);

        return ['page' => self::pageOut($page->fresh()), 'order' => $ids];
    }

    private function toggle(array $a): array
    {
        $section = $this->guard(PageSection::query()->find($a['section_id']), $a, 'section');
        $page = $section->page;
        $candidate = (clone $section)->fill(['is_visible' => (bool) $a['visible']]);
        Validate::h1($page, $page->sections()->get()->map(fn ($s) => $s->id === $section->id ? $candidate : $s));
        $section->update(['is_visible' => (bool) $a['visible']]);
        $page->touch();
        Activity::log('update', $section, ['is_visible' => ! $a['visible']], ['is_visible' => (bool) $a['visible']], ($a['visible'] ? 'show ' : 'hide ').'section '.$section->key, [$page->path]);

        return ['section' => self::sectionOut($section->fresh(), false)];
    }

    private function revisions(array $a): array
    {
        $model = ! empty($a['section_id']) ? PageSection::withTrashed()->find($a['section_id']) : (! empty($a['post_id']) ? Post::withTrashed()->find($a['post_id']) : null);
        if (! $model) {
            throw new ToolError('Give a section_id or post_id that exists.');
        }

        return $model->revisions()->limit((int) ($a['limit'] ?? 20))->get()->map(fn ($r) => [
            'revision_id' => $r->id, 'saved_at' => $r->created_at?->toIso8601String(), 'reason' => $r->reason,
            'preview' => mb_strimwidth(Html::text($r->snapshot['content'] ?? json_encode($r->snapshot['attrs'] ?? '')), 0, 200, '…'),
        ])->all();
    }

    private function restoreRevision(array $a): array
    {
        $rev = SectionRevision::query()->find($a['revision_id']) ?? throw new ToolError('No such revision.');
        $target = $rev->revisionable_type === (new Post)->getMorphClass() ? Post::withTrashed()->find($rev->revisionable_id) : PageSection::withTrashed()->find($rev->revisionable_id);
        $target = $this->guard($target, $a, 'record');
        $keep = $target instanceof Post ? ['title', 'excerpt', 'content', 'cover_image', 'options'] : ['content', 'component', 'attrs', 'embeds', 'key', 'is_visible'];
        $fields = array_intersect_key($rev->snapshot, array_flip($keep));
        if ($target instanceof PageSection) {
            if ($target->type === 'html') {
                Validate::html($fields['content'] ?? '', count($fields['embeds'] ?? []));
            }
            $candidate = (clone $target)->fill($fields);
            Validate::h1($target->page, $target->page->sections()->get()->map(fn ($s) => $s->id === $target->id ? $candidate : $s));
        }
        $before = Activity::snapshot($target);
        Activity::revision($target, 'before restoring revision #'.$rev->id);
        $target->update($fields);
        Activity::log('restore', $target, $before, Activity::snapshot($target), 'revision #'.$rev->id, [$target instanceof Post ? $target->path : $target->page?->path]);

        return ['restored' => $target instanceof Post ? BlogTools::postOut($target->fresh()) : self::sectionOut($target->fresh())];
    }

    // ------------------------------------------------------------------ search & replace

    private function search(array $a): array
    {
        $in = $a['in'] ?? ['sections', 'posts', 'faqs', 'seo'];
        $limit = (int) ($a['limit'] ?? 50);
        $cs = $a['case_sensitive'] ?? false;
        $pattern = ($a['regex'] ?? false) ? '~'.str_replace('~', '\~', $a['query']).'~u'.($cs ? '' : 'i') : '~'.preg_quote($a['query'], '~').'~u'.($cs ? '' : 'i');
        if (@preg_match($pattern, '') === false) {
            throw new ToolError('Invalid regex.');
        }
        $hits = [];
        $snip = function (string $text) use ($pattern) {
            preg_match($pattern, $text, $m, PREG_OFFSET_CAPTURE);
            $at = $m[0][1] ?? 0;

            return mb_strcut($text, max(0, $at - 70), 200);
        };

        if (in_array('sections', $in, true)) {
            $q = PageSection::query()->with('page:id,slug,path,page_type');
            if (! empty($a['page_type'])) {
                $q->whereHas('page', fn ($p) => $p->where('page_type', $a['page_type']));
            }
            foreach ($q->cursor() as $s) {
                $hay = $s->type === 'html' ? (string) $s->content : json_encode($s->attrs, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                if (preg_match_all($pattern, $hay) && count($hits) < $limit) {
                    $hits[] = ['where' => 'section', 'page' => $s->page?->path, 'page_id' => $s->page_id, 'section_id' => $s->id, 'key' => $s->key, 'matches' => preg_match_all($pattern, $hay), 'snippet' => $snip($hay)];
                }
            }
        }
        if (in_array('posts', $in, true) && empty($a['page_type'])) {
            foreach (Post::query()->cursor() as $p) {
                $hay = $p->title."\n".$p->excerpt."\n".$p->content;
                if (preg_match_all($pattern, $hay) && count($hits) < $limit) {
                    $hits[] = ['where' => 'post', 'page' => $p->path, 'post_id' => $p->id, 'matches' => preg_match_all($pattern, $hay), 'snippet' => $snip($hay)];
                }
            }
        }
        if (in_array('faqs', $in, true)) {
            foreach (Faq::query()->with('page:id,path')->cursor() as $f) {
                $hay = $f->question."\n".$f->answer;
                if (preg_match($pattern, $hay) && count($hits) < $limit) {
                    $hits[] = ['where' => 'faq', 'page' => $f->page?->path, 'faq_id' => $f->id, 'snippet' => $snip($hay)];
                }
            }
        }
        if (in_array('seo', $in, true)) {
            foreach (SeoMeta::query()->with('seoable')->cursor() as $m) {
                $hay = implode("\n", array_filter([$m->title, $m->description, $m->og_title, $m->og_description, $m->focus_keyword]));
                if (preg_match($pattern, $hay) && count($hits) < $limit) {
                    $hits[] = ['where' => 'seo', 'page' => $m->seoable?->path, 'snippet' => $snip($hay)];
                }
            }
        }

        return ['query' => $a['query'], 'results' => count($hits), 'hits' => $hits, 'truncated' => count($hits) >= $limit];
    }

    private function replaceText(array $a): array
    {
        $dry = $a['dry_run'] ?? true;
        $cs = $a['case_sensitive'] ?? true;
        $scope = $a['scope'] ?? 'all';
        if ($a['find'] === '' || $a['find'] === $a['replace']) {
            throw new ToolError('find must be non-empty and differ from replace.');
        }
        if ($errors = \App\Cms\Placeholders::validate($a['replace'])) {
            throw new ToolError(implode(' ', $errors));
        }
        $changes = [];
        $total = 0;

        if (in_array($scope, ['all', 'pages'], true) && empty($a['post_id'])) {
            $q = PageSection::query()->with('page');
            if (! empty($a['page_id'])) {
                $q->where('page_id', $a['page_id']);
            }
            foreach ($q->get() as $s) {
                $new = $this->replaceInSection($s, $a['find'], $a['replace'], $cs, $n);
                if ($n) {
                    $total += $n;
                    $changes[] = ['section_id' => $s->id, 'page' => $s->page?->path, 'key' => $s->key, 'replacements' => $n];
                    if (! $dry) {
                        Activity::revision($s, 'before replace_text');
                        $before = self::sectionOut($s);
                        $s->update($new);
                        Activity::log('update', $s, $before, self::sectionOut($s), 'replace_text on '.$s->page?->path, [$s->page?->path]);
                    }
                }
            }
        }
        if (in_array($scope, ['all', 'posts'], true) && empty($a['page_id'])) {
            $q = Post::query()->when($a['post_id'] ?? null, fn ($q, $v) => $q->whereKey($v));
            foreach ($q->get() as $p) {
                [$content, $n1] = Html::replaceText((string) $p->content, $a['find'], $a['replace'], $cs);
                [$title, $n2] = Html::replaceText((string) $p->title, $a['find'], strip_tags($a['replace']), $cs);
                [$excerpt, $n3] = Html::replaceText((string) $p->excerpt, $a['find'], strip_tags($a['replace']), $cs);
                if ($n = $n1 + $n2 + $n3) {
                    $total += $n;
                    $changes[] = ['post_id' => $p->id, 'page' => $p->path, 'replacements' => $n];
                    if (! $dry) {
                        Activity::revision($p, 'before replace_text');
                        $before = BlogTools::postOut($p);
                        $p->update(['content' => Validate::html($content)[0], 'title' => $title, 'excerpt' => $excerpt]);
                        Activity::log('update', $p, $before, BlogTools::postOut($p), 'replace_text on '.$p->path, [$p->path]);
                    }
                }
            }
        }
        if (in_array($scope, ['all', 'faqs'], true) && empty($a['post_id'])) {
            $q = Faq::query()->with('page')->when($a['page_id'] ?? null, fn ($q, $v) => $q->where('page_id', $v));
            foreach ($q->get() as $f) {
                [$qn, $n1] = Html::replaceText($f->question, $a['find'], $a['replace'], $cs);
                [$an, $n2] = Html::replaceText($f->answer, $a['find'], $a['replace'], $cs);
                if ($n = $n1 + $n2) {
                    $total += $n;
                    $changes[] = ['faq_id' => $f->id, 'page' => $f->page?->path, 'replacements' => $n];
                    if (! $dry) {
                        $before = $f->only(['question', 'answer']);
                        $f->update(['question' => $qn, 'answer' => $an]);
                        Activity::log('update', $f, $before, $f->only(['question', 'answer']), 'replace_text in FAQ', [$f->page?->path]);
                    }
                }
            }
        }

        return [
            'dry_run' => $dry, 'replacements' => $total, 'records' => count($changes), 'changes' => $changes,
            'next' => $dry && $total ? 'Run again with dry_run=false to apply.' : null,
        ];
    }

    /** Fields to save for a section after a text replacement; $n = replacements. */
    private function replaceInSection(PageSection $s, string $find, string $replace, bool $cs, ?int &$n): array
    {
        $n = 0;
        if ($s->type === 'html') {
            [$html, $n] = Html::replaceText((string) $s->content, $find, $replace, $cs);

            return $n ? ['content' => Validate::html($html, count($s->embeds ?? []))[0]] : [];
        }
        $attrs = $s->attrs ?? [];
        array_walk_recursive($attrs, function (&$v, $k) use ($find, $replace, $cs, &$n) {
            if (is_string($v) && ! in_array($k, ['image', 'url', 'icon', 'tile', 'variant'], true)) {
                [$v, $c] = Html::replaceText($v, $find, $replace, $cs);
                $n += $c;
            }
        });

        return $n ? ['attrs' => $attrs] : [];
    }

    // ------------------------------------------------------------------ helpers

    private function keyFrom(PageSection $s): string
    {
        if ($s->type === 'component') {
            return (string) $s->component;
        }
        $h = Html::headings((string) $s->content)[0]['text'] ?? '';

        return \Illuminate\Support\Str::limit(\Illuminate\Support\Str::slug($h), 50, '') ?: 'section';
    }

    private function uniqueKey(Page $page, string $key, ?int $ignore = null): string
    {
        $key = \Illuminate\Support\Str::slug($key) ?: 'section';
        $base = $key;
        $i = 2;
        while ($page->sections()->where('key', $key)->when($ignore, fn ($q) => $q->where('id', '!=', $ignore))->exists()) {
            $key = $base.'-'.$i++;
        }

        return $key;
    }
}
