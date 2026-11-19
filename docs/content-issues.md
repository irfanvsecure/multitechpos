# Content issues found during the move (flagged, NOT fixed)

Nothing below was changed. The refactor kept every page's content identical. Each item
says how to fix it through the MCP once you decide. The numbers come from the MCP's own audit
tools, run on the full site on 2026-11-19 (`audit_*`, `check_broken_links`, `find_orphan_pages`).

## 1. Things that are broken or misleading for visitors (fix first)

| # | Issue | Where | Suggested fix |
|---|---|---|---|
| 1 | **The quote form on `/pos-software-uae/` cannot be sent.** It only has "First Name" and "Message", but the server requires a phone or email, so every submission fails validation. | `/pos-software-uae/` (quote-form `variant: names`) | Switch the embed to the standard form: `update_page_section` with `embeds: [{"component":"quote-form","attrs":{}}]` |
| 2 | **The RestroPro "Download" button goes nowhere.** It points to `/download/restropro/`, which 301s back to `/restropro/` (the same page). The other three products link to Google Drive. | `/restropro/` | Set the real link: `update_download` slug `restropro`, `download_url` |
| 3 | **A private Claude chat link sits in the Support Policy.** The phone number in the contact block links to `https://claude.ai/chat/37b3c114-…` instead of `tel:`. | `/support-policy/` | `update_internal_link` / edit the section: href → `tel:{{phone_raw}}` |
| 4 | **"Whatsapp" links that dial the phone.** 50 links labelled "Whatsapp" use `tel:+971588979925`, not WhatsApp. | contact blocks on ~50 landing pages | Per section, `update_page_section` with find `<a href="tel:{{phone_raw}}">Whatsapp</a>` → replace `<a href="{{wa_link}}" target="_blank" rel="noopener">Whatsapp</a>` (`search_content` lists the sections) |
| 5 | **TailorPro shows the same file size as RestroPro (74.33 MB)**, which looks copy-pasted. All four show version 2.0. | `/tailorpro/` (hidden details table) | Confirm, then `update_download` |
| 6 | **Author archive pagination** links to `/author/multitech_admin/page/2/` and `/page/4/`, which 301 back to page 1. | `/author/multitech_admin/` | Remove the pager or list all posts |
| 7 | **160 links to the old WordPress URL `/index.php/features/`** ("Easy to configure", "Touch Screen Ready" … icon boxes). They work through a 301 but waste a redirect hop. | 10 pages (16 each), e.g. `/multi-branch-business-pos-uae/`, `/small-business-pos-uae/`, `/appointment-booking-pos-uae/` | `replace_link_sitewide old_url=/index.php/features/ new_url=/features/ dry_run=false` |
| 8 | **Unknown URLs 301 to the home page** (soft 404 for Google, and broken links stay hidden). Behaviour kept unchanged. | site-wide catch-all | Decide whether unknown URLs should return a real 404; the 404 page already exists |
| 9 | **Downloads details table is hidden on every device** (`hide-desktop hide-mobile`), so version, size and dates never show. | 4 download pages | Design decision |

## 2. Demo buttons that dial a phone instead of opening a form

**None left.** Every "Book a Demo", "Free Demo" and "Get a Quote" button opens the form
(`/contact/` or `#hero-demo`). The remaining `tel:` buttons are labelled as calls:
**"Talk To Expert" (36)** and **"Speak With an Expert" (home)**. Item 1.4 above covers the
mislabelled "Whatsapp" ones.

## 3. Lorem ipsum, blank or duplicate titles

- Lorem ipsum: **none** (`search_content` for lorem/ipsum/dolor sit).
- Blank titles: **none**. `/thank-you/` has no meta description, which is fine because it is noindex.
- Duplicate titles / descriptions: **none**.
- Length (aim for 50–60 / 140–160 characters): **58 titles** and **41 descriptions** are outside the range. **49 pages have no share (OG) image** (the logo is used). Full list: `audit_meta`.

## 4. Broken links

- Internal: none apart from 1.6 and 1.7.
- External (46 checked): 14 failed. The three below are real 404s. The rest are sites that block automated checks (HTTP 403/402 or no answer); open them in a browser before changing anything.

| Page | Link | Result |
|---|---|---|
| `/what-makes-a-tax-invoice-valid/` | mof.gov.ae …/Cabinet-Decision-No.-40-of-2017-and-its-amendments-v14.11.25.pdf | 404 |
| `/uae-e-invoicing-for-distributors/` | mof.gov.ae …/UAE-Electronic-Invoicing-Guidelines_V-1.1-01June2026.pdf | 404 |
| `/how-to-choose-flower-shop-pos-uae-guide/` | u.ae …/value-added-tax-vat | 404 |
| `/support-policy/` | claude.ai/chat/… | see 1.3 |

## 5. Industry pages that may cannibalise each other

These pages are **written differently**: text overlap between them is only 2–7% (5-word
phrases). But their **titles and H1s target the same head term** ("X POS Software in
UAE"), and several answer the same intent. Google tends to pick one page per intent, so
decide on one primary page per group and make the others clearly about their niche, or
merge them with a 301.

| Group | Pages | Suggested primary | Notes |
|---|---|---|---|
| Restaurant | `/food-restaurant-pos-software-uae/`, `/fast-food-pos-software/`, `/coffee-shop-pos-system-uae/`, `/cafeteria-pos-software-systems/`, `/bakery-pos-software/`, `/digital-dining-pos-software/`, `/delivery-pos-software/`, `/food-truck-pos-software/`, `/table-management-pos-uae/` (feature), `/restaurant-management/` (ERP), `/restropro/` (download) | `/food-restaurant-pos-software-uae/` | "Restaurant POS" vs "Restaurant Management Software" vs "RestroPro" all target restaurant owners. Two blog guides also overlap: `/choosing-restaurant-pos-software-uae-guide/` and `/restaurant-pos-buying-guide-uae/` (titles 88% alike). |
| Retail | `/retail-pos-software-uae/`, `/fashion-pos-system-in-uae/`, `/footwear-pos-system-uae/`, `/small-business-pos-uae/`, `/retail-management/` (ERP "RetailPro"), `/gift-store-pos-system-in-the-uae/`, `/toy-shop-pos-in-uae/` | `/retail-pos-software-uae/` | `/retail-management/` and `/retail-pos-software-uae/` both say "Retail … POS … in UAE". |
| Salon & spa | `/salon-spa-pos-software-uae/`, `/nail-salon-pos-software/`, `/barber-shop-pos-software/`, `/spa-pos-software-uae/`, `/medical-spa-pos-software/`, `/beauty-clinic-pos-software-uae/`, `/salon-management/` (ERP "SalonPro"), `/salonpro/`, `/appointment-booking-pos-uae/` | `/salon-spa-pos-software-uae/` | "Salon & Spa POS" vs "Spa POS" vs "Medical Spa POS". Six salon "guide" pages (`/best-point-of-sale-system-for-salons-spas-2026/`, `/salon-pos-systems-uae-increase-revenue/` …) are pages, not blog posts, and compete too. |
| Gold & jewelry | `/gold-shop-pos-software/`, `/jewelry-pos-system-in-uae/` | `/jewelry-pos-system-in-uae/` | Same buyers. |
| Pharmacy | `/pharmacy-pos-system-in-the-uae/`, `/dispensary-pos-software/`, `/hemp-pos-software/` | `/pharmacy-pos-system-in-the-uae/` | "Dispensary POS for UAE pharmacies". The hemp page also needs a compliance check for the UAE market. |
| Downloads vs ERP | `/restropro/` ↔ `/salonpro/` (74% identical text), `/laundrypro/` ↔ `/tailorpro/` (41%) | each product | Near-duplicate templated pages (see 6). |
| Features | `/multi-location-integration/`, `/customers/`, `/mobile-multitechpos/` (38–46% shared text) | `/multi-location-integration/` | Near-duplicate templated pages. |

Run `audit_cannibalisation` any time for the live grouping.

## 6. Near-duplicate templated pages and thin content

- **Templated headings** across industry pages: "`* POS Software in UAE`" (23 pages), "`Key Features of Our * POS Software`" (10), "`Why Choose Us for * POS Software in UAE`" (6). Shared H2 blocks repeated on 35 pages ("Stop losing time and money to manual work", "From first call to first sale", "Everything you need to get started", "Ready to transform your business?") come from shared components and are fine, but they are the same on every page.
- **Thin pages** (own content, not counting the header, footer or shared blocks): `/restropro/` (70 words, 96% also on other pages), `/salonpro/` (70, 85%), `/tailorpro/` (66), `/laundrypro/` (67), `/multi-location-integration/` (283 words, 97% shared), `/features/` (85% shared), `/mobile-multitechpos/`, `/customers/`, `/about-us/` (269). Full list: `audit_thin_content`.
- **Orphan pages** (no other page links to them in content; the menu or footer may): `/cafeteria-pos-software-systems/`, `/dispensary-pos-software/`, `/footwear-pos-system-uae/`, `/gaming-store-pos-software/`, `/toy-shop-pos-in-uae/`, `/laundry-management/`, `/tailor-management/`, `/business-size/`, `/by-industry/`, `/about-us/`, `/our-blogs/` and the policy pages.
- **Headings**: 86 pages have heading notes, mostly duplicate H3s inside one page (68), headings over 70 characters (14 H1s) and 9 skipped levels (`audit_headings`).
- **Images**: 119 content images have empty alt text, 64 below-the-fold images are not lazy-loaded, 41 have no WebP version, 30 lack width/height, 4 have alt text that looks like a file name, and 1 is over 300 KB (`/images/2026/08/multi-branch-stock-warehouse-shelves.webp`, 431 KB). `audit_images` and `bulk_update_alt` handle these.

## 7. Claims that need your confirmation

| Claim | Where | Why it needs checking |
|---|---|---|
| "**#1 POS Software & ERP Systems Company In UAE**" | Home page meta title (the one you see in Google) | Superlative with no source. Ranking claims like this can breach UAE advertising rules and Google's misrepresentation policy. |
| "**15+ years of experience**" | Home stats bar, "Why choose us", footer badge, About Us (twice), About meta description | MAIT was founded in 2014, which is 12 years in 2026. If the 15+ counts the founders' earlier experience, say so; otherwise change `stat_years` once (setting) and the About Us text. |
| "**24/7 local support**" / "24/7 Support" | 77 pages (page hero trust line, footer, home, many landing pages, FAQ answers) | The office hours are **Sat to Thu, 9 AM to 6 PM**, which the call-back card shows on every page right next to the "24/7" claim. Either support really is 24/7 (then say how, e.g. WhatsApp/remote) or change `stat_support`. `/support-policy/` says "24/7 submission, reviewed during business hours". |
| "**500+ businesses**" | 77 pages (client band, page hero, home) | Keep only if you can back it up; edit `stat_businesses` once to change it everywhere. |
| "Leading" / "the leading" provider | 18 pages | Same concern as #1. |
| Hardware list | `hardware_items` (6 names) | Only names the site mentions (barcode scanners, receipt printers, cash drawers, touchscreen terminals, POS terminals, hardware bundles); no descriptions were invented. Fill in real ones if you want Product schema. |
