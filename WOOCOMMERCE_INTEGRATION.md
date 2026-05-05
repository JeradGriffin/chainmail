# WooCommerce Integration Checklist

## How It Works

1. **Fetch products dynamically** — app calls WooCommerce REST API at load time to get goods, prices, and variation options. Cody manages everything in WP Admin; no code changes needed.
2. **Cart handoff** — at end of configurator, app pushes each selection to the WooCommerce cart via Store API, then redirects to `/checkout`.
3. **Variable products + Add-Ons** — each good (Tee, Hoodie, etc.) is a variable product; decoration options (embroidery, screen print) are Product Add-On line items.
4. **Order metadata** — logo file, shipping list, kit quantity, and message are stored as order metadata, visible to Cody in WP Admin.

---

## WooCommerce Setup
- [ ] WooCommerce installed & active on the WordPress site
- [ ] **WooCommerce Product Add-Ons** plugin installed (for decoration options as line items)
- [ ] Products built out in WP Admin with correct variation structure (Color × Size, etc.)
- [ ] A product category created (e.g. "Premium Goods") so the app can fetch by category
- [ ] Each product variation has a **SKU** set (needed to match app selections to variation IDs)

## API Credentials
- [ ] **WooCommerce REST API key pair** generated — WP Admin → WooCommerce → Settings → Advanced → REST API (Consumer Key + Consumer Secret)
- [ ] Decide auth method:
  - **Nonce-based** (recommended) — simpler, works natively when app is embedded in WordPress
  - **API key-based** — needed if app is ever hosted separately

## WordPress Setup
- [ ] React app built (`npm run build`) and deployed into WP theme or plugin folder
- [ ] `functions.php` updated to enqueue React JS/CSS via `wp_enqueue_script`
- [ ] Custom page template created (`page-configurator.php`) with `<div id="root">`
- [ ] WordPress nonce passed to React app via `wp_localize_script`
- [ ] A WordPress page created in WP Admin and assigned the custom template

## File Handling
- [ ] Decide where **logo uploads** go (WP Media Library via REST API is simplest)
- [ ] Decide where **shipping list files** go (attach to order as metadata, or upload to Media Library)
- [ ] Confirm max file size limits on server (`upload_max_filesize` in `php.ini`)
- [ ] Link the shipping list **template file** download in the app (currently a TODO)

## Checkout & Orders
- [ ] WooCommerce checkout page set up and styled
- [ ] Confirm what order metadata to capture: logo file URL, shipping method, kit quantity, message
- [ ] Verify order metadata shows up correctly in WP Admin order view for Cody

## Dev & Testing
- [ ] Local WordPress dev environment set up (e.g. LocalWP)
- [ ] WooCommerce test mode enabled during development
- [ ] At least one fully configured test product with variations to validate cart handoff end-to-end

---

## Key Dependency
> Cody needs to finish building the product catalog in WP Admin before the cart integration can be tested. Without real product and variation IDs, the handoff can't be validated.
