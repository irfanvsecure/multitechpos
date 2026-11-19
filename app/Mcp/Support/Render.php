<?php

namespace App\Mcp\Support;

use App\Cms\Renderer;
use App\Cms\Seo;
use App\Http\Controllers\PageController;
use App\Models\Page;
use App\Models\PageSection;
use App\Models\Post;
use App\Models\SchemaBlock;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ViewErrorBag;

/**
 * Renders pages the way visitors get them, from inside an MCP request
 * (which has no session or web middleware).
 */
class Render
{
    private static array $memo = [];

    private static function prepare(): void
    {
        $request = request();
        if (! $request->hasSession()) {
            $request->setLaravelSession(app('session')->driver('array'));
        }
        View::share('errors', new ViewErrorBag);
    }

    public static function base(): string
    {
        return rtrim(request()->getBasePath(), '/');
    }

    /** Full HTML document of a page or post (drafts included). */
    public static function full(Model $owner): string
    {
        $key = $owner::class.':'.$owner->getKey().':'.$owner->updated_at?->getTimestamp();
        if (isset(self::$memo[$key])) {
            return self::$memo[$key];
        }
        self::prepare();
        $base = self::base();
        $blocks = SchemaBlock::query()->where('is_active', true)->where(fn ($q) => $q->whereNull('schemable_type')
            ->orWhere(fn ($q) => $q->where('schemable_type', $owner->getMorphClass())->where('schemable_id', $owner->getKey())))
            ->orderBy('sort_order')->get();

        if ($owner instanceof Page) {
            $owner->load(['sections', 'seo']);
            $html = view('cms.page', [
                'page' => $owner, 'seo' => Seo::for($owner, $owner->name), 'schemaBlocks' => $blocks,
                'base' => $base, 'current' => $owner->path,
            ])->render();
        } else {
            [$prev, $next] = PageController::neighbours($owner);
            $html = view('cms.post', [
                'post' => $owner, 'prev' => $prev, 'next' => $next, 'seo' => Seo::for($owner, $owner->title),
                'schemaBlocks' => $blocks, 'base' => $base, 'current' => $owner->path,
            ])->render();
        }
        if (count(self::$memo) > 40) {
            self::$memo = [];
        }

        return self::$memo[$key] = $html;
    }

    /** HTML of one section on its own. */
    public static function section(PageSection $section): string
    {
        self::prepare();
        $page = $section->page;

        return view('cms.section-preview', ['section' => $section, 'page' => $page, 'base' => self::base(), 'current' => $page?->path ?? '/'])->render();
    }

    /** <main> part of the full HTML (no header, footer, widgets). */
    public static function main(string $html): string
    {
        if (preg_match('#<main id="content">(.*?)</main>#s', $html, $m)) {
            return $m[1];
        }

        return $html;
    }

    /** The page's own content: html sections (+ hero title/text) or the post body; no shared components. */
    public static function ownText(Model $owner): string
    {
        if ($owner instanceof Post) {
            return Html::text(\App\Cms\Placeholders::render($owner->content, '')).' '.$owner->title;
        }
        $parts = [];
        foreach ($owner->sections as $s) {
            if (! $s->is_visible) {
                continue;
            }
            if ($s->type === 'html') {
                $parts[] = Html::text(Renderer::section($s, $owner, '', $owner->path));
            } elseif (in_array($s->component, ['page-hero', 'home-hero'], true)) {
                $parts[] = Html::text(implode(' ', array_filter([$s->attrs['title'] ?? '', $s->attrs['text'] ?? '', $s->attrs['lead'] ?? ''])));
            }
        }

        return trim(implode(' ', $parts));
    }

    public static function forget(): void
    {
        self::$memo = [];
    }
}
