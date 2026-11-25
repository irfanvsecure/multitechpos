<?php

use App\Http\Controllers\LeadController;
use App\Http\Controllers\LegacyRedirectController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\RobotsController;
use App\Http\Controllers\SitemapController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// SEO
Route::get('/sitemap.xml', SitemapController::class);
Route::get('/robots.txt', RobotsController::class);

// Demo / quote forms
Route::post('/request-demo', [LeadController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('lead.store');

// Old WordPress URLs -> their new home (301, keeps search rankings).
// A controller, not a closure: nested closures break under `php artisan route:cache` (500 on prod).
$moved = fn (string $uri, string $to) => Route::get($uri, LegacyRedirectController::class)->defaults('to', $to);

foreach (['wp-sitemap.xml', 'sitemap_index.xml', 'page-sitemap.xml', 'post-sitemap.xml'] as $old) {
    $moved($old, '/sitemap.xml');
}
$moved('/wp-sitemap-{any}.xml', '/sitemap.xml')->where('any', '.*');
$moved('/feed', '/our-blogs/');
$moved('/comments/feed', '/our-blogs/');
$moved('/blog', '/our-blogs/');
$moved('/category/{any}', '/our-blogs/')->where('any', '.*');
$moved('/author/multitech_admin/page/{n}', '/author/multitech_admin/');
$moved('/download/restropro', '/restropro/');

// Images moved from /wp-content/uploads/... to /images/...
Route::get('/wp-content/uploads/{path}', fn (Request $request, string $path) => new RedirectResponse(
    $request->getSchemeAndHttpHost().$request->getBaseUrl().'/images/'.$path, 301
))->where('path', '.*');

// ---------------------------------------------------------------------------
// Pages (one route per page, grouped like the header menu).
// To add a page: create resources/views/<slug>.blade.php and add a line here.
// ---------------------------------------------------------------------------

// Company (3)
Route::get('/', function (\Illuminate\Http\Request $request) {
    // Redirect legacy ?item/... URLs (e.g. /?item/8109777586) to clean home
    if (str_starts_with($request->server('QUERY_STRING', ''), 'item')) {
        return redirect('/', 301);
    }
    // WordPress short links /?p=123 and /?page_id=123 (rows in the redirects table)
    foreach (['p', 'page_id'] as $key) {
        if (ctype_digit((string) $request->query($key))) {
            $redirect = \App\Models\Redirect::query()->where('from_path', '/?'.$key.'='.$request->query($key))->first();
            if ($redirect && $redirect->to_url) {
                return new RedirectResponse($request->getSchemeAndHttpHost().$request->getBaseUrl().$redirect->to_url, 301);
            }
        }
    }
    return app(PageController::class)->show($request, '');
})->name('home');

// POS Software (11)

// POS Features (8)

// Business ERPs (12)

// Downloads (5)

// Industries (39)

// Blog (42)

// Legal & help (6)

// After a demo / quote form is sent (not indexed by search engines)

// Pages and blog posts stored in the database (managed through the MCP server).
// Unknown paths: a saved redirect / 410, else 301 to the home page (the old catch-all).
Route::get('/{path}', [PageController::class, 'show'])->where('path', '(?!mcp(?:/|$)).*')->name('page');

// Catch-all for other methods: any URL not matched above → home (301)
Route::fallback(fn () => redirect('/', 301));
