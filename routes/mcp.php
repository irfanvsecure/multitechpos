<?php

use App\Http\Controllers\McpController;
use App\Http\Middleware\McpAuth;
use Illuminate\Support\Facades\Route;

// MCP server for claude.ai (no session, cookies or CSRF: the secret is the authentication).
Route::middleware(['throttle:mcp', McpAuth::class])->group(function () {
    Route::post('/mcp', [McpController::class, 'post']);
    Route::post('/mcp/{secret}', [McpController::class, 'post'])->where('secret', '[A-Za-z0-9_\-]+');
    Route::match(['get', 'delete'], '/mcp/{secret?}', [McpController::class, 'other']);
});
