```markdown
# Design System Document: High-End Editorial

## 1. Overview & Creative North Star
**Creative North Star: The Living Archive**
This design system rejects the "SaaS template" aesthetic in favor of a sophisticated, magazine-inspired digital experience. It is designed to feel like a high-end publication—intentional, authoritative, yet deeply human. 

To achieve this, we move away from rigid, symmetrical grids. We embrace **intentional asymmetry**, where large-scale serif typography acts as a structural element, and generous whitespace (negative space) is treated as a premium "material" rather than empty room. By layering surfaces and utilizing dramatic shifts in type scale, we transform a standard non-profit interface into a curated editorial journey that honors the GIRI FOUNDATION’s mission.

---

## 2. Colors & Surface Architecture
The palette is rooted in a "Paper & Ink" philosophy, utilizing a sophisticated range of warm neutrals and a signature deep forest green.

### Tonal Strategy
*   **Backgrounds:** Use `surface` (#FCF9F8) for the main canvas. It provides a warmer, more "foundational" feel than pure white.
*   **The "No-Line" Rule:** Prohibit the use of 1px solid borders to define sections. Layout boundaries must be created through background shifts—specifically moving from `surface` to `surface-container-low` (#F6F3F2) or `surface-variant` (#E5E2E1).
*   **Surface Hierarchy:** 
    *   **Level 0:** `surface` (Main page background)
    *   **Level 1:** `surface-container-low` (Subtle sectioning)
    *   **Level 2:** `surface-container-lowest` (#FFFFFF) (High-contrast "floating" content blocks)
*   **Signature Textures:** For hero sections or primary impact CTAs, use a subtle linear gradient from `primary` (#00604C) to `primary-container` (#1F7A63). This adds "soul" and prevents the deep green from feeling flat or sterile.

---

## 3. Typography
The system uses a high-contrast pairing of **Newsreader** (Serif) for storytelling and **Public Sans** (Sans-Serif) for functional clarity.

*   **Display (Newsreader):** Use `display-lg` (3.5rem) for impact stats and hero headlines. These should feel monumental. Decrease letter-spacing slightly (-0.02em) for a tighter, premium editorial look.
*   **Headlines (Newsreader):** Used for section titles. The serif typeface conveys wisdom and tradition.
*   **Title/Body (Public Sans):** Used for navigation and long-form reading. The high x-height of Public Sans ensures legibility against the more decorative headlines.
*   **Labels (Public Sans):** Always uppercase with increased letter-spacing (+0.05em) when used for categories or small metadata tags.

---

## 4. Elevation & Depth: The Layering Principle
We do not use shadows to create "pop"; we use them to create "atmosphere."

*   **Tonal Layering:** Depth is achieved by "stacking" tones. Place a `surface-container-lowest` card on a `surface-container-low` background. The slight shift in hex value creates a natural edge.
*   **Ambient Shadows:** For floating modals or "impact cards," use an ultra-diffused shadow:
    *   `box-shadow: 0 12px 40px rgba(15, 15, 15, 0.04);`
    *   The shadow is tinted with the `on-surface` color (#1C1B1B) to mimic natural light.
*   **Glassmorphism:** Use semi-transparent layers for navigation bars or overlays. 
    *   `background: rgba(252, 249, 248, 0.8);` with a `backdrop-filter: blur(12px);`. This allows the editorial photography to bleed through the UI, making the experience feel integrated.
*   **The Ghost Border:** If an element needs a boundary for accessibility (like a text input), use `outline-variant` (#BEC9C3) at 30% opacity. Never use 100% opaque borders.

---

## 5. Components

### Buttons
*   **Primary:** Background `primary-container` (#1F7A63), text `on-primary` (#FFFFFF). 8px radius (`lg`). No shadow on idle; 4% ambient shadow on hover.
*   **Secondary (Outlined):** `Ghost Border` (20% opacity `outline`) with `primary-container` text. 
*   **Tertiary:** No border or background. Underlined with a 1px offset to maintain the editorial feel.

### Chips & Tags
*   Use `secondary-container` (#C8EADC) with `on-secondary-container` (#4C6B60) text. Keep corners slightly rounded (8px) to match the button language.

### Cards & Impact Stats
*   **Forbid Divider Lines:** Separate content using the Spacing Scale (64px–96px between sections).
*   **The Impact Block:** Large `display-lg` numbers in `primary` (#00604C) paired with a `label-md` description. These should sit on a `surface-container-low` background with zero borders.

### Input Fields
*   **Default State:** `surface-container-lowest` background with a subtle bottom-border only (Ghost Border). This mimics a signature line on a high-end stationery set.
*   **Focus State:** Transition the bottom border to `primary` (#00604C) with a 2px stroke.

---

## 6. Do’s and Don’ts

### Do:
*   **Embrace Asymmetry:** Align text to the left while placing images with generous, offset margins.
*   **Use Vertical White Space:** If a section feels crowded, double the padding. "Generous" means 120px+ on desktop between major themes.
*   **Focus on Photography:** Use authentic, high-resolution imagery. The UI is the frame; the mission is the art.

### Don't:
*   **Don't use 1px Borders:** Never "box" content. Let the background color do the work.
*   **Don't use Pure Black:** Use `on-background` (#1C1B1B) for text to keep the contrast high but the "vibe" soft.
*   **Don't use Heavy Shadows:** If a shadow is clearly visible at a glance, it is too heavy. It should be felt, not seen.
*   **Don't use Standard Grids:** Avoid 3-column "feature" rows. Try a 2/3 and 1/3 split to create a more dynamic, editorial flow.```