# Chamevo UI Fixes — Deployment Guide

**Status:** Code complete. Deployment blocked — needs WordPress admin or SFTP access.  
**Deadline:** Tuesday, May 26, 2026  
**Time to deploy once access is granted:** ~15 minutes

---

## What These Fixes Do

### Fix 1 — Brand skin (configurator page)
Overrides Chamevo's default indigo/blue color scheme to Chainmail purple (`#993399`).  
Chamevo uses CSS custom properties (`--cv-primary` etc.) injected at `:root`, so we override them from the child theme stylesheet — no plugin code changes required.

The left sidebar ("Windows Explorer" panel with tiny icons) can also be hidden by uncommenting one line in `chamevo-fixes.css`.

### Fix 2 — Product page behavior
- **Hides** the generic gold "TEE" mockup that Chamevo renders on the product page before any variation is selected.
- **Auto-navigates** to the Chamevo configurator the moment the customer completes all 4 variation dropdowns (Color, Weight, Sleeve Length, Decoration Method). No manual "CONFIGURE" button click needed.

Technical mechanism:
- JS reads `window.chamevo_setup_configs.selector` (injected by Chamevo's plugin) to find and hide its container div.
- WooCommerce fires `show_variation` on the variations form when all dropdowns have values AND a matching variation exists. Our JS clicks `#cha-start-customizing-button` (which has a `data-href` pointing to the Chamevo configurator with the variation's product ID) 400ms after that event, navigating the customer directly into the configurator.

---

## Files to Deploy

All 3 files live in `/chamevo-fixes/` in this repo.

| File | Deploy to |
|------|-----------|
| `chamevo-fixes.css` | `wp-content/themes/hello-biz-child/chamevo-fixes.css` |
| `chamevo-fixes.js` | `wp-content/themes/hello-biz-child/chamevo-fixes.js` |
| `chamevo-fixes-functions.php` | Add contents to bottom of `wp-content/themes/hello-biz-child/functions.php` |

---

## Deployment Steps

### Option A — WP Admin Theme Editor (easiest)
1. Log in to WP Admin → **Appearance → Theme File Editor**
2. Select **Hello Biz Child** in the theme dropdown (top right)
3. **Edit `functions.php`** — paste the contents of `chamevo-fixes-functions.php` at the bottom, before the closing `?>` if one exists
4. **Create `chamevo-fixes.css`** — click "Add new file", name it `chamevo-fixes.css`, paste contents from `chamevo-fixes/chamevo-fixes.css`
5. **Create `chamevo-fixes.js`** — same, name it `chamevo-fixes.js`, paste from `chamevo-fixes/chamevo-fixes.js`

### Option B — SFTP
1. Connect via SFTP to `chainmail.store` → navigate to `wp-content/themes/hello-biz-child/`
2. Upload `chamevo-fixes.css` and `chamevo-fixes.js`
3. Download `functions.php`, append contents of `chamevo-fixes-functions.php`, re-upload

### Option C — Elementor Custom Code (no FTP needed, if Elementor Pro active)
1. WP Admin → **Elementor → Custom Code → Add New**
2. Add CSS code (from `chamevo-fixes.css`), set location to "Head", display on "Entire Site"
3. Add JS code (from `chamevo-fixes.js`), set location to "Body - End", display on "Entire Site"
4. This avoids editing `functions.php` entirely

---

## What's Blocked

WordPress admin login requires 2FA (Google Authenticator or similar). REST API basic auth is also blocked on this host.

**To unblock deployment, Cody needs to either:**
1. Share the current 2FA code so we can log in, OR
2. Go to WP Admin → Users → Profile → Application Passwords, generate one, and share it, OR
3. Go to WP Admin → Elementor → Custom Code and paste the CSS/JS himself using the contents above, OR
4. Grant SFTP credentials

---

## Testing After Deployment

1. Go to the TEE product page
2. Confirm: the gold "TEE" Chamevo mockup is NOT visible on page load — you should see the clean WooCommerce product images
3. Select a value for all 4 dropdowns: Color, Weight, Sleeve Length, Decoration Method
4. Confirm: browser auto-navigates to the Chamevo configurator within ~1 second (no button click needed)
5. In the Chamevo configurator, confirm: the primary color (buttons, highlights) is purple (`#993399`) instead of blue

---

## CSS Variables Available for Further Customization

Chamevo uses these CSS custom properties — all can be overridden at `:root` in `chamevo-fixes.css`:

```
--cv-primary          #993399 (was #5b5bd6) — buttons, active states
--cv-primary-hover    auto-calculated from primary
--cv-sidebar-width    280px → set to 0px to hide left panel
--cv-mainbar-width    70px  → set to 0px to hide icon toolbar
--cv-toolbar-height   48px  → top toolbar bar
--cv-panel-width      260px → right properties panel
--cv-bg               #fcfcfd — app background
--cv-text             #1c2024 — primary text
--cv-border           #d9d9e0 — border color
```
