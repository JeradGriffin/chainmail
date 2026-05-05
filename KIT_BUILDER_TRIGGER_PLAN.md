# Kit Builder Trigger — Approach Comparison & Plan

> Written for design-level understanding. No code knowledge required.

---

## What We're Deciding

When a visitor clicks "Build Your Kit" or "Start Your Kit" on the Chainmail website, something needs to happen. The kit builder (the React app we've been building) needs to appear. The question is: **how does it appear, and what happens to the page the visitor was already on?**

There are three realistic options.

---

## Option 1 — Take Them to a New Page

**How it works:** The button is just a link. Clicking it navigates the visitor away from whatever page they're on and loads the kit builder as its own full page — like going from a homepage to a checkout page.

**What the visitor experiences:** Click button → current page disappears → kit builder page loads.

**Pros:**
- Simplest thing to build. Very little can go wrong.
- Works perfectly on every phone and browser with zero special handling.
- When the user finishes and goes to checkout, it's totally natural — they're already on their own page.

**Cons:**
- The visitor leaves the current page entirely. If they want to go back, they hit the browser back button.
- Feels less "app-like." More like a traditional website navigation.

**Verdict:** Clean and reliable, but not the experience Cody is going for.

---

## Option 2 — iFrame Modal (Pop-up containing a mini browser window)

**How it works:** Clicking the button opens a pop-up overlay. Inside that pop-up is what's called an "iframe" — essentially a mini browser window embedded inside the current page. The kit builder loads inside that mini window.

Think of it like a picture-in-picture TV mode. The main page is still there behind it, and the kit builder plays inside a floating frame on top.

**What the visitor experiences:** Click button → overlay fades in → kit builder loads inside the overlay.

**Pros:**
- Visitor stays on the current page.
- The kit builder is visually isolated — any styles or code on the main WordPress page can't interfere with it.

**Cons (and these are significant):**
- **iPhone scrolling is broken by default inside iframes.** Apple has a well-known bug where content inside a fixed-height embedded window doesn't scroll properly on Safari. There are workarounds, but they're fragile and often break again with iOS updates.
- **The on-screen keyboard causes layout chaos on mobile.** When a visitor taps a text field inside the iframe, the keyboard pushes the screen up — but the iframe doesn't reflow correctly. Things end up off-screen or misaligned.
- **Checkout gets complicated.** When the visitor finishes the kit and the app tries to redirect them to the WooCommerce checkout page, that redirect would happen *inside the mini window* instead of the full browser. It would look broken. There's a fix for this, but it adds more complexity.
- Most fragile option on mobile. Mobile is our primary target.

**Verdict:** Achieves the "stay on the page" goal but introduces a real risk of broken mobile experience. Not recommended.

---

## Option 3 — Direct Embed Modal ✅ Recommended

**How it works:** The kit builder code is quietly loaded on the page in the background (invisible). When the visitor clicks "Build Your Kit," a full-screen overlay animates in — and the kit builder is already there, ready to go, running inside that overlay. There's no mini browser window involved. It's just a styled layer sitting on top of the current page.

Think of it like a drawer sliding up from the bottom, or a lightbox opening — same technique used for image galleries, cookie notices, or mobile menus. Except this one is full-screen and contains the entire kit-building experience.

**What the visitor experiences:** Click button → full-screen overlay slides/fades in → kit builder is ready → they complete the kit → they get redirected to WooCommerce checkout (overlay closes naturally as the page navigates).

**Pros:**
- Visitor stays on the current page. Achieves Cody's goal.
- No iframe. Zero mobile scrolling or keyboard issues. Works perfectly on iPhone.
- When checkout time comes, the page simply navigates away to WooCommerce — no special handling needed. It works exactly the same way as the full-page option in that moment.
- An X button (or close gesture) can dismiss the kit builder and return to the page underneath.
- Can be set up so the kit builder code only loads when the button is first clicked — so it doesn't slow down the page for visitors who never open it.

**Cons:**
- The kit builder's CSS needs to be carefully scoped so WordPress's existing styles don't bleed in and change how things look. (This is a normal, manageable task — not a blocker.)
- The kit builder code needs to be loaded on any WordPress page that has the trigger button. If the button is only on one or two pages, this is a non-issue.

**Verdict:** Best of both worlds. Visitor stays on the page, mobile works perfectly, and checkout handoff is clean.

---

## Side-by-Side Summary

| | Full-Page Nav | iFrame Modal | Direct Embed Modal |
|---|---|---|---|
| Visitor stays on page | ❌ | ✅ | ✅ |
| Works well on iPhone | ✅ | ⚠️ Risky | ✅ |
| Keyboard on mobile | ✅ | ❌ Broken | ✅ |
| Checkout redirect works | ✅ | ⚠️ Needs fix | ✅ |
| Complexity to build | Low | High | Medium |
| Risk of breaking on mobile | Low | High | Low |

---

## Recommendation

**Go with Option 3 — the Direct Embed Modal.**

It's the only approach that satisfies both of Cody's goals: (1) the visitor doesn't leave the page, and (2) it works reliably on mobile. The iframe approach technically does the same thing visually, but the mobile experience issues aren't cosmetic — they're functional. Scrolling and keyboard input are core interactions in the kit builder. Getting those wrong on iPhone means the app is unusable for a large portion of visitors.

The direct embed modal is standard practice for this kind of flow. It's the same pattern used by Intercom chat widgets, cookie consent tools, and full-screen mobile menus — all of which need to overlay the full page without breaking the underlying site.

---

## What Needs to Be Confirmed With Cody

1. **Which pages should have the trigger button?** Just the homepage? Product pages too? The more pages, the more places WordPress needs to load the kit builder code.
2. **What should the button look like?** "Build Your Kit" vs "Start Your Kit" — and is it a button in the header, a hero section CTA, or both?
3. **What happens if the visitor closes the kit builder mid-flow?** Does it reset completely next time they open it, or should it remember where they left off?

---

## Next Steps (Once Approved)

1. Update the React app so it can mount inside a named container (not just the default page container).
2. Build the WordPress overlay — a full-screen div that sits on top of the page, hidden by default, with an X close button.
3. Write a small WordPress snippet that listens for the button click, shows the overlay, and locks the page scroll behind it.
4. Add the button (with the correct trigger class) to whatever pages Cody wants it on.
5. Test on iPhone Safari — that's the device most likely to surface any issues.
