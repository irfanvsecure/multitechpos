<?php

namespace App\Mcp\Tools;

use App\Mcp\Support\Activity;
use App\Mcp\Support\Guard;
use App\Mcp\Support\Html;
use App\Mcp\Support\Redirects;
use App\Mcp\Support\Validate;
use App\Mcp\ToolError;
use App\Mcp\ToolSet;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Carbon\Carbon;
use Illuminate\Support\Str;

class BlogTools extends ToolSet
{
    public function tools(): array
    {
        $postId = self::i('Blog post id');
        $postFields = [
            'title' => self::s('Title (the H1)'),
            'content' => self::s('Body HTML (no <h1>: the title is the H1; use h2/h3). Placeholders allowed.'),
            'excerpt' => self::s('Short summary for lists'),
            'cover_image' => self::s('Main image path (share image + Article schema), e.g. /images/uploads/2026/11/x.webp'),
            'card_image' => self::s('Thumbnail for the blog list'),
            'author_name' => self::s('Author shown on the post (default multitech_admin)'),
            'narrow' => self::b('Narrow reading column'),
        ];

        return [
            $this->read('list_blogs', 'Blog posts, newest first.', [
                'status' => self::e('Filter', ['draft', 'published', 'scheduled']),
                'category' => self::s('Category slug'),
                'tag' => self::s('Tag slug'),
                'search' => self::s('Text in the title or slug'),
                'limit' => self::i('Default 50'),
            ], [], fn ($a) => $this->list($a)),

            $this->read('get_blog', 'One blog post with its full content, SEO, categories and tags.', ['post_id' => $postId, 'slug' => self::s('Or its slug')], [], fn ($a) => self::postOut($this->findPost($a), true)),

            $this->write('create_blog', 'Create a blog post (draft unless status=published). URL: /slug/. Write original content; never invent statistics, quotes or customer stories.', $postFields + [
                'slug' => self::s('URL slug'),
                'status' => self::e('Default draft', ['draft', 'published']),
                'categories' => self::a('Category slugs'),
                'tags' => self::a('Tag names (created when new)'),
                'seo_title' => self::s('Meta title'),
                'seo_description' => self::s('Meta description'),
                'focus_keyword' => self::s('Focus keyword'),
            ], ['slug', 'title', 'content'], fn ($a) => $this->create($a)),

            $this->write('update_blog', 'Change a blog post. For small content edits use find + replace (must match once). Changing the slug adds a 301. A revision is kept.', $postFields + [
                'post_id' => $postId,
                'expected_updated_at' => self::expected(),
                'slug' => self::s('New slug'),
                'find' => self::s('Exact HTML to find in the content (once)'),
                'replace' => self::s('Replacement'),
            ], ['post_id', 'expected_updated_at'], fn ($a) => $this->update($a)),

            $this->delete('delete_blog', 'Move a post to the trash. Give redirect_to (301) if it had traffic.', [
                'post_id' => $postId, 'expected_updated_at' => self::expected(),
                'redirect_to' => self::s('301 the old URL here'), 'gone' => self::b('Answer the old URL with 410'),
            ], ['post_id', 'expected_updated_at'], fn ($a) => $this->remove($a)),

            $this->write('publish_blog', 'Publish a post now (or with a past published_at).', [
                'post_id' => $postId, 'expected_updated_at' => self::expected(), 'published_at' => self::s('Optional date/time, not in the future'),
            ], ['post_id', 'expected_updated_at'], fn ($a) => $this->status($a, 'published'), true),

            $this->write('unpublish_blog', 'Take a post offline (back to draft). Its URL then falls to the catch-all; consider a redirect.', [
                'post_id' => $postId, 'expected_updated_at' => self::expected(),
            ], ['post_id', 'expected_updated_at'], fn ($a) => $this->status($a, 'draft'), true),

            $this->write('schedule_blog', 'Publish a post automatically at a future date/time (Asia/Dubai if no timezone is given).', [
                'post_id' => $postId, 'expected_updated_at' => self::expected(), 'published_at' => self::s('Future date/time, e.g. 2026-12-01 09:00'),
            ], ['post_id', 'expected_updated_at', 'published_at'], fn ($a) => $this->status($a, 'scheduled'), true),

            $this->read('list_stale_posts', 'Published posts not updated for a while (oldest first).', [
                'months' => self::i('Not updated for this many months (default 12)'),
            ], [], fn ($a) => $this->stale($a)),

            $this->read('list_categories', 'Blog categories with post counts.', [], [], fn () => Category::query()->withCount('posts')->orderBy('name')->get()
                ->map(fn ($c) => ['id' => $c->id, 'name' => $c->name, 'slug' => $c->slug, 'description' => $c->description, 'posts' => $c->posts_count, 'updated_at' => Guard::stamp($c)])->all()),
            $this->write('create_category', 'Add a blog category.', ['name' => self::s('Name'), 'slug' => self::s('Slug (default from name)'), 'description' => self::s('Description')], ['name'], fn ($a) => $this->createTerm(Category::class, $a)),
            $this->write('update_category', 'Rename or describe a category.', ['id' => self::i('Category id'), 'expected_updated_at' => self::expected(), 'name' => self::s('Name'), 'slug' => self::s('Slug'), 'description' => self::s('Description')], ['id', 'expected_updated_at'], fn ($a) => $this->updateTerm(Category::class, $a)),
            $this->delete('delete_category', 'Delete a category (posts keep their other categories).', ['id' => self::i('Category id'), 'expected_updated_at' => self::expected()], ['id', 'expected_updated_at'], fn ($a) => $this->deleteTerm(Category::class, $a)),

            $this->read('list_tags', 'Blog tags with post counts.', [], [], fn () => Tag::query()->withCount('posts')->orderBy('name')->get()
                ->map(fn ($t) => ['id' => $t->id, 'name' => $t->name, 'slug' => $t->slug, 'posts' => $t->posts_count, 'updated_at' => Guard::stamp($t)])->all()),
            $this->write('create_tag', 'Add a tag.', ['name' => self::s('Name'), 'slug' => self::s('Slug (default from name)')], ['name'], fn ($a) => $this->createTerm(Tag::class, $a)),
            $this->write('update_tag', 'Rename a tag.', ['id' => self::i('Tag id'), 'expected_updated_at' => self::expected(), 'name' => self::s('Name'), 'slug' => self::s('Slug')], ['id', 'expected_updated_at'], fn ($a) => $this->updateTerm(Tag::class, $a)),
            $this->delete('delete_tag', 'Delete a tag.', ['id' => self::i('Tag id'), 'expected_updated_at' => self::expected()], ['id', 'expected_updated_at'], fn ($a) => $this->deleteTerm(Tag::class, $a)),
            $this->write('merge_tags', 'Merge tags into one: posts move to the target tag, the others go to the trash, and their old /tag/slug/ URLs get a 301 (to /our-blogs/: the site has no tag pages).', [
                'source_ids' => self::a('Tags to merge away', ['type' => 'integer']), 'target_id' => self::i('Tag to keep'),
            ], ['source_ids', 'target_id'], fn ($a) => $this->merge($a)),

            $this->write('set_blog_taxonomies', 'Set a post\'s categories and/or tags (replaces them).', [
                'post_id' => $postId, 'expected_updated_at' => self::expected(),
                'categories' => self::a('Category slugs'), 'tags' => self::a('Tag names (created when new)'),
            ], ['post_id', 'expected_updated_at'], fn ($a) => $this->taxonomies($a), true),
        ];
    }

    public static function postOut(Post $p, bool $full = false): array
    {
        $out = [
            'id' => $p->id, 'path' => $p->path, 'slug' => $p->slug, 'title' => $p->title, 'status' => $p->status,
            'published_at' => $p->published_at?->toIso8601String(), 'is_live' => in_array($p->status, ['published', 'scheduled'], true) && $p->published_at && $p->published_at->isPast(),
            'author_name' => $p->author_name, 'updated_at' => Guard::stamp($p),
        ];
        if ($full) {
            $out += [
                'excerpt' => $p->excerpt, 'cover_image' => $p->cover_image, 'card_image' => $p->card_image, 'narrow' => (bool) ($p->options['narrow'] ?? false),
                'categories' => $p->categories()->pluck('slug')->all(), 'tags' => $p->tags()->pluck('name')->all(),
                'words' => Html::words(Html::text($p->content)),
                'seo' => SeoTools::seoOut($p),
                'content' => $p->content,
            ];
        }

        return $out;
    }

    private function list(array $a): array
    {
        $posts = Post::query()
            ->when($a['status'] ?? null, fn ($q, $v) => $q->where('status', $v))
            ->when($a['category'] ?? null, fn ($q, $v) => $q->whereHas('categories', fn ($c) => $c->where('slug', $v)))
            ->when($a['tag'] ?? null, fn ($q, $v) => $q->whereHas('tags', fn ($c) => $c->where('slug', $v)))
            ->when($a['search'] ?? null, fn ($q, $v) => $q->where(fn ($q) => $q->where('title', 'like', "%$v%")->orWhere('slug', 'like', "%$v%")))
            ->orderByDesc('published_at')->orderByDesc('id')->limit((int) ($a['limit'] ?? 50))->get();

        return ['count' => $posts->count(), 'posts' => $posts->map(fn ($p) => self::postOut($p))->all()];
    }

    private function cleanContent(string $html): array
    {
        [$clean, $removed] = Validate::html($html);
        if (preg_match('/<h1[\s>]/i', $clean)) {
            throw new ToolError('The post title is the H1: use <h2> and <h3> in the content.');
        }

        return [$clean, $removed];
    }

    private function image(?string $path): ?string
    {
        if ($path === null || $path === '') {
            return null;
        }
        if (! str_starts_with($path, '/') || ! is_file(public_path(ltrim($path, '/')))) {
            throw new ToolError("Image not found: $path (upload it with upload_image first).");
        }

        return $path;
    }

    private function create(array $a): array
    {
        $slug = Validate::slug($a['slug']);
        [$content, $removed] = $this->cleanContent($a['content']);
        $status = $a['status'] ?? 'draft';
        $post = Post::query()->create([
            'slug' => $slug, 'title' => trim(strip_tags($a['title'])), 'content' => $content, 'excerpt' => isset($a['excerpt']) ? strip_tags($a['excerpt']) : null,
            'cover_image' => $this->image($a['cover_image'] ?? null), 'card_image' => $this->image($a['card_image'] ?? null),
            'author_name' => $a['author_name'] ?? 'multitech_admin', 'status' => $status,
            'published_at' => $status === 'published' ? now() : null,
            'options' => ($a['narrow'] ?? false) ? ['narrow' => true] : null,
        ]);
        $post->seo()->create(array_filter(['title' => $a['seo_title'] ?? null, 'description' => $a['seo_description'] ?? null,
            'focus_keyword' => $a['focus_keyword'] ?? null, 'og_type' => 'article', 'og_image' => $post->cover_image]));
        $this->syncTerms($post, $a);
        Activity::log('create', $post, null, self::postOut($post), 'post '.$post->path, [$post->path, '/our-blogs/']);

        return ['post' => self::postOut($post->fresh(), true), 'removed_by_sanitizer' => $removed];
    }

    private function update(array $a): array
    {
        $post = $this->guard(Post::query()->find($a['post_id']), $a, 'post');
        $before = self::postOut($post, true);
        $fields = [];
        $removed = [];
        if (isset($a['find'])) {
            $n = substr_count((string) $post->content, $a['find']);
            if ($n !== 1) {
                throw new ToolError($n ? "find occurs $n times; make it longer." : 'find was not found in the content (read it with get_blog).');
            }
            $a['content'] = str_replace($a['find'], (string) ($a['replace'] ?? ''), $post->content);
        }
        if (isset($a['content'])) {
            [$fields['content'], $removed] = $this->cleanContent($a['content']);
        }
        foreach (['title', 'excerpt', 'author_name'] as $f) {
            if (isset($a[$f])) {
                $fields[$f] = trim(strip_tags($a[$f]));
            }
        }
        foreach (['cover_image', 'card_image'] as $f) {
            if (array_key_exists($f, $a)) {
                $fields[$f] = $this->image($a[$f]);
            }
        }
        if (isset($a['narrow'])) {
            $fields['options'] = array_merge($post->options ?? [], ['narrow' => (bool) $a['narrow']]);
        }
        $oldPath = $post->path;
        if (isset($a['slug']) && $a['slug'] !== $post->slug) {
            $fields['slug'] = Validate::slug($a['slug'], $post);
        }
        if (! $fields) {
            throw new ToolError('Nothing to change.');
        }
        Activity::revision($post, 'before update');
        $post->update($fields);
        $notes = [];
        if (isset($fields['slug'])) {
            $notes[] = Redirects::moved($oldPath, $post->path, 'post slug changed (#'.$post->id.')');
        }
        Activity::log('update', $post, $before, self::postOut($post, true), 'post '.$post->path, [$oldPath, $post->path, '/our-blogs/']);

        return ['post' => self::postOut($post->fresh(), true), 'removed_by_sanitizer' => $removed, 'notes' => $notes];
    }

    private function remove(array $a): array
    {
        $post = $this->guard(Post::query()->find($a['post_id']), $a, 'post');
        $before = self::postOut($post);
        $post->delete();
        $note = 'The URL now falls to the catch-all (301 to home).';
        if (! empty($a['redirect_to'])) {
            $note = Redirects::moved($post->path, $a['redirect_to'], 'post #'.$post->id.' deleted');
        } elseif (! empty($a['gone'])) {
            Redirects::save($post->path, null, 410, 'post #'.$post->id.' deleted');
            $note = $post->path.' answers 410 Gone.';
        }
        Activity::log('delete', $post, $before, null, 'post '.$post->path, [$post->path, '/our-blogs/']);

        return ['deleted' => $before, 'note' => $note, 'restore' => 'restore_item type=post id='.$post->id];
    }

    private function status(array $a, string $status): array
    {
        $post = $this->guard(Post::query()->find($a['post_id']), $a, 'post');
        $before = self::postOut($post);
        $when = isset($a['published_at']) ? Carbon::parse($a['published_at'], 'Asia/Dubai')->setTimezone(config('app.timezone')) : null;
        $fields = ['status' => $status];
        if ($status === 'published') {
            if ($when && $when->isFuture()) {
                throw new ToolError('That date is in the future: use schedule_blog.');
            }
            $fields['published_at'] = $when ?? ($post->published_at && $post->published_at->isPast() ? $post->published_at : now());
        } elseif ($status === 'scheduled') {
            if (! $when || ! $when->isFuture()) {
                throw new ToolError('published_at must be in the future.');
            }
            $fields['published_at'] = $when;
        }
        if ($status !== 'draft' && trim(Html::text($post->content)) === '') {
            throw new ToolError('The post has no content.');
        }
        $post->update($fields);
        Activity::log($status, $post, $before, self::postOut($post), $status.' '.$post->path, [$post->path, '/our-blogs/', '/sitemap.xml']);

        return ['post' => self::postOut($post->fresh())];
    }

    private function stale(array $a): array
    {
        $months = (int) ($a['months'] ?? 12);

        return Post::query()->live()->where('updated_at', '<', now()->subMonths($months))->orderBy('updated_at')->get()
            ->map(fn ($p) => self::postOut($p) + ['months_since_update' => (int) $p->updated_at->diffInMonths(now())])->all();
    }

    private function syncTerms(Post $post, array $a): void
    {
        if (isset($a['categories'])) {
            $ids = [];
            foreach ($a['categories'] as $slug) {
                $ids[] = (Category::query()->where('slug', Str::slug($slug))->first() ?? throw new ToolError("No category '$slug' (create_category first)."))->id;
            }
            $post->categories()->sync($ids);
        }
        if (isset($a['tags'])) {
            $ids = [];
            foreach ($a['tags'] as $name) {
                $slug = Str::slug($name);
                $tag = Tag::withTrashed()->firstOrCreate(['slug' => $slug], ['name' => strip_tags($name)]);
                if ($tag->trashed()) {
                    $tag->restore();
                }
                $ids[] = $tag->id;
            }
            $post->tags()->sync($ids);
        }
    }

    private function taxonomies(array $a): array
    {
        $post = $this->guard(Post::query()->find($a['post_id']), $a, 'post');
        $before = ['categories' => $post->categories()->pluck('slug')->all(), 'tags' => $post->tags()->pluck('name')->all()];
        $this->syncTerms($post, $a);
        $post->touch();
        $after = ['categories' => $post->categories()->pluck('slug')->all(), 'tags' => $post->tags()->pluck('name')->all()];
        Activity::log('update', $post, $before, $after, 'taxonomies of '.$post->path, [$post->path]);

        return $after + ['updated_at' => Guard::stamp($post->fresh())];
    }

    private function createTerm(string $class, array $a): array
    {
        $slug = Str::slug($a['slug'] ?? $a['name']);
        if ($slug === '' || $class::withTrashed()->where('slug', $slug)->exists()) {
            throw new ToolError("The slug '$slug' is empty or already used.");
        }
        $term = $class::query()->create(array_filter(['name' => strip_tags($a['name']), 'slug' => $slug, 'description' => $a['description'] ?? null]));
        Activity::log('create', $term, null, $term->toArray(), class_basename($class).' '.$term->name);

        return $term->fresh()->toArray();
    }

    private function updateTerm(string $class, array $a): array
    {
        $term = $this->guard($class::query()->find($a['id']), $a, strtolower(class_basename($class)));
        $before = $term->toArray();
        $fields = array_filter(['name' => isset($a['name']) ? strip_tags($a['name']) : null, 'description' => $a['description'] ?? null]);
        if (isset($a['slug'])) {
            $slug = Str::slug($a['slug']);
            if ($class::withTrashed()->where('slug', $slug)->where('id', '!=', $term->id)->exists()) {
                throw new ToolError("Slug '$slug' is taken.");
            }
            $fields['slug'] = $slug;
        }
        $term->update($fields);
        Activity::log('update', $term, $before, $term->toArray(), class_basename($class).' '.$term->name);

        return $term->fresh()->toArray();
    }

    private function deleteTerm(string $class, array $a): array
    {
        $term = $this->guard($class::query()->find($a['id']), $a, strtolower(class_basename($class)));
        $before = $term->toArray();
        $term->delete();
        Activity::log('delete', $term, $before, null, class_basename($class).' '.$term->name);

        return ['deleted' => $before, 'restore' => 'restore_item type='.strtolower(class_basename($class)).' id='.$term->id];
    }

    private function merge(array $a): array
    {
        $target = Tag::query()->find($a['target_id']) ?? throw new ToolError('No such target tag.');
        $sources = Tag::query()->whereIn('id', $a['source_ids'])->where('id', '!=', $target->id)->get();
        if ($sources->isEmpty()) {
            throw new ToolError('No source tags to merge.');
        }
        $moved = 0;
        foreach ($sources as $s) {
            foreach ($s->posts as $post) {
                $post->tags()->syncWithoutDetaching([$target->id]);
                $moved++;
            }
            $s->posts()->detach();
            Redirects::save('/tag/'.$s->slug.'/', '/our-blogs/', 301, 'tag merged into '.$target->slug);
            $s->delete();
            Activity::log('merge', $s, $s->toArray(), ['merged_into' => $target->slug], 'tag '.$s->slug.' → '.$target->slug);
        }

        return ['target' => $target->fresh()->loadCount('posts')->toArray(), 'merged' => $sources->pluck('slug'), 'post_links_moved' => $moved];
    }
}
