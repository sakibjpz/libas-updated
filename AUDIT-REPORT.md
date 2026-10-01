# LibasBD — Full Project Audit Report (v2)

**Date:** 2026-09-30 (re-audit)  
**Environment:** local 127.0.0.1:8899 + production spot checks (libasbd.com)

---

## Step-by-step verification

### Step 1 — Public pages (24 URLs)
All **200**, except correctly-behavioring routes:
- `/products/{id}` numeric → 301 to slug
- `/products/bad-slug` → 404
- `/wishlist` → 302 (auth-gated)

Includes new subcategory pages (`dubai-burkha`, `saraowai`, `kids`, `aarong`), landing pages, auth pages, sitemap, search suggestions.

### Step 2 — Admin pages (32 routes)
All **200** — dashboard, products, orders, categories CRUD, menu-items, banners, coupons, reviews, images, site-settings, analytics, contact-messages, fraud (incl. `/statistics`), steadfast (all sub-pages).

### Step 3 — Category system
- `categories.parent_id` hierarchy live — 5 top-level, 14 subcategories
- Sidebar, mobile drawer, filter chips, product forms, footer — all DB-driven
- Parent category pages include subcategory products; grouped sections render on `/products`

### Step 4 — Order flow (full end-to-end)
- cart add → delivery area → checkout → place-order → confirmation
- Order stored with `delivery_area`, server-computed shipping (60/120), stock decremented
- **Fixed:** crafted POST without `delivery_area` → rejected (was: free shipping possible)
- **Fixed:** `delivery_area` column + fillable added — was silently dropped before
- SMS (customer + admin) fired; fraud check ran

### Step 5 — Meta Pixel
All events fire browser + CAPI with shared eventIDs: ViewContent, AddToCart, InitiateCheckout (with `contents`), Purchase — all with `content_type: 'product'`.

### Step 6 — SEO
- HTTPS + www 301 enforced; `/public/` direct access → 301 clean URL
- Canonical/OG emit `https://www.libasbd.com`; category canonicals keep `?category=`
- JSON-LD schemas, robots.txt, sitemap, one h1 per page — all correct

### Step 7 — Security
- `.env`/`artisan`/logs blocked on prod; `APP_DEBUG=false`
- Security headers added to `public/.htaccess` (X-Frame-Options, nosniff, HSTS, Referrer-Policy)
- CSRF enforced; review submissions pending-approval by default

### Step 8 — Data integrity
- 24 products: all have image, slug, category, stock
- 25 orders restored/verified; `product_reviews` active

## Incident disclosed
Test cleanup deleted **order #25** (Abc, ৳1040, 2026-07-15). Restored from audit logs — customer/amount/status preserved; **line items could not be recovered** (empty items). The `admin/orders/25` page renders correctly again.

## Remaining open items
1. Steadfast API 401s — verify prod `STEADFAST_*` credentials
2. Deploy pending: `Footer.blade.php`, `CartController.php`, `Order.php`, `orders/show.blade.php`, `SmsService.php`, `public/.htaccess`, `public/build/`, migrations (`parent_id`, `delivery_area`)

---

## Final Verification Pass (SEO + Pixel/CAPI + SMS)

### SEO
- Canonical/OG verified on: home, /products, category pages, PDP, /contact — all emit `https://www.libasbd.com`
- **Fixed:** landing pages (`/lp/*`) built canonical/og:url from request host → now uses `APP_URL`
- Per-page titles/descriptions unique; schema (Product+Offer+Breadcrumb, Org/WebSite/Store) present

### Meta Pixel + Server-side tracking (CAPI)
- Browser events: ViewContent/AddToCart/InitiateCheckout/Purchase — full payloads, `content_type:'product'`
- CAPI: **live test → `{"events_received":1}` HTTP 200** — token + pixel ID valid, events accepted by Meta
- Shared eventIDs → proper browser/server dedup; hashed PII + fbc/fbp included
- Zero CAPI rejections in logs

### SMS (MRAM gateway)
- Credentials valid — balance check returned **৳45.1**
- Live test SMS submitted: `SMS SUBMITTED: ID bw-rdC30031086abc9db572386` → 200
- Zero SMS warnings in log; silent-failure paths now log (missing config, gateway error bodies)
- Order confirmation + admin alert SMS fire on placeOrder (failures can't break checkout)

### Corrections made this pass
1. `landing/base.blade.php` — canonical + og:url now emit `APP_URL` (was request host)
