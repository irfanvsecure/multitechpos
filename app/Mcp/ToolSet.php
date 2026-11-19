<?php

namespace App\Mcp;

use App\Mcp\Support\Guard;
use App\Models\Page;
use App\Models\Post;
use Illuminate\Database\Eloquent\Model;

/** Base for tool sets: small helpers to declare tools and their argument schemas. */
abstract class ToolSet
{
    /** @return list<Tool> */
    abstract public function tools(): array;

    protected function read(string $name, string $description, array $props, array $required, \Closure $handler, bool $openWorld = false): Tool
    {
        return new Tool($name, $description, $props, $required, 'read', $handler, true, $openWorld);
    }

    protected function write(string $name, string $description, array $props, array $required, \Closure $handler, bool $idempotent = false, bool $openWorld = false): Tool
    {
        return new Tool($name, $description, $props, $required, 'write', $handler, $idempotent, $openWorld);
    }

    protected function delete(string $name, string $description, array $props, array $required, \Closure $handler, bool $idempotent = false): Tool
    {
        return new Tool($name, $description, $props, $required, 'delete', $handler, $idempotent);
    }

    // ---- schema helpers

    protected static function s(string $d, array $extra = []): array
    {
        return ['type' => 'string', 'description' => $d] + $extra;
    }

    protected static function i(string $d, array $extra = []): array
    {
        return ['type' => 'integer', 'description' => $d] + $extra;
    }

    protected static function b(string $d): array
    {
        return ['type' => 'boolean', 'description' => $d];
    }

    protected static function e(string $d, array $values): array
    {
        return ['type' => 'string', 'description' => $d, 'enum' => $values];
    }

    protected static function a(string $d, array $items = ['type' => 'string']): array
    {
        return ['type' => 'array', 'description' => $d, 'items' => $items];
    }

    protected static function o(string $d, array $props = []): array
    {
        return ['type' => 'object', 'description' => $d] + ($props ? ['properties' => $props] : []);
    }

    protected static function expected(): array
    {
        return self::s('The updated_at value you got when you last read this record. The change is refused if the record changed since.');
    }

    protected static function dryRun(): array
    {
        return self::b('true (default) = only show what would change; false = apply.');
    }

    // ---- shared lookups

    protected function guard(?Model $model, array $args, string $label = 'record'): Model
    {
        if (! $model) {
            throw new ToolError("No such $label.");
        }
        Guard::check($model, $args['expected_updated_at'] ?? null);

        return $model;
    }

    protected function findPage(array $args, bool $withTrashed = false): Page
    {
        $q = $withTrashed ? Page::withTrashed() : Page::query();
        if (! empty($args['page_id'])) {
            $page = $q->find((int) $args['page_id']);
        } elseif (array_key_exists('path', $args) && $args['path'] !== null) {
            $slug = trim(parse_url((string) $args['path'], PHP_URL_PATH) ?? '', '/');
            $page = $q->where('slug', $slug)->first();
        } elseif (array_key_exists('slug', $args) && $args['slug'] !== null) {
            $page = $q->where('slug', trim((string) $args['slug'], '/'))->first();
        } else {
            throw new ToolError('Give page_id or path.');
        }

        return $page ?? throw new ToolError('No such page. Use list_pages.');
    }

    protected function findPost(array $args): Post
    {
        $post = ! empty($args['post_id']) ? Post::query()->find((int) $args['post_id'])
            : (! empty($args['slug']) ? Post::query()->where('slug', trim((string) $args['slug'], '/'))->first() : null);

        return $post ?? throw new ToolError('No such blog post. Use list_blogs.');
    }

    /** A page or a post, from page_id / post_id / path. */
    protected function findOwner(array $args): Model
    {
        if (! empty($args['post_id'])) {
            return $this->findPost($args);
        }
        if (! empty($args['page_id'])) {
            return $this->findPage($args);
        }
        if (isset($args['path'])) {
            $slug = trim(parse_url((string) $args['path'], PHP_URL_PATH) ?? '', '/');
            if ($page = Page::query()->where('slug', $slug)->first()) {
                return $page;
            }
            if ($post = Post::query()->where('slug', $slug)->first()) {
                return $post;
            }
            throw new ToolError('Nothing at '.$args['path'].'. Use list_pages or list_blogs.');
        }
        throw new ToolError('Give page_id, post_id or path.');
    }

    protected static function ownerProps(): array
    {
        return [
            'page_id' => self::i('Page id (from list_pages)'),
            'post_id' => self::i('Blog post id (from list_blogs)'),
            'path' => self::s('Or the URL path, e.g. /retail-pos-software-uae/'),
        ];
    }
}
