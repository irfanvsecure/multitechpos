<?php

namespace Tests\Feature\Mcp;

use App\Mcp\Registry;
use App\Models\Page;
use App\Models\Post;
use Illuminate\Support\Facades\DB;

/**
 * Happy path of every one of the MCP tools, in a realistic order, and proof that the MCP
 * only touches its own tables (plus reading leads).
 */
class EveryToolTest extends McpTestCase
{
    private array $called = [];

    private array $tables = [];

    private bool $recording = false;

    /** Record queries only while an MCP request runs (not the test's own setup). */
    protected function tool(string $tool, array $args = []): array
    {
        $this->recording = true;
        try {
            return parent::tool($tool, $args);
        } finally {
            $this->recording = false;
        }
    }

    protected function ok(string $tool, array $args = []): mixed
    {
        $this->called[$tool] = true;

        return parent::ok($tool, $args);
    }

    protected function refused(string $tool, array $args, string $contains = ''): string
    {
        $this->called[$tool] = true;

        return parent::refused($tool, $args, $contains);
    }

    private function png(int $w = 1800, int $h = 900): string
    {
        $img = imagecreatetruecolor($w, $h);
        imagefill($img, 0, 0, imagecolorallocate($img, 30, 90, 200));
        ob_start();
        imagepng($img);

        return base64_encode(ob_get_clean());
    }

    public function test_every_tool_works_and_touches_only_content_tables(): void
    {
        DB::listen(function ($q) {
            if ($this->recording && preg_match_all('/\b(?:from|into|update|join|table)\s+["`]?([a-z_]+)["`]?/i', $q->sql, $m)) {
                foreach ($m[1] as $t) {
                    $this->tables[strtolower($t)][strtok(strtolower(ltrim($q->sql)), ' ')] = true;
                }
            }
        });

        // ---- site
        $this->ok('get_site_info');
        $settings = $this->ok('get_settings');
        $this->ok('update_settings', ['values' => ['service_areas' => 'Dubai, Abu Dhabi, Sharjah and the wider UAE.'], 'expected_updated_at' => $settings['settings_updated_at']]);
        $this->ok('list_components');
        $this->ok('activity_log', ['limit' => 5]);
        $this->ok('clear_cache');
        $this->ok('audit_performance', ['page_id' => $this->pageId('')]);

        // ---- pages & sections
        $this->ok('list_pages', ['page_type' => 'industry']);
        $this->ok('get_page', ['path' => '/retail-pos-software-uae/']);
        $created = $this->ok('create_page', [
            'slug' => 'test-cafe-pos', 'name' => 'Cafe POS Test', 'page_type' => 'industry', 'parent_id' => $this->pageId('by-industry'),
            'hero_title' => 'Cafe POS Software in UAE', 'seo_title' => 'Cafe POS Software in UAE | MultiTech POS',
        ]);
        $pageId = $created['created']['id'];
        $sections = $this->ok('get_page_sections', ['page_id' => $pageId]);
        $this->assertSame(1, $sections['h1_count']);
        $added = $this->ok('add_page_section', ['page_id' => $pageId, 'type' => 'html',
            'content' => '<section class="section"><div class="container"><h2 class="title">Why cafes switch</h2><p>Order tickets go to the kitchen and the bakery POS menu stays in sync. Call {{phone}}.</p></div></section>']);
        $sid = $added['section']['id'];
        $upd = $this->ok('update_page_section', ['section_id' => $sid, 'expected_updated_at' => $added['section']['updated_at'], 'find' => 'Order tickets', 'replace' => 'Kitchen tickets']);
        $tog = $this->ok('toggle_section_visibility', ['section_id' => $sid, 'visible' => false, 'expected_updated_at' => $upd['section']['updated_at']]);
        $tog = $this->ok('toggle_section_visibility', ['section_id' => $sid, 'visible' => true, 'expected_updated_at' => $tog['section']['updated_at']]);
        $page = $this->ok('get_page', ['page_id' => $pageId]);
        $ids = array_column($page['sections'], 'id');
        $this->ok('reorder_page_sections', ['page_id' => $pageId, 'section_ids' => array_reverse($ids), 'expected_updated_at' => $page['updated_at']]);
        $page = $this->ok('get_page', ['page_id' => $pageId]);
        $this->ok('reorder_page_sections', ['page_id' => $pageId, 'section_ids' => $ids, 'expected_updated_at' => $page['updated_at']]);
        $revs = $this->ok('list_section_revisions', ['section_id' => $sid]);
        $this->assertNotEmpty($revs);
        $current = $this->ok('get_page_sections', ['section_id' => $sid]);
        $this->ok('restore_section_revision', ['revision_id' => $revs[0]['revision_id'], 'expected_updated_at' => $current['updated_at']]);
        $page = $this->ok('get_page', ['page_id' => $pageId]);
        $this->ok('update_page', ['page_id' => $pageId, 'expected_updated_at' => $page['updated_at'], 'status' => 'published']);
        $this->get('/test-cafe-pos/')->assertOk()->assertSee('Cafe POS Software in UAE')->assertSee('+971 58 897 9925');
        $hits = $this->ok('search_content', ['query' => 'Why cafes switch']);
        $this->assertSame(1, $hits['results']);
        $dry = $this->ok('replace_text', ['find' => 'Why cafes switch', 'replace' => 'Why UAE cafes switch', 'page_id' => $pageId]);
        $this->assertTrue($dry['dry_run']);
        $this->ok('replace_text', ['find' => 'Why cafes switch', 'replace' => 'Why UAE cafes switch', 'page_id' => $pageId, 'dry_run' => false]);

        // ---- headings
        $outline = $this->ok('get_page_outline', ['page_id' => $pageId]);
        $this->assertSame(1, $outline['h1_count']);
        $sec = $this->ok('get_page_sections', ['section_id' => $sid]);
        $this->ok('update_heading', ['page_id' => $pageId, 'current_text' => 'Why UAE cafes switch', 'new_text' => 'Why cafes in the UAE switch', 'expected_updated_at' => $sec['updated_at']]);
        $this->ok('audit_headings', ['page_type' => 'industry']);

        // ---- internal links
        $this->ok('get_internal_links', ['page_id' => $pageId]);
        $this->ok('find_orphan_pages');
        $page = $this->ok('get_page', ['page_id' => $pageId]);
        $link = $this->ok('add_internal_link', ['page_id' => $pageId, 'anchor_text' => 'bakery POS', 'url' => '/bakery-pos-software/', 'expected_updated_at' => $page['updated_at']]);
        $this->refused('add_internal_link', ['page_id' => $pageId, 'anchor_text' => 'menu', 'url' => '/bakery-pos-software/', 'expected_updated_at' => $link['page_updated_at']], 'already links');
        $moved = $this->ok('update_internal_link', ['page_id' => $pageId, 'old_url' => '/bakery-pos-software/', 'new_url' => '/pharmacy-pos-system-in-the-uae/', 'expected_updated_at' => $link['page_updated_at']]);
        $this->ok('remove_internal_link', ['page_id' => $pageId, 'url' => '/pharmacy-pos-system-in-the-uae/', 'expected_updated_at' => $moved['page_updated_at']]);
        $this->ok('replace_link_sitewide', ['old_url' => '/contact/', 'new_url' => '/pos-software-uae/']);
        $this->ok('anchor_text_report', ['target' => '/contact/']);
        $this->ok('check_broken_links', ['page_id' => $pageId, 'external' => false]);

        // ---- SEO
        $seo = $this->ok('get_seo', ['page_id' => $pageId]);
        $seo = $this->ok('update_seo', ['page_id' => $pageId, 'expected_updated_at' => $seo['updated_at'],
            'description' => 'Cafe POS software for UAE coffee shops: fast orders, kitchen tickets, stock and VAT-ready receipts, with setup and training from our Abu Dhabi team.', 'focus_keyword' => 'cafe pos uae']);
        $bulk = $this->ok('bulk_update_seo', ['items' => [['page_id' => $pageId, 'expected_updated_at' => $seo['seo']['updated_at'], 'og_type' => 'website']]]);
        $this->assertTrue($bulk['dry_run']);
        $this->ok('audit_meta', ['page_type' => 'industry']);
        $this->ok('audit_seo', ['limit' => 5]);
        $this->ok('audit_cannibalisation');
        $this->ok('audit_thin_content', ['max_words' => 400]);
        $this->ok('seo_report', ['page_id' => $pageId]);

        // ---- schema
        $this->ok('get_schema', ['page_id' => $pageId]);
        $this->ok('generate_schema', ['type' => 'LocalBusiness']);
        $block = $this->ok('update_schema', ['page_id' => $pageId, 'data' => ['@type' => 'Service', 'name' => 'Cafe POS', 'serviceType' => 'POS software']]);
        $this->ok('delete_schema', ['id' => $block['block']['id'], 'expected_updated_at' => $block['block']['updated_at']]);

        // ---- redirects & robots
        $this->ok('list_redirects', ['limit' => 5]);
        $r = $this->ok('save_redirect', ['from_path' => '/old-cafe-page/', 'to_url' => '/test-cafe-pos/']);
        $this->get('/old-cafe-page/')->assertStatus(301)->assertRedirect('http://localhost/test-cafe-pos/');
        $this->ok('delete_redirect', ['id' => $r['redirect']['id'], 'expected_updated_at' => $r['redirect']['updated_at']]);
        $check = $this->ok('validate_sitemap_robots');
        $this->ok('update_robots', ['content' => "User-agent: *\nAllow: /\nDisallow: /request-demo", 'expected_updated_at' => $check['robots']['updated_at']]);

        // ---- blog
        $this->ok('list_blogs', ['limit' => 5]);
        $this->ok('get_blog', ['slug' => 'what-makes-a-tax-invoice-valid']);
        $this->ok('list_categories');
        $cat = $this->ok('create_category', ['name' => 'Cafe POS']);
        $cat = $this->ok('update_category', ['id' => $cat['id'], 'expected_updated_at' => $cat['updated_at'], 'description' => 'Guides for cafes']);
        $post = $this->ok('create_blog', ['slug' => 'cafe-pos-checklist', 'title' => 'A Cafe POS Checklist', 'content' => '<p>Start with the menu.</p><h2>Menu</h2><p>Keep it short.</p>', 'categories' => ['cafe-pos'], 'tags' => ['Cafes']]);
        $p = $post['post'];
        $p = $this->ok('update_blog', ['post_id' => $p['id'], 'expected_updated_at' => $p['updated_at'], 'find' => 'Keep it short.', 'replace' => 'Keep it short and clear.'])['post'];
        $p = $this->ok('publish_blog', ['post_id' => $p['id'], 'expected_updated_at' => $p['updated_at']])['post'];
        $this->get('/cafe-pos-checklist/')->assertOk()->assertSee('A Cafe POS Checklist');
        $p = $this->ok('unpublish_blog', ['post_id' => $p['id'], 'expected_updated_at' => $p['updated_at']])['post'];
        $p = $this->ok('schedule_blog', ['post_id' => $p['id'], 'expected_updated_at' => $p['updated_at'], 'published_at' => now()->addDays(3)->format('Y-m-d H:i')])['post'];
        $this->assertSame('scheduled', $p['status']);
        $this->ok('list_stale_posts', ['months' => 1]);
        $this->ok('list_tags');
        $t1 = $this->ok('create_tag', ['name' => 'Coffee shops']);
        $t1 = $this->ok('update_tag', ['id' => $t1['id'], 'expected_updated_at' => $t1['updated_at'], 'name' => 'Coffee Shops']);
        $t2 = $this->ok('create_tag', ['name' => 'Coffee']);
        $p = $this->ok('get_blog', ['post_id' => $p['id']]);
        $this->ok('set_blog_taxonomies', ['post_id' => $p['id'], 'expected_updated_at' => $p['updated_at'], 'tags' => ['Coffee', 'Cafes']]);
        $this->ok('merge_tags', ['source_ids' => [$t2['id']], 'target_id' => $t1['id']]);
        $t1 = collect($this->ok('list_tags'))->firstWhere('id', $t1['id']);
        $this->ok('delete_tag', ['id' => $t1['id'], 'expected_updated_at' => $t1['updated_at']]);
        $p = $this->ok('get_blog', ['post_id' => $p['id']]);
        $this->ok('delete_blog', ['post_id' => $p['id'], 'expected_updated_at' => $p['updated_at']]);
        $cat = collect($this->ok('list_categories'))->firstWhere('slug', 'cafe-pos');
        $this->ok('delete_category', ['id' => $cat['id'], 'expected_updated_at' => $cat['updated_at']]);

        // ---- images
        $img = $this->ok('upload_image', ['base64' => $this->png(), 'alt' => 'Cafe counter with a POS terminal', 'filename' => 'cafe counter test']);
        $this->assertSame(1600, $img['image']['width']);
        $this->assertArrayHasKey('480', $img['image']['variants']);
        $this->assertFileExists(public_path(ltrim($img['image']['original'], '/')));
        $this->ok('list_images', ['search' => 'cafe-counter']);
        $img = $this->ok('update_image', ['id' => $img['image']['id'], 'expected_updated_at' => $img['image']['updated_at'], 'alt' => 'POS terminal on a cafe counter', 'filename' => 'cafe-pos-counter-test'])['image'];
        $this->assertStringEndsWith('/cafe-pos-counter-test.webp', $img['path']);
        $this->ok('audit_images', ['page_id' => $pageId]);
        $this->ok('bulk_update_alt', ['items' => [['id' => $img['id'], 'alt' => 'A cafe POS terminal']]]);
        $this->ok('replace_image', ['old_path' => $img['path'], 'new_path' => '/images/2026/06/retail-pos-multitech.webp']);
        $this->ok('delete_image', ['id' => $img['id'], 'expected_updated_at' => $img['updated_at']]);

        // ---- upload link: the owner uploads in a browser, then the MCP reads the result
        $link = $this->ok('create_image_upload_link', ['purpose' => 'Dubai section image', 'alt' => 'Cashier using a POS in a Dubai shop', 'max_files' => 1]);
        $this->assertSame('waiting', $this->ok('get_image_upload_link', ['link_id' => $link['link_id']])['status']);
        $uploadPath = parse_url($link['upload_url'], PHP_URL_PATH);
        $this->get($uploadPath)->assertOk()->assertSee('Dubai section image')->assertHeader('X-Robots-Tag', 'noindex, nofollow');
        $file = \Illuminate\Http\UploadedFile::fake()->createWithContent('shop.png', base64_decode($this->png(1000, 600)));
        $this->post($uploadPath, ['images' => [$file], 'alt' => 'Cashier using a POS in a Dubai shop'])->assertOk()->assertSee('Done');
        $done = $this->ok('get_image_upload_link', ['link_id' => $link['link_id']]);
        $this->assertSame('uploaded', $done['status']);
        $this->assertSame('Cashier using a POS in a Dubai shop', $done['images'][0]['alt']);
        $this->assertFileExists(public_path(ltrim($done['images'][0]['path'], '/')));
        $this->post($uploadPath, ['images' => [$file]])->assertOk()->assertSee('Done');   // used up: nothing more is added
        $this->assertCount(1, $this->ok('get_image_upload_link', ['link_id' => $link['link_id']])['images']);
        $this->get('/mcp/upload/'.str_repeat('a', 43))->assertNotFound();

        // ---- upload-and-set: one upload puts the image on the page with alt, description and geotag
        $old = collect(\App\Mcp\Support\Images::usage())->filter(fn ($w) => str_starts_with($w[0], 'page '))->keys()->first(fn ($p) => str_ends_with($p, '.webp'));
        $this->refused('create_image_upload_link', ['replace_path' => '/images/not-on-any-page.webp'], 'not used on any page');
        $link = $this->ok('create_image_upload_link', ['alt' => 'Cashier using MultiTech POS at a Sharjah supermarket', 'title' => 'POS software in Sharjah: barcode billing and VAT invoices',
            'geo' => ['lat' => 25.3463, 'lng' => 55.4209, 'place' => 'Sharjah, UAE'], 'replace_path' => $old]);
        $file = \Illuminate\Http\UploadedFile::fake()->createWithContent('sharjah.png', base64_decode($this->png(900, 600)));
        $this->post(parse_url($link['upload_url'], PHP_URL_PATH), ['images' => [$file], 'alt' => 'Cashier using MultiTech POS at a Sharjah supermarket'])->assertOk()->assertSee('now on the website');
        $done = $this->ok('get_image_upload_link', ['link_id' => $link['link_id']]);
        $this->assertNotEmpty($done['replaced_in']);
        $this->assertNull($done['replace_error']);
        $new = $done['images'][0];
        $this->assertSame(['lat' => 25.3463, 'lng' => 55.4209, 'place' => 'Sharjah, UAE'], $new['geo']);
        $usage = \App\Mcp\Support\Images::usage();
        $this->assertArrayNotHasKey($old, $usage);
        $this->assertArrayHasKey($new['path'], $usage);
        $webp = file_get_contents(public_path(ltrim($new['path'], '/')));
        $this->assertSame('VP8X', substr($webp, 12, 4));
        $this->assertStringContainsString('EXIF', $webp);
        $this->assertStringContainsString('POS software in Sharjah', $webp);
        $this->assertNotFalse(imagecreatefromstring($webp));
        $this->assertNotFalse(getimagesizefromstring($webp));

        // ---- business content: list, create, update, delete each
        $home = $this->pageId('');
        $logo = '/images/elementor/thumbs/bazaar-rh05mno1leaub84suaggl2evvu5jrlol0v4bw7fsyk.jpg';
        $cases = [
            'faq' => ['page_id' => $home, 'question' => 'Is there a free demo?', 'answer' => 'Yes, call {{phone}}.'],
            'testimonial' => ['quote' => 'Test quote given by the owner.', 'name' => 'Test Customer', 'provided_by_owner' => true, 'is_active' => false],
            'client_logo' => ['name' => 'Test Client', 'image' => $logo, 'is_active' => false],
            'hardware_item' => ['name' => 'Test Scanner', 'slug' => 'test-scanner', 'category' => 'Barcode scanners'],
            'pricing_plan' => ['slug' => 'test-plan', 'name' => 'Test Plan', 'is_active' => false, 'inclusions' => ['One thing']],
            'download' => ['slug' => 'test-pro', 'name' => 'TestPro', 'download_url' => 'https://drive.google.com/file/d/abc/view', 'version' => '1.0'],
            'office' => ['name' => 'Test Office', 'address' => 'Somewhere', 'city' => 'Dubai', 'country' => 'United Arab Emirates'],
            'form_option' => ['form' => 'quote_service', 'value' => 'Hardware Repair', 'is_active' => false],
        ];
        $plural = ['faq' => 'faqs', 'testimonial' => 'testimonials', 'client_logo' => 'client_logos', 'hardware_item' => 'hardware_items',
            'pricing_plan' => 'pricing_plans', 'download' => 'downloads', 'office' => 'offices', 'form_option' => 'form_options'];
        foreach ($cases as $name => $data) {
            $this->ok('list_'.$plural[$name]);
            $row = $this->ok('create_'.$name, $data)[$name];
            $row = $this->ok('update_'.$name, ['id' => $row['id'], 'expected_updated_at' => $row['updated_at']] + ($name === 'office' ? ['phone' => '+971500000000'] : ['sort_order' => 99]))[$name];
            $this->ok('delete_'.$name, ['id' => $row['id'], 'expected_updated_at' => $row['updated_at']]);
        }

        // ---- leads (read-only)
        \App\Models\Lead::query()->create(['name' => 'Test Lead', 'phone' => '0500000000', 'service' => 'Setup builder: Pharmacy']);
        $leads = $this->ok('list_enquiries', ['form' => 'setup_builder']);
        $this->assertSame(1, $leads['total']);

        // ---- trash
        $page = $this->ok('get_page', ['page_id' => $pageId]);
        $this->ok('delete_page', ['page_id' => $pageId, 'expected_updated_at' => $page['updated_at'], 'gone' => true]);
        $this->get('/test-cafe-pos/')->assertStatus(410);
        $trash = $this->ok('list_trash', ['type' => 'page']);
        $this->assertSame($pageId, $trash['trash'][0]['id']);
        $this->ok('restore_item', ['type' => 'page', 'id' => $pageId]);
        $this->get('/test-cafe-pos/')->assertOk();
        $sec = $this->ok('get_page_sections', ['section_id' => $sid]);
        $this->ok('delete_page_section', ['section_id' => $sid, 'expected_updated_at' => $sec['updated_at']]);
        $this->ok('purge_item', ['type' => 'section', 'id' => $sid, 'confirm' => true]);

        // ---- every tool was called
        $all = array_map(fn ($t) => $t->name, Registry::tools());
        $missing = array_diff($all, array_keys($this->called));
        $this->assertSame([], array_values($missing), 'Tools without a test: '.implode(', ', $missing));
        $this->assertCount(114, $all);

        // ---- only content tables (leads read-only)
        $allowed = array_merge(\App\Console\Commands\CmsImport::TABLES, ['upload_links', 'leads', 'sqlite_master', 'pragma_table_info', 'pragma_table_xinfo', 'main', 'migrations']);
        $touched = array_keys($this->tables);
        $this->assertSame([], array_values(array_diff($touched, $allowed)), 'Unexpected tables: '.implode(', ', array_diff($touched, $allowed)));
        $this->assertSame([], array_values(array_diff(array_keys($this->tables['leads'] ?? []), ['select'])), 'leads must only be read');
        foreach (['users', 'sessions', 'jobs', 'cache', 'password_reset_tokens'] as $appTable) {
            $this->assertArrayNotHasKey($appTable, $this->tables);
        }
    }

    protected function tearDown(): void
    {
        // uploaded test files
        foreach (['cafe*', 'cashier-using-a-pos-in-a-dubai-shop*', 'cashier-using-multitech-pos-at-a-sharjah*'] as $pattern) {
            foreach (glob(public_path('images/uploads/'.date('Y/m').'/'.$pattern)) ?: [] as $f) {
                @unlink($f);
            }
        }
        parent::tearDown();
    }
}
