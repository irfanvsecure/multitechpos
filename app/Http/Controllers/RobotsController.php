<?php

namespace App\Http\Controllers;

use App\Cms\Cms;
use App\Cms\Settings;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/** /robots.txt from the "robots_txt" setting. SITE_NOINDEX=true replaces it with "Disallow: /". */
class RobotsController extends Controller
{
    public function __invoke(Request $request): Response
    {
        return response(self::body($request->getSchemeAndHttpHost().$request->getBaseUrl()), 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }

    public static function body(string $site): string
    {
        if (Cms::noindex()) {
            return "# SITE_NOINDEX is on (pre-launch): nothing may be crawled.\nUser-agent: *\nDisallow: /\n";
        }

        return rtrim((string) Settings::get('robots_txt'))."\n\nSitemap: ".$site.'/sitemap.xml';
    }
}
