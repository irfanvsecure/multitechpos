<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

/**
 * Saves the HTML (plus status and Location) of every public URL, so a refactor
 * can be compared with `site:diff`. Needs the site running, e.g.
 *   php artisan serve --port=8010
 *   php artisan site:snapshot storage/app/snapshots/baseline --base=http://127.0.0.1:8010
 */
class SiteSnapshot extends Command
{
    protected $signature = 'site:snapshot {dir} {--base=http://127.0.0.1:8010} {--only=}';

    protected $description = 'Save the rendered HTML of every public URL for a before/after diff';

    /** URLs that are not pages but must keep behaving the same. */
    public const EXTRA = [
        '/sitemap.xml', '/robots.txt', '/feed/', '/blog/', '/category/retail/', '/wp-sitemap.xml',
        '/sitemap_index.xml', '/download/restropro/', '/wp-content/uploads/2026/06/retail-pos-multitech.webp',
        '/index.php/features/', '/about-us', '/no-such-page-xyz/', '/?item/8109777586',
        '/author/multitech_admin/page/2/',
    ];

    public function handle(): int
    {
        $dir = base_path($this->argument('dir'));
        File::ensureDirectoryExists($dir);
        $base = rtrim($this->option('base'), '/');
        $only = array_filter(explode(',', (string) $this->option('only')));

        $urls = $only ?: array_merge(self::pageUrls(), self::EXTRA);
        $index = [];

        foreach ($urls as $url) {
            $res = Http::withoutRedirecting()->timeout(60)->get($base.$url);
            $file = self::fileFor($url);
            File::put($dir.'/'.$file, $res->body());
            $index[$url] = [
                'file' => $file,
                'status' => $res->status(),
                'location' => str_replace($base, '', (string) $res->header('Location')),
                'content_type' => $res->header('Content-Type'),
            ];
            $this->line($res->status().'  '.$url);
        }

        if ($only && File::exists($dir.'/index.json')) {
            $index = array_merge(json_decode(File::get($dir.'/index.json'), true), $index);
        }
        File::put($dir.'/index.json', json_encode($index, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        $this->info(count($urls).' URLs saved to '.$dir);

        return self::SUCCESS;
    }

    /** Every public page URL: the GET routes without parameters, plus pages in the database. */
    public static function pageUrls(): array
    {
        $urls = [];
        foreach (Route::getRoutes() as $route) {
            $uri = $route->uri();
            if (! in_array('GET', $route->methods(), true) || str_contains($uri, '{')) {
                continue;
            }
            if (in_array($uri, ['up', 'sitemap.xml', 'wp-sitemap.xml', 'sitemap_index.xml', 'page-sitemap.xml', 'post-sitemap.xml', 'feed', 'comments/feed', 'blog', 'download/restropro', 'robots.txt', 'mcp'], true)) {
                continue;
            }
            $urls[] = $uri === '/' ? '/' : '/'.trim($uri, '/').'/';
        }

        if (class_exists(\App\Models\Page::class)) {
            try {
                foreach (\App\Models\Page::query()->pluck('path') as $path) {
                    $urls[] = $path;
                }
                foreach (\App\Models\Post::query()->pluck('slug') as $slug) {
                    $urls[] = '/'.$slug.'/';
                }
            } catch (\Throwable) {
                // tables not migrated yet
            }
        }

        $urls = array_values(array_unique($urls));
        sort($urls);

        return $urls;
    }

    public static function fileFor(string $url): string
    {
        $name = trim(preg_replace('/[^a-z0-9]+/i', '_', $url), '_');

        return ($name === '' ? 'home' : $name).'.html';
    }
}
