# How Chamevo Fits Into the App

---

## The Big Picture

The app stays exactly the same through the first three steps. Chamevo only comes in when a user taps on a specific product to customize it.

---

## Step by Step

### Step 1 — Install Chamevo on Cody's WordPress Site
Cody (or a developer) installs Chamevo the same way you install any WordPress plugin. It shows up in the WooCommerce dashboard and connects automatically to all of his products.

**Who does this:** Cody (or developer)
**What's needed:** A Chamevo subscription

---

### Step 2 — Cody Sets Up Each Product in Chamevo
For every product (Tee, Hoodie, Cap, Tote, Bottle, Journal), Cody goes into Chamevo and configures it:
- Uploads the product photos (front, side, back)
- Draws the print area (where the logo goes on each product)
- Sets the available options (colors, styles, decoration types)

Chamevo then knows exactly how each product looks and where the logo should sit.

**Who does this:** Cody
**What's needed:** The product photos, which he already has

---

### Step 3 — The User Goes Through the App as Normal
Nothing changes for the user through the first part of the flow:

- Welcome — pick quantity
- Step 1 — add your product
- Step 2 — shipping
- Step 3 — upload your logo

---

### Step 4 — User Picks a Good (Tee, Hoodie, etc.)
On the "Add Premium Goods" screen, the user taps a product — say, a Tee.

Instead of our custom configurator opening up, the app sends them to the Tee's product page on Cody's WooCommerce site, where Chamevo is already running and ready.

---

### Step 5 — User Customizes in Chamevo
On that product page, Chamevo takes over. The user sees:
- The product on a real model
- Multiple angles (front, side, back)
- Options for color, style, logo position, decoration
- Their logo dropped onto the product in real time

This is the premium visual experience from the design mockups.

---

### Step 6 — User Adds to Cart and Comes Back
When the user is happy and taps "Add to Cart", Chamevo saves all their choices and the app sends them back to the goods list to pick another item (Hoodie, Cap, etc.) or continue to Spirits.

Behind the scenes, Chamevo automatically generates a print-ready file for the decorator — no manual work needed after checkout.

---

### Step 7 — User Finishes the Rest of the App
The user continues through the remaining steps as normal:
- Spirits
- Message
- Checkout via WooCommerce

---

## The One Thing We Still Need to Confirm

When the user uploads their logo in Step 3 of the app, we need Chamevo to receive that logo automatically so they don't have to upload it again.

**This needs to be confirmed directly with Chamevo support before we build anything.** If they support it, the experience is seamless. If not, the user uploads their logo twice — which is not the end of the world but is not ideal.

**Question to ask Chamevo:** "Can we pass a logo file from an external app into your configurator so the customer doesn't have to upload it again?"

---

## What Chamevo Handles So We Don't Have To

| Task | Who handles it |
|------|----------------|
| Visual configurator (multi-angle, color swap) | Chamevo |
| Logo placement on product | Chamevo |
| Print-ready file generation after checkout | Chamevo |
| Sending files to the decorator | Chamevo (via email, Dropbox, or Google Drive) |
| Cart and checkout | WooCommerce |

---

## What Our App Still Handles

| Task | Who handles it |
|------|----------------|
| Quantity selection | Our app |
| Client's own product (Step 1) | Our app |
| Shipping options and list upload | Our app |
| Logo upload | Our app |
| Goods selection screen | Our app |
| Spirits selection | Our app |
| Custom message | Our app |
