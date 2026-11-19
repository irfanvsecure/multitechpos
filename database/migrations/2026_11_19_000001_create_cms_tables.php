<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Website content managed through the MCP server.
 * No table here clashes with an application table (the app has none), so plain names are used.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('pages')->nullOnDelete();
            $table->string('slug')->unique();             // '' = home, 'about-us', 'author/multitech_admin'
            $table->string('path')->unique();             // '/', '/about-us/'
            $table->string('name');                       // label in lists (usually the H1 text)
            $table->string('page_type', 20)->index();     // core_pos|business_size|industry|feature|erp|download|standard|legal
            $table->string('template', 20)->default('page');
            $table->string('status', 12)->default('published')->index(); // published|draft
            $table->string('body_class')->nullable();
            $table->string('page_kind', 20)->nullable();  // CRO widget hint: home|landing|post|general
            $table->boolean('show_cro')->default(true);   // interactive widgets + call-back card
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('page_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained('pages')->cascadeOnDelete();
            $table->string('key');
            $table->string('type', 12);                   // html|component
            $table->unsignedInteger('sort_order')->default(0);
            $table->longText('content')->nullable();      // html (with placeholders)
            $table->string('component')->nullable();      // component name for type=component
            $table->json('attrs')->nullable();            // component options
            $table->json('embeds')->nullable();           // components placed in html with {{embed:N}}
            $table->boolean('is_visible')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['page_id', 'sort_order']);
        });

        Schema::create('section_revisions', function (Blueprint $table) {
            $table->id();
            $table->morphs('revisionable');               // PageSection or Post
            $table->json('snapshot');
            $table->string('reason')->nullable();
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->text('excerpt')->nullable();
            $table->longText('content')->nullable();
            $table->string('cover_image')->nullable();    // og / schema image
            $table->string('card_image')->nullable();     // blog list thumbnail
            $table->string('archive_image')->nullable();  // author archive thumbnail
            $table->string('author_name')->default('multitech_admin');
            $table->json('options')->nullable();          // {"narrow": true, "nav_prev": {"path", "title"}}
            $table->string('status', 12)->default('draft')->index(); // draft|published|scheduled
            $table->dateTime('published_at')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('category_post', function (Blueprint $table) {
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->primary(['category_id', 'post_id']);
        });

        Schema::create('post_tag', function (Blueprint $table) {
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained()->cascadeOnDelete();
            $table->primary(['post_id', 'tag_id']);
        });

        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->string('path')->unique();             // /images/2026/06/x.webp
            $table->string('filename');
            $table->string('mime', 60)->nullable();
            $table->unsignedInteger('size')->nullable();
            $table->unsignedInteger('width')->nullable();
            $table->unsignedInteger('height')->nullable();
            $table->string('alt')->nullable();
            $table->string('title')->nullable();
            $table->string('original_path')->nullable();
            $table->json('variants')->nullable();         // {"480": "/images/..-480.webp", ...}
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('seo_meta', function (Blueprint $table) {
            $table->id();
            $table->morphs('seoable');
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('focus_keyword')->nullable();
            $table->string('canonical')->nullable();
            $table->string('robots')->nullable();
            $table->string('og_title')->nullable();
            $table->text('og_description')->nullable();
            $table->string('og_image')->nullable();
            $table->string('og_type', 20)->nullable();
            $table->string('twitter_card', 30)->nullable();
            $table->boolean('exclude_from_sitemap')->default(false);
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['seoable_type', 'seoable_id']);
        });

        Schema::create('redirects', function (Blueprint $table) {
            $table->id();
            $table->string('from_path')->unique();
            $table->string('to_url')->nullable();
            $table->unsignedSmallInteger('status_code')->default(301); // 301|302|410
            $table->unsignedInteger('hits')->default(0);
            $table->timestamp('last_hit_at')->nullable();
            $table->string('note')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('schema_blocks', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('schemable');          // null = every page
            $table->string('type', 60);
            $table->json('data');
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->nullable()->constrained('pages')->nullOnDelete();
            $table->text('question');
            $table->text('answer');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->text('quote');
            $table->string('name');
            $table->string('business')->nullable();
            $table->string('photo')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('client_logos', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image');
            $table->string('url')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('hardware_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('category')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('pricing_plans', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('audience')->nullable();
            $table->text('description')->nullable();
            $table->json('inclusions')->nullable();
            $table->string('price')->nullable();          // empty: the site shows no prices
            $table->string('price_period')->nullable();
            $table->string('badge')->nullable();
            $table->string('icon')->nullable();
            $table->string('tile', 20)->nullable();
            $table->string('cta_label')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('downloads', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('platform')->nullable();
            $table->string('version')->nullable();
            $table->string('file_size')->nullable();
            $table->string('download_url')->nullable();
            $table->foreignId('page_id')->nullable()->constrained('pages')->nullOnDelete();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('offices', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address');
            $table->string('city');
            $table->string('country', 60);
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('map_url', 1000)->nullable();
            $table->string('hours')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type', 10)->default('string'); // string|text|json|bool
            $table->string('group', 30)->default('general');
            $table->string('label')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('form_options', function (Blueprint $table) {
            $table->id();
            $table->string('form', 40)->index();          // business_types, quote_service, builder_branches ...
            $table->string('value');
            $table->string('label')->nullable();
            $table->json('meta')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('activity_log', function (Blueprint $table) {
            $table->id();
            $table->string('tool', 60)->nullable();
            $table->string('action', 30);
            $table->string('subject_type')->nullable();
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->string('summary')->nullable();
            $table->json('before')->nullable();
            $table->json('after')->nullable();
            $table->string('ip', 45)->nullable();
            $table->timestamp('created_at')->nullable()->index();
        });
    }

    public function down(): void
    {
        foreach (['activity_log', 'form_options', 'settings', 'offices', 'downloads', 'pricing_plans', 'hardware_items',
            'client_logos', 'testimonials', 'faqs', 'schema_blocks', 'redirects', 'seo_meta', 'media', 'post_tag',
            'category_post', 'posts', 'tags', 'categories', 'section_revisions', 'page_sections', 'pages'] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
