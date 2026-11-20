<?php

namespace App\Mcp\Tools;

use App\Mcp\Support\Activity;
use App\Mcp\Support\Guard;
use App\Mcp\Support\Images;
use App\Mcp\Support\Redirects;
use App\Mcp\Support\SiteIndex;
use App\Mcp\Support\Validate;
use App\Mcp\ToolError;
use App\Mcp\ToolSet;
use App\Models\Media;
use App\Models\UploadLink;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ImageTools extends ToolSet
{
    public function tools(): array
    {
        $ref = ['id' => self::i('Image id (from list_images)'), 'path' => self::s('Or its path, e.g. /images/2026/06/x.webp')];

        return [
            $this->read('list_images', 'Images in the media library with size, dimensions, alt text and where each is used.', [
                'search' => self::s('Text in the path or alt'),
                'unused_only' => self::b('Only images no page uses'),
                'limit' => self::i('Default 100'),
                'offset' => self::i('Skip this many'),
            ], [], fn ($a) => $this->list($a)),

            $this->write('create_image_upload_link', 'Make a one-time upload page link for the owner. Use this whenever the owner shares an image in the chat (or has it on their computer or phone) and it has no public https URL: files in the chat cannot be sent to upload_image, and base64 must never be typed out by hand. The owner opens the link, picks the file(s) and presses Upload; the images go into the media library (WebP + srcset, same as upload_image). Then call get_image_upload_link.', [
                'purpose' => self::s('Shown on the upload page, e.g. "Image for the VAT-ready section of /pos-software-dubai/"'),
                'alt' => self::s('Alt text to use (the owner can change it on the page)'),
                'filename' => self::s('File name to use (default from the alt text)'),
                'max_files' => self::i('How many images may be uploaded with this link (1-10, default 1)'),
                'expires_minutes' => self::i('Link lifetime in minutes (5-1440, default 60)'),
            ], [], fn ($a) => $this->createLink($a)),

            $this->read('get_image_upload_link', 'Status of an upload link from create_image_upload_link (waiting / partly_uploaded / uploaded / expired) and the images uploaded through it, with path and <img> snippet.', [
                'link_id' => self::i('link_id from create_image_upload_link'),
            ], ['link_id'], fn ($a) => $this->readLink($a)),

            $this->write('upload_image', 'Add an image from an https URL or base64 (for an image in the chat or on the owner\'s device, use create_image_upload_link instead). Converted to WebP (max 1600px wide) with 480/800/1200 versions for srcset; the original is kept. alt is required. Only JPEG/PNG/GIF/WebP: installers, archives and SVG are refused. Returns the path and an <img> snippet for page HTML.', [
                'url' => self::s('https URL of the image'),
                'base64' => self::s('Or the image as base64 / data URI'),
                'alt' => self::s('Alt text: what the image shows (required)'),
                'filename' => self::s('File name to use (default from the alt text)'),
                'title' => self::s('Title (optional)'),
            ], ['alt'], fn ($a) => $this->upload($a), false, true),

            $this->write('update_image', 'Change alt, title or file name. A new alt is also written into every <img> that shows it (set_alt_in_pages=false to skip). Renaming moves the files, updates every reference and 301s the old URL.', $ref + [
                'expected_updated_at' => self::expected(),
                'alt' => self::s('New alt text'),
                'title' => self::s('New title'),
                'filename' => self::s('New file name (without folder or extension)'),
                'set_alt_in_pages' => self::b('Default true'),
            ], ['expected_updated_at'], fn ($a) => $this->update($a)),

            $this->write('replace_image', 'Show another image wherever this one is used (pages, posts, share images, logos). width/height in <img> tags follow the new image. dry_run defaults to true.', [
                'old_path' => self::s('Image now used'),
                'new_path' => self::s('Image to show instead (upload it first)'),
                'dry_run' => self::dryRun(),
            ], ['old_path', 'new_path'], fn ($a) => $this->replace($a)),

            $this->delete('delete_image', 'Move an image to the trash. Refused while it is used, unless force=true (pages would then show a missing image after purge_item). The file stays until purge_item.', $ref + [
                'expected_updated_at' => self::expected(),
                'force' => self::b('Delete even if used'),
            ], ['expected_updated_at'], fn ($a) => $this->remove($a)),

            $this->read('audit_images', 'Image problems on live pages: missing or file-name-like alt, files over 300 KB, no width/height, not WebP, below-the-fold images not lazy-loaded.', [
                'page_id' => self::i('Only this page'), 'post_id' => self::i('Only this post'),
            ], [], fn ($a) => $this->audit($a)),

            $this->write('bulk_update_alt', 'Set alt text for several images (library + every <img> showing them). dry_run defaults to true.', [
                'items' => self::a('[{"path": "/images/...", "alt": "..."}] or with "id"', ['type' => 'object']),
                'dry_run' => self::dryRun(),
            ], ['items'], fn ($a) => $this->bulkAlt($a)),
        ];
    }

    private function find(array $a): Media
    {
        $m = ! empty($a['id']) ? Media::query()->find($a['id']) : (! empty($a['path']) ? Media::query()->where('path', '/'.ltrim($a['path'], '/'))->first() : null);

        return $m ?? throw new ToolError('No such image. Use list_images.');
    }

    public static function out(Media $m, array $usage = []): array
    {
        return ['id' => $m->id, 'path' => $m->path, 'alt' => $m->alt, 'title' => $m->title, 'width' => $m->width, 'height' => $m->height,
            'kb' => $m->size ? round($m->size / 1024) : null, 'variants' => $m->variants, 'original' => $m->original_path,
            'used_in' => $usage, 'updated_at' => Guard::stamp($m)];
    }

    private function list(array $a): array
    {
        $usage = Images::usage();
        $q = Media::query()->when($a['search'] ?? null, fn ($q, $v) => $q->where(fn ($q) => $q->where('path', 'like', "%$v%")->orWhere('alt', 'like', "%$v%")))->orderBy('path');
        $rows = $q->get()->map(fn ($m) => self::out($m, $usage[$m->path] ?? []));
        if ($a['unused_only'] ?? false) {
            $rows = $rows->filter(fn ($r) => ! $r['used_in'])->values();
        }

        return ['total' => $rows->count(), 'images' => $rows->slice((int) ($a['offset'] ?? 0), min(300, (int) ($a['limit'] ?? 100)))->values()->all()];
    }

    private function upload(array $a): array
    {
        if (mb_strlen(trim(strip_tags((string) $a['alt']))) < 5) {
            throw new ToolError('alt must describe the image (at least 5 characters).');
        }
        if (isset($a['filename'])) {
            Validate::notExecutable($a['filename']);
        }
        [$bytes] = Images::fetch($a['url'] ?? null, $a['base64'] ?? null);
        $media = Images::create($bytes, (string) $a['alt'], $a['filename'] ?? null, $a['title'] ?? null);

        return ['image' => self::out($media), 'html' => Images::snippet($media),
            'note' => 'Use path in component attrs / cover_image, or put html into a section. {{base}} is filled in when shown.'];
    }

    private function createLink(array $a): array
    {
        if (isset($a['filename'])) {
            Validate::notExecutable($a['filename']);
        }
        $alt = isset($a['alt']) ? mb_substr(trim(strip_tags($a['alt'])), 0, 250) : null;
        $minutes = max(5, min(1440, (int) ($a['expires_minutes'] ?? 60)));
        $token = Str::random(43);
        $link = UploadLink::query()->create([
            'token_hash' => hash('sha256', $token),
            'purpose' => isset($a['purpose']) ? mb_substr(trim(strip_tags($a['purpose'])), 0, 250) : null,
            'alt' => $alt ?: null,
            'filename' => $a['filename'] ?? null,
            'max_files' => max(1, min(10, (int) ($a['max_files'] ?? 1))),
            'expires_at' => now()->addMinutes($minutes),
        ]);
        Activity::log('create', $link, null, ['purpose' => $link->purpose, 'max_files' => $link->max_files, 'expires_at' => $link->expires_at->toIso8601String()], 'image upload link #'.$link->id);
        $url = rtrim((string) config('app.url'), '/').'/mcp/upload/'.$token;

        return [
            'link_id' => $link->id,
            'upload_url' => $url,
            'max_files' => $link->max_files,
            'expires_at' => $link->expires_at->toIso8601String(),
            'next' => 'Give upload_url to the owner as a clickable link and ask them to open it, choose the image(s) and press Upload '
                .'(it works on a phone too). When they say it is done, call get_image_upload_link with link_id='.$link->id
                .' to get the new image path, then use it (replace_image, update_page_section, cover_image ...). Never type base64 by hand.',
        ];
    }

    private function readLink(array $a): array
    {
        $link = UploadLink::query()->find((int) ($a['link_id'] ?? 0)) ?? throw new ToolError('No such upload link. Make one with create_image_upload_link.');
        $images = Media::query()->whereIn('id', $link->media_ids ?? [])->get()
            ->map(fn ($m) => self::out($m) + ['html' => Images::snippet($m)])->values()->all();
        $status = $link->status();

        return [
            'link_id' => $link->id,
            'status' => $status,
            'purpose' => $link->purpose,
            'uploaded' => count($images),
            'max_files' => $link->max_files,
            'expires_at' => $link->expires_at->toIso8601String(),
            'images' => $images,
            'next' => match ($status) {
                'waiting' => 'Nothing uploaded yet. Ask the owner to open the link, choose the image and press Upload, then check again.',
                'expired' => 'The link expired before anything was uploaded. Make a new one with create_image_upload_link.',
                default => 'Use images[].path: replace_image (old_path → path) to swap an image everywhere, or put images[].html into a section. Check the alt text and fix it with update_image if needed.',
            },
        ];
    }

    private function update(array $a): array
    {
        $m = $this->guard($this->find($a), $a, 'image');
        $before = self::out($m);
        $notes = [];
        if (isset($a['alt'])) {
            $alt = trim(strip_tags($a['alt']));
            $m->alt = $alt;
            if ($a['set_alt_in_pages'] ?? true) {
                $notes['alt_updated_in'] = Images::setAlt($m->path, $alt, false);
            }
        }
        if (isset($a['title'])) {
            $m->title = strip_tags($a['title']);
        }
        if (isset($a['filename'])) {
            Validate::notExecutable($a['filename']);
            $stem = Images::slugName($a['filename']);
            $dir = dirname($m->path);
            $ext = pathinfo($m->path, PATHINFO_EXTENSION);
            $newPath = $dir.'/'.$stem.'.'.$ext;
            if ($newPath !== $m->path) {
                if (File::exists(public_path(ltrim($newPath, '/'))) || Media::withTrashed()->where('path', $newPath)->exists()) {
                    throw new ToolError("$newPath already exists.");
                }
                $moves = [$m->path => $newPath];
                $variants = $m->variants ?? [];
                foreach ($variants as $w => $v) {
                    if ($v !== $m->path) {
                        $moves[$v] = $dir.'/'.$stem.'-'.$w.'.'.pathinfo($v, PATHINFO_EXTENSION);
                    }
                    $variants[$w] = $moves[$v];
                }
                if ($m->original_path) {
                    $moves[$m->original_path] = $dir.'/'.$stem.'-original.'.pathinfo($m->original_path, PATHINFO_EXTENSION);
                }
                foreach ($moves as $from => $to) {
                    if (File::exists(public_path(ltrim($from, '/')))) {
                        File::move(public_path(ltrim($from, '/')), public_path(ltrim($to, '/')));
                    }
                }
                foreach ($moves as $from => $to) {
                    if ($from !== $m->original_path) {
                        Images::rewrite($from, $to, false);
                        Redirects::save($from, $to, 301, 'image renamed');
                    }
                }
                $notes['renamed'] = $moves;
                $m->path = $newPath;
                $m->filename = basename($newPath);
                $m->variants = $variants ?: null;
                $m->original_path = $moves[$m->original_path] ?? $m->original_path;
            }
        }
        $m->save();
        Activity::log('update', $m, $before, self::out($m), 'image '.$m->path);

        return ['image' => self::out($m->fresh()), 'changes' => $notes];
    }

    private function replace(array $a): array
    {
        $dry = $a['dry_run'] ?? true;
        $old = '/'.ltrim($a['old_path'], '/');
        $new = '/'.ltrim($a['new_path'], '/');
        $file = public_path(ltrim($new, '/'));
        if (! is_file($file)) {
            throw new ToolError("$new does not exist (upload it first).");
        }
        $size = @getimagesize($file);
        $tag = $size ? function (string $img) use ($size) {
            $img = preg_replace('/\swidth="\d+"/', ' width="'.$size[0].'"', $img);

            return preg_replace('/\sheight="\d+"/', ' height="'.$size[1].'"', $img);
        } : null;
        $changed = Images::rewrite($old, $new, $dry, $tag);
        if (! $dry && $changed) {
            Activity::log('update', Media::query()->where('path', $old)->first(), ['path' => $old], ['path' => $new], "image $old replaced by $new");
        }

        return ['dry_run' => $dry, 'places' => count($changed), 'changed' => $changed, 'next' => $dry && $changed ? 'Run again with dry_run=false to apply.' : null];
    }

    private function remove(array $a): array
    {
        $m = $this->guard($this->find($a), $a, 'image');
        $used = Images::usage()[$m->path] ?? [];
        if ($used && ! ($a['force'] ?? false)) {
            throw new ToolError('This image is used in '.count($used).' place(s). Replace it first (replace_image) or pass force=true.', ['used_in' => $used]);
        }
        $before = self::out($m, $used);
        $m->delete();
        Activity::log('delete', $m, $before, null, 'image '.$m->path);

        return ['deleted' => $before, 'note' => 'The file stays until purge_item; restore_item type=image brings it back.'];
    }

    private function audit(array $a): array
    {
        $entries = (! empty($a['page_id']) || ! empty($a['post_id'])) ? [SiteIndex::entry($this->findOwner($a))] : array_values(SiteIndex::all());
        $byImage = [];
        foreach ($entries as $e) {
            preg_match_all('#<img\s[^>]*>#i', $e['main'], $imgs);
            foreach ($imgs[0] as $i => $img) {
                preg_match('#src="([^"]+)"#', $img, $s);
                $external = isset($s[1]) && preg_match('#^(https?:)?//#i', $s[1]) && \App\Mcp\Support\Html::internalPath($s[1]) === null;
                $src = isset($s[1]) ? ($external ? $s[1] : rawurldecode((string) parse_url($s[1], PHP_URL_PATH))) : '';
                $issues = [];
                preg_match('/\salt="([^"]*)"/', $img, $alt);
                $altText = html_entity_decode($alt[1] ?? '', ENT_QUOTES | ENT_HTML5);
                $stem = pathinfo($src, PATHINFO_FILENAME);
                if (! isset($alt[1])) {
                    $issues[] = 'no alt attribute';
                } elseif ($altText === '' && ! str_contains($img, 'aria-hidden') && ! str_contains($e['main'], 'aria-hidden="true">'.$img)) {
                    $issues[] = 'empty alt';
                } elseif (preg_match('/\.(jpe?g|png|webp|gif)$|^(img|image|dsc|photo|screenshot)[-_ ]?\d+|^[\w-]*\d{6,}[\w-]*$/i', $altText) || ($altText !== '' && mb_strtolower($altText) === mb_strtolower(str_replace(['-', '_'], ' ', $stem)))) {
                    $issues[] = 'alt looks like a file name';
                }
                if (! str_contains($img, 'width=') || ! str_contains($img, 'height=')) {
                    $issues[] = 'no width/height';
                }
                if ($i > 0 && ! str_contains($img, 'loading="lazy"')) {
                    $issues[] = 'below the fold but not lazy';
                }
                $file = public_path(ltrim($src, '/'));
                if ($external) {
                    // hosted elsewhere (e.g. Gravatar): size and format are not checked
                } elseif ($src && is_file($file)) {
                    if (filesize($file) > 300 * 1024) {
                        $issues[] = round(filesize($file) / 1024).' KB';
                    }
                    if (! preg_match('/\.(webp|avif|svg)$/i', $src) && ! is_file(preg_replace('/\.\w+$/', '.webp', $file))) {
                        $issues[] = 'no WebP version';
                    }
                } elseif ($src && str_starts_with($src, '/')) {
                    $issues[] = 'file missing';
                }
                if ($issues) {
                    $key = $src ?: '(no src)';
                    $byImage[$key]['issues'] = array_values(array_unique(array_merge($byImage[$key]['issues'] ?? [], $issues)));
                    $byImage[$key]['pages'][$e['path']] = true;
                }
            }
        }
        $rows = [];
        $counts = [];
        foreach ($byImage as $src => $r) {
            $rows[] = ['image' => $src, 'issues' => $r['issues'], 'pages' => count($r['pages']), 'examples' => array_slice(array_keys($r['pages']), 0, 5)];
            foreach ($r['issues'] as $i) {
                $k = preg_replace('/^\d+ KB$/', 'over 300 KB', $i);
                $counts[$k] = ($counts[$k] ?? 0) + 1;
            }
        }
        arsort($counts);

        return ['images_with_issues' => count($rows), 'issue_counts' => $counts, 'images' => $rows];
    }

    private function bulkAlt(array $a): array
    {
        $dry = $a['dry_run'] ?? true;
        $out = [];
        foreach ($a['items'] as $item) {
            $m = $this->find($item);
            $alt = trim(strip_tags((string) ($item['alt'] ?? '')));
            if ($alt === '') {
                $out[] = ['path' => $m->path, 'error' => 'alt missing'];

                continue;
            }
            $places = Images::setAlt($m->path, $alt, $dry);
            if (! $dry) {
                $before = $m->alt;
                $m->update(['alt' => $alt]);
                Activity::log('update', $m, ['alt' => $before], ['alt' => $alt], 'alt of '.$m->path);
            }
            $out[] = ['path' => $m->path, 'before' => $m->getOriginal('alt'), 'after' => $alt, 'pages' => $places];
        }

        return ['dry_run' => $dry, 'results' => $out, 'next' => $dry ? 'Run again with dry_run=false to apply.' : null];
    }
}
