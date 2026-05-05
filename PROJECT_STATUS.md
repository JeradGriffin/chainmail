# Chainmail Kit Builder — Agent Handoff Document

> **New agent? Start here.**
> Read this file top to bottom before touching any code. It will take 5 minutes and save you an hour.
> Run `/context` for a quick summary. Run `/loggit` at the end of every session to keep this file current.

---

## What This Project Is

A **React kit configurator** for a company called **Chainmail**, built for a client named **Cody Gilbertson**. Users build a custom branded kit (their own product + apparel + goods + spirits + a message) through a smooth, guided step-by-step UI, then check out through WooCommerce.

**This is not a standalone site.** It will be embedded inside a WordPress site Cody already manages. WooCommerce handles all products, pricing, cart, and orders.

**Cody's #1 priority**: manage products in WooCommerce Admin without needing a developer to touch this app — ever.

**Key UX mandate (Cody's exact words)**: *"the process is very important for this site"* — the smooth, guided step-by-step flow is a core product requirement. Do not shortcut or collapse it.

**Client contact**: Cody Gilbertson — cody.gilbertson@gmail.com
**Billing contact**: Christina — accounting@chainmail.store (billing only, not relevant to dev work)

---

## Tech Stack

| Layer | What |
|-------|------|
| Framework | React 19 |
| Build tool | Vite 7 |
| Styles | Custom CSS (`src/index.css`) — Tailwind is installed but barely used |
| Icons | lucide-react + custom SVGs in `public/` |
| Package manager | npm |

**To run locally:**
```
npm install
npm run dev
```

**To build for WordPress deploy:**
```
npm run build   # outputs to dist/
```

**Visual design:** Mobile-first, max-width 430px, centered. Brand red is `#ED2024`. Step indicator is a chain-link graphic. All screens follow the same skeleton: Header → Step indicator → Title/description → Content → Optional skip → Fixed bottom nav bar.

> **Figma link**: _(add link here — Cody has the design files)_

---

## Step Numbering — Read This to Avoid Confusion

The code uses `currentStep` which is **zero-indexed, with -1 for the welcome screen**. User-facing step labels are 1–6. Here's the mapping:

| `currentStep` | User-facing label | Screen name |
|---|---|---|
| `-1` | Welcome | Quantity selector |
| `0` | Step 1 | Add Your Product |
| `1` | Step 2 | Shipping |
| `2` | Step 3 | Logo Upload |
| `3` | Step 4 | Premium Goods |
| `4` | Step 5 | Spirits |
| `5` | Step 6 | Message |

**Rule of thumb**: `currentStep + 1` = the step number shown in the UI.

---

## Current State: What's Built

| `currentStep` | Screen | Status |
|---|---|---|
| `-1` | Welcome — quantity slider + Start Kit button | ✅ Complete |
| `0` | Add Your Product — radio + size confirmation | ✅ Complete |
| `1` | Shipping — upload list or ship to me | ✅ Complete |
| `2` | Logo Upload — file upload + vector warning modal | ✅ Complete |
| `3` | Premium Goods selector + per-good configurator | ✅ UI complete — not wired to WooCommerce |
| `4` | Spirits | ❌ Not started |
| `5` | Message | ❌ Not started |

### Step 4 (Premium Goods) details
The goods configurator is the most complex screen. It has two sub-states:
- **Goods list** — 6 items (Tee, Hoodie, Cap, Tote, Bottle, Journal), multi-select up to 3, each with an icon + price
- **Good configurator** — opens when user taps Configure on a selected good; shows 4 dropdowns (Sleeve/Style, Color, Logo Position, Decoration) + a preview lightbox

After "Add to Kit": **loops back to the goods list** (not forward to checkout). This is intentional.

**Nothing is wired to WooCommerce yet.** All product data is hardcoded in `goodsConfig` at the top of `src/App.jsx`.

---

## User Flow (End to End)

```
Welcome → select quantity (24 / 48 / 72 / 96 / 120+)
  ↓
Step 1 → do you want to add your own product? (optional, $250)
  ↓
Step 2 → shipping: upload recipient list (CSV/XLS) OR ship to me
  ↓
Step 3 → upload your logo (SVG/AI/EPS/PDF preferred; PNG/JPG accepted with warning)
  ↓
Step 4 → select 1–3 premium goods, configure each one (style, color, logo position, decoration)
         └→ loops back to goods list after each "Add to Kit"
  ↓
Step 5 → spirits (1 bottle per kit — no quantity selector needed)
  ↓
Step 6 → message to Cody (~500–1000 chars, optional/skippable)
  ↓
Checkout → push all selections to WooCommerce cart via Store API → redirect to /checkout
```

---

## Where This Lives: WordPress + WooCommerce

### Deployment Plan (not yet implemented)

1. **Build** — `npm run build` → `dist/` folder
2. **Copy assets** into WP theme: `wp-content/themes/<theme>/kit-builder/`
3. **Enqueue in `functions.php`**:
   ```php
   wp_enqueue_script('kit-builder', get_template_directory_uri() . '/kit-builder/index.js', [], null, true);
   wp_enqueue_style('kit-builder-css', get_template_directory_uri() . '/kit-builder/index.css');
   wp_localize_script('kit-builder', 'kitBuilderData', [
     'nonce'  => wp_create_nonce('wc_store_api'),
     'apiUrl' => rest_url(),
   ]);
   ```
4. **Create page template** — `page-configurator.php` with only `<div id="root"></div>` as body
5. **In WP Admin** — create a page, assign that template

### Auth
**Nonce-based** (not API keys). The app is embedded in WordPress so `window.kitBuilderData.nonce` is available. Pass it as the `Nonce` header on all WooCommerce Store API calls. If the app ever needs to be hosted separately from WordPress, switch to API key auth.

---

## WooCommerce Integration Plan (Not Yet Coded)

### Product Structure
- Each good (Tee, Hoodie, etc.) is a **WooCommerce variable product** (target: sub-50 SKUs per parent)
- Tees: 4 sleeve/weight types (SS Lightweight, SS Heavyweight, LS Lightweight, LS Heavyweight) × Color × Size
- Other goods: Color × Style as variation axes
- **SKU naming convention** (confirmed): short descriptive, e.g. `SSTBlk5ozCrewM` (Short Sleeve Tee, Black, 5oz, Crew, Medium)
- **Images**: one real photo per variation (per SKU) — **no color overlays or dynamic compositing**. Portrait orientation ~390×650px required.
- Decoration (embroidery, screen print, etc.) is **not a product attribute**. It's a **Product Add-On line item** with a fixed additional price. **Either/or per item** — user picks one, not multiple.

### On App Load — Fetch Products
```
GET /wp-json/wc/v3/products?category=premium-goods&per_page=100
```
Replace the hardcoded `goodsConfig` in `App.jsx` with data from this response. Map:
- Product name → `good.name`
- Product image → `good.image`
- Variation attributes → dropdown options

### At Checkout — Push to Cart
For each selected good + configuration:
```
POST /wp-json/wc/store/v1/cart/add-item
{
  "id": <variation_id>,
  "quantity": <kit_quantity>,
  "extensions": {
    "product-add-ons": [ { "field_name": "decoration", "value": "1 Color Embroider" } ]
  }
}
```
Then `window.location.href = '/checkout'` — use **native WooCommerce checkout**. Do not build a custom checkout screen. This is required to preserve ShipStation, Sezzle (buy-now-pay-later), and existing payment plugins.

### Shipping List / Size Distribution Logic (confirmed in Figma notes)
When pushing to WooCommerce cart:
- **If list uploaded** → parse the uploaded CSV/XLS and use it to populate cart line items
- **If "Ship to me" (no list)** → auto-populate a default size distribution from kit quantity, e.g. 24 kits = 30% XL / 50% Large / 20% Medium — add as cart line items for the user to edit before checkout
- Exact default size percentages TBD with Cody

### Order Metadata to Capture
- Logo file URL (upload to WP Media Library first, store the URL)
- Shipping method (`'list'` or `'me'`)
- Shipping list file URL (if uploaded)
- Kit quantity
- Message text (Step 6)

### Logo Upload
```
POST /wp-json/wp/v2/media
Authorization: Bearer <nonce>
Content-Disposition: attachment; filename="logo.svg"
```
Cody confirmed WP hosting space is available; logos can be wiped periodically.

### Pricing Display
- Fixed price per item shown in the UI (no dynamic running total in the configurator)
- Bulk discount tiers by quantity starting at MOQ 24 (exact tier percentages TBD — need from Cody)
- Premium/heavier variants show a price delta as a subheading, e.g. "+$5/per unit"

---

## The `goodsConfig` Object (Current Hardcoded Data)

Lives at the **top of `src/App.jsx`, outside the component**. This is what gets replaced by WooCommerce API data at load time.

```js
const goodsConfig = {
  tee: {
    name: 'Tee',
    image: '/tee-placeholder.svg',        // ← replace with real WC product image URL
    dropdowns: [
      { id: 'sleeve', label: 'SLEEVE LENGTH', options: [...], key: 'sleeve' },
      { id: 'color',  label: 'COLOR',         options: [...], key: 'color' },
      { id: 'logoPosition', label: 'LOGO POSITION', options: [...], key: 'logoPosition' },
      { id: 'decoration',   label: 'DECORATION',    options: [...], key: 'decoration' },
    ],
  },
  // hoodie, cap, tote, bottle, journal — same shape
};
```

---

## Key Files

```
src/App.jsx          — All screens + all state. Single component (intentional for now).
src/index.css        — All styles. No Tailwind utility classes — custom CSS only.
public/
  chainmail-logo.png         — Header logo
  chain-texture.png          — Step indicator chain link background image
  tee-placeholder.svg        — Tee preview image (needs replacing with real portrait photo)
  icon-tee.svg               — Goods list icons (all 6 present)
  icon-hoodie.svg
  icon-cap.svg
  icon-tote.svg
  icon-bottle.svg
  icon-journal.svg
CLIENT_EMAILS.md     — Raw email archive (WPConfigurator thread + full discovery thread)
CHANGELOG.md         — Session-by-session build log
WOOCOMMERCE_INTEGRATION.md  — WooCommerce setup checklist

⚠️  LOGIC.md          — OUT OF DATE. Only covers Welcome + Step 1. Do not rely on it.
```

---

## Key Technical Decisions — Do Not Reverse Without Discussion

| Decision | Rationale |
|----------|-----------|
| **No WPConfigurator plugin** | React app already handles configuration UI. Direct WooCommerce API is cleaner. |
| **Nonce-based auth (not API keys)** | App is embedded in WordPress; nonce is simpler and more secure |
| **`goodsConfig` replaced dynamically, not hardcoded** | Cody must be able to manage products without a developer |
| **Decoration as Product Add-Ons, not variation attributes** | Decoration is a per-line-item modifier, not a product variant |
| **Decoration is either/or per item** | User picks print OR embroider — not both on the same item |
| **Image-per-SKU (no color overlays)** | Real photos per variation — confirmed with Cody in discovery |
| **Logo renders on Preview click only** | Performance — not a live reactive render |
| **3 fixed logo placement locations** | Selectable dropdown, not free-position drag-and-drop |
| **Native WooCommerce checkout (no custom checkout screen)** | Preserves ShipStation, Sezzle, and existing payment plugins |
| **Shipping step is Step 2 (second in flow)** | Moved early to reduce cognitive load at checkout — confirmed in discovery |
| **After Add to Kit → loop back to goods list** | User adds up to 3 goods before proceeding — confirmed in discovery |
| **Spirits = 1 bottle per kit** | No quantity selector needed for spirits step |
| **Single `App.jsx` component for now** | Refactor into separate components once full flow is complete and stable |
| **Tee configurator uses in-flow bottom bar (not fixed)** | Dropdowns open upward; fixed footer was covering them. Do not revert to fixed. |

---

## Blockers — Waiting on Cody

| Blocker | Why It's Needed |
|---------|----------------|
| **WordPress site URL** | Can't make any API calls without it |
| **WooCommerce product catalog built out** | Products must exist with SKUs (`SSTBlk5ozCrewM` format) before variation IDs can be looked up |
| **Product Add-Ons plugin installed on WP** | Required for decoration options as priced line items |
| **Portrait product photos (~390×650px)** | One photo per variation/SKU — real head-to-thigh shots. Current placeholders are too square. |
| **Exact Logo Position labels** | Code has "Center / Left" — Cody mentioned "Center Chest / Right Chest" — confirm all 3 |
| **Dropdown options for all goods** | Hoodie, Cap, Tote, Bottle, Journal all have TODO comments in `goodsConfig` |
| **Shipping list template file** | Download link in Step 2 is a placeholder — need the actual CSV/Excel template |
| **Bulk discount tier percentages** | Exact % off at each quantity tier (24, 48, 72, 96, 120+) needed to display pricing |
| **Figma design link** | Add to this doc for visual reference |

---

## Immediate Next Steps (Priority Order)

1. **Deploy preview for Cody's boss** — Cody needs a shareable URL to show his boss the current UI flow. Deploy to a static host (Netlify, Vercel, or GitHub Pages via `npm run build`) and send Cody the link. This is blocking a stakeholder review.
2. **Get WordPress site URL from Cody** — unblocks all WooCommerce integration work
4. **Set up local WordPress dev environment** (LocalWP recommended) with WooCommerce installed
5. **Wire up product fetch** — replace `goodsConfig` with live WooCommerce API data; validate with Tee first
6. **Wire up logo upload** — POST to WP Media Library, store returned URL as order metadata
7. **Wire up cart handoff** — push all selections to WooCommerce cart, redirect to `/checkout`
8. **End-to-end test** — one full kit with a configured tee variation through to WooCommerce checkout

---

## App State Reference

| Variable | Type | Default | Purpose |
|----------|------|---------|---------|
| `currentStep` | number | `-1` | -1=welcome, 0–5=steps (see step mapping above) |
| `kitQuantityIndex` | number | `0` | Index into `[24, 48, 72, 96, '120+']` |
| `addProduct` | boolean | `false` | User wants to include their own product in the kit |
| `confirmSize` | boolean | `false` | User confirmed their product is ≤ 12×12×8″ |
| `shippingOption` | string | `'list'` | `'list'` = upload recipient CSV, `'me'` = ship to themselves |
| `shippingFile` | File\|null | `null` | Uploaded recipient CSV/Excel file |
| `logoFile` | File\|null | `null` | Uploaded logo file |
| `showLogoWarning` | boolean | `false` | Non-vector file warning modal is showing |
| `selectedGoods` | string[] | `[]` | Selected good IDs, max 3 (e.g. `['tee', 'hoodie']`) |
| `configuringGood` | string\|null | `null` | Which good's configurator panel is open |
| `goodConfigurations` | object | `{}` | Per-good selections: `{ tee: { sleeve, color, logoPosition, decoration } }` |
| `openDropdown` | string\|null | `null` | Which dropdown is currently open (only one at a time) |
| `showPreview` | boolean | `false` | Preview lightbox is visible |

---

## CSS Patterns Worth Knowing

**Full-width pink/gray bands** (upload areas, selected rows — bleeds past the 28px padding):
```css
margin-left: -28px; margin-right: -28px; padding: 16px 28px;
```

**Icon → red on selection** (CSS filter converts black SVG to `#ED2024`):
```css
filter: invert(16%) sepia(95%) saturate(6932%) hue-rotate(355deg) brightness(94%) contrast(98%);
```

**Fixed bottom nav bar** (all screens except the goods configurator):
```css
.bottom-area { position: fixed; bottom: 0; left: 50%; transform: translateX(-50%); width: 100%; max-width: 430px; }
```

**Goods configurator uses in-flow bottom bar** — not fixed — because dropdowns open upward and were being hidden behind a fixed footer. Do not change this back to fixed.

**Preview lightbox structure:**
- `.tee-preview-overlay` — `position: fixed; top: 112px; left: 0; right: 0; bottom: 0; padding: 16px` (starts below chain link step indicator)
- `.tee-preview-panel` — flex column wrapper for card + close bar
- `.tee-preview-card` — `flex: 1; background: #fff` — the white card
- `.tee-preview-close-bar` — flush against card bottom, full width, black background

---

## Slash Commands

| Command | What It Does |
|---------|-------------|
| `/loggit` | Reviews the session and updates memory files — run this at the end of every session |
| `/context` | Outputs a current project status summary |
