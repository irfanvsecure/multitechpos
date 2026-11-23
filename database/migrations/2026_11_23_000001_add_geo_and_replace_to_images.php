<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Image geotags (also written into the WebP EXIF) and upload links that put the uploaded
 * image straight onto the page (replace_path), so one upload = image uploaded and set.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('media', function (Blueprint $table) {
            $table->json('geo')->nullable();               // {lat, lng, place}
        });
        Schema::table('upload_links', function (Blueprint $table) {
            $table->string('title', 500)->nullable();      // image description
            $table->json('geo')->nullable();
            $table->string('replace_path')->nullable();    // image to swap out after the upload
            $table->json('replaced')->nullable();          // places changed by that swap
            $table->string('replace_error')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('upload_links', function (Blueprint $table) {
            $table->dropColumn(['title', 'geo', 'replace_path', 'replaced', 'replace_error']);
        });
        Schema::table('media', function (Blueprint $table) {
            $table->dropColumn('geo');
        });
    }
};
