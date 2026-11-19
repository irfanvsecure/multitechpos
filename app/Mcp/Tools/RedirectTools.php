<?php

namespace App\Mcp\Tools;

use App\Cms\Cms;
use App\Cms\Settings;
use App\Cms\Sitemap;
use App\Http\Controllers\RobotsController;
use App\Mcp\Support\Activity;
use App\Mcp\Support\Guard;
use App\Mcp\Support\Redirects;
use App\Mcp\ToolError;
use App\Mcp\ToolSet;
use App\Models\Page;
use App\Models\Post;
use App\Models\Redirect;
use App\Models\Setting;

class RedirectTools extends ToolSet
{
    public function tools(): array
    {
        return [
            $this->read('list_redirects', 'Saved redirects (301/302) and 410s, with hit counts. WordPress pattern redirects (/feed/, /category/*, /wp-content/uploads/*) are code and not listed.', [
                'search' => self::s('Text in from/to'),
                'status_code' => self::i('301, 302 or 410'),
                'limit' => self::i('Default 100'),
                'offset' => self::i('Skip this many'),
            ], [], fn ($a) => $this->list($a)),

            $this->write('save_redirect', 'Create or change a redirect. 301 = moved for good, 302 = temporary, 410 = gone. Changing an existing one needs its expected_updated_at.', [
                'from_path' => self::s('Old path, e.g. /old-page/ (or /?p=123)'),
                'to_url' => self::s('New path (/new-page/) or https:// URL; empty for 410'),
                'status_code' => self::i('301 (default), 302 or 410', ['enum' => [301, 302, 410]]),
                'note' => self::s('Why (shown in list_redirects)'),
                'expected_updated_at' => self::s('Needed when the redirect already exists'),
            ], ['from_path'], fn ($a) => $this->save($a), true),

            $this->delete('delete_redirect', 'Remove a redirect (to the trash).', [
                'id' => self::i('Redirect id'),
                'expected_updated_at' => self::expected(),
            ], ['id', 'expected_updated_at'], fn ($a) => $this->remove($a)),

            $this->write('update_robots', 'Replace the robots.txt rules. The Sitemap line is added automatically. While SITE_NOINDEX is on, robots.txt says "Disallow: /" whatever is saved here.', [
                'content' => self::s('Full robots.txt rules'),
                'expected_updated_at' => self::s('updated_at from validate_sitemap_robots (robots.updated_at)'),
            ], ['content', 'expected_updated_at'], fn ($a) => $this->robots($a), true),

            $this->read('validate_sitemap_robots', 'Check robots.txt and sitemap.xml: SITE_NOINDEX status, sitemap URLs that redirect or are noindex, published pages missing from the sitemap, robots rules that block sitemap URLs.', [], [], fn () => $this->validate()),
        ];
    }

    public static function out(Redirect $r): array
    {
        return ['id' => $r->id, 'from_path' => $r->from_path, 'to_url' => $r->to_url, 'status_code' => $r->status_code, 'hits' => $r->hits,
            'last_hit_at' => $r->last_hit_at?->toIso8601String(), 'note' => $r->note, 'updated_at' => Guard::stamp($r)];
    }

    private function list(array $a): array
    {
        $q = Redirect::query()
            ->when($a['search'] ?? null, fn ($q, $v) => $q->where(fn ($q) => $q->where('from_path', 'like', "%$v%")->orWhere('to_url', 'like', "%$v%")))
            ->when($a['status_code'] ?? null, fn ($q, $v) => $q->where('status_code', $v));
        $total = (clone $q)->count();

        return ['total' => $total, 'redirects' => $q->orderBy('from_path')->offset((int) ($a['offset'] ?? 0))->limit(min(500, (int) ($a['limit'] ?? 100)))->get()->map(fn ($r) => self::out($r))->all()];
    }

    private function save(array $a): array
    {
        $from = Redirects::normalisePath($a['from_path']);
        $existing = Redirect::query()->where('from_path', $from)->first();
        if ($existing) {
            Guard::check($existing, $a['expected_updated_at'] ?? null);
        }
        $status = (int) ($a['status_code'] ?? 301);
        $before = $existing ? self::out($existing) : null;
        $r = Redirects::save($from, $a['to_url'] ?? null, $status, $a['note'] ?? null);
        $notes = [];
        $slug = trim(strtok($from, '?'), '/');
        if (! str_contains($from, '?') && (Page::query()->where('slug', $slug)->exists() || Post::query()->where('slug', $slug)->exists())) {
            $notes[] = "$from is a live page/post: the page is shown and this redirect is ignored until that page is deleted or moved.";
        }
        Activity::log($existing ? 'update' : 'create', $r, $before, self::out($r), "redirect $from", [$from]);

        return ['redirect' => self::out($r->fresh()), 'notes' => $notes];
    }

    private function remove(array $a): array
    {
        $r = $this->guard(Redirect::query()->find($a['id']), $a, 'redirect');
        $before = self::out($r);
        $r->delete();
        Activity::log('delete', $r, $before, null, 'redirect '.$r->from_path, [$r->from_path]);

        return ['deleted' => $before];
    }

    private function robots(array $a): array
    {
        $setting = Setting::query()->where('key', 'robots_txt')->first() ?? throw new ToolError('robots_txt setting missing; run cms:import.');
        Guard::check($setting, $a['expected_updated_at']);
        $content = str_replace("\r\n", "\n", trim(strip_tags($a['content'])));
        $content = preg_replace('/^\s*Sitemap:.*$/mi', '', $content);
        if (! preg_match('/^\s*User-agent:/mi', $content)) {
            throw new ToolError('robots.txt needs at least one "User-agent:" line.');
        }
        if (preg_match('/^User-agent:\s*\*\s*\n(?:(?!User-agent).*\n)*?Disallow:\s*\/\s*$/mi', $content."\n")) {
            throw new ToolError('These rules would block the whole site for every crawler. Pre-launch blocking is done with SITE_NOINDEX in .env, not here.');
        }
        $before = $setting->value;
        $setting->update(['value' => trim($content)]);
        Settings::forget();
        Activity::log('update', $setting, $before, $setting->value, 'robots.txt', ['/robots.txt']);

        return ['robots_txt' => RobotsController::body(rtrim((string) config('app.url'), '/')), 'updated_at' => Guard::stamp($setting->fresh()), 'site_noindex' => Cms::noindex()];
    }

    private function validate(): array
    {
        $site = rtrim((string) config('app.url'), '/');
        $entries = Sitemap::entries();
        $issues = [];
        $redirected = Redirect::query()->pluck('from_path')->flip();
        foreach ($entries as $e) {
            if (isset($redirected[$e['path']])) {
                $issues[] = ['url' => $e['path'], 'issue' => 'in the sitemap but also has a redirect (the page wins; remove the redirect or the page)'];
            }
        }
        $missing = [];
        $inSitemap = array_flip(array_column($entries, 'path'));
        foreach (Page::query()->where('status', 'published')->with('seo')->get() as $p) {
            if (! isset($inSitemap[$p->path])) {
                $missing[] = ['url' => $p->path, 'why' => $p->seo?->exclude_from_sitemap ? 'excluded_from_sitemap' : 'robots: '.$p->seo?->robots];
            }
        }
        foreach (Post::query()->live()->with('seo')->get() as $p) {
            if (! isset($inSitemap[$p->path])) {
                $missing[] = ['url' => $p->path, 'why' => $p->seo?->exclude_from_sitemap ? 'excluded_from_sitemap' : 'robots: '.$p->seo?->robots];
            }
        }
        $rules = (string) Settings::get('robots_txt');
        $disallow = [];
        if (preg_match('/User-agent:\s*\*\s*\n((?:(?!User-agent).*\n?)*)/i', $rules, $g)) {
            preg_match_all('/^Disallow:\s*(\S+)/mi', $g[1], $d);
            $disallow = $d[1];
        }
        foreach ($entries as $e) {
            foreach ($disallow as $rule) {
                if ($rule !== '' && str_starts_with($e['path'], $rule)) {
                    $issues[] = ['url' => $e['path'], 'issue' => "blocked by robots.txt rule Disallow: $rule"];
                }
            }
        }
        $setting = Setting::query()->where('key', 'robots_txt')->first();

        return [
            'site_noindex' => Cms::noindex()
                ? 'ON: every page says noindex and robots.txt is "Disallow: /". Search engines will not index the site until the owner sets SITE_NOINDEX=false in .env.'
                : 'off',
            'robots' => ['served' => RobotsController::body($site), 'saved_rules' => $rules, 'updated_at' => Guard::stamp($setting)],
            'sitemap' => ['url' => $site.'/sitemap.xml', 'urls' => count($entries)],
            'published_but_not_in_sitemap' => $missing,
            'issues' => $issues,
        ];
    }
}
