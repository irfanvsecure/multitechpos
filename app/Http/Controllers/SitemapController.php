<?php

namespace App\Http\Controllers;

use App\Cms\Sitemap;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/** /sitemap.xml from the published pages and posts in the database. */
class SitemapController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $site = $request->getSchemeAndHttpHost().rtrim($request->getBasePath(), '/');
        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
        foreach (Sitemap::entries() as $url) {
            $xml .= '  <url><loc>'.e($site.$url['path']).'</loc><lastmod>'.$url['lastmod'].'</lastmod><priority>'.$url['priority'].'</priority></url>'."\n";
        }
        $xml .= '</urlset>'."\n";

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }
}
