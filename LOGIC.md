# Chainmail Kit Builder — Logic & Flow

## Screens

### 1. Welcome Screen (`currentStep === -1`)

- **Quantity Selector**: Slider with options [24, 48, 72, 96, 120+]
  - Selecting "120+" opens a mailto link to `sales@chainmail.com`
  - Active quantity label turns red
- **"Start my kit"** button advances to Step 1 (`currentStep = 0`)
- **"More than 120 Kits?"** button opens mailto link to `sales@chainmail.com`
- **"Back"** button (no action currently assigned)

---

### 2. Step 1 — Add Your Product (`currentStep === 0`)

#### Step Indicator
- 6 steps displayed as numbered circles connected by lines
- Current step circle: red background, white text
- Completed steps: black background, white text
- Future steps: white background, black border

#### Content
- **Title**: "All about you"
- **Description**: Explains that each kit has space for the user's product

#### Selections
- **"Yes, add my product" radio** ($250)
  - Sets `addProduct = true` when selected
- **"Please confirm your item is less than 12″ x 12″ x 8″" radio**
  - Only interactive when `addProduct` is true
  - Toggles `confirmSize` on/off

#### Conditional Content
- *Currently all content is always visible for layout review*
- **Italic note**: "At the end of this process we will send an inbound form to get info about your product and how to ship it to us."
  - Will eventually be shown only when `addProduct === true`

#### Continue Button Logic
- **Disabled state** (white background, red outline, red text):
  - When `addProduct` is false OR `confirmSize` is false
  - Button is non-functional
- **Enabled state** (solid red background, white text):
  - When both `addProduct === true` AND `confirmSize === true`
  - Advances to Step 2 (`currentStep = 1`)

#### Skip
- **"Skip to next, do not add my item."** button
  - Resets `addProduct = false` and `confirmSize = false`
  - Advances to Step 2 (`currentStep = 1`)

#### Back
- Returns to Welcome Screen (`currentStep = -1`)

---

### 3. Steps 2–6 (Not yet implemented)

Steps array: `['Add Your Product', 'Shipping', 'Logo', 'Customize Goods', 'Spirits', 'Message']`

Currently shows placeholder text: "Step X: [Name] — coming soon..."

---

## State

| State Variable    | Type    | Default | Purpose                                      |
|-------------------|---------|---------|----------------------------------------------|
| `currentStep`     | number  | `-1`    | `-1` = welcome, `0–5` = process steps        |
| `kitQuantityIndex`| number  | `0`     | Index into quantities array [24,48,72,96,120+]|
| `addProduct`      | boolean | `false` | Whether user wants to include their product   |
| `confirmSize`     | boolean | `false` | Whether user confirmed item size requirement  |

---

## Header (Shared)

- Logo: chainmail logo image
- Icons (clickable buttons, no actions assigned yet):
  - Search
  - Shopping Bag
  - Menu
