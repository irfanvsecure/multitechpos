<?php

namespace App\Mcp\Tools;

use App\Cms\FormOptions;
use App\Cms\Placeholders;
use App\Mcp\Support\Activity;
use App\Mcp\Support\Guard;
use App\Mcp\Support\Validate;
use App\Mcp\ToolError;
use App\Mcp\ToolSet;
use App\Models\ClientLogo;
use App\Models\Download;
use App\Models\Faq;
use App\Models\FormOption;
use App\Models\HardwareItem;
use App\Models\Office;
use App\Models\Page;
use App\Models\PageSection;
use App\Models\PricingPlan;
use App\Models\Testimonial;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

/**
 * list / create / update / delete for the business content tables.
 */
class BusinessTools extends ToolSet
{
    private function resources(): array
    {
        return [
            'faq' => [
                'plural' => 'faqs', 'model' => Faq::class, 'label' => 'question',
                'about' => 'FAQs of a page. The page\'s FAQPage schema updates automatically. On pages whose visible FAQ list is part of an html section, update that text too (the response tells you).',
                'fields' => [
                    'page_id' => [self::i('Page the FAQ belongs to (the FAQ page itself for global FAQs)'), ['integer', 'exists:pages,id']],
                    'question' => [self::s('Question (placeholders allowed)'), ['string', 'max:500']],
                    'answer' => [self::s('Answer (plain text, placeholders allowed)'), ['string', 'max:3000']],
                    'sort_order' => [self::i('Order'), ['integer']],
                    'is_active' => [self::b('Shown'), ['boolean']],
                ],
                'required' => ['page_id', 'question', 'answer'],
                'filter' => ['page_id' => self::i('Only this page')],
            ],
            'testimonial' => [
                'plural' => 'testimonials', 'model' => Testimonial::class, 'label' => 'name',
                'about' => 'Customer testimonials (none yet; the section stays hidden while empty). Only add real testimonials the owner gives you, word for word. Never write or embellish one.',
                'fields' => [
                    'quote' => [self::s('Exact words of the customer'), ['string', 'max:1200']],
                    'name' => [self::s('Customer name'), ['string', 'max:120']],
                    'business' => [self::s('Role and business, e.g. Owner, Al Noor Pharmacy, Abu Dhabi'), ['nullable', 'string', 'max:160']],
                    'photo' => [self::s('Photo path (optional)'), ['nullable', 'string']],
                    'sort_order' => [self::i('Order'), ['integer']],
                    'is_active' => [self::b('Shown'), ['boolean']],
                    'provided_by_owner' => [self::b('Required true on create: the owner gave you this testimonial'), ['boolean']],
                ],
                'required' => ['quote', 'name', 'provided_by_owner'],
            ],
            'client_logo' => [
                'plural' => 'client_logos', 'model' => ClientLogo::class, 'label' => 'name',
                'about' => 'Client logos in the "Trusted by" strip (home + landing pages). Only real clients.',
                'fields' => [
                    'name' => [self::s('Client name (used as alt text)'), ['string', 'max:120']],
                    'image' => [self::s('Logo image path (upload_image first)'), ['string']],
                    'url' => [self::s('Client website (optional)'), ['nullable', 'url']],
                    'sort_order' => [self::i('Order'), ['integer']],
                    'is_active' => [self::b('Shown'), ['boolean']],
                ],
                'required' => ['name', 'image'],
            ],
            'hardware_item' => [
                'plural' => 'hardware_items', 'model' => HardwareItem::class, 'label' => 'name',
                'about' => 'POS hardware the company supplies (used for Product schema). No prices.',
                'fields' => [
                    'name' => [self::s('Name'), ['string', 'max:150']],
                    'slug' => [self::s('Slug'), ['string', 'regex:/^[a-z0-9]+(-[a-z0-9]+)*$/']],
                    'category' => [self::s('Category'), ['nullable', 'string', 'max:100']],
                    'description' => [self::s('Description (facts only)'), ['nullable', 'string', 'max:2000']],
                    'image' => [self::s('Image path'), ['nullable', 'string']],
                    'sort_order' => [self::i('Order'), ['integer']],
                    'is_active' => [self::b('Active'), ['boolean']],
                ],
                'required' => ['name', 'slug'],
            ],
            'pricing_plan' => [
                'plural' => 'pricing_plans', 'model' => PricingPlan::class, 'label' => 'name',
                'about' => 'Plans shown on the home page (Single Outlet, Multi-Branch, Enterprise & Custom). The site shows NO prices: leave price empty unless the owner gives you one (then set owner_provided_price=true). {{plan:slug}} shows the name in page text.',
                'fields' => [
                    'slug' => [self::s('Slug (used by {{plan:slug}})'), ['string', 'regex:/^[a-z0-9]+(-[a-z0-9]+)*$/']],
                    'name' => [self::s('Plan name'), ['string', 'max:100']],
                    'description' => [self::s('Who it is for (shown under the name)'), ['nullable', 'string', 'max:300']],
                    'inclusions' => [self::a('What is included (list)'), ['array']],
                    'price' => [self::s('Price, only from the owner (empty = quote only)'), ['nullable', 'string', 'max:60']],
                    'price_period' => [self::s('e.g. one-time licence / per month'), ['nullable', 'string', 'max:60']],
                    'badge' => [self::s('Badge text'), ['nullable', 'string', 'max:60']],
                    'icon' => [self::s('Font Awesome class, e.g. fas fa-store'), ['nullable', 'string', 'max:60']],
                    'tile' => [self::s('Icon colour: blue, violet, teal, green, amber, rose, cyan, pink, slate'), ['nullable', 'string', 'max:20']],
                    'cta_label' => [self::s('Button text'), ['nullable', 'string', 'max:40']],
                    'is_featured' => [self::b('Highlighted plan'), ['boolean']],
                    'sort_order' => [self::i('Order'), ['integer']],
                    'is_active' => [self::b('Shown'), ['boolean']],
                    'owner_provided_price' => [self::b('Must be true when setting a price'), ['boolean']],
                ],
                'required' => ['slug', 'name'],
            ],
            'download' => [
                'plural' => 'downloads', 'model' => Download::class, 'label' => 'name',
                'about' => 'Downloadable products (RestroPro, SalonPro, LaundryPro, TailorPro): metadata and the download LINK only. Installer files are never uploaded or hosted here; host them elsewhere (e.g. Google Drive) and save the link. {{download:slug}} / {{download:slug.version}} show them in page text.',
                'fields' => [
                    'slug' => [self::s('Slug'), ['string', 'regex:/^[a-z0-9]+(-[a-z0-9]+)*$/']],
                    'name' => [self::s('Product name'), ['string', 'max:100']],
                    'description' => [self::s('Description'), ['nullable', 'string', 'max:1000']],
                    'platform' => [self::s('e.g. Windows 7, 8, 10, or 11'), ['nullable', 'string', 'max:120']],
                    'version' => [self::s('Version'), ['nullable', 'string', 'max:40']],
                    'file_size' => [self::s('e.g. 70.10 MB'), ['nullable', 'string', 'max:40']],
                    'download_url' => [self::s('Link to the installer (https, hosted elsewhere) or a page path'), ['nullable', 'string', 'max:500']],
                    'page_id' => [self::i('Product page'), ['nullable', 'integer', 'exists:pages,id']],
                    'sort_order' => [self::i('Order'), ['integer']],
                ],
                'required' => ['slug', 'name'],
            ],
            'office' => [
                'plural' => 'offices', 'model' => Office::class, 'label' => 'name',
                'about' => 'Office locations (one today: Musaffah, Abu Dhabi). Used for LocalBusiness schema. Site-wide contact details are settings.',
                'fields' => [
                    'name' => [self::s('Name'), ['string', 'max:150']],
                    'address' => [self::s('Street address'), ['string', 'max:255']],
                    'city' => [self::s('City'), ['string', 'max:100']],
                    'country' => [self::s('Country'), ['string', 'max:60']],
                    'phone' => [self::s('Phone'), ['nullable', 'string', 'max:40']],
                    'email' => [self::s('Email'), ['nullable', 'email']],
                    'map_url' => [self::s('Google Maps link'), ['nullable', 'url', 'max:1000']],
                    'hours' => [self::s('Opening hours, e.g. Sat to Thu, 9 AM to 6 PM'), ['nullable', 'string', 'max:120']],
                    'is_primary' => [self::b('Main office'), ['boolean']],
                ],
                'required' => ['name', 'address', 'city', 'country'],
            ],
            'form_option' => [
                'plural' => 'form_options', 'model' => FormOption::class, 'label' => 'value',
                'about' => 'Option lists of the code forms (field labels stay in code). business_types: hero demo form + Setup Builder + home business picker (meta: label, icon, tile, url, text, points). builder_branches / builder_counters: Setup Builder step 2. quote_service: "Interested Service" of the quote forms.',
                'fields' => [
                    'form' => [self::e('Which list', FormOptions::FORMS), ['string']],
                    'value' => [self::s('Option text (what is submitted)'), ['string', 'max:100']],
                    'label' => [self::s('Display label (business_types: tab label)'), ['nullable', 'string', 'max:100']],
                    'meta' => [self::o('business_types: {icon, tile, url, text, points[]}'), ['nullable', 'array']],
                    'sort_order' => [self::i('Order'), ['integer']],
                    'is_active' => [self::b('Shown'), ['boolean']],
                ],
                'required' => ['form', 'value'],
                'filter' => ['form' => self::e('Only this list', FormOptions::FORMS)],
            ],
        ];
    }

    public function tools(): array
    {
        $tools = [];
        foreach ($this->resources() as $name => $r) {
            $fieldProps = array_map(fn ($f) => $f[0], $r['fields']);
            $tools[] = $this->read('list_'.$r['plural'], 'List '.str_replace('_', ' ', $r['plural']).'. '.$r['about'], ($r['filter'] ?? []) + ['include_inactive' => self::b('Include hidden ones (default true)')], [], fn ($a) => $this->list($r, $a));
            $tools[] = $this->write('create_'.$name, 'Add a '.str_replace('_', ' ', $name).'. '.$r['about'], $fieldProps, $r['required'], fn ($a) => $this->create($name, $r, $a));
            $tools[] = $this->write('update_'.$name, 'Change a '.str_replace('_', ' ', $name).' (only the fields you send).', ['id' => self::i('Id'), 'expected_updated_at' => self::expected()] + $fieldProps, ['id', 'expected_updated_at'], fn ($a) => $this->update($name, $r, $a));
            $tools[] = $this->delete('delete_'.$name, 'Delete a '.str_replace('_', ' ', $name).' (to the trash).', ['id' => self::i('Id'), 'expected_updated_at' => self::expected()], ['id', 'expected_updated_at'], fn ($a) => $this->remove($name, $r, $a));
        }

        return $tools;
    }

    private function out(Model $m): array
    {
        return collect($m->attributesToArray())->except(['created_at', 'deleted_at'])->merge(['updated_at' => Guard::stamp($m)])->all();
    }

    private function list(array $r, array $a): array
    {
        $class = $r['model'];
        $q = $class::query()->when(isset($a['include_inactive']) && ! $a['include_inactive'] && in_array('is_active', array_keys($r['fields']), true), fn ($q) => $q->where('is_active', true));
        foreach (array_keys($r['filter'] ?? []) as $f) {
            if (isset($a[$f])) {
                $q->where($f, $a[$f]);
            }
        }
        $order = in_array('sort_order', array_keys($r['fields']), true) ? 'sort_order' : 'id';
        $rows = $q->orderBy($order)->orderBy('id')->get();

        return ['count' => $rows->count(), 'items' => $rows->map(fn ($m) => $this->out($m))->all()];
    }

    private function validated(string $name, array $r, array $a, ?Model $existing): array
    {
        $data = array_intersect_key($a, $r['fields']);
        $rules = [];
        foreach ($data as $f => $_) {
            $rules[$f] = array_merge(in_array($f, $r['required'], true) && ! $existing ? ['required'] : ['sometimes'], $r['fields'][$f][1]);
        }
        Validator::make($data, $rules)->validate();

        // text: no markup; placeholders checked
        foreach ($data as $f => $v) {
            if (is_string($v) && ! in_array($f, ['download_url', 'map_url', 'url', 'image', 'photo'], true)) {
                $data[$f] = trim(strip_tags($v));
                if ($errors = Placeholders::validate($data[$f])) {
                    throw new ToolError("$f: ".implode(' ', $errors));
                }
            }
        }
        foreach (['image', 'photo'] as $f) {
            if (! empty($data[$f]) && (! str_starts_with($data[$f], '/') || ! is_file(public_path(ltrim($data[$f], '/'))))) {
                throw new ToolError("$f: file not found (upload it with upload_image).");
            }
        }

        $model = $r['model'];
        if (isset($data['slug']) && $model::withTrashed()->where('slug', $data['slug'])->when($existing, fn ($q) => $q->where('id', '!=', $existing->id))->exists()) {
            throw new ToolError("The slug '{$data['slug']}' is already used.");
        }
        if ($name === 'testimonial' && ! $existing && ! ($a['provided_by_owner'] ?? false)) {
            throw new ToolError('Testimonials must be real and given by the owner: set provided_by_owner=true only if so.');
        }
        unset($data['provided_by_owner']);
        if ($name === 'pricing_plan') {
            if (filled($data['price'] ?? null) && ! ($a['owner_provided_price'] ?? false)) {
                throw new ToolError('The site shows no prices. Set a price only when the owner gives it to you, with owner_provided_price=true.');
            }
            unset($data['owner_provided_price']);
            if (isset($data['inclusions'])) {
                $data['inclusions'] = array_values(array_map(fn ($i) => trim(strip_tags((string) $i)), $data['inclusions']));
            }
        }
        if ($name === 'download' && ! empty($data['download_url'])) {
            $url = $data['download_url'];
            if (! str_starts_with($url, '/') && ! str_starts_with($url, 'https://')) {
                throw new ToolError('download_url must be an https link or a site path.');
            }
            $host = strtolower((string) parse_url($url, PHP_URL_HOST));
            $own = str_starts_with($url, '/') || in_array($host, ['multitechpos.com', 'www.multitechpos.com', strtolower((string) parse_url((string) config('app.url'), PHP_URL_HOST))], true);
            if ($own) {
                Validate::notExecutable($url); // never host installers on this site
            }
        }
        if ($name === 'form_option' && isset($data['meta'])) {
            array_walk_recursive($data['meta'], function (&$v) {
                $v = is_string($v) ? trim(strip_tags($v)) : $v;
            });
        }
        if ($name === 'office' && ($data['is_primary'] ?? false)) {
            Office::query()->when($existing, fn ($q) => $q->where('id', '!=', $existing->id))->update(['is_primary' => false]);
        }

        return $data;
    }

    private function create(string $name, array $r, array $a): array
    {
        $data = $this->validated($name, $r, $a, null);
        $class = $r['model'];
        $m = $class::query()->create($data);
        Activity::log('create', $m, null, $this->out($m), $name.' '.($m->{$r['label']} ?? ''), $this->urls($name, $m));

        return [$name => $this->out($m->fresh())] + $this->notes($name, $m, null);
    }

    private function update(string $name, array $r, array $a): array
    {
        $class = $r['model'];
        $m = $this->guard($class::query()->find($a['id']), $a, str_replace('_', ' ', $name));
        $before = $this->out($m);
        $data = $this->validated($name, $r, $a, $m);
        if (! $data) {
            throw new ToolError('Nothing to change.');
        }
        $old = clone $m;
        $m->update($data);
        Activity::log('update', $m, $before, $this->out($m), $name.' '.($m->{$r['label']} ?? ''), $this->urls($name, $m));

        return [$name => $this->out($m->fresh())] + $this->notes($name, $m, $old);
    }

    private function remove(string $name, array $r, array $a): array
    {
        $class = $r['model'];
        $m = $this->guard($class::query()->find($a['id']), $a, str_replace('_', ' ', $name));
        $before = $this->out($m);
        $m->delete();
        Activity::log('delete', $m, $before, null, $name.' '.($before[$r['label']] ?? ''), $this->urls($name, $m));

        return ['deleted' => $before, 'restore' => 'restore_item type='.$name.' id='.$m->id] + $this->notes($name, $m, $m);
    }

    private function urls(string $name, Model $m): array
    {
        return $name === 'faq' ? [Page::query()->find($m->page_id)?->path] : [];
    }

    /** FAQ text that also sits in the page's html (visible FAQ list) must be changed there too. */
    private function notes(string $name, Model $m, ?Model $old): array
    {
        if ($name !== 'faq' || ! $old) {
            return [];
        }
        $q = html_entity_decode((string) $old->question);
        $hits = PageSection::query()->where('page_id', $old->page_id)->where('type', 'html')->get()
            ->filter(fn ($s) => str_contains(html_entity_decode(strip_tags((string) $s->content)), $q))->pluck('id')->all();

        return $hits ? ['note' => 'The FAQPage schema is updated. This page also shows the FAQ as text in section(s) '.implode(', ', $hits).': update it there too (update_page_section) so both match.'] : [];
    }
}
