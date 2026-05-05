# Client Email Thread — Cody Gilbertson

> Raw email archive for reference. Key decisions extracted from these threads are summarized in PROJECT_STATUS.md.

---

## Thread 1 — Initial Tasks + WPConfigurator Discussion (March 7–9, 2026)

---

**From: Cody**
**Date: ~March 7, 2026**

Hey Jerad,

Here is the requested list of tasks for chainmail:

1) Please get in touch with Christina: accounting@chainmail.store — she is in charge of all the billing. Make sure you have your W-9 and your split invoice. Follow the attached document for any additional instructions.

2) WPConfigurator:
   - Investigate to see if it can be implemented inside the app via an iframe or however you'd like it to work.
   - What is the structure of items required — ie variations, composite, simple, etc. I think this should still work the same but give more expandability with less load time. Every item has its own SKU and ItemId for cart identification. I am building out all of the Tees right now as:
     - Short Sleeve - Lightweight
       - Color
       - Size
     - Short Sleeve - Heavyweight
       - Color
       - Size
     - Long Sleeve - Lightweight
       - Color
       - Size
     - Long Sleeve - Heavyweight
       - Color
       - Size

   - I will be adding the "Customizations" as separate items like 'embroidery' '4-color screen print' etc so they'll have set additional add-on prices if selected.

3) I just want to get a functioning test of the Tees first and then the rest of it is just copy/paste with fewer options. I will have the products built out today and hopefully start working on the other static pages.

Please let me know if you have any additional questions. If you think WPConfigurator will work I will purchase ASAP so I can connect it to the site.

Thank you,
Cody

---

**From: Jerad**
**Date: Sat, Mar 7, 3:49 PM**

Hey Cody,

Sounds good. I'll reach out to Christina about the W-9 and invoice stuff.

I'll dig into WPConfigurator today and see what the structure looks like for the Tees.

Thanks,
Jerad

---

**From: Jerad**
**Date: Sun, Mar 8, 9:08 PM**

Hey Cody,

So I did more digging into WPConfigurator. The main reason you'd want it is so you can add/remove products without needing a developer every time right? Correct me if I'm wrong.

We can build that same capability directly into the app. Instead of hardcoding the products, we connect the app to WooCommerce so it pulls the product list dynamically. So you manage everything through WooCommerce admin like normal, and the app just stays in sync automatically. Same workflow you'd get from WPConfigurator, just without the extra plugin in WP.

The app we're building is basically doing the same thing WPConfigurator does anyway — it's a step-by-step configurator. The main thing we'd need to add is a handoff to WooCommerce at the end so your cart and checkout work.

The easiest way to make all of this work cleanly is to host the app inside WordPress rather than as a separate thing. That avoids headaches and keeps everything on one domain. Cleaner and easier to maintain in the long run.

Let me know what you think.

---

**From: Cody**
**Date: Mon, Mar 9, 6:38 AM**

Hey Jerad,

That works for me. The WPConfigurator is basically a very similar concept of what the app is — I was mostly asking if it would make your life easier to just use that plugin, however that plugin doesn't take the user through the process as smoothly and I think the process is very important for this site.

If you think we can do it without, let's go! I'm going to start making the static pages today.

//CG

---

## Thread 2 — Discovery / Scoping Session (January 22 – February 14, 2026)

> Note: The raw email text from this thread was reviewed in a prior session but not preserved verbatim here. The key decisions extracted from this thread are documented below and in PROJECT_STATUS.md.

---

### Product Structure

- **Variable products** preferred over individual simple products (keeps SKU count manageable — target sub-50 per parent product)
- Each product variation has its own SKU and WooCommerce variation ID used for cart line items
- **SKU naming convention**: short descriptive format, e.g. `SSTBlk5ozCrewM` (Short Sleeve Tee, Black, 5oz, Crew neck, Medium)
- Tees have 4 sleeve/weight types × Color × Size = many variations

### Images

- **Image-per-SKU strategy**: one real product photo per variation (color/style combo). No color overlays or compositing in the app.
- Product photos should be portrait orientation (~390×650px) to fill the preview card correctly
- Current `tee-placeholder.svg` is a temporary stand-in — all need to be replaced with real photos from Cody

### Logo Handling

- User uploads their logo (PNG or SVG) **once, at the start** of the wizard
- Logo is **only rendered on Preview click** — not live/reactive — for performance
- Logo placement: **3 fixed locations** (selectable from a dropdown, not free-position drag-and-drop)
- Logo position labels TBD — Cody to confirm exact copy (current code: "Center / Left"; discussion mentioned "Center Chest / Right Chest")
- Logo stored in WooCommerce Media Library; hosting space is available; can be wiped periodically

### Decoration / Customization

- Decoration options (embroidery, 4-color screen print, etc.) are **either/or per item** — not both on the same item
- Implemented as **Product Add-Ons** (WooCommerce extension), appearing as separate line items with fixed additional prices
- These are not product variation attributes

### Pricing

- **Fixed price per item** (not dynamic calculation in the configurator UI)
- **Bulk discount tiers** by quantity, starting at MOQ 24 units (exact tier structure TBD)
- Price delta for heavier/premium variants shown as a **subheading** under the option (e.g. "+$5/per unit") — not a running total

### Flow & UX

- **Shipping step is Step 1** (moved to the front of the flow to reduce mental load at checkout)
- **After "Add to Kit"**: user loops back to Step 3 (goods selection), not forward to checkout
- **Spirits (Step 5)**: 1 bottle per kit only — no quantity selector needed
- **Message field (Step 6)**: optional, ~500–1000 characters, skippable

### Checkout

- Use **native WooCommerce checkout** — do not build a custom checkout screen
- Required to preserve: ShipStation integration, Sezzle (buy-now-pay-later), existing payment plugins
- At end of configurator: push all items to WooCommerce cart via Store API, then redirect to `/checkout`

### Order Metadata

- Each line item uses the actual WooCommerce variation SKU/ID (not a custom string)
- Logo file URL stored as order metadata (uploaded to WP Media Library)
- Shipping method and list file URL stored as order metadata
- Kit quantity stored as order metadata
