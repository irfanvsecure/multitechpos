<?php

namespace App\Mcp\Tools;

use App\Cms\Cms;
use App\Cms\Components;
use App\Cms\Placeholders;
use App\Cms\Settings;
use App\Cms\Sitemap;
use App\Mcp\Registry;
use App\Mcp\Server;
use App\Mcp\Support\Activity;
use App\Mcp\Support\Html;
use App\Mcp\Support\Render;
use App\Mcp\ToolError;
use App\Mcp\ToolSet;
use App\Models\ActivityLog;
use App\Models\Media;
use App\Models\Page;
use App\Models\Post;
use App\Models\Redirect;
use App\Models\Setting;
use Illuminate\Support\Facades\File;

class SiteTools extends ToolSet
{
    public function tools(): array
    {
        return [
            $this->read('get_site_info', 'Overview: site URL, versions, SITE_NOINDEX status, Cloudflare purge status, content counts, last change.', [], [], fn () => $this->info()),

            $this->read('get_settings', 'All site settings (contact details, stats, robots.txt ...) grouped. Returns settings_updated_at for update_settings.', [
                'group' => self::e('Only one group', ['company', 'contact', 'brand', 'stats', 'seo']),
            ], [], fn ($a) => $this->settings($a['group'] ?? null)),

            $this->write('update_settings', 'Change settings (single source: phone, WhatsApp, email, address, hours, service areas, social links, stats ...). Pass expected_updated_at = settings_updated_at from get_settings. Never invent stats.', [
                'values' => self::o('key => new value. social_links takes a list of URLs.'),
                'expected_updated_at' => self::expected(),
            ], ['values', 'expected_updated_at'], fn ($a) => $this->updateSettings($a), true),

            $this->read('list_components', 'Code components a section can use (with their attrs) and the allowed placeholders.', [], [], fn () => [
                'components' => Components::describe(),
                'placeholders' => Placeholders::help(),
            ]),

            $this->read('activity_log', 'Recent changes made through the MCP, newest first, with before/after values.', [
                'limit' => self::i('Default 30, max 200'),
                'tool' => self::s('Only this tool'),
                'subject_type' => self::s('e.g. Page, PageSection, Post, Setting'),
                'subject_id' => self::i('Only this record'),
                'with_values' => self::b('Include before/after values (default true)'),
            ], [], fn ($a) => $this->activity($a)),

            $this->write('clear_cache', 'Clear the site caches (pages, settings, sitemap) and purge Cloudflare when configured. Normally not needed: every write clears the cache.', [
                'urls' => self::a('Only these URLs on Cloudflare (default: everything)'),
            ], [], fn ($a) => Cms::flush($a['urls'] ?? []), true),

            $this->read('audit_performance', 'Page weight check: HTML size, images (bytes, missing width/height, not lazy, not WebP), external scripts/styles, JSON-LD size. Without page_id: the 15 heaviest pages.', [
                'page_id' => self::i('One page'),
                'post_id' => self::i('One blog post'),
            ], [], fn ($a) => $this->performance($a)),
        ];
    }

    private function info(): array
    {
        $last = ActivityLog::query()->latest('id')->first();

        return [
            'site' => Settings::get('site_name'),
            'url' => rtrim((string) config('app.url'), '/'),
            'environment' => app()->environment(),
            'laravel' => app()->version(),
            'php' => PHP_VERSION,
            'database' => config('database.default'),
            'cache_store' => config('cache.default'),
            'site_noindex' => Cms::noindex()
                ? 'ON: every page outputs noindex and robots.txt disallows all (pre-launch). Only the owner can switch it off in .env (SITE_NOINDEX=false).'
                : 'off: pages follow their own robots setting',
            'cloudflare_purge' => Cms::cloudflareConfigured() ? 'configured' : 'not configured',
            'mcp' => ['server' => Server::NAME.' '.Server::VERSION, 'tools' => count(Registry::tools())],
            'counts' => [
                'pages' => Page::query()->count(),
                'pages_by_type' => Page::query()->selectRaw('page_type, count(*) n')->groupBy('page_type')->pluck('n', 'page_type'),
                'blog_posts' => Post::query()->count(),
                'published_posts' => Post::query()->live()->count(),
                'images' => Media::query()->count(),
                'redirects' => Redirect::query()->count(),
                'sitemap_urls' => count(Sitemap::entries()),
            ],
            'last_change' => $last ? ['at' => $last->created_at?->toIso8601String(), 'tool' => $last->tool, 'summary' => $last->summary] : null,
        ];
    }

    private function settings(?string $group): array
    {
        $rows = Setting::query()->when($group, fn ($q) => $q->where('group', $group))->orderBy('group')->orderBy('id')->get();
        $out = [];
        foreach ($rows as $s) {
            $out[$s->group][$s->key] = [
                'value' => $s->type === 'json' ? json_decode((string) $s->value, true) : $s->value,
                'label' => $s->label,
                'type' => $s->type,
            ];
        }

        return [
            'settings' => $out,
            'settings_updated_at' => Setting::query()->max('updated_at') ? \Carbon\Carbon::parse(Setting::query()->max('updated_at'))->toIso8601String() : null,
            'note' => 'Placeholders in page text read these: {{phone}}=phone_display, {{phone_raw}}=phone, {{whatsapp}}=whatsapp_number, {{wa_link}}, {{email}}, {{address}}, {{hours}}, {{service_areas}}, {{setting:key}}.',
        ];
    }

    private function updateSettings(array $a): array
    {
        $current = Setting::query()->max('updated_at');
        $stamp = $current ? \Carbon\Carbon::parse($current) : null;
        if (! $a['expected_updated_at'] || ($stamp && \Carbon\Carbon::parse($a['expected_updated_at'])->getTimestamp() !== $stamp->getTimestamp())) {
            throw new ToolError('Settings changed since you read them. Call get_settings again.', ['settings_updated_at' => $stamp?->toIso8601String()]);
        }
        $changed = [];
        foreach ($a['values'] as $key => $value) {
            $setting = Setting::query()->where('key', $key)->first();
            if (! $setting) {
                throw new ToolError("Unknown setting '$key'. Use get_settings.");
            }
            if ($key === 'robots_txt') {
                throw new ToolError('Use update_robots for robots.txt.');
            }
            if ($setting->type === 'json') {
                if (! is_array($value)) {
                    throw new ToolError("$key needs a list.");
                }
                foreach ($value as $url) {
                    if (! filter_var($url, FILTER_VALIDATE_URL) || ! str_starts_with($url, 'https://')) {
                        throw new ToolError("$key: '$url' is not an https URL.");
                    }
                }
                $value = json_encode(array_values($value), JSON_UNESCAPED_SLASHES);
            } else {
                if (! is_scalar($value)) {
                    throw new ToolError("$key needs text.");
                }
                $value = trim(strip_tags((string) $value));
            }
            if ($key === 'wa_link' && ! str_starts_with($value, 'https://wa.me/') && ! str_starts_with($value, 'https://api.whatsapp.com/')) {
                throw new ToolError('wa_link must be a https://wa.me/... link.');
            }
            if ($key === 'email' && ! filter_var($value, FILTER_VALIDATE_EMAIL)) {
                throw new ToolError('Not a valid email address.');
            }
            if ($setting->value === $value) {
                continue;
            }
            $before = $setting->value;
            $setting->update(['value' => $value]);
            $changed[$key] = ['before' => $before, 'after' => $value];
            Activity::log('update', $setting, $before, $value, "setting $key");
        }

        Settings::forget();

        return [
            'changed' => $changed,
            'settings_updated_at' => \Carbon\Carbon::parse(Setting::query()->max('updated_at'))->toIso8601String(),
            'note' => $changed ? 'Every page showing these values now shows the new ones.' : 'Nothing changed.',
        ];
    }

    private function activity(array $a): array
    {
        $with = $a['with_values'] ?? true;
        $rows = ActivityLog::query()
            ->when($a['tool'] ?? null, fn ($q, $v) => $q->where('tool', $v))
            ->when($a['subject_type'] ?? null, fn ($q, $v) => $q->where('subject_type', $v))
            ->when($a['subject_id'] ?? null, fn ($q, $v) => $q->where('subject_id', $v))
            ->latest('id')->limit(min(200, (int) ($a['limit'] ?? 30)))->get();

        return $rows->map(fn ($r) => array_filter([
            'id' => $r->id, 'at' => $r->created_at?->toIso8601String(), 'tool' => $r->tool, 'action' => $r->action,
            'subject' => $r->subject_type ? $r->subject_type.' #'.$r->subject_id : null, 'summary' => $r->summary,
            'before' => $with ? $r->before : null, 'after' => $with ? $r->after : null,
        ], fn ($v) => $v !== null))->all();
    }

    private function performance(array $a): array
    {
        if (! empty($a['page_id']) || ! empty($a['post_id'])) {
            return $this->weigh($this->findOwner($a));
        }
        $all = [];
        foreach (Page::query()->where('status', 'published')->get() as $p) {
            $all[] = $this->weigh($p, true);
        }
        foreach (Post::query()->live()->get() as $p) {
            $all[] = $this->weigh($p, true);
        }
        usort($all, fn ($x, $y) => $y['total_kb'] <=> $x['total_kb']);
        $css = collect(File::glob(public_path('css/*.css')))->mapWithKeys(fn ($f) => [basename($f) => round(filesize($f) / 1024).' KB']);
        $js = collect(File::glob(public_path('js/*.js')))->mapWithKeys(fn ($f) => [basename($f) => round(filesize($f) / 1024).' KB']);

        return ['heaviest' => array_slice($all, 0, 15), 'assets' => ['css' => $css, 'js' => $js], 'pages_checked' => count($all),
            'note' => 'Static check of the rendered HTML; it does not run Lighthouse.'];
    }

    private function weigh($owner, bool $brief = false): array
    {
        $html = Render::full($owner);
        $main = Render::main($html);
        preg_match_all('#<img\s[^>]*>#i', $main, $imgs);
        $bytes = 0;
        $issues = [];
        foreach ($imgs[0] as $i => $img) {
            preg_match('#src="([^"]+)"#', $img, $src);
            $path = isset($src[1]) ? parse_url($src[1], PHP_URL_PATH) : null;
            $file = $path ? public_path(ltrim(rawurldecode($path), '/')) : null;
            $size = $file && is_file($file) ? filesize($file) : 0;
            $bytes += $size;
            $p = [];
            if (! str_contains($img, 'width=') || ! str_contains($img, 'height=')) {
                $p[] = 'no width/height';
            }
            if ($i > 0 && ! str_contains($img, 'loading="lazy"')) {
                $p[] = 'not lazy';
            }
            if ($size > 300 * 1024) {
                $p[] = round($size / 1024).' KB';
            }
            if ($path && ! preg_match('/\.(webp|avif|svg)$/i', $path)) {
                $p[] = 'not WebP';
            }
            if ($p) {
                $issues[] = ['src' => $path, 'issues' => $p];
            }
        }
        preg_match_all('#<script[^>]+src="(https?://[^"]+)"#', $html, $ext);
        preg_match_all('#<link[^>]+rel="stylesheet"[^>]+href="(https?://[^"]+)"#', $html, $extCss);
        preg_match_all('#<script type="application/ld\+json">(.*?)</script>#s', $html, $ld);
        $row = [
            'url' => $owner->path,
            'html_kb' => round(strlen($html) / 1024, 1),
            'images' => count($imgs[0]),
            'image_kb' => round($bytes / 1024),
            'total_kb' => round((strlen($html) + $bytes) / 1024),
            'image_issues' => $brief ? count($issues) : $issues,
            'external_scripts' => $ext[1],
            'external_styles' => $extCss[1],
            'json_ld_kb' => round(strlen(implode('', $ld[1])) / 1024, 1),
            'words' => Html::words(Html::text($main)),
        ];

        return $row;
    }
}
