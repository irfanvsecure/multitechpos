<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\Response;

/**
 * /sitemap.xml built from the page views, so new pages appear automatically.
 */
class SitemapController extends Controller
{
    /** View folders / files that are not public pages. */
    private const SKIP = ['layouts', 'partials', 'components', 'errors', 'welcome', 'thank-you'];

    public function __invoke(Request $request): Response
    {
        $root = resource_path('views');
        $site = $request->getSchemeAndHttpHost().rtrim($request->getBasePath(), '/');
        $urls = [];

        foreach (File::allFiles($root) as $file) {
            $name = str_replace('\\', '/', $file->getRelativePathname());
            if (! str_ends_with($name, '.blade.php')) {
                continue;
            }

            $slug = substr($name, 0, -strlen('.blade.php'));
            if (in_array(explode('/', $slug)[0], self::SKIP, true)) {
                continue;
            }

            $path = $slug === 'index' ? '/' : '/'.$slug.'/';
            $urls[] = [
                'loc' => $site.$path,
                'lastmod' => date('Y-m-d', $file->getMTime()),
                'priority' => $slug === 'index' ? '1.0' : (str_contains($file->getContents(), "'page-post'") ? '0.6' : '0.8'),
            ];
        }

        usort($urls, fn ($a, $b) => strcmp($a['loc'], $b['loc']));

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
        foreach ($urls as $url) {
            $xml .= '  <url><loc>'.e($url['loc']).'</loc><lastmod>'.$url['lastmod'].'</lastmod><priority>'.$url['priority'].'</priority></url>'."\n";
        }
        $xml .= '</urlset>'."\n";

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }
}
