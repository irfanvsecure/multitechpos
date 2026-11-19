<?php

namespace App\Http\Controllers;

use App\Mcp\Server;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/** MCP Streamable HTTP endpoint (POST only, JSON responses, stateless). */
class McpController extends Controller
{
    public function post(Request $request, Server $server): Response
    {
        $message = json_decode($request->getContent(), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return new JsonResponse(['jsonrpc' => '2.0', 'id' => null, 'error' => ['code' => -32700, 'message' => 'Parse error']], 400);
        }

        $result = $server->handle($message);

        // only notifications / responses: nothing to send back
        if ($result === null) {
            return response('', 202);
        }

        return new JsonResponse($result, 200, [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
    }

    /** No server-to-client stream: GET/DELETE are not supported (stateless server). */
    public function other(): Response
    {
        return response('', 405, ['Allow' => 'POST']);
    }
}
