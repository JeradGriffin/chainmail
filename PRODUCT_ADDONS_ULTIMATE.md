# WooCommerce Product Add-Ons Ultimate

**Plugin:** [Product Add-Ons Ultimate Bundle](https://pluginrepublic.com/wordpress-plugins/product-add-ons-ultimate-bundle/) by Plugin Republic  
**Status:** Installed on WP (chainmail.store/dev) as of May 30, 2026  
**Replaces:** Chamevo (shelved May 2026)

---

## Why We're Using It

Cody evaluated this plugin and confirmed it meets current needs. Key reasons:

- More integrated into the WooCommerce ecosystem
- Logo placement UI is a popup where users drag their logo into a bounding box on the product image
- Matches what Cody designed in Figma — "It's literally just like what I designed"
- Cody can bake the bounding box area into product images in WP Admin and define the field — no developer needed
- Basic but sufficient for now

Cody's words (email May 30, 2026):
> "I think this actually is going to do what we need because its more integrated into the WooCommerce world and their UI is only a pop-up and you drag a box of your logo and it drops it into the image. It's super basic but I think it honestly is going to meet our needs for right now."

---

## Role in the Project

### 1. Logo Placement (new — replaces React app logo step)
- Plugin handles logo drag-and-drop via a popup on the WooCommerce product page
- Cody defines a bounding box area on each product image in WP Admin
- User drags their logo into the box; plugin places it
- **Impact on React app**: Step 2 (Logo Upload) and the live logo overlay may be removed or simplified — TBD

### 2. Decoration Options (already planned)
- Embroidery, 4-color screen print, etc. are Product Add-On line items with fixed additional prices
- Either/or per item — user picks one decoration method, not multiple
- Cart handoff format (Store API):
  ```json
  {
    "id": "<variation_id>",
    "quantity": "<kit_quantity>",
    "extensions": {
      "product-add-ons": [{ "field_name": "decoration", "value": "1 Color Embroider" }]
    }
  }
  ```

---

## Open Questions

- Does logo placement via the plugin fully replace the React app's Step 2, or does the app still need to collect/store the logo for order metadata?
- Does the plugin's logo file get attached to the WooCommerce order automatically, or do we still need to handle that?
- Confirm logo position label names with Cody (Center, Left chest, Right chest?)

---

## WP Setup Notes

- Plugin installed on `chainmail.store/dev` — May 30, 2026
- Cody manages field definitions and bounding boxes in WP Admin per product
- No additional API keys needed beyond existing WooCommerce REST API credentials
