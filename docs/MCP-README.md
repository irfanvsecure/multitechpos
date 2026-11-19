# MultiTech POS website: content database + MCP server

The whole public site (93 pages, 34 blog posts) now comes from the database. Claude can
manage it A to Z through a remote **MCP server** built into this Laravel app (112 tools).
The design, HTML, CSS and JS are unchanged: all 140 public URLs rendered identically before
and after the move (`docs/diff/`).

---

## 1. Before you deploy: read this

**multitechpos.com is already running this Laravel app** (not WordPress). The brief asked for
`SITE_NOINDEX` to default to `true` until launch. That is how the code behaves when the
variable is missing. On the live server that would tell Google to drop every page.
**Put `SITE_NOINDEX=false` in the production `.env` before you pull this branch.**

## 2. Create the MCP secret (once)

Run this on your computer or the server. It prints a 64-character random secret:

```bash
php -r "echo bin2hex(random_bytes(32)), PHP_EOL;"
```

Put it in the server's `.env` as `MCP_SECRET=...`. Nothing was written to `.env` by me.
While `MCP_SECRET` is empty or shorter than 32 characters, `/mcp` answers 503 (off).

## 3. Deploy (git pull on the LiteSpeed host)

The repo has no CI. The root `.htaccess` routes into `public/`, so the site is deployed by
pulling into the folder that holds `artisan`. Copy and paste, adjusting the first line:

```bash
cd ~/domains/multitechpos.com/public_html        # the folder with "artisan" in it

# 0. Back up the database and .env
cp .env .env.backup-$(date +%F)
[ -f database/database.sqlite ] && cp database/database.sqlite database/database.sqlite.backup-$(date +%F)

# 1. .env: keep the live site indexed, add the MCP secret (edit with nano/vi or the file manager)
grep -q '^SITE_NOINDEX=' .env || echo 'SITE_NOINDEX=false' >> .env
grep -q '^MCP_SECRET=' .env || echo 'MCP_SECRET=PASTE_THE_64_CHARACTER_SECRET_HERE' >> .env
grep -E '^(SITE_NOINDEX|MCP_SECRET)=' .env      # check: SITE_NOINDEX=false and your secret

# 2. Code
git fetch origin
git checkout main && git pull origin main         # after merging feature/mcp-content into main

# 3. Dependencies, database, content
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan cms:import                            # loads database/content/site.json + redirects (refuses if content exists)

# 4. Caches
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. Permissions (uploads go to public/images/uploads)
mkdir -p public/images/uploads
chmod -R ug+rwX storage bootstrap/cache public/images/uploads
```

Check: `https://multitechpos.com/` loads, `https://multitechpos.com/robots.txt` shows the
normal rules plus `Sitemap:` (not `Disallow: /`), and `/sitemap.xml` lists the pages.

`cms:import` runs once. Later deploys skip it: it never overwrites content edited through the
MCP. `cms:import --fresh` replaces everything, which **destroys MCP edits**.

### Database user for the MCP (optional, MySQL only)

The app uses SQLite today. If you move to MySQL, the site and the MCP can use a user limited
to this database. The MCP has no SQL tool, so this is only defence in depth:

```sql
CREATE USER 'mtp_web'@'localhost' IDENTIFIED BY 'a-long-random-password';
GRANT SELECT, INSERT, UPDATE, DELETE ON multitechpos.* TO 'mtp_web'@'localhost';
-- run migrations with your admin user; the app itself needs no CREATE/DROP/ALTER
FLUSH PRIVILEGES;
```

### Caching

- There is no page cache. Content lookups are cached (`CACHE_STORE=database`) and every MCP
  write clears them (one version number, so nothing goes stale).
- `/mcp` responses send `Cache-Control: no-store, private` and `X-Robots-Tag: noindex`.
- Cloudflare is not in front of the site today (no `cf-ray` header). If you add it, put
  `CLOUDFLARE_API_TOKEN` (permission: Zone → Cache Purge only) and `CLOUDFLARE_ZONE_ID` in
  `.env`; `clear_cache` and every write then purge Cloudflare too. Also add a Cloudflare
  Cache Rule: *URI Path starts with `/mcp`* → **Bypass cache**.

## 4. Connect Claude

Connector URL:

```
https://multitechpos.com/mcp/<MCP_SECRET>
```

1. claude.ai → **Settings → Connectors → Add custom connector**.
2. Name: `MultiTech POS website`. URL: the line above with your secret.
3. Under the connector's tool permissions, set **"Write/delete tools" to "Needs approval"**
   for production. Read-only tools can stay "Always allow".

Other MCP clients can send `Authorization: Bearer <MCP_SECRET>` to `https://multitechpos.com/mcp`.

**Security notes**
- The path secret ends up in web server access logs. Rotate it (new `MCP_SECRET`, then
  `php artisan config:cache`, then update the connector) if logs are shared.
- HTTPS is enforced (HTTP gets 403). The limit is 120 requests/minute per IP (`MCP_RATE_LIMIT`).
- Every call is logged to `storage/logs/mcp-YYYY-MM-DD.log` (tool, shortened arguments,
  outcome, time, IP). Every change is in `activity_log` with before/after values (tool `activity_log`).
- There are no tools for SQL, shell, files, `.env`, users, POS licences, customers or invoices.
  Enquiries are read-only. Installers and archives (`.exe .msi .apk .zip .dmg` …) can never
  be uploaded; downloads are links only.

## 5. How the content is stored

| What | Where |
|---|---|
| Page | `pages` (slug, `page_type` core_pos/business_size/industry/feature/erp/download/standard/legal, parent for the By Industry / By Business Size hubs) |
| Page body | `page_sections`: `html` (exact HTML with placeholders) or `component` (code block with options); `{{embed:N}}` places a component (e.g. the quote form) inside html |
| History | `section_revisions` (sections and posts), `activity_log` (everything, before/after) |
| Blog | `posts`, `categories`, `tags` |
| SEO | `seo_meta` (title, description, focus keyword, canonical, robots, OG, Twitter, exclude from sitemap), `schema_blocks` (extra JSON-LD), `redirects` (301/302/410) |
| Single source | `settings` (phone, WhatsApp, email, address, hours, service areas, social links, stats, robots.txt), `pricing_plans`, `downloads`, `offices`, `form_options` |
| Other | `faqs` (drive FAQPage schema), `client_logos`, `testimonials` (empty, hidden while empty), `hardware_items`, `media` |

Placeholders (filled in when shown, never executed): `{{base}} {{phone}} {{phone_raw}}
{{email}} {{whatsapp}} {{wa_link}} {{address}} {{hours}} {{service_areas}} {{plan:slug}}
{{download:slug}} {{setting:key}} {{embed:N}}`. Anything else in double braces is refused on save.

Header, footer, the shared blocks (CTA band, "service why", widgets, Setup Builder) and the forms
stay as Blade code. Their contact details, stats, logos and option lists come from the database.

**`storage/app/convert-tool/tool/gen.mjs` no longer affects the site.** The database is the
source of truth now.

## 6. Tools (112): 42 read-only, 70 write/delete

⚠ = destructive (soft delete to the trash, except `purge_item`).

| Group | Read-only (42) | Write / delete (70: 53 write, 17 delete) |
|---|---|---|
| Site | `get_site_info`, `get_settings`, `list_components`, `activity_log`, `audit_performance` | `update_settings`, `clear_cache` |
| Pages & sections | `list_pages`, `get_page`, `get_page_sections`, `list_section_revisions`, `search_content` | `create_page`, `update_page`, `add_page_section`, `update_page_section`, `reorder_page_sections`, `toggle_section_visibility`, `restore_section_revision`, `replace_text` (dry run by default), `delete_page ⚠`, `delete_page_section ⚠` |
| Headings | `get_page_outline`, `audit_headings` | `update_heading` |
| Internal links | `get_internal_links`, `find_orphan_pages`, `anchor_text_report`, `check_broken_links` | `add_internal_link`, `update_internal_link`, `remove_internal_link`, `replace_link_sitewide` (dry run by default) |
| SEO & meta | `get_seo`, `audit_meta`, `audit_seo`, `audit_cannibalisation`, `audit_thin_content`, `seo_report` | `update_seo`, `bulk_update_seo` (dry run by default) |
| Schema | `get_schema` | `update_schema`, `generate_schema`, `delete_schema ⚠` |
| Redirects & indexing | `list_redirects`, `validate_sitemap_robots` | `save_redirect`, `update_robots`, `delete_redirect ⚠` |
| Blog | `list_blogs`, `get_blog`, `list_stale_posts`, `list_categories`, `list_tags` | `create_blog`, `update_blog`, `publish_blog`, `unpublish_blog`, `schedule_blog`, `create_category`, `update_category`, `create_tag`, `update_tag`, `merge_tags`, `set_blog_taxonomies`, `delete_blog ⚠`, `delete_category ⚠`, `delete_tag ⚠` |
| Images | `list_images`, `audit_images` | `upload_image`, `update_image`, `replace_image`, `bulk_update_alt`, `delete_image ⚠` |
| Business content | `list_faqs`, `list_testimonials`, `list_client_logos`, `list_hardware_items`, `list_pricing_plans`, `list_downloads`, `list_offices`, `list_form_options` | `create_/update_` + `delete_ ⚠` for faq, testimonial, client_logo, hardware_item, pricing_plan, download, office, form_option |
| Leads | `list_enquiries` (read-only) | – |
| Trash | `list_trash` | `restore_item`, `purge_item ⚠` (needs `confirm=true`) |

Rules enforced by the tools: read-before-write (`expected_updated_at`), exactly one H1 per
published page, sanitized HTML (scripts, event handlers, forms and `javascript:` removed;
classes, inline styles, SVG, `data-*`, `aria-*` and Alpine attributes kept, and all 642
existing sections and posts pass through it byte-identical), unique lowercase slugs, auto-301
on slug change, no AggregateRating/Review schema, no price without `owner_provided_price`, no
testimonial without `provided_by_owner`.

## 7. Old WordPress URLs

- `docs/url-map.csv`: 155 old URLs (the Oct 2026 WordPress capture, the Yoast sitemap
  archived Nov 2025, and every Wayback capture). **126 keep the same URL** (no redirect needed).
- 273 redirects in the `redirects` table: WordPress short links `/?p=ID` and `/?page_id=ID`
  (124 pages × 2), 13 pages removed from WordPress before the move (pricing pages → product
  pages or /contact/, /clients/ and portfolio → /about-us/, `/restorantpro/` → `/restropro/`, ...),
  `/download/<product>/` → product pages.
- **410 Gone**: `/login/`, `/register/`, `/my-account/` (old WooCommerce), `/trackback/`, and three old theme files.
- Pattern redirects stay in `routes/web.php`: `/wp-content/uploads/*` → `/images/*`, `/feed/`,
  `/category/*`, WP sitemaps → `/sitemap.xml`, `/index.php/*`.
- `docs/meta-differences.md`: 71 titles/descriptions where WordPress (Yoast) and the new site
  differ. The new ones were kept; none were empty, so nothing was imported.
- Marked "(confirm)" in the CSV: `/pricing/` and `/one-time-pricing/` → `/contact/`,
  `/clients/` and `/portfolio-types/*` → `/about-us/`, `/support/` → `/support-policy/`,
  `/reseller/` → `/contact/`, `/download/multitechpos-retailpro/` → `/downloads/`,
  `/author/mark/` → `/our-blogs/`.

## 8. Launch checklist

1. Production `.env`: `SITE_NOINDEX=false` (already needed now, see section 1), then
   `php artisan config:cache`.
2. Clear caches: `php artisan optimize:clear && php artisan config:cache && php artisan route:cache && php artisan view:cache`
   (or the `clear_cache` tool).
3. `validate_sitemap_robots` → "site_noindex: off", robots.txt with the `Sitemap:` line.
4. Google Search Console → Sitemaps → resubmit `https://multitechpos.com/sitemap.xml`.
5. Search Console → Performance → last 3 months → top 10 pages by clicks/impressions →
   open each old URL: it must return **200** or **301 to the right page**
   (`curl -sI https://multitechpos.com/<path>/`).
6. Send a test from the home **demo form**, a page-hero form, the **contact form** and the
   **Setup Builder**: each must reach `LEAD_EMAIL` and show in `list_enquiries`.
7. Deactivate the old WordPress MCP plugin connector in claude.ai once the new one works.
8. Review `docs/content-issues.md` (broken RestroPro download, the `/pos-software-uae/`
   form that cannot be sent, the Claude chat link in the support policy, claims to confirm).

## 9. Commands

| Command | What it does |
|---|---|
| `php artisan cms:import [--fresh]` | Load `database/content/site.json` + `redirects.json` (once per server) |
| `php artisan cms:extract` | One-off used for the move (read the Blade pages into `site.json`). It no longer works now that the Blade page files are gone. |
| `php artisan cms:seo-carryover` | One-off: WordPress URL map, redirects, meta differences |
| `php artisan site:snapshot <dir>` / `site:diff <a> <b>` | Save every public URL and compare two saves (used for the refactor diffs) |
| `php artisan test` | 29 tests, 2,263 assertions (all 112 tools, auth, rules, sanitizer round trip) |
