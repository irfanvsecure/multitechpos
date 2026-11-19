<?php

namespace App\Console\Commands;

use App\Cms\Cms;
use App\Cms\Settings;
use App\Models\Category;
use App\Models\ClientLogo;
use App\Models\Download;
use App\Models\Faq;
use App\Models\FormOption;
use App\Models\HardwareItem;
use App\Models\Media;
use App\Models\Office;
use App\Models\Page;
use App\Models\Post;
use App\Models\PricingPlan;
use App\Models\Redirect;
use App\Models\Setting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

/**
 * Loads database/content/site.json (made by cms:extract) into the content tables.
 * Run once per server. Refuses when content exists, so live edits are never overwritten,
 * unless --fresh is given (which empties the content tables first; app tables are never touched).
 */
class CmsImport extends Command
{
    protected $signature = 'cms:import {--file=database/content/site.json} {--fresh : Empty the content tables first (destroys edits made through the MCP)} {--redirects=database/content/redirects.json}';

    protected $description = 'Load the website content into the database';

    /** Content tables only. Leads, users, sessions, cache and jobs are never emptied. */
    public const TABLES = ['activity_log', 'form_options', 'settings', 'offices', 'downloads', 'pricing_plans', 'hardware_items',
        'client_logos', 'testimonials', 'faqs', 'schema_blocks', 'redirects', 'seo_meta', 'media', 'post_tag',
        'category_post', 'posts', 'tags', 'categories', 'section_revisions', 'page_sections', 'pages'];

    public function handle(): int
    {
        if (Page::withTrashed()->exists() && ! $this->option('fresh')) {
            $this->warn('Content already imported ('.Page::withTrashed()->count().' pages). Nothing changed. Use --fresh to replace it (this destroys edits made through the MCP).');

            return self::SUCCESS;
        }

        $data = json_decode(File::get(base_path($this->option('file'))), true, 512, JSON_THROW_ON_ERROR);

        DB::transaction(function () use ($data) {
            if ($this->option('fresh')) {
                foreach (self::TABLES as $table) {
                    DB::table($table)->delete();
                }
            }

            foreach (Settings::DEFINITIONS as $key => [$value, $group, $label, $type]) {
                Setting::query()->updateOrCreate(['key' => $key], compact('value', 'group', 'label', 'type'));
            }

            $ids = [];
            foreach ($data['pages'] as $p) {
                $page = Page::query()->create([
                    'slug' => $p['slug'], 'path' => Page::pathFor($p['slug']), 'name' => $p['name'],
                    'page_type' => $p['page_type'], 'template' => $p['template'], 'status' => $p['status'],
                    'body_class' => $p['body_class'], 'page_kind' => $p['page_kind'], 'show_cro' => $p['show_cro'],
                    'sort_order' => $p['sort_order'],
                ]);
                $ids[$p['slug']] = $page->id;
                $page->seo()->create(array_filter($p['seo'], fn ($v) => $v !== null));
                foreach ($p['sections'] as $s) {
                    $page->sections()->create([
                        'key' => $s['key'], 'type' => $s['type'], 'sort_order' => $s['sort_order'],
                        'content' => $s['content'] ?? null, 'component' => $s['component'] ?? null,
                        'attrs' => $s['attrs'] ?? null, 'embeds' => $s['embeds'] ?? null, 'is_visible' => true,
                    ]);
                }
            }
            foreach ($data['pages'] as $p) {
                if ($p['parent']) {
                    Page::query()->whereKey($ids[$p['slug']])->update(['parent_id' => $ids[$p['parent']] ?? null]);
                }
            }

            $cats = [];
            foreach ($data['categories'] as $c) {
                $cats[$c['name']] = Category::query()->create($c)->id;
            }
            foreach ($data['posts'] as $p) {
                $post = Post::query()->create([
                    'slug' => $p['slug'], 'title' => $p['title'], 'excerpt' => $p['excerpt'], 'content' => $p['content'],
                    'cover_image' => $p['cover_image'], 'card_image' => $p['card_image'], 'archive_image' => $p['archive_image'],
                    'author_name' => $p['author_name'], 'status' => $p['status'], 'published_at' => $p['published_at'],
                    'options' => $p['options'],
                ]);
                $post->seo()->create(array_filter($p['seo'], fn ($v) => $v !== null));
                $post->categories()->sync(array_map(fn ($n) => $cats[$n], $p['categories']));
            }

            foreach ($data['faqs'] as $f) {
                Faq::query()->create(['page_id' => $ids[$f['page']], 'question' => $f['question'], 'answer' => $f['answer'], 'sort_order' => $f['sort_order']]);
            }
            foreach ($data['client_logos'] as $row) {
                ClientLogo::query()->create($row);
            }
            foreach ($data['pricing_plans'] as $row) {
                PricingPlan::query()->create($row);
            }
            foreach ($data['downloads'] as $row) {
                $row['page_id'] = $ids[$row['page']] ?? null;
                unset($row['page']);
                Download::query()->create($row);
            }
            foreach ($data['form_options'] as $row) {
                FormOption::query()->create($row);
            }
            foreach ($data['hardware_items'] as $row) {
                HardwareItem::query()->create($row);
            }
            foreach ($data['offices'] as $row) {
                Office::query()->create($row);
            }
            foreach ($data['media'] as $row) {
                unset($row['missing']);
                Media::query()->create($row);
            }

            $redirects = base_path($this->option('redirects'));
            if (File::exists($redirects)) {
                foreach (json_decode(File::get($redirects), true) as $r) {
                    Redirect::query()->updateOrCreate(['from_path' => $r['from_path']], $r);
                }
            }
        });

        Cms::flush();
        $this->info(sprintf('Imported %d pages, %d sections, %d posts, %d FAQs, %d images, %d redirects.',
            Page::count(), \App\Models\PageSection::count(), Post::count(), Faq::count(), Media::count(), Redirect::count()));

        return self::SUCCESS;
    }
}
