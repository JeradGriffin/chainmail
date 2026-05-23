# Chamevo Action Plan

**Date:** May 23, 2026
**From:** Cody
**Decision needed:** Today

---

## Cody's Update (in his words)

**The Pros**
Got it all working. Made the variable products, figured out the layering, configuration, how to get it to link to products, pull everything correctly, and size the artwork. Made it work in 5 steps with icons so users know how to proceed.

**The Cons**
The Chamevo UI is trash. It looks like Windows Explorer with tiny icons for zoom and preview scattered around the exterior. Nobody would know to click them.

The product page currently auto-loads the Chamevo configurator as a generic image, then the user makes 4 variation selections which loads the actual product image. What he wants instead: show a static image first, and once all 4 selections are made, the configurator loads automatically — no separate "CONFIGURE" button click needed.

**The two things blocking this:**
1. The UI skin — Windows Explorer look, bad small icons
2. The product page — how it initially loads and the configure button not being obvious

**The upside if we fix it:**
- Chamevo is already responsive — only the other 8 app pages would need to be made responsive later
- Uses native WooCommerce for cart — easier in the long run

**Timeline:**
- Something to show Tuesday May 26
- Soft launch goal: June 1 (likely pushed 1–2 weeks)
- Must have something completed by June 1

---

**Yes, this is doable.**

---

## What We Need From Cody

1. **WordPress admin access** — so we can add the CSS and JavaScript fixes directly to his site
2. **Confirmation that his child theme is set up** — if not, we create one first (30 min job, protects his customizations from being wiped on updates)

---

## What We're Going to Fix

3. **Hide the orange "TEE" placeholder on page load** — show a clean product photo instead
4. **Auto-open the configurator** once the customer makes all 4 selections — no mystery button to click
5. **CSS skin** — hide the useless left sidebar, make the icons bigger and obvious, match Chainmail colors and style

---

## What Cody Already Has Done (No Action Needed)

- Variable products set up ✓
- Layering and artwork sizing working ✓
- Product images loading correctly after selections ✓
- Print area positioned correctly ✓
- Chamevo connected to WooCommerce cart ✓

---

## Timeline

Once we have WordPress access — fixes 3, 4, and 5 can be done in a day. Plenty of time before Tuesday.
