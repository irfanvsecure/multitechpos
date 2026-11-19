<?php

namespace App\Mcp\Tools;

use App\Mcp\Support\Activity;
use App\Mcp\Support\Images;
use App\Mcp\Support\Validate;
use App\Mcp\ToolError;
use App\Mcp\ToolSet;
use App\Models;
use App\Models\Media;
use App\Models\Page;
use App\Models\PageSection;
use App\Models\Post;
use App\Models\Redirect;
use Illuminate\Support\Facades\File;

class TrashTools extends ToolSet
{
    public const TYPES = [
        'page' => Models\Page::class, 'section' => Models\PageSection::class, 'post' => Models\Post::class,
        'category' => Models\Category::class, 'tag' => Models\Tag::class, 'image' => Models\Media::class,
        'redirect' => Models\Redirect::class, 'schema' => Models\SchemaBlock::class, 'faq' => Models\Faq::class,
        'testimonial' => Models\Testimonial::class, 'client_logo' => Models\ClientLogo::class, 'hardware_item' => Models\HardwareItem::class,
        'pricing_plan' => Models\PricingPlan::class, 'download' => Models\Download::class, 'office' => Models\Office::class,
        'form_option' => Models\FormOption::class,
    ];

    public function tools(): array
    {
        $type = self::e('Kind of item', array_keys(self::TYPES));

        return [
            $this->read('list_trash', 'Deleted items that can still be restored.', ['type' => $type], [], fn ($a) => $this->list($a)),
            $this->write('restore_item', 'Bring a deleted item back.', ['type' => $type, 'id' => self::i('Item id')], ['type', 'id'], fn ($a) => $this->restore($a), true),
            $this->delete('purge_item', 'Delete an item from the trash for good (cannot be undone; images: the files are removed). Needs confirm=true.', [
                'type' => $type, 'id' => self::i('Item id'), 'confirm' => self::b('Must be true'),
            ], ['type', 'id', 'confirm'], fn ($a) => $this->purge($a)),
        ];
    }

    private function label($m): string
    {
        return (string) ($m->path ?? $m->slug ?? $m->from_path ?? $m->name ?? $m->title ?? $m->question ?? $m->key ?? $m->value ?? $m->type ?? ('#'.$m->id));
    }

    private function list(array $a): array
    {
        $out = [];
        foreach (self::TYPES as $type => $class) {
            if (! empty($a['type']) && $a['type'] !== $type) {
                continue;
            }
            foreach ($class::onlyTrashed()->latest('deleted_at')->limit(200)->get() as $m) {
                $out[] = ['type' => $type, 'id' => $m->id, 'what' => mb_strimwidth($this->label($m), 0, 120, '…'), 'deleted_at' => $m->deleted_at?->toIso8601String()];
            }
        }

        return ['items' => count($out), 'trash' => $out];
    }

    private function find(array $a)
    {
        $class = self::TYPES[$a['type']] ?? throw new ToolError('Unknown type.');

        return $class::onlyTrashed()->find($a['id']) ?? throw new ToolError('Not in the trash.');
    }

    private function restore(array $a): array
    {
        $m = $this->find($a);
        if ($m instanceof Page || $m instanceof Post) {
            $clash = Page::query()->where('slug', $m->slug)->exists() || Post::query()->where('slug', $m->slug)->exists();
            if ($clash) {
                throw new ToolError("/{$m->slug}/ is used by another page or post now; change that one first.");
            }
            if ($m instanceof Page && $m->parent_id && ! Page::query()->find($m->parent_id)) {
                $m->parent_id = null;
            }
        }
        if ($m instanceof PageSection) {
            $page = Page::query()->find($m->page_id) ?? throw new ToolError('Its page is deleted; restore the page first.');
            $m->deleted_at = null;
            Validate::h1($page, $page->sections()->get()->push($m)->sortBy('sort_order'));
        }
        if ($m instanceof Media && ! is_file(public_path(ltrim($m->path, '/')))) {
            throw new ToolError('The image file no longer exists.');
        }
        $m->restore();
        $note = null;
        if ($m instanceof Page || $m instanceof Post) {
            $gone = Redirect::query()->where('from_path', $m->path)->first();
            if ($gone) {
                $gone->delete();
                $note = 'The redirect/410 that replaced it was removed.';
            }
        }
        Activity::log('restore', $m, null, Activity::snapshot($m), 'restored '.$a['type'].' '.$this->label($m), [$m->path ?? null]);

        return ['restored' => ['type' => $a['type'], 'id' => $m->id, 'what' => $this->label($m)], 'note' => $note];
    }

    private function purge(array $a): array
    {
        if (($a['confirm'] ?? false) !== true) {
            throw new ToolError('purge_item deletes for good: pass confirm=true after the owner agreed.');
        }
        $m = $this->find($a);
        $what = $this->label($m);
        $before = Activity::snapshot($m);
        if ($m instanceof Media) {
            if (Images::usage()[$m->path] ?? false) {
                throw new ToolError('Pages still show this image; replace it first (replace_image).');
            }
            foreach (array_unique(array_filter(array_merge([$m->path, $m->original_path], array_values($m->variants ?? [])))) as $file) {
                File::delete(public_path(ltrim($file, '/')));
            }
        }
        if ($m instanceof Page) {
            $m->seo()->forceDelete();
            Models\Faq::withTrashed()->where('page_id', $m->id)->forceDelete();
            PageSection::withTrashed()->where('page_id', $m->id)->forceDelete();
        }
        if ($m instanceof Post) {
            $m->seo()->forceDelete();
            $m->categories()->detach();
            $m->tags()->detach();
        }
        $m->forceDelete();
        Activity::log('purge', null, $before, null, 'purged '.$a['type'].' '.$what);

        return ['purged' => ['type' => $a['type'], 'id' => (int) $a['id'], 'what' => $what]];
    }
}
