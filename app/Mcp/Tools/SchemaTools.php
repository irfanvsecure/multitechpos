<?php

namespace App\Mcp\Tools;

use App\Cms\Faqs;
use App\Cms\Settings;
use App\Mcp\Support\Activity;
use App\Mcp\Support\Guard;
use App\Mcp\Support\Html;
use App\Mcp\Support\Render;
use App\Mcp\Support\Validate;
use App\Mcp\ToolError;
use App\Mcp\ToolSet;
use App\Models\Download;
use App\Models\HardwareItem;
use App\Models\Office;
use App\Models\Page;
use App\Models\Post;
use App\Models\PricingPlan;
use App\Models\SchemaBlock;
use Illuminate\Database\Eloquent\Model;

class SchemaTools extends ToolSet
{
    public const GENERATE = ['Organization', 'LocalBusiness', 'SoftwareApplication', 'Service', 'Product', 'FAQPage', 'BreadcrumbList', 'Article'];

    public function tools(): array
    {
        $owner = self::ownerProps();

        return [
            $this->read('get_schema', 'JSON-LD of a page/post: the automatic blocks the templates output (Organization, WebSite, BreadcrumbList, FAQPage, BlogPosting) and the extra blocks saved through the MCP (with ids). Without a page: the site-wide extra blocks.', $owner, [], fn ($a) => $this->get($a)),

            $this->write('update_schema', 'Add or change an extra JSON-LD block for a page/post (or site-wide without a page). Refused: AggregateRating/Review for our own business or products, and a second block of a @type the page already has.', $owner + [
                'id' => self::i('Existing block id (to change it)'),
                'data' => self::o('The JSON-LD object (with @type)'),
                'is_active' => self::b('Output it (default true)'),
                'expected_updated_at' => self::s('Needed when changing an existing block'),
            ], ['data'], fn ($a) => $this->save($a)),

            $this->delete('delete_schema', 'Remove an extra JSON-LD block (to the trash).', [
                'id' => self::i('Block id'),
                'expected_updated_at' => self::expected(),
            ], ['id', 'expected_updated_at'], fn ($a) => $this->remove($a)),

            $this->write('generate_schema', 'Build a JSON-LD block from site data: Organization, LocalBusiness (Musaffah office, areaServed Dubai/Abu Dhabi/Sharjah/UAE), SoftwareApplication (POS page or download; no price unless one is set in pricing_plans; never ratings), Service (ERP/industry pages), Product (hardware item, no price), FAQPage, BreadcrumbList, Article (blog post). save=false (default) only returns it.', $owner + [
                'type' => self::e('Schema type', self::GENERATE),
                'download_slug' => self::s('SoftwareApplication for this download (restropro, salonpro, laundrypro, tailorpro)'),
                'hardware_id' => self::i('Product for this hardware item'),
                'save' => self::b('Save as an extra block on the page (or site-wide for Organization/LocalBusiness without a page)'),
            ], ['type'], fn ($a) => $this->generate($a)),
        ];
    }

    private function ownerOrNull(array $a): ?Model
    {
        return (! empty($a['page_id']) || ! empty($a['post_id']) || isset($a['path'])) ? $this->findOwner($a) : null;
    }

    public static function blockOut(SchemaBlock $b): array
    {
        return ['id' => $b->id, 'type' => $b->type, 'scope' => $b->schemable_type ? class_basename($b->schemable_type).' #'.$b->schemable_id : 'site-wide',
            'is_active' => $b->is_active, 'data' => $b->data, 'updated_at' => Guard::stamp($b)];
    }

    /** @return list<array> automatic JSON-LD objects output by the templates */
    private function automatic(Model $owner): array
    {
        preg_match_all('#<script type="application/ld\+json">(.*?)</script>#s', Render::full($owner), $m);
        $custom = SchemaBlock::query()->where('is_active', true)->get()->map(fn ($b) => json_encode($b->data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE))->all();
        $out = [];
        foreach ($m[1] as $json) {
            $data = json_decode($json, true);
            if ($data && ! in_array(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), $custom, true)) {
                $out[] = $data;
            }
        }

        return $out;
    }

    private static function types(array $data): array
    {
        $types = [];
        foreach ($data['@graph'] ?? [$data] as $node) {
            foreach ((array) ($node['@type'] ?? []) as $t) {
                $types[] = $t;
            }
        }

        return $types;
    }

    private function get(array $a): array
    {
        $owner = $this->ownerOrNull($a);
        $global = SchemaBlock::query()->whereNull('schemable_type')->orderBy('sort_order')->get()->map(fn ($b) => self::blockOut($b))->all();
        if (! $owner) {
            return ['site_wide_extra_blocks' => $global];
        }
        $auto = $this->automatic($owner);

        return [
            'url' => $owner->path,
            'automatic' => array_map(fn ($d) => ['types' => self::types($d), 'data' => $d], $auto),
            'extra_blocks' => $owner->schemaBlocks()->orderBy('sort_order')->get()->map(fn ($b) => self::blockOut($b))->all(),
            'site_wide_extra_blocks' => $global,
        ];
    }

    private function save(array $a): array
    {
        $data = $a['data'];
        if (empty($data['@type']) && empty($data['@graph'])) {
            throw new ToolError('data needs an @type.');
        }
        Validate::noSelfServingReviews($data);
        $data = ['@context' => 'https://schema.org'] + $data;
        $owner = $this->ownerOrNull($a);
        $existing = ! empty($a['id']) ? SchemaBlock::query()->find($a['id']) : null;
        if (! empty($a['id']) && ! $existing) {
            throw new ToolError('No such schema block.');
        }
        if ($existing) {
            Guard::check($existing, $a['expected_updated_at'] ?? null);
        }
        $type = implode(',', self::types($data));

        // one block per @type on a page
        $have = $owner ? array_merge(...array_map(fn ($d) => self::types($d), $this->automatic($owner)) ?: [[]]) : ['Organization', 'WebSite'];
        $others = SchemaBlock::query()->where('is_active', true)->when($existing, fn ($q) => $q->where('id', '!=', $existing->id))
            ->where(fn ($q) => $q->whereNull('schemable_type')->when($owner, fn ($q) => $q->orWhere(fn ($q) => $q->where('schemable_type', $owner->getMorphClass())->where('schemable_id', $owner->getKey()))))
            ->get()->flatMap(fn ($b) => self::types($b->data))->all();
        if ($existing) {
            $have = array_diff($have, self::types($existing->data));
        }
        foreach (self::types($data) as $t) {
            if (in_array($t, array_merge($have, $others), true)) {
                throw new ToolError("This page already outputs a $t block. Change that one instead of adding a second.");
            }
        }

        $before = $existing ? self::blockOut($existing) : null;
        $block = $existing ?? new SchemaBlock;
        $block->fill([
            'type' => $type, 'data' => $data, 'is_active' => $a['is_active'] ?? true,
            'schemable_type' => $existing ? $existing->schemable_type : $owner?->getMorphClass(),
            'schemable_id' => $existing ? $existing->schemable_id : $owner?->getKey(),
        ])->save();
        Activity::log($existing ? 'update' : 'create', $block, $before, self::blockOut($block), "$type schema".($owner ? ' on '.$owner->path : ' (site-wide)'), [$owner?->path]);

        return ['block' => self::blockOut($block->fresh())];
    }

    private function remove(array $a): array
    {
        $b = $this->guard(SchemaBlock::query()->find($a['id']), $a, 'schema block');
        $before = self::blockOut($b);
        $b->delete();
        Activity::log('delete', $b, $before, null, $b->type.' schema', []);

        return ['deleted' => $before];
    }

    // ------------------------------------------------------------------ generate

    private function generate(array $a): array
    {
        $owner = $this->ownerOrNull($a);
        $data = match ($a['type']) {
            'Organization' => $this->organization(),
            'LocalBusiness' => $this->localBusiness(),
            'SoftwareApplication' => $this->software($owner, $a['download_slug'] ?? null),
            'Service' => $this->service($owner ?? throw new ToolError('Service needs a page.')),
            'Product' => $this->product((int) ($a['hardware_id'] ?? 0)),
            'FAQPage' => $this->faqPage($owner ?? throw new ToolError('FAQPage needs a page.')),
            'BreadcrumbList' => $this->breadcrumb($owner ?? throw new ToolError('BreadcrumbList needs a page.')),
            'Article' => $this->article($owner instanceof Post ? $owner : throw new ToolError('Article needs a blog post (post_id).')),
        };
        Validate::noSelfServingReviews($data);
        $data = ['@context' => 'https://schema.org'] + $data;

        if (! ($a['save'] ?? false)) {
            return ['data' => $data, 'saved' => false, 'note' => 'Call again with save=true (or pass data to update_schema) to output it.'];
        }

        return $this->save(['data' => $data] + array_intersect_key($a, array_flip(['page_id', 'post_id', 'path'])));
    }

    private function site(): string
    {
        return rtrim((string) config('app.url'), '/');
    }

    private function organization(): array
    {
        $s = Settings::all();

        return array_filter([
            '@type' => 'Organization',
            '@id' => $this->site().'/#organization',
            'name' => $s['site_name'],
            'legalName' => $s['legal_name'],
            'url' => $this->site().'/',
            'logo' => $this->site().$s['logo'],
            'email' => $s['email'],
            'telephone' => $s['phone'],
            'address' => $this->address(),
            'sameAs' => $s['social_links'],
            'parentOrganization' => ['@type' => 'Organization', 'name' => 'Micro Aegis International Technologies (MAIT)', 'url' => 'https://mait.ae/'],
        ]);
    }

    private function address(): array
    {
        $o = Office::query()->where('is_primary', true)->first();
        $s = Settings::all();

        return ['@type' => 'PostalAddress', 'streetAddress' => $s['address_street'], 'addressLocality' => $o?->city ?? $s['address_city'], 'addressCountry' => $s['address_country']];
    }

    private function areaServed(): array
    {
        return [
            ['@type' => 'City', 'name' => 'Dubai'], ['@type' => 'City', 'name' => 'Abu Dhabi'],
            ['@type' => 'City', 'name' => 'Sharjah'], ['@type' => 'Country', 'name' => 'United Arab Emirates'],
        ];
    }

    private function localBusiness(): array
    {
        $o = Office::query()->where('is_primary', true)->first();
        $s = Settings::all();
        $hours = $o?->hours ?? $s['hours'];

        return array_filter([
            '@type' => 'LocalBusiness',
            '@id' => $this->site().'/#localbusiness',
            'name' => $s['site_name'],
            'url' => $this->site().'/',
            'image' => $this->site().$s['logo'],
            'telephone' => $o?->phone ?? $s['phone'],
            'email' => $o?->email ?? $s['email'],
            'address' => $this->address(),
            'hasMap' => $o?->map_url,
            // only the default wording is translated; anything else is left out rather than guessed
            'openingHoursSpecification' => $hours === 'Sat to Thu, 9 AM to 6 PM' ? [[
                '@type' => 'OpeningHoursSpecification',
                'dayOfWeek' => ['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday'],
                'opens' => '09:00', 'closes' => '18:00',
            ]] : null,
            'areaServed' => $this->areaServed(),
            'parentOrganization' => ['@id' => $this->site().'/#organization'],
        ]);
    }

    private function offers(): ?array
    {
        $priced = PricingPlan::query()->where('is_active', true)->whereNotNull('price')->where('price', '!=', '')->get();
        if ($priced->isEmpty()) {
            return null; // the site shows no prices: no offers
        }

        return $priced->map(fn ($p) => array_filter([
            '@type' => 'Offer', 'name' => $p->name,
            'price' => preg_replace('/[^0-9.]/', '', (string) $p->price), 'priceCurrency' => 'AED', 'description' => $p->price_period,
        ]))->all();
    }

    private function software(?Model $owner, ?string $downloadSlug): array
    {
        $download = $downloadSlug ? Download::query()->where('slug', $downloadSlug)->first() : ($owner instanceof Page ? Download::query()->where('page_id', $owner->id)->first() : null);
        if ($downloadSlug && ! $download) {
            throw new ToolError('No such download.');
        }
        if (! $download && ! $owner) {
            throw new ToolError('SoftwareApplication needs a page or a download_slug.');
        }
        $page = $download?->page ?? $owner;
        $seo = $page ? $page->seo()->first() : null;

        return array_filter([
            '@type' => 'SoftwareApplication',
            'name' => $download?->name ?? ($page instanceof Page ? $page->name : null),
            'description' => $download?->description ?? $seo?->description,
            'url' => $page ? $this->site().$page->path : null,
            'applicationCategory' => 'BusinessApplication',
            'operatingSystem' => $download?->platform ?: ($download ? 'Windows' : null),
            'softwareVersion' => $download?->version,
            'fileSize' => $download?->file_size,
            'downloadUrl' => $download && str_starts_with((string) $download->download_url, 'http') ? $download->download_url : null,
            'offers' => $this->offers(),
            'publisher' => ['@id' => $this->site().'/#organization'],
        ]);
    }

    private function service(Model $owner): array
    {
        if (! $owner instanceof Page) {
            throw new ToolError('Service is for pages.');
        }
        $seo = $owner->seo()->first();

        return array_filter([
            '@type' => 'Service',
            'name' => $owner->name,
            'serviceType' => $owner->name,
            'description' => $seo?->description,
            'url' => $this->site().$owner->path,
            'provider' => ['@id' => $this->site().'/#organization'],
            'areaServed' => $this->areaServed(),
        ]);
    }

    private function product(int $id): array
    {
        $item = HardwareItem::query()->find($id) ?? throw new ToolError('Give hardware_id (see list_hardware_items).');

        return array_filter([
            '@type' => 'Product',
            'name' => $item->name,
            'description' => $item->description,
            'category' => $item->category,
            'image' => $item->image ? (str_starts_with($item->image, '/') ? $this->site().$item->image : $item->image) : null,
            'brand' => ['@type' => 'Brand', 'name' => Settings::get('site_name')],
        ]);
    }

    private function faqPage(Model $owner): array
    {
        $faqs = $owner instanceof Page ? Faqs::forPage($owner) : [];
        if (! $faqs) {
            throw new ToolError('This page has no FAQs (add them with create_faq). Pages with FAQs already output FAQPage automatically.');
        }

        return ['@type' => 'FAQPage', 'mainEntity' => array_map(fn ($f) => ['@type' => 'Question', 'name' => strip_tags($f['q']),
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => strip_tags($f['a'])]], $faqs)];
    }

    private function breadcrumb(Model $owner): array
    {
        $items = [['Home', '/']];
        if ($owner instanceof Post) {
            $items[] = ['Blog', '/our-blogs/'];
            $items[] = [$owner->title, $owner->path];
        } else {
            if ($owner->parent) {
                $items[] = [$owner->parent->name, $owner->parent->path];
            }
            $items[] = [$owner->name, $owner->path];
        }

        return ['@type' => 'BreadcrumbList', 'itemListElement' => array_map(fn ($x, $i) => [
            '@type' => 'ListItem', 'position' => $i + 1, 'name' => Html::text($x[0]), 'item' => $this->site().$x[1],
        ], $items, array_keys($items))];
    }

    private function article(Post $post): array
    {
        return array_filter([
            '@type' => 'BlogPosting',
            'headline' => $post->title,
            'image' => $post->cover_image ? $this->site().$post->cover_image : null,
            'datePublished' => $post->published_at?->format('Y-m-d'),
            'dateModified' => $post->updated_at?->format('Y-m-d'),
            'author' => ['@type' => 'Person', 'name' => $post->author_name, 'url' => $this->site().'/author/multitech_admin/'],
            'publisher' => ['@id' => $this->site().'/#organization'],
            'mainEntityOfPage' => $this->site().$post->path,
        ]);
    }
}
