# MCP audit (Phase 0)

Date: 2026-11-19 · Branch: `feature/mcp-content`

## Versions and runtime

| Item | Found |
|---|---|
| Laravel | 12.70.0 (`laravel/framework ^12.0`) |
| PHP | 8.2.12 locally (composer requires `^8.2`) |
| Extensions | gd (with WebP), pdo_sqlite, pdo_mysql, mbstring, dom, curl |
| Database | SQLite (`DB_CONNECTION=sqlite`, `database/database.sqlite`, not in git). Only app tables: `users`, `cache*`, `jobs*`, `sessions`, `leads`. No POS app, customer, licence or invoice tables, so there is no name clash and every new table uses a plain name. |
| Cache | `CACHE_STORE=database`, `SESSION_DRIVER=database`. There is no page cache. Responses send `Cache-Control: no-cache, private`. |
| Tests | PHPUnit 11: 6 tests, all passing before this work (no failing tests to fix). Tests use in-memory SQLite. |
| Front end | No build step in use. Hand-written `public/css/style.css` and `public/js/main.js` (Vite config is the unused skeleton default). Font Awesome from cdnjs. |

## IMPORTANT: the live site is already this Laravel app

`https://multitechpos.com/` is served by **this Laravel app**, not WordPress. It sets a
`multitech-pos-session` cookie and serves `style.css?v=37` and the same `robots.txt` as
`public/robots.txt`. The server is LiteSpeed (Hostinger-style shared hosting). The
WordPress to Laravel move already happened on 2026-10-19. The old WordPress pages were
captured into `storage/app/convert-tool/ex/*.meta.json` (title, description, og image,
body classes) and the Laravel slugs were kept identical.

Consequences:
* **SITE_NOINDEX**: the brief wants it to default to `true` until launch, but the site is
  already launched and indexed. The code follows the brief (`config('cms.noindex')`
  defaults to `true` when the variable is missing). **Production `.env` must get
  `SITE_NOINDEX=false` BEFORE this branch is deployed**, or Google is told to drop
  every page. The deploy steps in `docs/MCP-README.md` start with this.
* **Phase 1b (Yoast carry-over)**: WordPress is no longer reachable on the domain. The
  WordPress titles and descriptions are taken from the Oct 2026 capture
  (`convert-tool/ex/*.meta.json`), with the Wayback Machine as a cross-check where it can
  be reached. See `docs/url-map.csv` and `docs/meta-differences.md`.

## Deploy method found in the repo

* There is no CI config (`.github/` is missing) and no deploy script.
* The root `.htaccess` sends every request into `public/`, strips `/public/` and forces
  non-www. Recent commits are LiteSpeed fixes. This points to a **git pull on shared
  hosting** with the document root at the repo root.
* `vendor/` is git-ignored, so the server runs `composer install`.

## Routes, views and data sources (before this work)

All pages are `Route::view()` lines in `routes/web.php`. One Blade file per page sits in
`resources/views/<slug>.blade.php`, and every page uses `layouts/app.blade.php`. The
content is **hard-coded HTML inside each Blade file**. There is no database content.

| Group (routes/web.php) | Count | Examples | Data source |
|---|---|---|---|
| Company | 3 | `/`, `/about-us/`, `/contact/` | Blade (home has PHP arrays: business picker, FAQs) |
| POS Software | 11 | `/pos-software-uae/`, `/retail-pos-software-uae/`, `/small-business-pos-uae/` | Blade |
| POS Features | 8 | `/features/`, `/woocommerce-pos/`, `/paperless-invoicing/` | Blade |
| Business ERPs | 12 | `/business-erps/`, `/stock-management/`, `/payroll-software/` | Blade |
| Downloads | 5 | `/downloads/`, `/restropro/`, `/salonpro/` | Blade |
| Industries | 39 | `/pharmacy-pos-system-in-the-uae/`, `/van-sales-management-system-in-uae/` | Blade |
| Blog | 42 | `/our-blogs/` (static list), `/author/multitech_admin/`, 34 posts + 6 long-form guides | Blade |
| Legal & help | 6 | `/privacy-policy/`, `/frequently-asked-questions-multitech-pos/` | Blade |
| Other | 1 | `/thank-you/` (noindex) | Blade |

Shared parts are partials: `partials/header`, `footer`, `page-hero` (H1, breadcrumb and
demo form), `clients-band` (logos from a PHP array), `cta-band`, `service-why`,
`service-quote`, `testimonials` (from `config/site.php`, empty), `post-cta`, `cro/*`
(interactive widgets with the Setup Builder), `callback`, `mobile-cta` and `whatsapp`.

## SEO plumbing

| What | Where |
|---|---|
| Title / description | `@section('title')` and `@section('description')` in each Blade file. Original values are in `convert-tool/tool/seo.json`, applied by the generator. |
| Robots meta | `layouts/app`: `@yield('robots', 'index, follow, …')`. Only `/thank-you/` overrides it. |
| Canonical | `layouts/app`: always the current URL (scheme + host + path). |
| OG / Twitter | `layouts/app`, from title, description and `@section('og_image')`. |
| JSON-LD | `partials/schema-site` (Organization + WebSite, every page), `schema-breadcrumb` (from page-hero and posts), `schema-faq` (62 pages, Q&A arrays inline), `schema-article` (34 posts). No AggregateRating or Review anywhere. |
| Sitemap | `SitemapController`: built by listing the Blade files, lastmod = file mtime. |
| robots.txt | Static `public/robots.txt`. A route cannot override it while the file exists. |
| Old WP URLs | 301 closures in `routes/web.php` (`/feed`, `/category/*`, `/wp-content/uploads/*` → `/images/*`, WP sitemaps). The `AddTrailingSlash` middleware forces the trailing `/`. |
| Unknown URLs | `Route::fallback` sends **every unknown URL to the home page with a 301** (soft 404, flagged in content-issues). |

## Images

Images are static files in `public/images/<yyyy>/<mm>/…` (457 files, 50 MB, committed to
git), copied from `wp-content/uploads`. Alt text appears only inline in the HTML. There
is no media table, no upload code and no WebP pipeline: WebP files exist because they
were copied from WordPress.

## Forms

| Form | Where | Submits to |
|---|---|---|
| Hero demo form (name, phone, business type) | home | `POST /request-demo` → `LeadController` (saves to `leads`, emails `LEAD_EMAIL`, 303 to `/thank-you/`) |
| Quick demo form | `partials/quick-form` in every page-hero | same |
| "Get Your Quote Today!" form (name, email, phone, company, service, message) | 14 landing pages, identical markup | same |
| Contact form | `/contact/` | same |
| Call-back card | `partials/callback`, every page | same |
| Setup Builder (business type, branches, counters, modules) | `partials/cro/builder` | same (puts the summary in `message`) |

Every form carries `@csrf`, a honeypot (`website`) and a 10/min throttle.

**Demo buttons and tel: links.** No "Book a Demo" or "Quote" button dials a `tel:` link
any more (the generator re-pointed them to `/contact/`, and `main.js` sends them to
`#hero-demo`). Some `tel:` buttons remain, but they are labelled **"Talk To Expert"**
(36 of them) and **"Speak With an Expert"** (home), so they are honest call buttons. They
are listed in content-issues for review.

## Risks found

1. **SITE_NOINDEX defaulting to true on an already-live site.** See above. This is the
   biggest risk of this whole change.
2. The fallback route 301s every unknown URL to `/`. Google treats this as soft 404s, and
   it also hides broken links. It is kept as is (no behaviour change during the refactor)
   and flagged. Redirect rows are checked before it.
3. `public/robots.txt` is static. It has to be removed so `/robots.txt` can be dynamic
   (needed for SITE_NOINDEX and `update_robots`). Its content moves into the `robots_txt`
   setting, unchanged.
4. Pages are generated by `storage/app/convert-tool/tool/gen.mjs` (git-ignored). Once
   content lives in the database, **re-running gen.mjs no longer changes the site**. The
   database is the source of truth.
5. Contact details are partly hard-coded: the WhatsApp URL is written out in `cta-band`,
   `post-cta` and `whatsapp`, and some pages hard-code `tel:+971588979925`. These are
   moved to settings and placeholders.
6. Stats ("500+ businesses", "15+ years", "30+ industries", "24/7", "48 hours") are
   written out in many pages and partials. They are moved to settings.
7. No HTTPS enforcement in the app. LiteSpeed or the host handles it. The MCP endpoint
   checks it itself.
