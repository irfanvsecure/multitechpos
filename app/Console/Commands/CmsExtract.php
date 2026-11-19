<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * One-off: reads every page Blade file and writes ALL its content (text, headings, CTAs,
 * images, alt texts, FAQs, stats, logos, plans, downloads, form options, meta) to
 * database/content/site.json, unchanged. `cms:import` loads that file into the database.
 *
 * Nothing is rewritten: contact details and stats are only swapped for placeholders that
 * render the same text ({{phone}}, {{setting:stat_businesses}} ...).
 */
class CmsExtract extends Command
{
    protected $signature = 'cms:extract {--out=database/content/site.json}';

    protected $description = 'Copy the content of the Blade page files into database/content/site.json';

    private array $problems = [];

    /** route group comment in routes/web.php => page_type */
    private const GROUPS = [
        'Company' => 'standard', 'POS Software' => 'core_pos', 'POS Features' => 'feature', 'Business ERPs' => 'erp',
        'Downloads' => 'download', 'Industries' => 'industry', 'Blog' => 'standard', 'Legal & help' => 'legal',
    ];

    private const BUSINESS_SIZE = ['business-size', 'small-business-pos-uae', 'multi-branch-business-pos-uae', 'enterprise-pos-uae'];

    private const CORE_POS_FROM_INDUSTRIES = ['grocery-pos-system-in-uae', 'pharmacy-pos-system-in-the-uae'];

    private const HUBS = ['core_pos' => 'pos-software-uae', 'business_size' => 'business-size', 'industry' => 'by-industry',
        'feature' => 'features', 'erp' => 'business-erps', 'download' => 'downloads'];

    private const DOWNLOADS = [
        'restropro' => 'RestroPro', 'salonpro' => 'SalonPro', 'laundrypro' => 'LaundryPro', 'tailorpro' => 'TailorPro',
    ];

    public function handle(): int
    {
        $views = resource_path('views');
        $routes = $this->routes();
        $out = [
            'generated_at' => now()->toIso8601String(),
            'pages' => [], 'posts' => [], 'categories' => [], 'faqs' => [], 'client_logos' => [], 'pricing_plans' => [],
            'downloads' => [], 'form_options' => [], 'hardware_items' => [], 'offices' => [], 'settings' => [], 'media' => [],
        ];

        $archive = $this->archiveCards(File::get($views.'/author/multitech_admin.blade.php'));
        $blogCards = $this->blogCards(File::get($views.'/our-blogs.blade.php'));

        foreach ($routes as $route) {
            $file = $views.'/'.str_replace('.', '/', $route['view']).'.blade.php';
            $src = File::get($file);
            $slug = $route['slug'];

            if (str_contains($src, "@include('partials.schema-article'")) {
                $out['posts'][] = $this->post($slug, $src, $archive[$slug] ?? null, $blogCards[$slug] ?? null);

                continue;
            }

            [$page, $faqs] = $slug === '' ? $this->home($src) : $this->page($slug, $src);
            $page += $this->typeOf($slug, $route['group']);
            $page['sort_order'] = count($out['pages']);
            $out['pages'][] = $page;
            foreach ($faqs as $i => $faq) {
                $out['faqs'][] = ['page' => $slug, 'question' => $faq['q'], 'answer' => $faq['a'], 'sort_order' => $i];
            }
        }

        $this->orderPosts($out['posts']);
        $out['categories'] = collect($out['posts'])->pluck('categories')->flatten()->unique()->values()
            ->map(fn ($name) => ['name' => $name, 'slug' => Str::slug($name)])->all();
        $out['client_logos'] = $this->clientLogos();
        $out['pricing_plans'] = $this->plans();
        $out['downloads'] = $this->downloads();
        $out['form_options'] = $this->formOptions();
        $out['hardware_items'] = $this->hardware();
        $out['offices'] = [[
            'name' => 'MultiTech POS – Abu Dhabi office', 'address' => '16 Al Isayfir 2 St - Musaffah - M33 - Abu Dhabi',
            'city' => 'Abu Dhabi', 'country' => 'United Arab Emirates', 'phone' => '+971588979925', 'email' => 'support@multitechpos.com',
            'map_url' => 'https://maps.google.com/maps?q=16%20Al%20Isayfir%202%20St%20-%20Musaffah%20-%20M33%20-%20Abu%20Dhabi',
            'hours' => 'Sat to Thu, 9 AM to 6 PM', 'is_primary' => true,
        ]];
        $out['media'] = $this->media($out);

        $path = base_path($this->option('out'));
        File::ensureDirectoryExists(dirname($path));
        File::put($path, json_encode($out, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)."\n");

        $this->info(sprintf('%d pages, %d sections, %d posts, %d FAQs, %d images -> %s',
            count($out['pages']), collect($out['pages'])->sum(fn ($p) => count($p['sections'])), count($out['posts']),
            count($out['faqs']), count($out['media']), $this->option('out')));
        foreach ($this->problems as $p) {
            $this->error($p);
        }

        return $this->problems ? self::FAILURE : self::SUCCESS;
    }

    // ------------------------------------------------------------------ routes

    private function routes(): array
    {
        $group = 'Company';
        $routes = [['slug' => '', 'view' => 'index', 'group' => 'Company']];
        // the Route::view list as it was before the move to the database
        foreach (file(base_path('database/content/legacy-routes.php.txt')) as $line) {
            if (preg_match('#^// (.+?) \(\d+\)#', trim($line), $m)) {
                $group = $m[1];
            }
            if (preg_match("#Route::view\('/([^']*)/', '([^']+)'\)#", $line, $m)) {
                $routes[] = ['slug' => $m[1], 'view' => $m[2], 'group' => $group];
            }
        }
        $routes[] = ['slug' => 'thank-you', 'view' => 'thank-you', 'group' => 'Company'];

        return collect($routes)->unique('slug')->values()->all();
    }

    private function typeOf(string $slug, string $group): array
    {
        $type = self::GROUPS[$group] ?? 'standard';
        if (in_array($slug, self::BUSINESS_SIZE, true)) {
            $type = 'business_size';
        } elseif ($slug === 'by-industry') {
            $type = 'industry';
        } elseif (in_array($slug, self::CORE_POS_FROM_INDUSTRIES, true)) {
            $type = 'core_pos';
        } elseif (in_array($slug, ['frequently-asked-questions-multitech-pos', 'thank-you'], true)) {
            $type = 'standard';
        }
        $hub = self::HUBS[$type] ?? null;

        return ['page_type' => $type, 'parent' => $hub && $hub !== $slug ? $hub : null];
    }

    // ------------------------------------------------------------------ blade parsing

    /** Inline @section('name', expr) values before @section('content'). */
    private function sections(string $src): array
    {
        $head = Str::before($src, "@section('content')");
        $out = [];
        $offset = 0;
        while (preg_match("/@section\('([a-z_]+)',\s*/", $head, $m, PREG_OFFSET_CAPTURE, $offset)) {
            $start = $m[0][1] + strlen($m[0][0]);
            $expr = $this->balanced($head, $m[0][1] + strlen("@section"), '(', ')');
            $args = substr($expr, 1, -1);
            $value = trim(substr($args, strlen("'".$m[1][0]."',")));
            $out[$m[1][0]] = $this->evalPhp($value);
            $offset = $start;
        }

        return $out;
    }

    private function content(string $src): string
    {
        $body = Str::after($src, "@section('content')");

        return substr($body, 0, strrpos($body, '@endsection'));
    }

    /** Text from $pos (an opening bracket) to its matching close bracket, quotes respected. */
    private function balanced(string $s, int $pos, string $open, string $close): string
    {
        $depth = 0;
        $quote = null;
        for ($i = $pos, $n = strlen($s); $i < $n; $i++) {
            $c = $s[$i];
            if ($quote) {
                if ($c === '\\') {
                    $i++;
                } elseif ($c === $quote) {
                    $quote = null;
                }

                continue;
            }
            if ($c === "'" || $c === '"') {
                $quote = $c;
            } elseif ($c === $open) {
                $depth++;
            } elseif ($c === $close && --$depth === 0) {
                return substr($s, $pos, $i - $pos + 1);
            }
        }
        $this->problems[] = 'Unbalanced brackets near: '.substr($s, $pos, 80);

        return '';
    }

    private function evalPhp(string $expr, array $vars = []): mixed
    {
        $base = '';
        $current = '$current';
        extract($vars);

        return eval('return '.$expr.';');
    }

    /**
     * Content -> list of sections. Includes become component sections, the HTML between them
     * is split into top-level elements, forms become embedded quote-form components.
     */
    private function parse(string $slug, string $content, array &$faqs): array
    {
        $content = preg_replace('/\{\{--.*?--\}\}/s', '', $content);

        // forms -> markers (they stay code components)
        $content = preg_replace_callback('#<form class="form" action="\{\{ \$base \}\}/request-demo" method="post">.*?</form>#s', function ($m) use ($slug) {
            $variant = str_contains($m[0], 'placeholder="First Name"') ? 'names' : 'standard';
            if ($variant === 'standard' && ! str_contains($m[0], '<option>Demo Request</option>')) {
                $this->problems[] = "$slug: quote form with unknown options";
            }

            return '%%FORM:'.$variant.'%%';
        }, $content);

        $sections = [];
        $pos = 0;
        while (preg_match("/@include\('([^']+)'/", $content, $m, PREG_OFFSET_CAPTURE, $pos)) {
            $at = $m[0][1];
            $this->htmlSections(substr($content, $pos, $at - $pos), $slug, $sections);
            $call = $this->balanced($content, $at + strlen('@include'), '(', ')');
            $pos = $at + strlen('@include') + strlen($call);
            $name = $m[1][0];
            $args = trim(substr($call, 1, -1));
            $args = trim(substr($args, strlen("'$name'")), " \t\n\r,");
            $attrs = $args === '' ? [] : $this->evalPhp($args);

            $component = Str::after($name, 'partials.');
            if ($component === 'schema-faq') {
                foreach ($attrs['faqs'] as $faq) {
                    $faqs[] = ['q' => $this->swap($faq['q']), 'a' => $this->swap($faq['a'])];
                }
                $attrs = [];
            }
            if (! \App\Cms\Components::exists($component)) {
                $this->problems[] = "$slug: unknown include $name";
            }
            array_walk_recursive($attrs, fn (&$v) => $v = is_string($v) ? $this->swap($v) : $v);
            $sections[] = ['type' => 'component', 'component' => $component, 'attrs' => $attrs ?: null];
        }
        $this->htmlSections(substr($content, $pos), $slug, $sections);

        // keys, embeds
        $used = [];
        foreach ($sections as $i => &$s) {
            $s['key'] = $this->key($s, $used);
            $s['sort_order'] = $i;
            if ($s['type'] === 'html') {
                $s['embeds'] = null;
                $n = 0;
                $s['content'] = preg_replace_callback('/%%FORM:(\w+)%%/', function ($m) use (&$s, &$n) {
                    $s['embeds'][] = ['component' => 'quote-form', 'attrs' => $m[1] === 'standard' ? [] : ['variant' => $m[1]]];

                    return '{{embed:'.++$n.'}}';
                }, $s['content']);
                $this->checkLeftovers($slug, $s['content']);
            }
        }

        return $sections;
    }

    /** Split HTML into top-level elements; whitespace between them is dropped. */
    private function htmlSections(string $html, string $slug, array &$sections): void
    {
        $html = trim($html);
        if ($html === '') {
            return;
        }
        $pos = 0;
        $len = strlen($html);
        while ($pos < $len) {
            if (! preg_match('/<([a-z][a-z0-9]*)\b/i', $html, $m, PREG_OFFSET_CAPTURE, $pos)) {
                break;
            }
            $start = $m[0][1];
            $between = trim(substr($html, $pos, $start - $pos));
            if ($between !== '') {
                $this->problems[] = "$slug: text outside elements: ".substr($between, 0, 60);
            }
            $tag = strtolower($m[1][0]);
            $end = $this->closeOf($html, $start, $tag);
            $sections[] = ['type' => 'html', 'content' => $this->swap(substr($html, $start, $end - $start))];
            $pos = $end;
        }
        if (trim(substr($html, $pos)) !== '') {
            $this->problems[] = "$slug: trailing text: ".substr(trim(substr($html, $pos)), 0, 60);
        }
    }

    /** Offset just after the element that opens at $start (same-name nesting counted). */
    private function closeOf(string $html, int $start, string $tag): int
    {
        $depth = 0;
        preg_match_all('#<(/?)'.$tag.'\b[^>]*>#i', $html, $all, PREG_OFFSET_CAPTURE | PREG_SET_ORDER, $start);
        foreach ($all as $t) {
            if (str_ends_with($t[0][0], '/>')) {
                continue;
            }
            $depth += $t[1][0] === '/' ? -1 : 1;
            if ($depth === 0) {
                return $t[0][1] + strlen($t[0][0]);
            }
        }
        $this->problems[] = "unclosed <$tag> at $start";

        return strlen($html);
    }

    private function key(array $s, array &$used): string
    {
        if ($s['type'] === 'component') {
            $key = $s['component'];
        } else {
            preg_match('#<h[1-4][^>]*>(.*?)</h[1-4]>#s', $s['content'], $h);
            preg_match('#<p class="[^"]*title[^"]*"[^>]*>(.*?)</p>#s', $s['content'], $t);
            preg_match('#class="([a-z0-9_-]+)#', $s['content'], $c);
            $text = trim(html_entity_decode(strip_tags($h[1] ?? $t[1] ?? ''), ENT_QUOTES | ENT_HTML5));
            $key = Str::limit(Str::slug($text), 50, '') ?: ($c[1] ?? 'section');
        }
        $base = $key;
        $i = 2;
        while (isset($used[$key])) {
            $key = $base.'-'.$i++;
        }
        $used[$key] = true;

        return $key;
    }

    /** Blade left in an html section means the page needs a component: report it. */
    private function checkLeftovers(string $slug, string $html): void
    {
        $html = preg_replace('/\{\{(base|phone|phone_raw|email|whatsapp|wa_link|address|hours|service_areas|(?:setting|plan|download|embed):[a-z0-9_\-.]+)\}\}/', '', $html);
        if (preg_match('/\{\{|\{!!|@(?:if|foreach|php|include|csrf|isset|endif|section|push)\b/', $html, $m, PREG_OFFSET_CAPTURE)) {
            $this->problems[] = "$slug: Blade left in html: ".substr($html, max(0, $m[0][1] - 30), 90);
        }
        foreach (\App\Cms\Placeholders::validate($html, 99) as $e) {
            $this->problems[] = "$slug: $e";
        }
    }

    /**
     * Same text, single source: Blade expressions, contact details and stats become placeholders
     * that render exactly what was there.
     */
    private function swap(string $s): string
    {
        $s = str_replace(
            ['{{ $base }}', "{{ config('site.phone') }}", "{{ config('site.phone_display') }}", "{{ config('site.whatsapp') }}", "{{ config('site.email') }}"],
            ['{{base}}', '{{phone_raw}}', '{{phone}}', '{{wa_link}}', '{{email}}'],
            $s
        );
        $s = str_replace('https://wa.me/971588979925?text=Hi%20MultiTech%2C%20I%27d%20like%20a%20free%20POS%20demo', '{{wa_link}}', $s);
        $s = str_replace(['wa.me/971588979925', 'send?phone=971588979925'], ['wa.me/{{whatsapp}}', 'send?phone={{whatsapp}}'], $s);
        $s = str_replace(['tel:+971588979925', '>+971588979925<', '+971 58 897 9925', 'support@multitechpos.com'], ['tel:{{phone_raw}}', '>{{phone_raw}}<', '{{phone}}', '{{email}}'], $s);
        $s = str_replace('16 Al Isayfir 2 St - Musaffah - M33 - Abu Dhabi', '{{address}}', $s);
        // stats (only where they are the business claims)
        $s = preg_replace('/500\+(?= (?:UAE )?businesses)/', '{{setting:stat_businesses}}', $s);
        $s = preg_replace('/15\+(?= [Yy]ears)/', '{{setting:stat_years}}', $s);
        $s = preg_replace('/30\+(?= (?:other )?[Ii]ndustries)/', '{{setting:stat_industries}}', $s);
        $s = preg_replace('#24/7(?! submission)#', '{{setting:stat_support}}', $s);
        $s = preg_replace('/(?<=as little as |within |set up in |up in )48 hours/', '{{setting:stat_setup}}', $s);

        return $s;
    }

    // ------------------------------------------------------------------ pages

    private function page(string $slug, string $src): array
    {
        $meta = $this->sections($src);
        $faqs = [];
        $sections = $this->parse($slug, $this->content($src), $faqs);

        if ($faqs) {
            // the FAQ schema include was the first component: keep its place as a schema-faq section
            foreach ($sections as &$s) {
                if ($s['type'] === 'component' && $s['component'] === 'schema-faq') {
                    $s['attrs'] = null;
                }
            }
            unset($s);
        }
        // conversion script pushed from outside the content section (thank-you page)
        if (str_contains($src, "@push('scripts')") && str_contains($src, 'generate_lead')) {
            $sections[] = ['type' => 'component', 'component' => 'conversion-tracking', 'attrs' => null, 'key' => 'conversion-tracking', 'sort_order' => count($sections)];
        }
        if (isset(self::DOWNLOADS[$slug])) {
            $this->downloadPlaceholders($slug, $sections);
        }

        return [[
            'slug' => $slug,
            'name' => $this->nameOf($sections, $meta['title'] ?? $slug),
            'template' => 'page',
            'status' => 'published',
            'body_class' => $meta['body_class'] ?? null,
            'page_kind' => $meta['page_kind'] ?? null,
            'show_cro' => ! array_key_exists('no_cro', $meta),
            'seo' => $this->seo($meta),
            'sections' => $sections,
        ], $faqs];
    }

    private function seo(array $meta): array
    {
        return [
            'title' => $meta['title'] ?? null,
            'description' => $meta['description'] ?? null,
            'og_image' => $meta['og_image'] ?? null,
            'og_type' => $meta['og_type'] ?? null,
            'robots' => $meta['robots'] ?? null,
        ];
    }

    private function nameOf(array $sections, string $fallback): string
    {
        foreach ($sections as $s) {
            if ($s['type'] === 'component' && in_array($s['component'], ['page-hero'], true)) {
                return trim(html_entity_decode(strip_tags($s['attrs']['title']), ENT_QUOTES | ENT_HTML5));
            }
            if ($s['type'] === 'html' && preg_match('#<h1[^>]*>(.*?)</h1>#s', $s['content'], $m)) {
                return trim(html_entity_decode(strip_tags($m[1]), ENT_QUOTES | ENT_HTML5));
            }
        }

        return $fallback;
    }

    /** Home: its @php arrays become FAQs, form options and component attrs. */
    private function home(string $src): array
    {
        preg_match('/@php(.*?)@endphp/s', $src, $php);
        $code = preg_replace('/\$variant = .*?\n    \}\n/s', '', $php[1]);
        $code = str_replace("\$rating = config('site.google_rating');", '', $code);
        $vars = (function () use ($code) {
            eval($code);

            return get_defined_vars();
        })();
        $this->homeBusinesses = $vars['businesses'];

        $meta = $this->sections($src);
        $faqs = array_map(fn ($f) => ['q' => $this->swap($f['q']), 'a' => $this->swap($f['a'])], $vars['faqs']);
        $content = $this->content($src);

        // hand-made sections: the blocks that were PHP loops become components
        $blocks = preg_split('/\n(?=\{\{-- )/', preg_replace('/^\s*@include\(\'partials\.schema-faq\', \[\'faqs\' => \$faqs\]\)\s*/', '', trim($content)));
        $sections = [['type' => 'component', 'component' => 'schema-faq', 'attrs' => null]];
        foreach ($blocks as $block) {
            preg_match('/^\{\{-- (.*?) --\}\}/', $block, $c);
            $label = $c[1] ?? '';
            $body = trim(preg_replace('/^\{\{--.*?--\}\}/', '', $block));
            $section = match (true) {
                $label === 'Hero' => ['type' => 'component', 'component' => 'home-hero', 'attrs' => $this->heroAttrs($body)],
                str_starts_with($label, 'Business picker') => ['type' => 'component', 'component' => 'business-picker', 'attrs' => $this->headAttrs($body)],
                str_starts_with($label, 'Plans') => ['type' => 'component', 'component' => 'plans', 'attrs' => $this->headAttrs($body) + ['note' => $this->between($body, '<p class="plans__note" data-reveal><i class="fas fa-info-circle"></i> ', '</p>')]],
                str_starts_with($label, 'FAQ') => ['type' => 'component', 'component' => 'home-faq', 'attrs' => $this->faqHeadAttrs($body)],
                str_starts_with($body, '@include(') => null,
                default => ['type' => 'html', 'content' => $this->swap($body)],
            };
            if ($section === null) {
                preg_match("/@include\('partials\.([^']+)'\)/", $body, $inc);
                $section = ['type' => 'component', 'component' => $inc[1], 'attrs' => null];
            }
            $sections[] = $section;
        }
        $used = [];
        foreach ($sections as $i => &$s) {
            $s['key'] = $s['type'] === 'component' ? $this->key($s, $used) : $this->key($s, $used);
            $s['sort_order'] = $i;
            if ($s['type'] === 'html') {
                $s['embeds'] = null;
                $this->checkLeftovers('home', $s['content']);
            }
        }

        return [[
            'slug' => '',
            'name' => 'Home',
            'template' => 'page',
            'status' => 'published',
            'body_class' => null,
            'page_kind' => $meta['page_kind'] ?? 'home',
            'show_cro' => true,
            'seo' => $this->seo($meta),
            'sections' => $sections,
        ], $faqs];
    }

    private array $homeBusinesses = [];

    private function between(string $s, string $a, string $b): string
    {
        $from = strpos($s, $a);
        if ($from === false) {
            $this->problems[] = 'home: not found: '.$a;

            return '';
        }
        $from += strlen($a);

        return substr($s, $from, strpos($s, $b, $from) - $from);
    }

    private function headAttrs(string $body): array
    {
        return [
            'tag' => $this->between($body, '<span class="tag">', '</span>'),
            'heading' => $this->between($body, '<h2>', '</h2>'),
            'text' => html_entity_decode($this->between($body, '</h2>'."\n            <p>", '</p>'), ENT_QUOTES | ENT_HTML5),
        ];
    }

    private function faqHeadAttrs(string $body): array
    {
        return [
            'tag' => $this->between($body, '<span class="tag">', '</span>'),
            'heading' => $this->between($body, '<h2>', '</h2>'),
            'text' => html_entity_decode($this->between($body, '</h2>'."\n            <p>", '</p>'), ENT_QUOTES | ENT_HTML5),
        ];
    }

    private function heroAttrs(string $body): array
    {
        preg_match_all('#<li><i class="fas fa-check"></i> (.*?)</li>#', $body, $checks);
        preg_match('#<ul class="hero-trust" data-reveal>(.*?)</ul>#s', $body, $trustUl);
        preg_match_all('#<li><i class="([^"]+)"></i> (.*?)</li>#', $trustUl[1], $trust, PREG_SET_ORDER);
        preg_match_all('#<span class="float-card__icon[^"]*"><i class="([^"]+)"></i></span>\s*<span><strong>(.*?)</strong><small>(.*?)</small></span>#', $body, $floats, PREG_SET_ORDER);
        $d = fn ($s) => html_entity_decode($s, ENT_QUOTES | ENT_HTML5);

        return [
            'pill' => $this->swap($d($this->between($body, '<span class="pill__dot"></span> ', '</span>'))),
            'title' => $this->between($body, "@else\n                <h1 data-reveal>", '</h1>'),
            'title_b' => $this->between($body, "@if (\$variant === 'b')\n                <h1 data-reveal>", '</h1>'),
            'lead' => $d($this->between($body, '<p class="lead" data-reveal>', '</p>')),
            'checks' => array_map($d, $checks[1]),
            'button_demo' => $d($this->between($body, 'data-focus-form>', ' <i')),
            'button_whatsapp' => $d($this->between($body, '<i class="fab fa-whatsapp"></i> ', '</a>')),
            'trust' => array_map(fn ($t) => [$t[1], $d($t[2])], $trust),
            'form_eyebrow' => $d($this->between($body, '<p class="hero-form__eyebrow"><i class="fas fa-bolt"></i> ', '</p>')),
            'form_title' => $d($this->between($body, '<h2 class="hero-form__title">', '</h2>')),
            'form_text' => $d($this->between($body, '<p class="hero-form__text">', '</p>')),
            'button' => 'Get My Free Demo',
            'button_b' => 'Get a Free Demo & Quote',
            'form_note' => $this->between($body, '<p class="hero-form__note"><i class="fas fa-lock"></i> ', '</p>'),
            'floats' => array_map(fn ($f) => [$f[1], $d($f[2]), $d($f[3])], $floats),
        ];
    }

    // ------------------------------------------------------------------ posts

    private function post(string $slug, string $src, ?array $archive, ?array $card): array
    {
        $meta = $this->sections($src);
        preg_match("/@include\('partials\.schema-article', (\[.*?\])\)\n/", $src, $a);
        $article = $this->evalPhp($a[1]);
        preg_match('#<div class="post__content( post__content--narrow)? prose">\n(.*)\n    </div>\n    @include\(\'partials\.post-cta\'\)#s', $src, $c);
        preg_match('#<a href="\{\{ \$base \}\}(/[^"]+/)" rel="prev"><span>&larr; Previous</span>(.*?)</a>#', $src, $prevLink);
        preg_match('#<h1>(.*?)</h1>#', $src, $h1);
        preg_match('#<div class="post__meta">By <a href="\{\{ \$base \}\}/author/multitech_admin/">([^<]+)</a> / ([^<]+)</div>#', $src, $pm);
        preg_match('#href="\{\{ \$base \}\}/([^"]+)/" rel="prev"#', $src, $prev);
        preg_match('#href="\{\{ \$base \}\}/([^"]+)/" rel="next"#', $src, $next);

        if (! isset($c[2])) {
            $this->problems[] = "post $slug: content not found";
        }
        $title = html_entity_decode($article['headline'], ENT_QUOTES | ENT_HTML5);
        if (e($title) !== $h1[1]) {
            $this->problems[] = "post $slug: H1 '{$h1[1]}' differs from headline";
        }
        if (date('F j, Y', strtotime($article['published'])) !== $pm[2]) {
            $this->problems[] = "post $slug: date '{$pm[2]}' differs from {$article['published']}";
        }
        $content = $this->swap($c[2] ?? '');
        $this->checkLeftovers("post $slug", $content);

        return [
            'slug' => $slug,
            'title' => $title,
            'excerpt' => $archive['excerpt'] ?? $card['excerpt'] ?? $meta['description'] ?? null,
            'content' => $content,
            'cover_image' => $article['image'],
            'card_image' => $card['image'] ?? null,
            'archive_image' => $archive['image'] ?? null,
            'author_name' => $article['author'],
            'status' => 'published',
            'published_date' => $article['published'],
            'prev' => $prev[1] ?? null,
            'next' => $next[1] ?? null,
            'categories' => array_filter([$archive['category'] ?? null]),
            'options' => [
                'narrow' => ($c[1] ?? '') !== '' ? true : null,
                'nav_prev_link' => $prevLink ? ['path' => $prevLink[1], 'title' => html_entity_decode($prevLink[2], ENT_QUOTES | ENT_HTML5)] : null,
            ],
            'seo' => $this->seo($meta),
        ];
    }

    /**
     * Publish times so that "previous" = older and "next" = newer reproduces the
     * existing post links exactly (posts on the same day are ordered along the chain).
     */
    private function orderPosts(array &$posts): void
    {
        $bySlug = collect($posts)->keyBy('slug');
        // the newest post has no "next" link
        $chain = [];
        $current = collect($posts)->first(fn ($p) => $p['next'] === null);
        while ($current && count($chain) < count($posts)) {
            $chain[] = $current['slug'];
            $current = $current['prev'] ? $bySlug->get($current['prev']) : null;
        }
        $chain = array_reverse($chain); // oldest first
        if (count($chain) !== count($posts)) {
            $this->problems[] = 'posts: prev/next links do not form one chain ('.count($chain).' of '.count($posts).')';
        }
        $order = array_flip($chain);
        foreach ($posts as &$p) {
            $i = $order[$p['slug']] ?? 0;
            $p['published_at'] = $p['published_date'].' '.sprintf('09:%02d:%02d', intdiv($i, 60), $i % 60);
            $p['chain_position'] = $i;
        }
        unset($p);
        // check: sorting by published_at must follow the chain
        $sorted = collect($posts)->sortBy('published_at')->pluck('slug')->values()->all();
        if ($sorted !== $chain) {
            $this->problems[] = 'posts: the chain order does not match the publish dates; post-nav will differ';
        }
        foreach ($posts as &$p) {
            // the oldest post links back to a page: keep that link
            if ($p['prev'] && ! $bySlug->has($p['prev'])) {
                $p['options']['nav_prev'] = $p['options']['nav_prev_link'];
            }
            unset($p['options']['nav_prev_link']);
            $p['options'] = array_filter($p['options']) ?: null;
        }
        unset($p);
    }

    private function archiveCards(string $src): array
    {
        preg_match_all('#<article class="archive-card">\s*<a href="\{\{ \$base \}\}/([^"]+)/" class="archive-card__img"><img src="\{\{ \$base \}\}([^"]+)"[^>]*></a>.*?<span class="archive-card__cat">([^<]*)</span>.*?<p class="archive-card__excerpt">(.*?)</p>#s', $src, $m, PREG_SET_ORDER);

        return collect($m)->mapWithKeys(fn ($x) => [$x[1] => ['image' => $x[2], 'category' => html_entity_decode($x[3], ENT_QUOTES | ENT_HTML5), 'excerpt' => html_entity_decode($x[4], ENT_QUOTES | ENT_HTML5)]])->all();
    }

    private function blogCards(string $src): array
    {
        preg_match_all('#<article class="post-card">\s*<a href="\{\{ \$base \}\}/([^"]+)/" class="post-card__img"><img src="\{\{ \$base \}\}([^"]+)"[^>]*></a>.*?<p class="post-card__excerpt">(.*?)</p>#s', $src, $m, PREG_SET_ORDER);

        return collect($m)->mapWithKeys(fn ($x) => [$x[1] => ['image' => $x[2], 'excerpt' => html_entity_decode($x[3], ENT_QUOTES | ENT_HTML5)]])->all();
    }

    // ------------------------------------------------------------------ business content

    private function clientLogos(): array
    {
        $src = File::get(base_path('database/content/legacy-clients-band.blade.php.txt')); // the partial before logos moved to the DB
        preg_match_all("/\['([a-z-]+)', '([^']+)'\]/", $src, $m, PREG_SET_ORDER);

        return collect($m)->values()->map(fn ($x, $i) => [
            'name' => $x[2],
            'image' => '/images/elementor/thumbs/'.$x[1].'-rh05mno1leaub84suaggl2evvu5jrlol0v4bw7fsyk.jpg',
            'sort_order' => $i,
        ])->all();
    }

    private function plans(): array
    {
        $src = File::get(resource_path('views/index.blade.php'));
        preg_match_all('#<article class="plan( plan--featured)?" data-reveal data-tilt>(.*?)</article>#s', $src, $m, PREG_SET_ORDER);
        $d = fn ($s) => html_entity_decode($s, ENT_QUOTES | ENT_HTML5);

        return collect($m)->values()->map(function ($x, $i) use ($d) {
            preg_match('#<span class="plan__badge">(.*?)</span>#', $x[2], $badge);
            preg_match('#icon-tile--md icon-tile--([a-z]+)"><i class="([^"]+)"#', $x[2], $icon);
            preg_match('#<h3>(.*?)</h3>#', $x[2], $name);
            preg_match('#<p class="plan__for">(.*?)</p>#', $x[2], $for);
            preg_match_all('#<li><i class="fas fa-check-circle"></i> (.*?)</li>#', $x[2], $li);
            preg_match('#data-focus-form>(.*?)(?: <i|</a>)#', $x[2], $cta);

            return [
                'slug' => Str::slug($d($name[1])),
                'name' => $d($name[1]),
                'description' => $d($for[1]),
                'inclusions' => array_map($d, $li[1]),
                'price' => null,
                'badge' => isset($badge[1]) ? $d($badge[1]) : null,
                'tile' => $icon[1],
                'icon' => $icon[2],
                'cta_label' => $d($cta[1]),
                'is_featured' => $x[1] !== '',
                'sort_order' => $i,
            ];
        })->all();
    }

    private function downloads(): array
    {
        $out = [];
        foreach (self::DOWNLOADS as $slug => $name) {
            $src = File::get(resource_path("views/$slug.blade.php"));
            preg_match('#href="([^"]+)" class="btn btn--sm btn--lg"[^>]*>Download #', $src, $url);
            preg_match('#<div class="stack justify-center">\s*<ul[^>]*>\s*<li>([^<]+)</li>\s*</ul>\s*<ul[^>]*>\s*<li>[^<]+</li>\s*</ul>\s*<ul[^>]*>\s*<li>([^<]+)</li>#', $src, $table);
            preg_match('#(Windows 7[^<.]*)#', $src, $platform);
            $meta = $this->sections($src);
            $out[] = [
                'slug' => $slug,
                'name' => $name,
                'description' => $meta['description'] ?? null,
                'platform' => isset($platform[1]) ? trim($platform[1]) : null,
                'version' => $table[1] ?? null,
                'file_size' => $table[2] ?? null,
                'download_url' => str_replace('{{ $base }}', '', $url[1] ?? ''),
                'page' => $slug,
            ];
        }

        return $out;
    }

    /** Download pages: the button URL, version and size become {{download:slug...}} placeholders. */
    private function downloadPlaceholders(string $slug, array &$sections): void
    {
        $d = collect($this->downloads())->firstWhere('slug', $slug);
        foreach ($sections as &$s) {
            if ($s['type'] !== 'html') {
                continue;
            }
            $url = str_starts_with($d['download_url'], '/') ? '{{base}}'.$d['download_url'] : $d['download_url'];
            $s['content'] = str_replace('href="'.$url.'" class="btn btn--sm btn--lg"', 'href="'.(str_starts_with($d['download_url'], '/') ? '{{base}}' : '').'{{download:'.$slug.'}}" class="btn btn--sm btn--lg"', $s['content']);
            $s['content'] = preg_replace_callback('#(<div class="stack justify-center">\s*<ul[^>]*>\s*<li>)'.preg_quote($d['version'], '#').'(</li>\s*</ul>\s*<ul[^>]*>\s*<li>[^<]+</li>\s*</ul>\s*<ul[^>]*>\s*<li>)'.preg_quote($d['file_size'], '#').'(</li>)#',
                fn ($m) => $m[1].'{{download:'.$slug.'.version}}'.$m[2].'{{download:'.$slug.'.file_size}}'.$m[3], $s['content']);
        }
    }

    private function formOptions(): array
    {
        $out = [];
        foreach ($this->homeBusinesses as $i => $b) {
            $out[] = ['form' => 'business_types', 'value' => $b['name'], 'label' => $b['label'], 'sort_order' => $i,
                'meta' => ['icon' => $b['icon'], 'tile' => $b['tile'], 'url' => $b['url'], 'text' => $b['text'], 'points' => $b['points']]];
        }
        foreach (['1 branch', '2 to 5 branches', '6 or more'] as $i => $v) {
            $out[] = ['form' => 'builder_branches', 'value' => $v, 'label' => null, 'sort_order' => $i, 'meta' => null];
        }
        foreach (['1 counter', '2 to 3 counters', '4 or more'] as $i => $v) {
            $out[] = ['form' => 'builder_counters', 'value' => $v, 'label' => null, 'sort_order' => $i, 'meta' => null];
        }
        foreach (['POS Software', 'POS Hardware', 'Demo Request'] as $i => $v) {
            $out[] = ['form' => 'quote_service', 'value' => $v, 'label' => null, 'sort_order' => $i, 'meta' => null];
        }

        return $out;
    }

    /** Hardware named on the site (home FAQ, "Quality Hardware" card). Names only: no descriptions or prices invented. */
    private function hardware(): array
    {
        return collect(['Barcode scanners', 'Receipt printers', 'Cash drawers', 'Touchscreen terminals', 'POS terminals', 'Hardware bundles'])
            ->map(fn ($n, $i) => ['name' => $n, 'slug' => Str::slug($n), 'category' => 'POS hardware', 'description' => null, 'sort_order' => $i])
            ->all();
    }

    /** Every image used in the content, with the first alt text found for it. */
    private function media(array $out): array
    {
        $alts = [];
        $add = function (string $html) use (&$alts) {
            preg_match_all('#<img\s[^>]*>#i', $html, $imgs);
            foreach ($imgs[0] as $img) {
                if (preg_match('#src="\{\{base\}\}(/images/[^"]+)"#', $img, $src)) {
                    preg_match('#alt="([^"]*)"#', $img, $alt);
                    $a = html_entity_decode($alt[1] ?? '', ENT_QUOTES | ENT_HTML5);
                    if (! isset($alts[$src[1]]) || ($alts[$src[1]] === '' && $a !== '')) {
                        $alts[$src[1]] = $a;
                    }
                }
            }
        };
        foreach ($out['pages'] as $p) {
            foreach ($p['sections'] as $s) {
                if ($s['type'] === 'html') {
                    $add($s['content']);
                }
                foreach (['image'] as $k) {
                    if (isset($s['attrs'][$k])) {
                        $alts[$s['attrs'][$k]] ??= '';
                    }
                }
            }
            if ($p['seo']['og_image'] && str_starts_with($p['seo']['og_image'], '/images/')) {
                $alts[$p['seo']['og_image']] ??= '';
            }
        }
        foreach ($out['posts'] as $p) {
            $add($p['content']);
            foreach (['cover_image', 'card_image', 'archive_image'] as $k) {
                if ($p[$k]) {
                    $alts[$p[$k]] ??= '';
                }
            }
        }
        foreach ($out['client_logos'] as $l) {
            $alts[$l['image']] = $l['name'];
        }
        ksort($alts);
        $media = [];
        foreach ($alts as $path => $alt) {
            $file = public_path(ltrim($path, '/'));
            $info = File::exists($file) ? @getimagesize($file) : false;
            $media[] = [
                'path' => $path, 'filename' => basename($path), 'alt' => $alt,
                'mime' => $info['mime'] ?? null, 'size' => File::exists($file) ? File::size($file) : null,
                'width' => $info[0] ?? null, 'height' => $info[1] ?? null, 'missing' => ! File::exists($file),
            ];
        }

        return $media;
    }
}
