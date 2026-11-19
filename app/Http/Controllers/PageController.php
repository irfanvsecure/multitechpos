<?php

namespace App\Http\Controllers;

use App\Cms\Cms;
use App\Cms\Seo;
use App\Models\Page;
use App\Models\Post;
use App\Models\Redirect;
use App\Models\SchemaBlock;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

/**
 * Every content URL: a page or blog post from the database, else a saved redirect / 410,
 * else the old behaviour (301 to the home page).
 */
class PageController extends Controller
{
    public function show(Request $request, string $path = ''): Response
    {
        $slug = trim($path, '/');

        if ($page = self::findPage($slug)) {
            return response()->view('cms.page', [
                'page' => $page,
                'seo' => Seo::for($page, $page->name),
                'schemaBlocks' => self::schemaBlocks($page),
            ]);
        }

        if (! str_contains($slug, '/') && ($post = self::findPost($slug))) {
            [$prev, $next] = self::neighbours($post);

            return response()->view('cms.post', [
                'post' => $post,
                'prev' => $prev,
                'next' => $next,
                'seo' => Seo::for($post, $post->title),
                'schemaBlocks' => self::schemaBlocks($post),
            ]);
        }

        return self::redirectOrFallback($request);
    }

    public static function findPage(string $slug): ?Page
    {
        $id = Cache::remember(Cms::cacheKey('page-id:'.$slug), 3600, fn () => Page::query()
            ->where('slug', $slug)->where('status', 'published')->value('id') ?? 0);

        return $id ? Page::query()->with(['sections', 'seo'])->find($id) : null;
    }

    public static function findPost(string $slug): ?Post
    {
        return Post::query()->live()->where('slug', $slug)->with('seo')->first();
    }

    /** Older post = previous, newer post = next (by publish time). */
    public static function neighbours(Post $post): array
    {
        $prev = Post::query()->live()->where(fn ($q) => $q->where('published_at', '<', $post->published_at)
            ->orWhere(fn ($q) => $q->where('published_at', $post->published_at)->where('id', '<', $post->id)))
            ->orderByDesc('published_at')->orderByDesc('id')->first(['id', 'slug', 'title']);
        $next = Post::query()->live()->where(fn ($q) => $q->where('published_at', '>', $post->published_at)
            ->orWhere(fn ($q) => $q->where('published_at', $post->published_at)->where('id', '>', $post->id)))
            ->orderBy('published_at')->orderBy('id')->first(['id', 'slug', 'title']);

        return [$prev, $next];
    }

    private static function schemaBlocks($owner)
    {
        return SchemaBlock::query()->where('is_active', true)
            ->where(fn ($q) => $q->whereNull('schemable_type')
                ->orWhere(fn ($q) => $q->where('schemable_type', $owner->getMorphClass())->where('schemable_id', $owner->id)))
            ->orderBy('sort_order')->orderBy('id')->get();
    }

    /** Saved redirect (301/302) or 410 for this path; otherwise the old catch-all: 301 to home. */
    public static function redirectOrFallback(Request $request): Response
    {
        $path = '/'.ltrim($request->getPathInfo(), '/');
        $candidates = array_unique([$path, rtrim($path, '/'), rtrim($path, '/').'/', rawurldecode($path)]);
        $redirect = Redirect::query()->whereIn('from_path', $candidates)->first();

        if ($redirect) {
            $redirect->timestamps = false;
            $redirect->increment('hits', 1, ['last_hit_at' => now()]);

            if ($redirect->status_code === 410) {
                return response()->view('errors.410', [], 410);
            }

            $to = (string) $redirect->to_url;
            if (str_starts_with($to, '/')) {
                $to = $request->getSchemeAndHttpHost().$request->getBaseUrl().$to;
            }

            return new RedirectResponse($to, $redirect->status_code === 302 ? 302 : 301);
        }

        return redirect('/', 301); // unchanged from the old Route::fallback
    }
}
