<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * MCP endpoint guard: MCP_SECRET as "Authorization: Bearer <secret>" or in the path /mcp/<secret>
 * (claude.ai custom connectors). Constant-time compare; HTTPS only outside local/testing.
 */
class McpAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        $secret = (string) config('cms.mcp.secret');
        if (strlen($secret) < 32) {
            return $this->deny($request, 503, 'MCP is not configured (set a 64-character MCP_SECRET in .env).');
        }

        if (config('cms.mcp.https_only') && ! app()->environment('local', 'testing')
            && ! $request->isSecure() && strtolower((string) $request->headers->get('X-Forwarded-Proto')) !== 'https') {
            return $this->deny($request, 403, 'HTTPS is required.');
        }

        $given = (string) ($request->route('secret') ?? '');
        if ($given === '' && preg_match('/^Bearer\s+(.+)$/i', (string) $request->headers->get('Authorization'), $m)) {
            $given = trim($m[1]);
        }
        if ($given === '' || ! hash_equals($secret, $given)) {
            return $this->deny($request, 401, 'Unauthorized.');
        }

        $response = $next($request);
        $response->headers->set('Cache-Control', 'no-store, private');
        $response->headers->set('X-Robots-Tag', 'noindex, nofollow');

        return $response;
    }

    private function deny(Request $request, int $status, string $message): Response
    {
        Log::channel('mcp')->warning('MCP request refused', ['status' => $status, 'ip' => $request->ip()]);

        return response()->json(['jsonrpc' => '2.0', 'id' => null, 'error' => ['code' => -32001, 'message' => $message]], $status, [
            'Cache-Control' => 'no-store, private',
            'WWW-Authenticate' => 'Bearer',
        ]);
    }
}
