<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * One-time image upload links made by the MCP tool create_image_upload_link.
 * The owner opens the link in a browser and picks the file; the MCP then reads the result.
 * (Claude cannot pass an image from the chat to the server itself.)
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('upload_links', function (Blueprint $table) {
            $table->id();
            $table->string('token_hash', 64)->unique();   // sha256 of the token in the URL
            $table->string('purpose')->nullable();         // shown on the upload page
            $table->string('alt')->nullable();             // suggested alt text
            $table->string('filename')->nullable();        // suggested file name
            $table->unsignedTinyInteger('max_files')->default(1);
            $table->json('media_ids')->nullable();         // images uploaded through it
            $table->timestamp('expires_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('upload_links');
    }
};
