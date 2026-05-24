# Chainmail Kit Builder — Current Status
_Last updated: 2026-05-23_

---

## What's Built and Working

| Step | Screen | Status | Notes |
|------|--------|--------|-------|
| Welcome | Quantity slider + Start Kit | ✅ Done | 24 / 48 / 72 / 96 / 120+ |
| Step 1 | Add Your Product | ✅ Done | Yes/No + size confirm checkbox |
| Step 2 | Shipping | ✅ Done | Upload list or Ship to me |
| Step 3 | Logo Upload | ✅ Done | Vector warning + resolution warning modals |
| Step 4 | Premium Goods selector | ✅ Done | Multi-select up to 3, loops back after each configure |
| Step 4 | Per-good configurator | ✅ Done | 4 dropdowns + live logo overlay + preview lightbox |
| Step 5 | Spirits — category select | ✅ Done | 6 categories: Bourbon, Vodka, Rum, Mezcal, Champagne, Gin |
| Step 5 | Spirits — brand select | ✅ UI done | Only Mezcal has brand data — all others empty (see Blockers) |
| Step 6 | Message | ✅ Done | Textarea + confirm checkbox + skip option |
| Step 7 | Order Review / Checkout | ❌ Not built | Hits "coming soon" fallback |

---

## Remaining Build Items

### 1. Order Review Screen (`currentStep === 6`)
The final screen before checkout. Needs to:
- Show a summary of all kit selections (quantity, product, shipping, logo, goods, spirit, message)
- Have a "Go to Checkout" button
- Eventually: push all selections to WooCommerce cart via Store API, then `window.location.href = '/checkout'`
- For now: button can be a styled placeholder until WC is wired

### 2. WooCommerce Integration (nothing wired yet)
All product data is hardcoded in `goodsConfig` at the top of `App.jsx`.

- [ ] **Product fetch** — replace `goodsConfig` with live data from `GET /wp-json/wc/v3/products?category=premium-goods`
- [ ] **Logo upload** — `POST /wp-json/wp/v2/media` to store logo in WP Media Library
- [ ] **Cart handoff** — `POST /wp-json/wc/store/v1/cart/add-item` for each selected good + decoration add-on
- [ ] **Order metadata** — attach logo URL, shipping method, kit quantity, message text to the order

### 3. WordPress Embed
- [ ] `npm run build` → copy `dist/` into WP theme at `wp-content/themes/hello-biz-child/kit-builder/`
- [ ] Add enqueue + `wp_localize_script` to child theme `functions.php` (nonce + apiUrl)
- [ ] Create `page-configurator.php` template with `<div id="root"></div>` as body
- [ ] Assign template to a page in WP Admin

### 4. Chamevo UI Fixes (separate from kit builder — Cody's product pages)
- [ ] CSS skin — hide left sidebar, bigger icons, match brand color
- [ ] Hide orange placeholder on page load, show clean product photo
- [ ] Auto-open configurator after all 4 variation selections are made (no manual "CONFIGURE" click)
- [ ] **Needs WordPress admin access from Cody to deploy**
- [ ] Deadline: something to show Cody's boss by **Tuesday May 26**

---

## Blockers — Waiting on Cody

| Blocker | Why It's Needed |
|---------|----------------|
| **WordPress admin access** | Required to deploy Chamevo CSS/JS fixes (Tuesday deadline) |
| **Spirit brand lists** | Bourbon, Vodka, Rum, Champagne, Gin all have empty `[]` brand arrays in code — need names + prices |
| **Logo position labels** | Code has "Center / Left" — Cody mentioned "Center Chest / Right Chest" — confirm all options |
| **Portrait product photos (~390×650px)** | One real photo per SKU/variation — current Tee uses a placeholder, Hoodie/Cap/Tote/Bottle/Journal all placeholder |
| **Dropdown options for non-Tee goods** | All marked TODO in code — Hoodie, Cap, Tote, Bottle, Journal styles/colors need confirmation |
| **Shipping list CSV/Excel template** | Download link in Step 2 is a `// TODO` — need the actual file |
| **Bulk discount tier percentages** | MOQ 24, exact % off at 24/48/72/96/120+ needed to display pricing |
| **WooCommerce product catalog** | Products must exist with SKUs before variation IDs can be looked up |
| **Product Add-Ons plugin** | Must be installed on WP for decoration options to work as priced line items |

---

## Environment / Credentials
All in `.env` (gitignored):
- **WP URL**: `https://chainmail.store/dev/gloryhole`
- **WP credentials**: set
- **WC REST API keys**: set (consumer key + secret)

Child theme zip ready to install: `hello-biz-child.zip` — Elementor Hello Biz child theme with clean `functions.php`.

---

## Key Decisions — Do Not Reverse Without Discussion

| Decision | Why |
|----------|-----|
| No WPConfigurator / Chamevo for main wizard flow | React app's "ask once, apply everywhere" logic can't be replicated by plugins |
| Nonce-based WC auth (not API keys in browser) | App is embedded in WordPress — nonce is available and more secure |
| Decoration as Product Add-On line items, not variation attributes | Per-line-item modifier, not a product variant |
| Decoration is either/or per item | User picks print OR embroider — not both |
| One real photo per SKU — no color overlays | Confirmed with Cody in discovery |
| Native WooCommerce checkout — no custom checkout UI | Preserves ShipStation, Sezzle, and existing payment plugins |
| Goods configurator bottom bar is in-flow (not fixed) | Dropdowns open upward — fixed footer was covering them |
| Single `App.jsx` until full flow is complete | Refactor into components once stable |
