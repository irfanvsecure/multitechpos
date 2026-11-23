<?php

namespace App\Mcp\Support;

use App\Mcp\ToolError;
use App\Models\ClientLogo;
use App\Models\HardwareItem;
use App\Models\PageSection;
use App\Models\Post;
use App\Models\SeoMeta;
use App\Models\Testimonial;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

/** Image files: upload pipeline (WebP + srcset), where images are used, reference rewrites. */
class Images
{
    public const MAX_BYTES = 15 * 1024 * 1024;

    public const WIDTHS = [480, 800, 1200];

    /** path => list of places it is used */
    public static function usage(): array
    {
        $map = [];
        $scan = function (?string $text, string $where) use (&$map) {
            if (! $text) {
                return;
            }
            preg_match_all('#/images/[^"\'\s)<>,]+?\.(?:jpe?g|png|gif|webp|avif|svg)#i', $text, $m);
            foreach (array_unique($m[0]) as $path) {
                $map[rawurldecode($path)][] = $where;
            }
        };
        foreach (PageSection::query()->with('page:id,path')->get() as $s) {
            $scan($s->content.' '.json_encode($s->attrs, JSON_UNESCAPED_SLASHES), 'page '.$s->page?->path.' (section #'.$s->id.' '.$s->key.')');
        }
        foreach (Post::query()->get() as $p) {
            $scan($p->content.' '.$p->cover_image.' '.$p->card_image.' '.$p->archive_image, 'post '.$p->path);
        }
        foreach (SeoMeta::query()->whereNotNull('og_image')->with('seoable')->get() as $m) {
            $scan($m->og_image, 'share image of '.$m->seoable?->path);
        }
        foreach (ClientLogo::query()->get() as $l) {
            $scan($l->image, 'client logo '.$l->name);
        }
        foreach (Testimonial::query()->whereNotNull('photo')->get() as $t) {
            $scan($t->photo, 'testimonial #'.$t->id);
        }
        foreach (HardwareItem::query()->whereNotNull('image')->get() as $h) {
            $scan($h->image, 'hardware '.$h->name);
        }

        return $map;
    }

    /** Raw bytes from an https URL or base64 (data URI allowed). */
    public static function fetch(?string $url, ?string $base64): array
    {
        if ($url) {
            if (! str_starts_with($url, 'https://') && ! str_starts_with($url, 'http://')) {
                throw new ToolError('url must be http(s).');
            }
            Validate::notExecutable($url);
            $host = parse_url($url, PHP_URL_HOST);
            $ip = gethostbyname((string) $host);
            if (! filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                throw new ToolError('That URL points to a private network address.');
            }
            $res = Http::timeout(25)->withHeaders(['User-Agent' => 'Mozilla/5.0 (compatible; MultiTechPOS-site/1.0)'])->get($url);
            if (! $res->successful()) {
                throw new ToolError('Could not download the image: HTTP '.$res->status());
            }
            $bytes = $res->body();
            $name = basename((string) parse_url($url, PHP_URL_PATH));
        } elseif ($base64) {
            if (preg_match('#^data:([^;]+);base64,#', $base64, $m)) {
                $base64 = substr($base64, strlen($m[0]));
            }
            $bytes = base64_decode($base64, true);
            if ($bytes === false) {
                throw new ToolError('base64 is not valid.');
            }
            $name = 'image';
        } else {
            throw new ToolError('Give url or base64.');
        }
        if (strlen($bytes) > self::MAX_BYTES) {
            throw new ToolError('The image is larger than 15 MB.');
        }

        return [$bytes, $name];
    }

    /** Image type check: only JPEG, PNG, GIF and WebP (never SVG, executables or archives). */
    public static function inspect(string $bytes): array
    {
        $magic = substr($bytes, 0, 8);
        if (str_starts_with($magic, 'MZ') || str_starts_with($magic, "PK\x03\x04") || str_starts_with($magic, "\x7fELF") || str_starts_with($magic, 'Rar!') || str_starts_with($magic, "7z\xBC\xAF")) {
            throw new ToolError('This is an executable or archive, not an image. Installers and archives can never be uploaded through the MCP.');
        }
        $info = @getimagesizefromstring($bytes);
        $types = [IMAGETYPE_JPEG => 'jpg', IMAGETYPE_PNG => 'png', IMAGETYPE_GIF => 'gif', IMAGETYPE_WEBP => 'webp'];
        if (! $info || ! isset($types[$info[2]])) {
            throw new ToolError('Only JPEG, PNG, GIF or WebP images can be uploaded.');
        }

        return ['width' => $info[0], 'height' => $info[1], 'ext' => $types[$info[2]], 'mime' => $info['mime']];
    }

    /** Full upload: check, store (WebP + variants), add to the media library, log. Used by upload_image and the upload page. */
    public static function create(string $bytes, string $alt, ?string $filename = null, ?string $title = null, ?array $geo = null): \App\Models\Media
    {
        $alt = trim(strip_tags($alt));
        if (mb_strlen($alt) < 5) {
            throw new ToolError('alt must describe the image (at least 5 characters).');
        }
        if ($filename !== null && $filename !== '') {
            Validate::notExecutable($filename);
        }
        $geo = self::geo($geo);
        $title = $title !== null && trim(strip_tags($title)) !== '' ? mb_substr(trim(strip_tags($title)), 0, 500) : null;
        $info = self::inspect($bytes);
        $stored = self::store($bytes, $info, self::slugName($filename ?: $alt));

        // GD drops all metadata: write description + GPS back into every WebP file
        $exif = self::exif($title ?: $alt, $geo);
        foreach (array_unique(array_merge([$stored['path']], array_values($stored['variants']))) as $p) {
            self::addExifToWebp(public_path(ltrim($p, '/')), $exif);
        }
        $stored['size'] = filesize(public_path(ltrim($stored['path'], '/')));

        $media = \App\Models\Media::query()->create($stored + [
            'filename' => basename($stored['path']), 'mime' => 'image/webp', 'alt' => mb_substr($alt, 0, 250),
            'title' => $title, 'geo' => $geo,
        ]);
        Activity::log('create', $media, null, \App\Mcp\Tools\ImageTools::out($media), 'image '.$media->path);

        return $media;
    }

    /** Check {lat, lng, place?}. Null when not given. */
    public static function geo(?array $geo): ?array
    {
        if (! $geo || (! isset($geo['lat']) && ! isset($geo['lng']))) {
            return null;
        }
        $lat = filter_var($geo['lat'] ?? null, FILTER_VALIDATE_FLOAT);
        $lng = filter_var($geo['lng'] ?? null, FILTER_VALIDATE_FLOAT);
        if ($lat === false || $lng === false || abs($lat) > 90 || abs($lng) > 180) {
            throw new ToolError('geo needs lat (-90..90) and lng (-180..180) as numbers, e.g. {"lat": 25.3463, "lng": 55.4209, "place": "Sharjah, UAE"}.');
        }
        $place = isset($geo['place']) ? mb_substr(trim(strip_tags((string) $geo['place'])), 0, 120) : null;

        return ['lat' => round($lat, 6), 'lng' => round($lng, 6)] + ($place ? ['place' => $place] : []);
    }

    /**
     * Minimal EXIF (little-endian TIFF): ImageDescription + Artist + GPS latitude/longitude.
     */
    public static function exif(string $description, ?array $geo): string
    {
        $ascii = fn (string $s) => [2, strlen($s) + 1, $s."\0"];
        $rational = function (float $v) {
            $v = abs($v);
            $d = (int) floor($v);
            $m = (int) floor(($v - $d) * 60);
            $s = (int) round((($v - $d) * 60 - $m) * 60 * 10000);

            return [5, 3, pack('V6', $d, 1, $m, 1, $s, 10000)];
        };
        $desc = mb_substr(preg_replace('/[^\x20-\x7E]/', '', Str::ascii($description)), 0, 500);
        $ifd0 = [0x010E => $ascii($desc), 0x013B => $ascii('MultiTech POS')];
        $gps = null;
        if ($geo) {
            $gps = [
                0x0000 => [1, 4, "\x02\x03\x00\x00"],
                0x0001 => $ascii($geo['lat'] >= 0 ? 'N' : 'S'),
                0x0002 => $rational($geo['lat']),
                0x0003 => $ascii($geo['lng'] >= 0 ? 'E' : 'W'),
                0x0004 => $rational($geo['lng']),
            ];
            $ifd0[0x8825] = [4, 1, pack('V', 0)];   // GPS IFD offset, filled in below
        }
        $ifdLength = fn (array $e) => 2 + 12 * count($e) + 4 + array_sum(array_map(fn ($x) => strlen($x[2]) > 4 ? strlen($x[2]) + (strlen($x[2]) % 2) : 0, $e));
        if ($gps) {
            $ifd0[0x8825][2] = pack('V', 8 + $ifdLength($ifd0));
        }
        $write = function (array $entries, int $offset) {
            ksort($entries);
            $data = '';
            $dataAt = $offset + 2 + 12 * count($entries) + 4;
            $out = pack('v', count($entries));
            foreach ($entries as $tag => [$type, $count, $value]) {
                if (strlen($value) <= 4) {
                    $out .= pack('vvV', $tag, $type, $count).str_pad($value, 4, "\0");
                } else {
                    $out .= pack('vvVV', $tag, $type, $count, $dataAt + strlen($data));
                    $data .= $value.(strlen($value) % 2 ? "\0" : '');
                }
            }

            return $out.pack('V', 0).$data;
        };
        $tiff = "II\x2A\x00".pack('V', 8);
        $tiff .= $write($ifd0, 8);
        if ($gps) {
            $tiff .= $write($gps, strlen($tiff));
        }

        return $tiff;
    }

    /** Put an EXIF chunk into a WebP file (adds the VP8X header that EXIF needs). */
    public static function addExifToWebp(string $file, string $exif): void
    {
        $bytes = @file_get_contents($file);
        if (! $bytes || substr($bytes, 0, 4) !== 'RIFF' || substr($bytes, 8, 4) !== 'WEBP') {
            return;
        }
        $chunks = [];
        for ($pos = 12; $pos + 8 <= strlen($bytes);) {
            $fourcc = substr($bytes, $pos, 4);
            $size = unpack('V', substr($bytes, $pos + 4, 4))[1];
            $chunks[] = [$fourcc, substr($bytes, $pos + 8, $size)];
            $pos += 8 + $size + ($size % 2);
        }
        $chunks = array_values(array_filter($chunks, fn ($c) => $c[0] !== 'EXIF'));
        if (! $chunks) {
            return;
        }
        if ($chunks[0][0] === 'VP8X') {
            $chunks[0][1][0] = chr(ord($chunks[0][1][0]) | 0x08);
        } else {
            $size = @getimagesizefromstring($bytes);
            if (! $size) {
                return;
            }
            $flags = 0x08 | ($chunks[0][0] === 'VP8L' ? 0x10 : 0);
            $w = $size[0] - 1;
            $h = $size[1] - 1;
            $vp8x = chr($flags)."\0\0\0".substr(pack('V', $w), 0, 3).substr(pack('V', $h), 0, 3);
            array_unshift($chunks, ['VP8X', $vp8x]);
        }
        $chunks[] = ['EXIF', $exif];
        $body = 'WEBP';
        foreach ($chunks as [$fourcc, $payload]) {
            $body .= $fourcc.pack('V', strlen($payload)).$payload.(strlen($payload) % 2 ? "\0" : '');
        }
        file_put_contents($file, 'RIFF'.pack('V', strlen($body)).$body);
    }

    /**
     * Show $new wherever $old is shown: src, srcset (rebuilt from the new image's sizes), width/height,
     * and the new image's alt text. Returns the places changed.
     */
    public static function replaceEverywhere(string $old, string $new, bool $dry): array
    {
        $old = '/'.ltrim($old, '/');
        $new = '/'.ltrim($new, '/');
        $file = public_path(ltrim($new, '/'));
        if (! is_file($file)) {
            throw new ToolError("$new does not exist (upload it first).");
        }
        if ($old === $new) {
            throw new ToolError('old_path and new_path are the same image.');
        }
        $media = \App\Models\Media::query()->where('path', $new)->first();
        $oldMedia = \App\Models\Media::query()->where('path', $old)->first();
        $size = @getimagesize($file);
        $srcset = $media ? collect($media->variants ?? [])->sortKeys()->map(fn ($p, $w) => '{{base}}'.$p.' '.$w.'w')->implode(', ') : '';
        $tag = function (string $img) use ($size, $srcset) {
            if ($size) {
                $img = preg_replace('/\swidth="\d+"/', ' width="'.$size[0].'"', $img);
                $img = preg_replace('/\sheight="\d+"/', ' height="'.$size[1].'"', $img);
            }
            // the old srcset lists the old image's other sizes: the browser would keep showing it
            if (preg_match('/\ssrcset="[^"]*"/', $img)) {
                $img = $srcset !== ''
                    ? preg_replace('/\ssrcset="[^"]*"/', ' srcset="'.$srcset.'"', $img)
                    : preg_replace('/\s(srcset|sizes)="[^"]*"/', '', $img);
            }

            return $img;
        };
        $changed = self::rewrite($old, $new, $dry, $tag);
        // old variant files (x-480.webp ...) referenced on their own, e.g. in a <picture><source>
        foreach (($oldMedia?->variants ?? []) as $w => $v) {
            if ($v !== $old) {
                $to = $media->variants[$w] ?? $new;
                $changed = array_merge($changed, self::rewrite($v, $to, $dry));
            }
        }
        $changed = array_values(array_unique($changed));
        if ($media && $media->alt && ! $dry && $changed) {
            self::setAlt($new, $media->alt, false);
        }
        if (! $dry && $changed) {
            Activity::log('update', $oldMedia ?? $media, ['path' => $old], ['path' => $new], "image $old replaced by $new");
        }

        return $changed;
    }

    public static function slugName(string $name): string
    {
        $stem = Str::slug(pathinfo($name, PATHINFO_FILENAME)) ?: 'image';

        return Str::limit($stem, 80, '');
    }

    /**
     * Save original + WebP (max 1600px) + 480/800/1200 variants under /images/uploads/Y/m/.
     *
     * @return array{path:string,original_path:string,variants:array,width:int,height:int,size:int}
     */
    public static function store(string $bytes, array $info, string $stem): array
    {
        if (! function_exists('imagewebp')) {
            throw new ToolError('The server\'s PHP GD has no WebP support.');
        }
        $dir = 'images/uploads/'.date('Y/m');
        File::ensureDirectoryExists(public_path($dir));
        $stem = self::unique($dir, $stem);

        $originalPath = '/'.$dir.'/'.$stem.'-original.'.$info['ext'];
        File::put(public_path(ltrim($originalPath, '/')), $bytes);

        $src = @imagecreatefromstring($bytes);
        if (! $src) {
            throw new ToolError('The image could not be read.');
        }
        imagepalettetotruecolor($src);
        $main = self::resize($src, $info['width'], $info['height'], 1600);
        $path = '/'.$dir.'/'.$stem.'.webp';
        imagewebp($main, public_path(ltrim($path, '/')), 82);
        [$w, $h] = [imagesx($main), imagesy($main)];
        imagedestroy($main);

        $variants = [];
        foreach (self::WIDTHS as $width) {
            if ($width < $w) {
                $img = self::resize($src, $info['width'], $info['height'], $width);
                $v = '/'.$dir.'/'.$stem.'-'.$width.'.webp';
                imagewebp($img, public_path(ltrim($v, '/')), 80);
                imagedestroy($img);
                $variants[(string) $width] = $v;
            }
        }
        imagedestroy($src);
        $variants[(string) $w] = $path;

        return ['path' => $path, 'original_path' => $originalPath, 'variants' => $variants, 'width' => $w, 'height' => $h,
            'size' => filesize(public_path(ltrim($path, '/')))];
    }

    private static function resize(\GdImage $src, int $w, int $h, int $max): \GdImage
    {
        $nw = min($w, $max);
        $nh = (int) round($h * $nw / $w);
        $dst = imagecreatetruecolor($nw, $nh);
        imagealphablending($dst, false);
        imagesavealpha($dst, true);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $nw, $nh, $w, $h);

        return $dst;
    }

    private static function unique(string $dir, string $stem): string
    {
        $try = $stem;
        $i = 2;
        while (File::exists(public_path($dir.'/'.$try.'.webp')) || File::exists(public_path($dir.'/'.$try.'-original.jpg'))) {
            $try = $stem.'-'.$i++;
        }

        return $try;
    }

    /** <img> snippet with srcset for content. */
    public static function snippet(\App\Models\Media $m): string
    {
        $srcset = collect($m->variants ?? [])->sortKeys()->map(fn ($p, $w) => '{{base}}'.$p.' '.$w.'w')->implode(', ');

        return '<img src="{{base}}'.$m->path.'"'.($srcset ? ' srcset="'.$srcset.'" sizes="(max-width: 800px) 100vw, 800px"' : '')
            .' alt="'.e((string) $m->alt).'" width="'.$m->width.'" height="'.$m->height.'" loading="lazy">';
    }

    /**
     * Replace one image path with another in every stored place. Returns the places changed.
     */
    public static function rewrite(string $from, string $to, bool $dry, ?callable $imgTag = null): array
    {
        $changed = [];
        $swap = function (?string $text) use ($from, $to, $imgTag) {
            if ($text === null || ! str_contains($text, $from)) {
                return [$text, 0];
            }
            $n = substr_count($text, $from);
            if ($imgTag) {
                $text = preg_replace_callback('#<img\b[^>]*>#i', fn ($m) => str_contains($m[0], $from) ? $imgTag($m[0]) : $m[0], $text);
            }

            return [str_replace($from, $to, $text), $n];
        };
        foreach (PageSection::query()->with('page:id,path')->get() as $s) {
            [$content, $n1] = $swap($s->content);
            $attrsJson = json_encode($s->attrs, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            [$attrsNew, $n2] = $swap($attrsJson);
            if ($n1 + $n2) {
                $changed[] = 'page '.$s->page?->path.' section #'.$s->id;
                if (! $dry) {
                    Activity::revision($s, 'before image change');
                    $s->update(['content' => $content, 'attrs' => json_decode($attrsNew, true)]);
                }
            }
        }
        foreach (Post::query()->get() as $p) {
            $fields = [];
            foreach (['content', 'cover_image', 'card_image', 'archive_image'] as $f) {
                [$v, $n] = $swap($p->{$f});
                if ($n) {
                    $fields[$f] = $v;
                }
            }
            if ($fields) {
                $changed[] = 'post '.$p->path;
                if (! $dry) {
                    Activity::revision($p, 'before image change');
                    $p->update($fields);
                }
            }
        }
        foreach ([[SeoMeta::class, 'og_image'], [ClientLogo::class, 'image'], [Testimonial::class, 'photo'], [HardwareItem::class, 'image']] as [$class, $field]) {
            foreach ($class::query()->where($field, $from)->get() as $row) {
                $changed[] = class_basename($class).' #'.$row->id;
                if (! $dry) {
                    $row->update([$field => $to]);
                }
            }
        }

        return $changed;
    }

    /** Set alt="..." on every <img> showing $path (in sections and posts). */
    public static function setAlt(string $path, string $alt, bool $dry): array
    {
        $changed = [];
        $fix = function (?string $html) use ($path, $alt) {
            $n = 0;
            $html = preg_replace_callback('#<img\b[^>]*>#i', function ($m) use ($path, $alt, &$n) {
                if (! preg_match('#\ssrc="(?:\{\{base\}\})?'.preg_quote($path, '#').'"#', $m[0])) {
                    return $m[0];
                }
                $n++;
                $attr = 'alt="'.e($alt).'"';

                return preg_match('/\salt="[^"]*"/', $m[0]) ? preg_replace('/\salt="[^"]*"/', ' '.$attr, $m[0], 1) : preg_replace('#\s*/?>$#', ' '.$attr.'>', $m[0]);
            }, (string) $html);

            return [$html, $n];
        };
        foreach (PageSection::query()->where('type', 'html')->where('content', 'like', '%'.$path.'%')->with('page:id,path')->get() as $s) {
            [$html, $n] = $fix($s->content);
            if ($n) {
                $changed[] = 'page '.$s->page?->path.' section #'.$s->id.' ('.$n.')';
                if (! $dry) {
                    Activity::revision($s, 'before alt change');
                    $s->update(['content' => $html]);
                }
            }
        }
        foreach (Post::query()->where('content', 'like', '%'.$path.'%')->get() as $p) {
            [$html, $n] = $fix($p->content);
            if ($n) {
                $changed[] = 'post '.$p->path.' ('.$n.')';
                if (! $dry) {
                    Activity::revision($p, 'before alt change');
                    $p->update(['content' => $html]);
                }
            }
        }

        return $changed;
    }
}
