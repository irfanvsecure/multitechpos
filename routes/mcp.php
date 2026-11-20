<?php

use App\Http\Controllers\ImageUploadController;
use App\Http\Controllers\McpController;
use App\Http\Middleware\McpAuth;
use Illuminate\Support\Facades\Route;

// One-time image upload page made by the create_image_upload_link tool (the token is the authentication).
// Registered before the routes below so /mcp/upload/... never reaches the MCP secret check.
Route::middleware('throttle:30,1')->group(function () {
    Route::get('/mcp/upload/{token}', [ImageUploadController::class, 'show'])->where('token', '[A-Za-z0-9]{43}');
    Route::post('/mcp/upload/{token}', [ImageUploadController::class, 'store'])->where('token', '[A-Za-z0-9]{43}');
});

// MCP server for claude.ai (no session, cookies or CSRF: the secret is the authentication).
Route::middleware(['throttle:mcp', McpAuth::class])->group(function () {
    Route::post('/mcp', [McpController::class, 'post']);
    Route::post('/mcp/{secret}', [McpController::class, 'post'])->where('secret', '[A-Za-z0-9_\-]+');
    Route::match(['get', 'delete'], '/mcp/{secret?}', [McpController::class, 'other']);
});
