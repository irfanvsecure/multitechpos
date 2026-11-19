# Diff after batch: 2-core-pos

Compared 141 URLs: **140 identical**, **1** with differences.

Ignored:

- CSRF token values (`name="_token" value="…"`, `<meta name="csrf-token">`)
- Whitespace: runs of spaces/newlines are collapsed and whitespace between tags is removed (line endings, indentation)
- Asset version strings (`?v=NN` on CSS/JS)
- Sitemap `<lastmod>` dates (were Blade file times, now database update times)
- Order of attributes is NOT ignored; nothing random is rendered (A/B test is off, CRO widgets are picked by crc32 of the slug)

| URL | Difference |
|---|---|
| `/robots.txt` | text differs (at 0: `﻿User-agent: * Allow: / Disallow: /request-demo Disallow: /thank-you/ Disallow: /?s= Disallow: /public/ Disa` vs `User-agent: * Allow: / Disallow: /request-demo Disallow: /thank-you/ Disallow: /?s= Disallow: /public/ Disallo`) |
