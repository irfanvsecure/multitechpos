<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Every page lives at one URL ending in "/" (same as the old WordPress site),
 * so /about-us is redirected to /about-us/ instead of serving duplicate content.
 * Old WordPress links like /index.php/features/ are sent to /features/.
 */
class AddTrailingSlash
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->isMethod('GET') && ! $request->isMethod('HEAD')) {
            return $next($request);
        }

        $site = $request->getSchemeAndHttpHost();
        $uri = $request->getRequestUri();
        $query = $request->getQueryString();

        // /index.php/features -> /features/
        if (preg_match('#^(.*?)/index\.php(/[^?]+)#', $uri, $m)) {
            return new RedirectResponse($site.$m[1].rtrim($m[2], '/').'/'.($query ? '?'.$query : ''), 301);
        }

        $path = $request->getPathInfo();

        if (
            $path !== '/'
            && ! str_ends_with($path, '/')
            && ! preg_match('/\.[a-z0-9]{2,5}$/i', $path)
            && $path !== '/up'
        ) {
            return new RedirectResponse($site.$request->getBaseUrl().$path.'/'.($query ? '?'.$query : ''), 301);
        }

        return $next($request);
    }
}
