# Chainmail Kit Builder — Session Changelog

## Session: 2026-03-01

### Overview

Built Steps 2–4 of the kit builder flow. The app previously had a working Welcome screen and Step 1 (Add Your Product). Steps 2–6 were stubs showing "coming soon." This session added fully functional Shipping, Logo, and Premium Goods screens.

---

### Files Modified

| File | Change |
|------|--------|
| `src/App.jsx` | Added state, render functions, and routing for Steps 2–4 |
| `src/index.css` | Added styles for shipping, logo, and goods steps |

### Files Created

| File | Purpose |
|------|---------|
| `public/icon-tee.svg` | Tee product icon (custom SVG, black strokes) |
| `public/icon-hoodie.svg` | Hoodie product icon (custom SVG, black strokes) |
| `public/icon-cap.svg` | Cap product icon (custom SVG, black strokes) |
| `public/icon-bag.svg` | Bag product icon (custom SVG, black strokes) |
| `public/icon-more.svg` | "+ More!" product icon (custom SVG, black strokes) |

---

### Step 2 — Shipping (`renderShipping`)

**State added:**
- `shippingOption` — `'list'` (default) or `'me'`
- `shippingFile` — `File | null`

**UI:**
- Two radio options: "Ship to my list" and "Ship to me"
- "Ship to my list" is pre-selected by default
- Upload dropzone (drag/drop or tap) accepts XLSX, XLS, CSV
- Upload section is **always visible** regardless of which radio is selected
- Full-width pink band (`#fef2f2`) behind upload area
- Full-width gray band (`#f2f2f2`) behind "Download shipping list template" button
- Download template button is white with black text on gray band
- "Ship to me" shows sub-text: "I will provide my shipping information during the checkout process."
- No skip section (shipping is required)
- Continue enabled when: "Ship to me" selected, OR "Ship to my list" + file uploaded

**Navigation:** Back → Step 1, Continue → Step 3

---

### Step 3 — Your Logo (`renderLogo`)

**State added:**
- `logoFile` — `File | null`
- `showLogoWarning` — `boolean`

**UI:**
- Title: "Your logo" (black, not red)
- Description about uploading logo or skipping for later
- Full-width pink band with drag/drop upload zone (accepts SVG, AI, EPS, PDF, PNG, JPG)
- Red italic hint text below: explains vector files show up best, PNG with transparency works, JPG won't preview
- Non-vector file warning modal (triggered by JPG/PNG uploads):
  - Explains the file isn't vector and offers two buttons side by side:
    - "Upload a vector file" (red) — clears file, reopens picker
    - "Continue with current file" (white/bordered) — dismisses modal
- Skip section: "Skip to next, I'll do it later."
- Continue enabled when a file is uploaded

**Navigation:** Back → Step 2, Continue/Skip → Step 4

---

### Step 4 — Add Premium Goods (`renderGoods`)

**State added:**
- `selectedGoods` — `string[]`, default `['tee']`

**UI:**
- Title: "Add premium goods"
- Description: "Select 1-3 of our curated premium items to be branded with your logo."
- 5 product rows, each with: radio circle, custom SVG icon, product name (bold 22px), price
  - Tee — $25
  - Hoodie — $45
  - Cap — $30
  - Bag — $35
  - + More! — $20
- Multi-select (1–3 items max). Tapping a 4th item does nothing.
- Selected rows get:
  - Full-width pink background band (`#fef2f2`) via negative margins
  - Red radio circle (filled)
  - Red product name text (`#ED2024`)
  - Red icon via CSS filter (`invert/sepia/hue-rotate` to convert black → red)
- Unselected rows: black icon, black text, no background
- Skip section: "Skip to next, do not include goods."
- Continue enabled when at least 1 item selected

**Navigation:** Back → Step 3, Continue/Skip → Step 5

---

### Design Patterns Established

**Full-width background bands:**
Achieved with negative margins on the `px-7` (28px) padded parent:
```css
margin-left: -28px;
margin-right: -28px;
padding: 16px 28px;
```
Used for: upload areas (pink `#fef2f2`), template button (gray `#f2f2f2`), selected goods rows.

**Upload dropzone (reusable):**
Red dashed border, transparent background on pink band, hover darkens slightly. Shows filename after upload. Class: `.upload-dropzone`.

**Bottom bar structure:**
Fixed to bottom of viewport, max-width 430px centered. Optional skip section (white bg, centered text with divider) above gray button bar. Classes: `.bottom-area`, `.bottom-skip`, `.bottom-bar-buttons`.

**Continue button states:**
- Enabled: red bg, white text, white chevron
- Disabled: white bg, red text/border, red chevron

**SVG icon color switching:**
Product icons use black strokes by default. On selection, a CSS filter converts black to `#ED2024`:
```css
filter: invert(16%) sepia(95%) saturate(6932%) hue-rotate(355deg) brightness(94%) contrast(98%);
```

---

### Key Decisions / User Preferences

- **Never hide elements unless explicitly asked.** Upload sections, product rows, etc. stay visible at all times.
- **Background colors matter.** Pink band behind upload areas, gray band behind action buttons — these go full-width across the screen.
- **Icons should be custom SVGs** provided by the client, not emoji or generic library icons.
- **All steps follow the same skeleton:** Header → StepIndicator → Title → Description → Content → (Skip) → BottomBar.

---

### What's Next

Steps 5 (Spirits) and 6 (Message) are still stubs. The larger refactoring plan (component extraction, WP Configurator integration, WordPress handoff prep) is on hold pending client details.
