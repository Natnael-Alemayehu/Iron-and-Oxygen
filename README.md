# Iron & Oxygen -- Benchmark Vault & Percentage Calculator
**WordPress Plugin - v1.0.0**

A mobile-first, industry-dark utility for your member portal: a Benchmark Workout Library (Custom Post Type) combined with a client-side 1-Rep Max Percentage Calculator.

---

## Quick Install

1. Upload the `iron-oxygen-vault/` folder to `/wp-content/plugins/`.
2. Activate via **WordPress Admin -> Plugins**.
3. Add the shortcode `[io_vault]` to any page.

---

## Shortcode Reference

|Shortcode|Description|
|`[io_vault]`|Renders both the Calculator tab and the Benchmark Value tab|
|`[io_vault tab="calculator"]`|Calculator only (no tab nav)|
|`[io_vault tab="benchmarks"]`|Benchmark Vault only (no tab nav)|
|`[io_vault posts_per_page="20"]`|Limit how many benchmarks are shown (default: 50)|

---

## Adding Benchmarks
1. In your WordPress admin, go to **Benchmarks-> Add Benchmark** (look for the fast performance icon in the sidebar at position 25).
2. Fill in:
    - **Title** - Workout name (e.g. "Century Test", "The Iron Mile")
    - **Body (Editor)** - Optional: add a full workout description with Gutenberg blocks
    - **Goal Time/Score** (meta box) - e.g. `Sub 10:00`, `150+ reps`, `225 lb+`
    - **Category** - Conditioning | Strength | Gymnastics | Mixed Modal
    - **Equipment** - Comma-separated list (e.g. `Barbell, pull-up Bar, 24" Box`)
    - **Movement Description / Rx Standards** - workout structure, rep schemas, scaling notes
3. Set status to **publish**.

The benchmark will immediately appear in the Vault (alphabetically sorted).

---

## Using the Calculator

1. Select a lift from the dropdown, or choose **Custom...** and type your own.
2. Enter your **1-Rep Max** weight.
3. Toggle the unit between **lb** and **kg**.
4. Choose a **rounding increment** (5/2.5/1/None).
5. The table populates instantly - no page reload required.
6. Use the **Copy** button to copy the full table to your clipboard.


### Training Zones
