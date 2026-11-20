<?php

namespace Tests\Feature\Mcp;

use App\Cms\Sanitizer;
use App\Mcp\Registry;
use App\Mcp\Tools\SchemaTools;
use App\Models\Page;
use App\Models\PageSection;

class McpRulesTest extends McpTestCase
{
    private function section(string $slug, string $type = 'html'): PageSection
    {
        return PageSection::query()->where('page_id', $this->pageId($slug))->where('type', $type)->orderBy('sort_order')->first();
    }

    // ---------------------------------------------------------------- auth & transport

    public function test_auth_is_required_and_constant_time_checked(): void
    {
        $body = ['jsonrpc' => '2.0', 'id' => 1, 'method' => 'tools/list'];
        $this->postJson('/mcp', $body)->assertStatus(401);
        $this->postJson('/mcp/not-the-secret-not-the-secret-not-the-secret', $body)->assertStatus(401);
        $this->postJson('/mcp', $body, ['Authorization' => 'Bearer wrong'])->assertStatus(401);
        $this->postJson('/mcp', $body, ['Authorization' => 'Bearer '.$this->secret])->assertOk()->assertJsonPath('result.tools.0.name', 'get_site_info');
        $this->postJson('/mcp/'.$this->secret, $body)->assertOk()->assertHeader('Cache-Control', 'no-store, private');
        $this->get('/mcp/'.$this->secret)->assertStatus(405);
    }

    public function test_endpoint_is_off_without_a_secret(): void
    {
        config(['cms.mcp.secret' => '']);
        $this->postJson('/mcp', ['jsonrpc' => '2.0', 'id' => 1, 'method' => 'ping'])->assertStatus(503);
    }

    public function test_https_is_required_in_production(): void
    {
        $this->app['env'] = 'production';
        $this->postJson('/mcp/'.$this->secret, ['jsonrpc' => '2.0', 'id' => 1, 'method' => 'ping'])->assertStatus(403);
        $this->postJson('/mcp/'.$this->secret, ['jsonrpc' => '2.0', 'id' => 1, 'method' => 'ping'], ['X-Forwarded-Proto' => 'https'])->assertOk();
    }

    public function test_rate_limit(): void
    {
        config(['cms.mcp.rate_limit' => 10]);
        for ($i = 0; $i < 10; $i++) {
            $this->rpc('ping')->assertOk();
        }
        $this->rpc('ping')->assertStatus(429);
    }

    public function test_protocol_initialize_list_and_errors(): void
    {
        $init = $this->rpc('initialize', ['protocolVersion' => '2025-06-18', 'capabilities' => (object) [], 'clientInfo' => ['name' => 'test', 'version' => '1']]);
        $init->assertOk()->assertJsonPath('result.protocolVersion', '2025-06-18')->assertJsonPath('result.capabilities.tools.listChanged', false);
        $instructions = $init->json('result.instructions');
        foreach (['expected_updated_at', 'SITE_NOINDEX', '{{phone}}', 'read-only', 'never upload', 'AggregateRating', 'page_type', 'Never invent prices'] as $needle) {
            $this->assertStringContainsStringIgnoringCase($needle, $instructions);
        }

        $tools = $this->rpc('tools/list')->json('result.tools');
        $this->assertCount(114, $tools);
        $byName = collect($tools)->keyBy('name');
        foreach ($tools as $t) {
            $this->assertArrayHasKey('readOnlyHint', $t['annotations']);
            $this->assertArrayHasKey('destructiveHint', $t['annotations']);
            $this->assertArrayHasKey('idempotentHint', $t['annotations']);
        }
        $this->assertTrue($byName['get_page']['annotations']['readOnlyHint']);
        $this->assertFalse($byName['update_page_section']['annotations']['readOnlyHint']);
        $this->assertTrue($byName['delete_page']['annotations']['destructiveHint']);
        $this->assertTrue($byName['purge_item']['annotations']['destructiveHint']);
        $this->assertTrue($byName['list_enquiries']['annotations']['readOnlyHint']);
        $this->assertFalse(collect($tools)->contains(fn ($t) => preg_match('/enquir|lead/', $t['name']) && ! $t['annotations']['readOnlyHint']), 'enquiries must be read-only');

        $this->rpc('no/such/method')->assertJsonPath('error.code', -32601);
        $this->rpc('tools/call', ['name' => 'nope'])->assertJsonPath('error.code', -32602);
        $this->postJson('/mcp/'.$this->secret, ['jsonrpc' => '2.0', 'method' => 'notifications/initialized'])->assertStatus(202);
    }

    public function test_no_tool_for_raw_sql_files_env_users_or_app_data(): void
    {
        $names = implode(' ', array_map(fn ($t) => $t->name, Registry::tools()));
        foreach (['sql', 'query_db', 'shell', 'exec', 'file_read', 'file_write', 'env', 'user', 'licence', 'license', 'invoice', 'customer', 'password'] as $bad) {
            $this->assertDoesNotMatchRegularExpression('/(^|_| )'.$bad.'(s)?($|_| )/', $names, "No tool may touch: $bad");
        }
        $this->assertStringNotContainsString('update_enquir', $names);
        $this->assertStringNotContainsString('delete_enquir', $names);
    }

    // ---------------------------------------------------------------- validation & read-before-write

    public function test_arguments_are_validated(): void
    {
        $this->refused('get_page', ['page_id' => 'abc'], 'must be integer');
        $this->refused('get_page', ['nonsense' => 1], 'Unknown argument');
        $this->refused('create_page', ['slug' => 'x'], "'name' is required");
        $this->refused('create_page', ['slug' => 'Bad Slug', 'name' => 'X', 'page_type' => 'industry'], 'Invalid slug');
        $this->refused('create_page', ['slug' => 'retail-pos-software-uae', 'name' => 'X', 'page_type' => 'industry'], 'already used');
        $this->refused('create_page', ['slug' => 'mcp', 'name' => 'X', 'page_type' => 'industry'], 'reserved');
        $this->refused('create_page', ['slug' => 'x-y', 'name' => 'X', 'page_type' => 'nope'], 'must be one of');
        $this->refused('create_faq', ['page_id' => 999999, 'question' => 'Q?', 'answer' => 'A'], 'Invalid arguments');
    }

    public function test_expected_updated_at_conflict_is_refused(): void
    {
        $s = $this->section('retail-pos-software-uae');
        $read = $this->ok('get_page_sections', ['section_id' => $s->id]);
        $this->refused('update_page_section', ['section_id' => $s->id, 'content' => $s->content], "expected_updated_at' is required");
        $this->refused('update_page_section', ['section_id' => $s->id, 'expected_updated_at' => '2020-01-01T00:00:00+00:00', 'content' => $s->content], 'changed since you read it');
        // someone else edits it
        $this->travel(5)->seconds();
        $s->update(['content' => $s->content.' ']);
        $this->refused('update_page_section', ['section_id' => $s->id, 'expected_updated_at' => $read['updated_at'], 'content' => $s->content], 'changed since you read it');
    }

    public function test_delete_and_restore(): void
    {
        $id = $this->pageId('bakery-pos-software');
        $page = $this->ok('get_page', ['page_id' => $id]);
        $this->refused('delete_page', ['page_id' => $this->pageId(''), 'expected_updated_at' => $this->ok('get_page', ['path' => '/'])['updated_at']], 'home page cannot');
        $this->ok('delete_page', ['page_id' => $id, 'expected_updated_at' => $page['updated_at'], 'redirect_to' => '/pos-software-uae/']);
        $this->assertSoftDeleted('pages', ['id' => $id]);
        $this->get('/bakery-pos-software/')->assertRedirect('http://localhost/pos-software-uae/');
        $this->ok('restore_item', ['type' => 'page', 'id' => $id]);
        $this->get('/bakery-pos-software/')->assertOk();
        $this->assertDatabaseMissing('redirects', ['from_path' => '/bakery-pos-software/', 'deleted_at' => null]);
        $this->refused('purge_item', ['type' => 'page', 'id' => $id, 'confirm' => true], 'Not in the trash');
    }

    public function test_purge_needs_confirm(): void
    {
        $s = $this->section('retail-pos-software-uae');
        $this->ok('delete_page_section', ['section_id' => $s->id, 'expected_updated_at' => $this->ok('get_page_sections', ['section_id' => $s->id])['updated_at']]);
        $this->refused('purge_item', ['type' => 'section', 'id' => $s->id, 'confirm' => false], 'confirm=true');
        $this->ok('purge_item', ['type' => 'section', 'id' => $s->id, 'confirm' => true]);
        $this->assertDatabaseMissing('page_sections', ['id' => $s->id]);
    }

    // ---------------------------------------------------------------- sanitizer

    public function test_sanitizer_removes_unsafe_html_and_reports_it(): void
    {
        $s = $this->section('retail-pos-software-uae');
        $res = $this->ok('add_page_section', ['page_id' => $s->page_id, 'type' => 'html', 'content' => '<section class="section"><div class="container" x-data="{ open: false }" @click="open = !open" :class="{ on: open }" data-x="1" aria-label="t">'
            .'<h2 onclick="steal()">Hello</h2><a href="javascript:alert(1)">bad</a><a href="/contact/">good</a><script>alert(1)</script>'
            .'<form action="/x"><input name="a"></form><iframe src="https://evil.example/"></iframe><svg viewBox="0 0 1 1"><path d="M0 0"/></svg></div></section>']);
        $html = $res['section']['content'];
        $this->assertStringNotContainsString('<script', $html);
        $this->assertStringNotContainsString('onclick', $html);
        $this->assertStringNotContainsString('javascript:', $html);
        $this->assertStringNotContainsString('<form', $html);
        $this->assertStringNotContainsString('evil.example', $html);
        foreach (['x-data="{ open: false }"', '@click="open = !open"', ':class="{ on: open }"', 'data-x="1"', 'aria-label="t"', '<svg viewBox="0 0 1 1"><path d="M0 0"/></svg>', 'href="/contact/"'] as $kept) {
            $this->assertStringContainsString($kept, $html);
        }
        $this->assertNotEmpty($res['removed_by_sanitizer']);
    }

    /** Every existing section and post passes through the sanitizer byte-identical. */
    public function test_sanitizer_round_trip_of_all_existing_content(): void
    {
        $data = json_decode(file_get_contents(base_path('database/content/site.json')), true);
        $checked = 0;
        foreach ($data['pages'] as $p) {
            foreach ($p['sections'] as $s) {
                if ($s['type'] === 'html') {
                    [$clean, $removed] = Sanitizer::clean($s['content']);
                    $this->assertSame($s['content'], $clean, "Section {$s['key']} of /{$p['slug']}/ changed: ".implode(', ', $removed));
                    $checked++;
                }
                $attrs = $s['attrs'] ?? [];
                array_walk_recursive($attrs, function ($v) use ($p) {
                    if (is_string($v)) {
                        $this->assertSame($v, Sanitizer::clean($v)[0], "attr of /{$p['slug']}/ changed");
                    }
                });
            }
        }
        foreach ($data['posts'] as $p) {
            $this->assertSame($p['content'], Sanitizer::clean($p['content'])[0], "post {$p['slug']} changed");
            $checked++;
        }
        $this->assertGreaterThanOrEqual(640, $checked); // 608 html sections + 34 posts
    }

    // ---------------------------------------------------------------- rules

    public function test_one_h1_is_enforced(): void
    {
        $page = Page::query()->where('slug', 'retail-pos-software-uae')->first();
        $this->refused('add_page_section', ['page_id' => $page->id, 'type' => 'html', 'content' => '<section class="section"><h1>Second H1</h1></section>'], 'exactly one H1');
        $hero = PageSection::query()->where('page_id', $page->id)->where('component', 'page-hero')->first();
        $this->refused('delete_page_section', ['section_id' => $hero->id, 'expected_updated_at' => $this->ok('get_page_sections', ['section_id' => $hero->id])['updated_at']], 'without an H1');
        $this->refused('toggle_section_visibility', ['section_id' => $hero->id, 'visible' => false, 'expected_updated_at' => $this->ok('get_page_sections', ['section_id' => $hero->id])['updated_at']], 'without an H1');
    }

    public function test_unknown_placeholders_are_rejected(): void
    {
        $id = $this->pageId('retail-pos-software-uae');
        $this->refused('add_page_section', ['page_id' => $id, 'type' => 'html', 'content' => '<section><p>{{ $secret }}</p></section>'], 'Unknown placeholder');
        $this->refused('add_page_section', ['page_id' => $id, 'type' => 'html', 'content' => '<section><p>{{setting:nope}}</p></section>'], 'Unknown setting');
        $this->refused('add_page_section', ['page_id' => $id, 'type' => 'html', 'content' => '<section><p>{{embed:1}}</p></section>'], 'no matching embedded component');
        $this->ok('add_page_section', ['page_id' => $id, 'type' => 'html', 'content' => '<section><p>Call {{phone}}, {{setting:stat_years}} years, {{plan:single-outlet}}, {{download:salonpro.version}}</p></section>']);
        $this->get('/retail-pos-software-uae/')->assertSee('Call +971 58 897 9925, 15+ years, Single Outlet, 2.0', false);
    }

    public function test_slug_change_adds_a_301(): void
    {
        $id = $this->pageId('bakery-pos-software');
        $page = $this->ok('get_page', ['page_id' => $id]);
        $this->ok('update_page', ['page_id' => $id, 'expected_updated_at' => $page['updated_at'], 'slug' => 'bakery-pos-system-uae']);
        $this->get('/bakery-pos-software/')->assertStatus(301)->assertRedirect('http://localhost/bakery-pos-system-uae/');
        $this->get('/bakery-pos-system-uae/')->assertOk();
    }

    public function test_site_noindex_overrides_every_page(): void
    {
        config(['cms.noindex' => true]);
        $this->get('/retail-pos-software-uae/')->assertSee('<meta name="robots" content="noindex, nofollow">', false);
        $this->get('/robots.txt')->assertSee('Disallow: /')->assertDontSee('Sitemap:');
        $info = $this->ok('get_site_info');
        $this->assertStringContainsString('ON', $info['site_noindex']);
        $this->assertStringContainsString('ON', $this->ok('validate_sitemap_robots')['site_noindex']);
        // a page's own robots value does not win
        $seo = $this->ok('get_seo', ['path' => '/retail-pos-software-uae/']);
        $this->ok('update_seo', ['path' => '/retail-pos-software-uae/', 'robots' => 'index, follow', 'expected_updated_at' => $seo['updated_at']]);
        $this->get('/retail-pos-software-uae/')->assertSee('content="noindex, nofollow"', false);

        config(['cms.noindex' => false]);
        \App\Cms\Cms::flush();
        $this->get('/retail-pos-software-uae/')->assertSee('<meta name="robots" content="index, follow">', false);
        $this->get('/robots.txt')->assertSee('Sitemap: http://localhost/sitemap.xml')->assertDontSee("Disallow: /\n", false);
    }

    public function test_settings_cannot_touch_env_or_secrets(): void
    {
        $settings = $this->ok('get_settings');
        $this->refused('update_settings', ['values' => ['SITE_NOINDEX' => 'false'], 'expected_updated_at' => $settings['settings_updated_at']], 'Unknown setting');
        $this->refused('update_settings', ['values' => ['lead_email' => 'x@example.com'], 'expected_updated_at' => $settings['settings_updated_at']], 'Unknown setting');
    }

    public function test_upload_link_takes_only_images_and_expires(): void
    {
        $link = $this->ok('create_image_upload_link', ['purpose' => 'Test', 'alt' => 'A test image of a shop']);
        $path = parse_url($link['upload_url'], PHP_URL_PATH);
        $exe = \Illuminate\Http\UploadedFile::fake()->createWithContent('photo.png', "MZ\x90\x00 fake exe");
        $this->post($path, ['images' => [$exe]])->assertStatus(422)->assertSee('executable or archive');
        $svg = \Illuminate\Http\UploadedFile::fake()->createWithContent('x.png', '<svg xmlns="http://www.w3.org/2000/svg"><script>alert(1)</script></svg>');
        $this->post($path, ['images' => [$svg]])->assertStatus(422)->assertSee('Only JPEG');
        $this->post($path, [])->assertStatus(422)->assertSee('Choose an image');
        $this->assertSame('waiting', $this->ok('get_image_upload_link', ['link_id' => $link['link_id']])['status']);

        \App\Models\UploadLink::query()->whereKey($link['link_id'])->update(['expires_at' => now()->subMinute()]);
        $this->get($path)->assertStatus(410)->assertSee('expired');
        $this->assertSame('expired', $this->ok('get_image_upload_link', ['link_id' => $link['link_id']])['status']);
        $this->assertStringContainsString('create_image_upload_link', $this->rpc('initialize', ['protocolVersion' => '2025-06-18', 'capabilities' => (object) [], 'clientInfo' => ['name' => 't', 'version' => '1']])->json('result.instructions'));
    }

    public function test_executables_and_archives_are_never_uploaded(): void
    {
        $this->refused('upload_image', ['base64' => base64_encode("MZ\x90\x00 fake exe"), 'alt' => 'An installer pretending'], 'executable or archive');
        $this->refused('upload_image', ['base64' => base64_encode("PK\x03\x04 zip"), 'alt' => 'A zip pretending'], 'executable or archive');
        $this->refused('upload_image', ['url' => 'https://example.com/RestroPro-setup.exe', 'alt' => 'An installer link'], '.exe files cannot');
        $this->refused('upload_image', ['base64' => base64_encode('<svg xmlns="http://www.w3.org/2000/svg"><script>alert(1)</script></svg>'), 'alt' => 'An svg file'], 'Only JPEG');
        // a download may link to a file hosted elsewhere, but never to an installer on this site
        $d = collect($this->ok('list_downloads')['items'])->firstWhere('slug', 'salonpro');
        $this->refused('update_download', ['id' => $d['id'], 'expected_updated_at' => $d['updated_at'], 'download_url' => '/files/SalonPro.exe'], 'cannot be uploaded or hosted');
        $this->ok('update_download', ['id' => $d['id'], 'expected_updated_at' => $d['updated_at'], 'download_url' => 'https://drive.google.com/file/d/new/view', 'version' => '2.1']);
        $this->get('/salonpro/')->assertSee('https://drive.google.com/file/d/new/view', false);
    }

    public function test_no_aggregate_rating_or_review_in_any_generated_schema(): void
    {
        $post = \App\Models\Post::query()->first();
        $hardware = \App\Models\HardwareItem::query()->first();
        $args = [
            'Organization' => [], 'LocalBusiness' => [], 'SoftwareApplication' => ['download_slug' => 'restropro'],
            'Service' => ['path' => '/pos-software-uae/'], 'Product' => ['hardware_id' => $hardware->id],
            'FAQPage' => ['path' => '/'], 'BreadcrumbList' => ['path' => '/bakery-pos-software/'], 'Article' => ['post_id' => $post->id],
        ];
        $this->assertSame(SchemaTools::GENERATE, array_keys($args));
        foreach ($args as $type => $a) {
            $json = json_encode($this->ok('generate_schema', ['type' => $type] + $a)['data']);
            $this->assertDoesNotMatchRegularExpression('/aggregateRating|"Review"|reviewRating|ratingValue/i', $json, $type);
            $this->assertStringNotContainsString('"price"', $json, "$type must have no invented price");
        }
        $this->refused('update_schema', ['path' => '/', 'data' => ['@type' => 'Product', 'name' => 'MultiTech POS', 'aggregateRating' => ['@type' => 'AggregateRating', 'ratingValue' => 5, 'reviewCount' => 100]]], 'not allowed');
        $this->refused('update_schema', ['data' => ['@type' => 'Review', 'reviewBody' => 'Great']], 'not allowed');
        // and no page outputs one
        foreach (['/', '/retail-pos-software-uae/', '/salonpro/', '/what-makes-a-tax-invoice-valid/'] as $url) {
            $this->get($url)->assertDontSee('AggregateRating')->assertDontSee('"@type":"Review"', false);
        }
    }

    public function test_no_invented_prices_or_testimonials(): void
    {
        $plan = collect($this->ok('list_pricing_plans')['items'])->first();
        $this->refused('update_pricing_plan', ['id' => $plan['id'], 'expected_updated_at' => $plan['updated_at'], 'price' => 'AED 999'], 'site shows no prices');
        $this->refused('create_testimonial', ['quote' => 'Great!', 'name' => 'Someone', 'provided_by_owner' => false], 'real');
    }

    public function test_duplicate_internal_link_is_refused(): void
    {
        $page = $this->ok('get_page', ['path' => '/retail-pos-software-uae/']);
        // the page already links to /pos-software-uae/
        $this->refused('add_internal_link', ['page_id' => $page['id'], 'anchor_text' => 'boutique', 'url' => '/pos-software-uae/', 'expected_updated_at' => $page['updated_at']], 'already links');
        $this->refused('add_internal_link', ['page_id' => $page['id'], 'anchor_text' => 'no such words here', 'url' => '/bakery-pos-software/', 'expected_updated_at' => $page['updated_at']], 'not found');
        $this->refused('add_internal_link', ['page_id' => $page['id'], 'anchor_text' => 'multi branch POS solution', 'url' => '/bakery-pos-software/', 'expected_updated_at' => $page['updated_at']], 'inside an existing link');
        $this->refused('add_internal_link', ['page_id' => $page['id'], 'anchor_text' => 'boutique', 'url' => '/no-such-page/', 'expected_updated_at' => $page['updated_at']], 'not a live page');
    }

    public function test_redirects_and_wordpress_short_links(): void
    {
        $this->ok('save_redirect', ['from_path' => '/gone-page/', 'status_code' => 410]);
        $this->get('/gone-page/')->assertStatus(410);
        $this->refused('save_redirect', ['from_path' => '/a/', 'to_url' => '/a/'], 'itself');
        $this->ok('save_redirect', ['from_path' => '/?p=123', 'to_url' => '/contact/']);
        $this->get('/?p=123')->assertRedirect('http://localhost/contact/');
        $this->get('/sitemap.xml')->assertOk()->assertSee('/retail-pos-software-uae/')->assertDontSee('/thank-you/');
    }
}
