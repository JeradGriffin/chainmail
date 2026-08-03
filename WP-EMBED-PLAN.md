# WordPress Embed Migration Plan

Moving the kit builder from Vercel (`chainmail-pi.vercel.app`) to a WordPress page on `chainmail.store`. Once done, the cross-origin issues go away and the Vercel deployment is no longer needed.

---

## Overview

The React app is built as a static bundle (`npm run build` → `dist/`). WordPress serves it from a dedicated page. Everything else — WooCommerce, product pages, checkout, Code Snippets — stays the same.

---

## Step 1 — Build and Upload Assets

```bash
npm run build
```

Upload the contents of `dist/` to WP. Two good options:

**Option A — Child theme folder** (simplest)
- Upload `dist/assets/` into `wp-content/themes/hello-biz-child/kit-builder/`
- Enqueue from `functions.php` of the child theme

**Option B — Custom plugin** (cleaner separation)
- Create a small plugin `chainmail-kit-builder/`
- Drop `dist/assets/` inside it
- Enqueue from the plugin

Either way works. Option A is faster to ship.

---

## Step 2 — Enqueue Scripts and Styles in WP

Add to child theme `functions.php` (or plugin):

```php
add_action('wp_enqueue_scripts', function() {
    if (!is_page('kit')) return; // match your page slug
    wp_enqueue_style(
        'cm-kit',
        get_stylesheet_directory_uri()
            . '/kit-builder/assets/index-[hash].css',
        [], null
    );
    wp_enqueue_script(
        'cm-kit',
        get_stylesheet_directory_uri()
            . '/kit-builder/assets/index-[hash].js',
        [], null, true
    );
});
```

The `[hash]` in the filename changes on every build — either hardcode it after each deploy, or use a glob/manifest approach to pick it up automatically.

---

## Step 3 — Create the WP Page

- Create a new WP page at `/kit/` (or whatever slug you want)
- Set its template to a blank/canvas template (no header/footer from the theme) so the React app's own header shows
- The page body just needs `<div id="root"></div>` — add it via a Custom HTML block in the editor

---

## Step 4 — URL Find-and-Replace

Replace all `chainmail-pi.vercel.app` references. The new base URL is `chainmail.store/kit/` (adjust to match your page slug).

Files to update:

| File | What changes |
|---|---|
| `src/App.jsx` | Checkout URL, any absolute links |
| `wc-product-configurator.php` | `cBase` variable (line ~988) |
| `wc-redirect-after-goods.php` | `resumeUrl` and `backUrl` |
| `wc-redirect-after-tee.php` | `resumeUrl` and `backUrl` |
| `wc-redirect-after-tee.wp-backup-*.php` | `resumeUrl` and `backUrl` |

After updating, do a final grep to confirm nothing is left:

```bash
grep -r "chainmail-pi.vercel.app" --include="*.php" --include="*.jsx" --include="*.js" .
```

---

## Step 5 — Simplify the Redirect Snippets (Optional)

Once the kit builder is on `chainmail.store`, localStorage is same-origin. The goods snippet can go back to writing `cm_good_hoodie` to localStorage directly — the URL params workaround is no longer needed.

Revert `wc-redirect-after-goods.php` `goResume()` to:
```js
function goResume() {
    captureGood(); // writes to localStorage
    sessionStorage.removeItem('cm_kit');
    window.location.href = resumeUrl;
}
```

And remove the URL param reading from App.jsx's `?resume=goods` handler.

This is optional — the URL params approach works fine either way.

---

## Step 6 — Deploy and Test

1. Push to WP (upload new `dist/assets/`, update enqueue hash)
2. Flush WP caches
3. Visit `chainmail.store/kit/` — kit builder should load
4. Run through the full flow end-to-end:
   - Welcome → quantity
   - Add Your Product
   - Shipping
   - Goods: tap a good → WC product page → Add to Cart → back to kit
   - Spirits → Message → Review
   - Go to Checkout → `chainmail.store/checkout/`
5. Verify review screen shows images and detail lines for all added goods

---

## Step 7 — Decommission Vercel

Once end-to-end is confirmed on WP:
- Remove the Vercel project (or just stop pushing to it)
- The GitHub repo stays — WP deploys are manual upload for now

---

## Product Page Overlay — Planned Rethink

The current `wc-product-configurator.php` injects a full-screen mobile overlay on WC product pages (the `#tee-conf` div). The intent was to create a custom step-by-step UI matching the kit builder's look.

**Cody's preference**: native WC product pages styled for mobile with CSS — no overlay. This is simpler and more maintainable.

**Plan** (not yet implemented):
- Delete the mobile overlay JS from `wc-product-configurator.php` (or delete the file entirely if the desktop tab widget isn't needed either)
- Expand `wc-tee-product-styles.php` to cover all the mobile CSS needed on product pages
- The "Back to Kit Builder" link, quantity pre-fill, and redirect-on-add-to-cart all stay in the redirect snippets — those are unaffected

This should be done after the WP embed is confirmed working, so there's only one big change at a time.
