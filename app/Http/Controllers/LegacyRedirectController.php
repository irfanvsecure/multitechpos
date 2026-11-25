<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * 301 for an old WordPress URL. The target comes from the route's "to" default:
 *   Route::get('/feed', LegacyRedirectController::class)->defaults('to', '/our-blogs/');
 * A controller (not a closure) so it still works after `php artisan route:cache`.
 * RedirectResponse is used directly because redirect() would drop the trailing slash.
 */
class LegacyRedirectController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        $to = (string) $request->route()->defaults['to'];

        return new RedirectResponse($request->getSchemeAndHttpHost().$request->getBaseUrl().$to, 301);
    }
}
