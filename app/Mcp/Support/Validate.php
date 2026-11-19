<?php

namespace App\Mcp\Support;

use App\Cms\Components;
use App\Cms\Placeholders;
use App\Cms\Renderer;
use App\Cms\Sanitizer;
use App\Mcp\ToolError;
use App\Models\Page;
use App\Models\Post;
use Illuminate\Database\Eloquent\Model;

class Validate
{
    /** Paths used by the app itself. */
    public const RESERVED = ['mcp', 'request-demo', 'sitemap.xml', 'robots.txt', 'feed', 'blog', 'category', 'tag', 'author',
        'wp-content', 'wp-admin', 'wp-json', 'images', 'css', 'js', 'fonts', 'up', 'storage', 'public', 'vendor', 'index.php', 'download'];

    public static function slug(string $slug, ?Model $ignore = null): string
    {
        if (! preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $slug)) {
            throw new ToolError("Invalid slug '$slug': use lowercase letters, digits and single hyphens (e.g. retail-pos-software-uae).");
        }
        if (in_array($slug, self::RESERVED, true)) {
            throw new ToolError("The slug '$slug' is reserved by the site.");
        }
        $page = Page::withTrashed()->where('slug', $slug)->first();
        $post = Post::withTrashed()->where('slug', $slug)->first();
        foreach ([$page, $post] as $hit) {
            if ($hit && ! ($ignore && $hit::class === $ignore::class && $hit->getKey() === $ignore->getKey())) {
                throw new ToolError("The URL /$slug/ is already used by ".($hit instanceof Page ? 'page' : 'blog post').' #'.$hit->getKey().($hit->trashed() ? ' (in the trash)' : '').'.');
            }
        }

        return $slug;
    }

    /**
     * Sanitize + check placeholders.
     *
     * @return array{0:string,1:list<string>} [clean html, removed]
     */
    public static function html(?string $html, ?int $embedCount = null): array
    {
        [$clean, $removed] = Sanitizer::clean((string) $html);
        if ($errors = Placeholders::validate($clean, $embedCount)) {
            throw new ToolError('Placeholder problem: '.implode(' ', $errors), ['allowed' => Placeholders::help()]);
        }

        return [$clean, $removed];
    }

    /** Validate component name + attrs; sanitize string attrs. */
    public static function component(string $name, ?array $attrs, bool $asEmbed = false): array
    {
        if ($errors = Components::validate($name, $attrs, $asEmbed)) {
            throw new ToolError(implode(' ', $errors));
        }
        $removed = [];
        array_walk_recursive($attrs, function (&$v) use (&$removed) {
            if (is_string($v)) {
                [$v, $r] = Sanitizer::clean($v);
                $removed = array_merge($removed, $r);
            }
        });

        return [$attrs ?? [], $removed];
    }

    /** @param list<array{component:string,attrs?:array}>|null $embeds */
    public static function embeds(?array $embeds): array
    {
        $out = [];
        foreach ($embeds ?? [] as $e) {
            if (! is_array($e) || empty($e['component'])) {
                throw new ToolError('Each embed needs {"component": name, "attrs": {...}}.');
            }
            [$attrs] = self::component($e['component'], $e['attrs'] ?? [], true);
            $out[] = ['component' => $e['component'], 'attrs' => $attrs];
        }

        return $out;
    }

    /**
     * Exactly one H1 per published page. A change may not leave a published page with
     * zero or several H1s (unless the count was already wrong and does not change).
     */
    public static function h1(Page $page, iterable $sectionsAfter, ?int $before = null): void
    {
        $after = Renderer::h1Count($sectionsAfter);
        $before ??= Renderer::h1Count($page->sections()->get());
        if ($after === 1 || $after === $before) {
            return;
        }
        if ($after > 1) {
            throw new ToolError("This change would give the page $after H1 headings. A page must have exactly one H1 (use <h2> for section headings).");
        }
        if ($page->status === 'published') {
            throw new ToolError('This change would leave the published page without an H1. Every page needs exactly one H1 (usually the page-hero title).');
        }
    }

    public static function noSelfServingReviews(mixed $data): void
    {
        $json = strtolower(json_encode($data));
        if (preg_match('/"@type"\s*:\s*"(aggregaterating|review|rating)"|"(aggregaterating|review|reviews|reviewrating)"\s*:/', $json)) {
            throw new ToolError('AggregateRating / Review structured data is not allowed for MultiTech POS itself or its products (Google treats it as self-serving and the site has no reviews to back it).');
        }
    }

    /** No installers, archives or scripts, by name or URL. */
    public static function notExecutable(string $nameOrUrl): void
    {
        $path = strtolower((string) (parse_url($nameOrUrl, PHP_URL_PATH) ?? $nameOrUrl));
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        if ($ext !== '' && in_array($ext, config('cms.blocked_extensions'), true)) {
            throw new ToolError(".$ext files cannot be uploaded or hosted through the MCP (installers, archives and scripts are blocked). Host the file elsewhere (e.g. Google Drive) and save only its link.");
        }
    }
}
