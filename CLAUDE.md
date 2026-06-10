# Chainmail Kit Builder — Agent Quickstart

Read this top to bottom before touching anything. It covers the full current state of the project as of June 10, 2026.

---

## What This Is

A **React kit configurator** for a company called **Chainmail**, built for a client named **Cody Gilbertson** (cody.gilbertson@gmail.com). Users build a custom branded kit — their own product + apparel + spirits + a message — through a guided step-by-step mobile UI, then check out through WooCommerce.

- **Not a standalone site.** Will be embedded in Cody's WordPress site. Currently demoing on Vercel.
- **WooCommerce handles all products, cart, and orders.** The React app is the configurator UI only.
- **Cody's #1 UX mandate (his words):** *"the process is very important for this site"* — the smooth step-by-step flow is non-negotiable. Do not collapse or shortcut it.

---

## Tech Stack

| Layer | What |
|---|---|
| Framework | React 19 |
| Build tool | Vite 7 |
| Styles | Custom CSS in `src/index.css` — Tailwind installed but barely used |
| Icons | lucide-react + custom SVGs in `public/` |
| WP plugin | WooCommerce Product Add-Ons Ultimate (Plugin Republic) |

```bash
npm run dev        # local dev server
npm run build      # build for deploy (outputs dist/)
```

---

## Deployment

| Environment | URL |
|---|---|
| React app (demo) | `https://chainmail-pi.vercel.app/` — auto-deploys on push to `main` |
| WordPress site | `https://chainmail.store/dev/` |
| WP admin | `chainmail.store/dev/gloryhole` |
| GitHub | `git@github.com:JeradGriffin/chainmail.git` |

- WP is in **Coming Soon mode** — demo requires being logged into WP admin
- WP child theme: `hello-biz-child`
- **`chainmail.store/dev` URL is going away** — when it does, find-and-replace all `/dev/` references. No architectural change needed.

---

## Brand

- **Purple**: `#6A449B` — used as `--color-brand` in `index.css`
- **Max width**: 430px, centered, mobile-first
- **Font**: Inter / system sans

---

## App Flow (Current, Confirmed)

```
Welcome (-1)    → quantity selector (24 / 48 / 72 / 96 / 120+)
Step 1 (0)      → Add Your Product (optional, $1/item service fee)
Step 2 (1)      → Shipping (upload recipient list OR ship to me)
[Step 3 removed — logo upload now owned by WooCommerce plugin]
Step 3 (3)      → Add Premium Goods → each good → WC product page → back here
Step 4 (4)      → Spirits (1 bottle per kit, no qty selector)
Step 5 (5)      → Message (optional, ~500–1000 chars)
Step 6 (6)      → Review → "Go to Checkout" → chainmail.store/dev/checkout/
```

`currentStep` is 0-indexed, -1 for welcome. Step 2 (logo) is skipped in code — `renderLogo` exists but is unreachable.

---

## Key Files

```
src/App.jsx                          — All screens + all state. Single component.
src/index.css                        — All styles.
public/
  tee-preview.jpg                    — Tee product image (real photo)
  chain_0.svg … chain_5.svg          — Step indicator chain graphics (5-link)
  icon-tee.svg … icon-journal.svg    — Goods list icons
wc-tee-configurator.php              — WP Code Snippets: mobile overlay + desktop tabs on all WC product pages
wc-redirect-after-tee.php           — Repo version (NOT what's in WP — see below)
wc-redirect-after-tee.wp-backup-2026-06-10.php  — ← THIS is what's currently in WP
wc-redirect-after-goods.php         — WP Code Snippets: redirect for hoodie/cap/tote/tumbler/journal
wc-tee-product-styles.php           — WP Code Snippets: mobile CSS for Tee product page (post ID 1946)
```

---

## WordPress — 4 Active Code Snippets

| Snippet file | Scope | Purpose |
|---|---|---|
| `wc-redirect-after-tee.wp-backup-2026-06-10.php` | `/product/tee` URL | Hide page, inject Back button, detect add-to-cart, redirect to kit builder |
| `wc-redirect-after-goods.php` | `?kit=1` on any non-Tee page | Same as above for hoodie/cap/tote/tumbler/journal |
| `wc-tee-configurator.php` | All WC product pages | Mobile overlay + desktop tabs UI, step chain indicator |
| `wc-tee-product-styles.php` | Post ID 1946 (Tee) only | Mobile CSS polish for Tee product page |

**CRITICAL:** The file `wc-redirect-after-tee.php` in the repo is NOT what's deployed to WP. The backup file (`wc-redirect-after-tee.wp-backup-2026-06-10.php`) is what's in Code Snippets. Do not update the Tee redirect snippet without extensive testing — it broke twice in one session.

---

## How the WC Product Page Redirect Flow Works

### Tee
1. Kit builder redirects to `chainmail.store/dev/product/tee/?quantity={qty}&kit=1&gid=tee`
2. `wc-tee-configurator.php` shows the step overlay; user configures (color, sleeve, logo, decoration)
3. User clicks "Add to Kit" → AJAX add-to-cart → `added_to_cart` jQuery event fires
4. Configurator redirects to `chainmail-pi.vercel.app/?resume=goods`
5. Kit builder restores state from localStorage, marks Tee selected, returns to goods list

### Non-Tee Goods (Hoodie, Cap, Tote, Tumbler, Journal)
1. Kit builder redirects to `{wcUrl}?quantity={qty}&kit=1&gid={good.id}`
2. `wc-redirect-after-goods.php` hides page, injects Back button, sets sessionStorage `cm_kit` = current pathname
3. User fills in WC product form → Add to Cart → page reloads
4. On reload: sessionStorage path matches + `.woocommerce-message` in DOM → **before redirecting**, `captureGood()` runs:
   - Reads `params.get('gid')` to get the good ID
   - Captures `img.wp-post-image` src → stores as `cfg.image`
   - Reads selected variation attributes (table.variations select)
   - Reads selected add-on radio values (pewc items)
   - Formats as `"Black | Pullover | 1 Color Print"` → stores as `cfg.detail`
   - Writes to `localStorage('cm_good_{gid}', JSON.stringify(cfg))`
5. Redirects to `chainmail-pi.vercel.app/?resume=goods`
6. Kit builder reads `cm_good_*` from localStorage, merges into `goodConfigurations` state
7. Review screen shows captured image + detail line under each product

### On `?resume=goods` — State Restore (App.jsx useEffect)
```js
const saved = JSON.parse(localStorage.getItem('chainmail_kit_state'));
// restore kitQuantityIndex, shippingOption, selectedGoods, goodConfigurations…
// ALSO: read cm_good_* from localStorage and merge into goodConfigurations
['tee','hoodie','cap','tote','bottle','journal'].forEach(id => {
    const raw = localStorage.getItem('cm_good_' + id);
    if (raw) wcCaptured[id] = JSON.parse(raw);
});
setGoodConfigurations({ ...baseCfg, ...wcCaptured });
setCurrentStep(3); // returns to goods list
```

---

## Critical Rules — Do Not Break These

### 1. NEVER modify `wc-tee-configurator.php` for redirect logic
The configurator's `added_to_cart` handler (`window.location.href = cBase + '?resume=goods'`) is fragile. Adding code before it broke the Tee twice. Leave it alone. Only change the configurator for UI changes.

### 2. Keep the two redirect snippets separate
Every attempt to merge the Tee redirect and the goods redirect into one snippet broke one or the other. Tee snippet is scoped to `/product/tee` URL. Goods snippet is scoped to `?kit=1` + excludes `/product/tee`. They must stay separate.

### 3. WC false-redirect root causes
- **Cart fragment AJAX**: fires on every product page load, injects mini-cart with "View cart" text → observer must watch for `.woocommerce-message` CLASS, not "view cart" text
- **Stale session notices**: after Tee AJAX add-to-cart, WC stores a success notice in session but never renders it on the Tee page. That notice renders on the NEXT product page visited. On-load `.woocommerce-message` checks must be gated (goods snippet uses sessionStorage to distinguish first visit from post-add-to-cart reload)

### 4. `&gid=` param is required for localStorage capture
The URL slug (e.g. `tumbler`) may not match the React app's good ID (e.g. `bottle`). App.jsx appends `&gid=${good.id}` to all WC redirect URLs. The goods snippet reads `params.get('gid')` — never try to infer the ID from the URL slug.

### 5. WP Code Snippets JS line length
WordPress wraps lines over ~80 chars in mixed `?> <script> <?php` mode, breaking string literals. Keep all JS lines under 75 chars. See `memory/feedback_wp_snippet_heredoc.md`.

### 6. `.pewc` selectors
Never use `.pewc-item`, `.pewc-group` class selectors to find inputs — class names are unreliable and matched 140 elements once. Find inputs by type: `input[type="file"]`, `input[type="radio"]`, etc. See `memory/feedback_pewc_and_observer.md`.

---

## WooCommerce Product Setup

| Good | WC URL | Product ID |
|---|---|---|
| Tee | `chainmail.store/dev/product/tee/` | 1946 |
| Hoodie | `chainmail.store/dev/product/hoodie/` | unknown |
| Cap | `chainmail.store/dev/product/cap/` | unknown |
| Tote | `chainmail.store/dev/product/tote/` | unknown |
| Tumbler | `chainmail.store/dev/product/tumbler/` | unknown |
| Journal | `chainmail.store/dev/product/journal/` | unknown |

- Tee has Product Add-Ons configured: logo upload/drag-drop, Logo Position (Left/Center), Decoration Method
- All goods use Product Add-Ons Ultimate (Plugin Republic)
- Max 3 goods selectable at once — 4th selection is silently blocked

---

## App State Reference

| Variable | Type | Default | Purpose |
|---|---|---|---|
| `currentStep` | number | `-1` | -1=welcome, 0–5=steps |
| `kitQuantityIndex` | number | `0` | Index into `[24, 48, 72, 96, '120+']` |
| `addProduct` | boolean | `false` | User adding their own product ($1/item) |
| `confirmSize` | boolean | `false` | Size confirmation checkbox |
| `shippingOption` | string | `'list'` | `'list'` or `'me'` |
| `shippingFile` | File\|null | `null` | Uploaded recipient list |
| `selectedGoods` | string[] | `[]` | Selected good IDs, max 3 |
| `goodConfigurations` | object | `{}` | Per-good config incl. `image` and `detail` captured from WC |
| `selectedSpirit` | string\|null | `null` | Selected spirit ID |
| `messageText` | string | `''` | Kit message |

---

## Step 0 — Add Your Product

Three checkboxes, all in `.checkbox-group` div:
1. Size confirm (< 12″ × 12″ × 8″)
2. "$1 per item service fee" agree
3. Regulated substance checkbox + policy link (in `.regulated-block`) — policy link `href="#"` is a placeholder pending Cody's actual URL

## Step 1 — Shipping

- Upload dropzone for recipient list (XLSX/XLS/CSV)
- Download link: `chainmail.store/dev/wp-content/uploads/2026/06/CHAINMAIL-SHIPPING-TEMPLATE.xlsx`
- `canContinue`: selecting any option is enough — file upload not required to proceed

## Step 3 — Add Premium Goods

Goods with WC URLs redirect to WC product page. Others (if any) open in-app configurator. `goodsWcUrls` maps good ID to WC URL. All 6 goods have WC URLs configured.

## Step 4 — Spirits

7 spirits: Kettle One (Vodka), Manojo (Mezcal), Johnnie Walker Black (Scotch), Jameson (Irish Whiskey), Four Roses (Bourbon), Casamigos (Tequila), Maestro Dobel (Tequila)

## Step 6 — Review

Shows inclusions list with:
- Image: `goodConfigurations[gid].image` (captured from WC) or `goodsConfig[gid].image` fallback
- Detail line: `goodConfigurations[gid].detail` (e.g. "Black | Pullover | 1 Color Print") or parts joined from in-app config

---

## Chain Step Indicator

SVGs in `public/chain_0.svg` through `chain_5.svg` (5 links). Map from `currentStep` to active link count:
```js
[0,1,2,2,3,4,5,5][currentStep + 1] ?? 0
```
WC product pages show `chain_3.svg` (step 3 = Premium Goods).

---

## Logo Handling

**Plugin owns logo upload.** The WC Product Add-Ons plugin handles logo upload, drag-and-drop placement, and position on the product page. Step 2 (logo upload) was removed from the React flow. `renderLogo` function still exists in App.jsx but is unreachable — safe to delete once confirmed.

Logo preview limitation: on mobile, the drag-and-drop placement canvas doesn't render inside our configurator overlay. Users select Left/Center from dropdown. Known limitation — flagged to Cody.

---

## Pending Work

- Verify end-to-end flow for Cap, Tote, Tumbler, Journal (Hoodie confirmed working)
- Verify review screen shows captured image + detail for non-Tee goods
- Full end-to-end mobile Tee test
- Confirm Logo Position options with Cody (Left/Center — is Right needed?)
- Confirm dropdown options for all non-Tee goods
- Replace policy link `href="#"` with actual URL (waiting on Cody)
- WC product fetch to replace hardcoded `goodsConfig` (blocked on catalog)
- Long-term: embed kit builder directly in WordPress (replace Vercel demo link)
- Delete `renderLogo` once plugin ownership is confirmed final
- When `chainmail.store/dev` goes away: find-and-replace `/dev/` in all URLs

---

## Slash Commands

| Command | What |
|---|---|
| `/loggit` | Update memory files — run at end of every session |
| `/context` | Output current project status summary |
