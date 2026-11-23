<?php

namespace App\Http\Controllers;

use App\Mcp\Context;
use App\Mcp\Support\Images;
use App\Mcp\ToolError;
use App\Models\Media;
use App\Models\UploadLink;
use App\Support\AutoMigrate;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\Response;

/**
 * The page behind create_image_upload_link: the owner picks image files in a browser and they go
 * into the media library. The unguessable token in the URL is the only authentication, so the
 * link is single-purpose: images only, a few files, a short lifetime.
 */
class ImageUploadController extends Controller
{
    public function show(string $token): Response
    {
        AutoMigrate::ensure();
        $link = UploadLink::findByToken($token);

        return $this->page($link, $token);
    }

    public function store(Request $request, string $token): Response
    {
        AutoMigrate::ensure();
        $link = UploadLink::findByToken($token);
        if (! $link || $link->expired() || $link->remaining() === 0) {
            return $this->page($link, $token);
        }

        $files = $request->file('images');
        $files = array_values(array_filter(is_array($files) ? $files : ($files ? [$files] : [])));
        $errors = [];
        if (! $files) {
            // post_max_size exceeded: PHP drops the whole body
            $errors[] = (int) $request->server('CONTENT_LENGTH') > 0
                ? 'The file is too large for the server. Use an image under '.ini_get('upload_max_filesize').'.'
                : 'Choose an image first.';
        }
        if (count($files) > $link->remaining()) {
            $errors[] = 'This link takes '.$link->remaining().' more image(s). The first '.$link->remaining().' were used.';
            $files = array_slice($files, 0, $link->remaining());
        }

        $alt = trim((string) $request->input('alt', $link->alt ?? ''));
        $ids = $link->media_ids ?? [];
        Context::$tool = 'upload_page';
        foreach ($files as $i => $file) {
            /** @var UploadedFile $file */
            $name = $file->getClientOriginalName();
            if (! $file->isValid()) {
                $errors[] = $name.': '.($file->getError() === UPLOAD_ERR_INI_SIZE ? 'too large for the server (max '.ini_get('upload_max_filesize').').' : 'upload failed, try again.');

                continue;
            }
            if ($file->getSize() > Images::MAX_BYTES) {
                $errors[] = $name.': larger than 15 MB.';

                continue;
            }
            try {
                $stem = $link->filename ?: ($alt ?: pathinfo($name, PATHINFO_FILENAME));
                $media = Images::create(
                    (string) file_get_contents($file->getRealPath()),
                    $alt !== '' ? $alt : (string) $link->purpose,
                    count($files) > 1 ? $stem.'-'.($i + 1) : $stem,
                    $link->title,
                    $link->geo,
                );
                $ids[] = $media->id;
                if ($link->replace_path) {
                    $this->swap($link, $media);
                }
            } catch (ToolError $e) {
                $errors[] = $name.': '.$e->getMessage();
            }
        }
        $added = count($ids) - count($link->media_ids ?? []);
        $link->update(['media_ids' => $ids]);

        return $this->page($link->fresh(), $token, $errors, $added === 0 ? 422 : 200);
    }

    /** Put the uploaded image on the page in place of replace_path (the "set" half of upload-and-set). */
    private function swap(UploadLink $link, Media $media): void
    {
        try {
            $changed = Images::replaceEverywhere($link->replace_path, $media->path, false);
            $link->update(['replaced' => $changed, 'replace_error' => $changed ? null : $link->replace_path.' is no longer used on any page']);
        } catch (\Throwable $e) {
            report($e);
            $link->update(['replace_error' => mb_substr($e->getMessage(), 0, 250)]);
        }
    }

    private function page(?UploadLink $link, string $token, array $errors = [], ?int $status = null): Response
    {
        $state = ! $link ? 'missing' : ($link->remaining() === 0 ? 'done' : ($link->expired() ? 'expired' : 'open'));
        $images = $link ? Media::query()->whereIn('id', $link->media_ids ?? [])->get() : collect();

        return response()->view('mcp.upload', ['link' => $link, 'token' => $token, 'problems' => $errors, 'state' => $state, 'images' => $images], $status ?? match ($state) {
            'missing' => 404, 'expired' => 410, default => 200,
        })->withHeaders([
            'Cache-Control' => 'no-store, private',
            'X-Robots-Tag' => 'noindex, nofollow',
            'Referrer-Policy' => 'no-referrer',
        ]);
    }
}
