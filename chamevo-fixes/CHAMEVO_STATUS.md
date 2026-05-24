# Chamevo Integration — Current Status & Investigation Log

_Last updated: 2026-05-24_

---

## Bottom Line

Our CSS/JS fixes are **code-complete and committed**. They cannot be tested or deployed yet because Chamevo has not been configured for the TEE product in WordPress Admin. That is the only remaining blocker.

---

## What We Built (Ready to Deploy)

Three files in `chamevo-fixes/`:

| File | Purpose |
|------|---------|
| `chamevo-fixes.css` | Overrides Chamevo brand color to `#993399`, hides Chamevo container on product page load |
| `chamevo-fixes.js` | Auto-navigates to Chamevo configurator when all 4 variation dropdowns are selected |
| `chamevo-fixes-functions.php` | Snippet to add to `hello-biz-child/functions.php` to enqueue both files |

See `CHAMEVO_FIXES.md` for full deployment instructions (3 options: WP Theme Editor, SFTP, or Elementor Custom Code).

---

## How the JS Fix Works (Technical)

1. On page load, reads `window.chamevo_setup_configs.selector` (injected by Chamevo's PHP) to find and hide the Chamevo embed container so the generic "TEE" mockup doesn't show.
2. Listens for WooCommerce's `show_variation` jQuery event on `form.cart.variations_form` — this fires when ALL 4 dropdowns have values AND a matching product variation exists.
3. 400ms after `show_variation`, reads `data-href` from `#cha-start-customizing-button` (which Chamevo's own JS populates with the correct variation product ID) and navigates there automatically.
4. Resets on `reset_data` so it re-fires if the customer changes a selection.

---

## What We Discovered During Investigation

### WordPress Site
- **WP root**: `https://chainmail.store/dev/`
- **Child theme**: `hello-biz-child` — confirmed active, `style.css` is currently empty (header only)
- **Coming Soon mode**: Active via Elementor — blocks unauthenticated page requests but NOT direct static file access (`wp-content/` files are publicly readable)
- **REST API auth**: Basic auth (username/password) is **disabled** on this host. WP admin login requires 2FA. WC REST API (consumer key/secret) works but only covers products/orders — not theme files or settings.

### Chamevo Plugin
- **Version**: 2.1.0
- **Script path**: `wp-content/plugins/chamevo/assets/frontend-v2/js/frontend-woo-variations.js`
- **Technology**: StencilJS shadow DOM web components. All UI colors/sizes are controlled via CSS custom properties (`--cv-*`) injected at `:root` — these CAN be overridden from an external stylesheet.
- **Container**: Chamevo renders its editor into `document.getElementById(window.chamevo_setup_configs.selector)`. The `selector` value is set per-product by Chamevo's PHP via `wp_localize_script`.
- **Configure button**: `#cha-start-customizing-button` — shown by Chamevo's JS after `show_variation` fires, contains `data-href` pointing to the configurator URL with the variation's product ID appended as `&fpd_product=<id>`.
- **WooCommerce variation select IDs**: `#color`, `#weight`, `#sleeve-length`, `#decoration-method`

### Key CSS Variables (for future skinning)
```
--cv-primary          #5b5bd6 → override to #993399 (brand purple)
--cv-primary-hover    auto-calculated
--cv-sidebar-width    280px  → set to 0px to hide left panel
--cv-mainbar-width    70px   → set to 0px to hide icon toolbar
--cv-toolbar-height   48px
--cv-panel-width      260px  → right properties panel
--cv-bg               #fcfcfd
--cv-text             #1c2024
```

### TEE Product (ID 1846)
- Only product currently in WooCommerce
- Has 4 variation attributes: Color, Weight, Sleeve Length, Decoration Method
- Status: **Private** (set back to private after testing — use WC API to temporarily publish)
- **Chamevo is NOT configured on this product** — `window.chamevo_setup_configs` returns `undefined`, `#cha-start-customizing-button` returns `null`

---

## Why Chamevo Isn't Showing on the Product Page

Chamevo requires a two-part setup in WP Admin before it does anything on a product page:

1. **Create a Chamevo design** — in WP Admin → Chamevo, create a design template for the TEE (upload the blank shirt canvas, define the print area, etc.)
2. **Link the design to the product** — in the product's WP Admin edit page, connect the Chamevo design to the TEE product

Until both steps are done, `chamevo_setup_configs` is never injected, the configure button never appears, and our JS has nothing to hook into.

Cody mentioned in the May 17 meeting that he had "got it all working" with Chamevo — but this configuration either:
- Was done on a different/local environment and not on `chainmail.store/dev/`, OR
- Was done on a different product that no longer exists, OR
- Has not been done on this WP site yet

---

## What Needs to Happen Next (In Order)

### Step 1 — Cody configures Chamevo in WP Admin
Cody logs in (requires his 2FA) and:
- Creates a Chamevo design for the TEE product
- Links it to WooCommerce product ID 1846
- Verifies the `#cha-start-customizing-button` appears on the product page
- Verifies the Chamevo configurator opens when that button is clicked

### Step 2 — We deploy our fixes
Once Step 1 is confirmed, deploy the 3 files from `chamevo-fixes/` to the child theme.  
Easiest method: Cody uses **WP Admin → Elementor → Custom Code** (no FTP, no file editing) — see `CHAMEVO_FIXES.md` Option C.

### Step 3 — Test
1. Go to TEE product page
2. Verify: Chamevo mockup is hidden on load (clean product photo only)
3. Select all 4 dropdowns
4. Verify: browser auto-navigates to Chamevo configurator (~400ms, no button click)
5. In configurator: verify buttons/accents are purple (`#993399`) not blue

---

## Console Test Snippet (for when Chamevo IS configured)

Paste in browser DevTools console on the product page to test without deploying:

```javascript
var s = document.createElement('style');
s.textContent = `
  :root { --cv-primary: #993399; --cv-primary-hover: color-mix(in srgb, #993399, black 10%); }
  .chainmail-chamevo-hidden { display: none !important; }
  body.single-product #cha-start-customizing-button { display: none !important; }
`;
document.head.appendChild(s);

var cfg = window.chamevo_setup_configs;
if (cfg && cfg.selector) {
  var el = document.getElementById(cfg.selector);
  if (el) el.classList.add('chainmail-chamevo-hidden');
  console.log('Chamevo container hidden:', cfg.selector);
} else {
  console.log('chamevo_setup_configs not found — Chamevo not configured on this product yet');
}

var hasOpened = false;
jQuery('form.cart.variations_form').on('show_variation', function(e, variation) {
  if (hasOpened || !variation || !variation.chamevo_variation_product_id) return;
  hasOpened = true;
  console.log('All variations selected — navigating in 400ms...');
  setTimeout(function() {
    var btn = document.querySelector('#cha-start-customizing-button');
    if (btn && btn.dataset.href) window.location.href = btn.dataset.href;
    else console.log('Button not found or no href:', btn);
  }, 400);
});
jQuery('form.cart.variations_form').on('reset_data', function() { hasOpened = false; });
console.log('Chamevo fixes active — waiting for variation selection.');
```

---

## WC API Commands (useful for testing)

Temporarily publish TEE product:
```bash
curl -s -X PUT "https://chainmail.store/dev/wp-json/wc/v3/products/1846" \
  --user "ck_f33ae122196e264232ce4bd02b325cf7e9e67ba8:cs_b6d4c1db3ffd097f53b5c05b64547d536f46b7c8" \
  -H "Content-Type: application/json" \
  -d '{"status":"publish"}'
```

Set back to private:
```bash
curl -s -X PUT "https://chainmail.store/dev/wp-json/wc/v3/products/1846" \
  --user "ck_f33ae122196e264232ce4bd02b325cf7e9e67ba8:cs_b6d4c1db3ffd097f53b5c05b64547d536f46b7c8" \
  -H "Content-Type: application/json" \
  -d '{"status":"private"}'
```
