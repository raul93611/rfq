# Re-Quote Total Profit wrong after switching Services Payment Terms to Net 30/CC

**Status:** fixed

## Resolution
- `Rfq::obtener_re_quote_total_cost()` ([app/Quote/Rfq.inc.php](../app/Quote/Rfq.inc.php)) now adds `getTotalQuoteServices()` instead of `ReQuoteServiceRepository::get_total()`, so services cancel out of the re-quote profit calc regardless of payment terms — matching the main Quote page, per confirmed intent.
- Bottom summary bar is now live: `id`s added to the three spans in `plantillas/re_quote/re_quote.inc.php`, `js/reQuote.js`'s `calculateTotals()` recomputes them every polling tick from the live items cost and the page-load-fixed `window.RE_QUOTE_TOTAL_PRICE`.
- Regression test: `tests/php/re_quote_cc_profit_test.php` (reproduces the exact $200→$197 swing pre-fix, asserts it's gone post-fix).
- Verified live against the real quote #118063 in a browser: toggling Services Payment Terms between Net 30 and Net 30/CC no longer moves Total Profit ($8,288.88 stays fixed either way).
- Out of scope, not fixed here (noted for a future pass if desired): the three duplicated CC-fee magic-number constants, and the PDF export's separate, services-excluding profit formula.

## Summary
On the Re-Quote page, switching Payment Terms in the Total Services section from Net 30 to Net 30/CC (and saving) makes the bottom "Total Profit" figure wrong.

## Steps to Reproduce
1. Open `perfil/re_quote/118063`.
2. Note the Items table's own TOTAL row profit (e.g. $7,337.88) and the bottom summary bar's Total Profit.
3. In the Total Services section, change the Payment Terms dropdown from `Net 30` to `Net 30/CC`.
4. Click Save.

Reporter wasn't fully certain whether the bad number appears immediately in the browser or only after Save — investigation shows it's the latter (see Root Cause).

## Expected vs Actual
- **Expected:** Total Profit should track live edits (item costs, quantities, provider changes, payment terms) and services should remain margin-neutral under Net 30/CC, same as on the main Quote Edit page — i.e. the CC fee should not silently reduce profit as a phantom services cost.
- **Actual:** Bottom Total Profit jumped to $11,024.84 in the reported screenshot, disconnected from the Items table's live profit of $7,337.88.

## Severity
Partially blocking — doesn't prevent saving/using the re-quote, but the wrong number can mislead pricing/margin decisions.

## Screenshot
User-provided screenshot of `localhost/rfq/perfil/re_quote/118063`: Items TOTAL row shows $125,108.94 / $132,446.82 / $7337.88 (5.54%); Total Services section (Payment Terms: Net 30) shows a $1,105.00 services total; bottom bar shows Total Price $133,397.82 / Total Profit $11,024.84 / Profit % 8.26%.

## Investigation

Confirmed directly in code (file/line refs below). Two distinct, compounding bugs:

### 1. The bottom summary bar is frozen, server-rendered PHP — it never updates live
[plantillas/re_quote/re_quote.inc.php:66-79](../plantillas/re_quote/re_quote.inc.php#L66-L79) prints `$quote->obtener_quote_total_price()` / `obtener_re_quote_profit()` / `obtener_re_quote_profit_percentage()` once at page load. The `<span>`s have **no `id`/hook at all**. Unlike:
- the Items table, recalculated every 100ms by `calculateTotals()` ([js/reQuote.js:4-27](../js/reQuote.js#L4-L27)), and
- the Services table, recalculated every 100ms by `calcServices()` ([js/reQuote.js:68-86](../js/reQuote.js#L68-L86)),

...the bottom bar only reflects reality after Save + page reload (`Redireccion::redirigir(...)` in `scripts/re_quote/save_re_quote.php:58`). This is the dominant cause of the large discrepancy seen — the bottom number wasn't tracking any live edits made since page load, not just the payment-terms toggle. Contrast with the main Quote Edit page, where `js/quote.js`'s `calcQuoteTable()` (lines 42-151) keeps `#bar-total-price`/`#bar-total-profit`/`#bar-profit-pct` live.

### 2. Net 30/CC breaks the services pass-through cancellation on Save
By design, services should be margin-neutral (confirmed by user: should work the same as the main Quote page — no difference). On the main Quote Edit page this works: `obtener_quote_total_price()` and `obtener_quote_total_cost()` ([app/Quote/Rfq.inc.php:370-376](../app/Quote/Rfq.inc.php#L370-L376)) both add `getTotalQuoteServices()` — the **same** `services` table — to price and cost, so a CC markup applied by `ServiceRepository::calc_items_with_CC()` ([app/Service/ServiceRepository.inc.php:262-278](../app/Service/ServiceRepository.inc.php#L262-L278)) lands on both sides identically and cancels out.

On the Re-Quote page this symmetry is broken:
- Price side: `obtener_quote_total_price()` reads the **original, static** `services` table (never touched by re-quote).
- Cost side: `obtener_re_quote_total_cost()` ([app/Quote/Rfq.inc.php:390-396](../app/Quote/Rfq.inc.php#L390-L396)) reads the **separate, editable** `re_quote_services` table.

When Net 30/CC is selected and saved, [`ReQuoteServiceRepository::calc_items_with_CC()`](../app/ReQuote/ReQuoteServiceRepository.inc.php#L186-L199) inflates only `re_quote_services.total_price` by 3% (`unit_price * 1.03`, no rounding cast, unlike the main-quote version which casts to `DECIMAL(10,4)`). That markup no longer cancels — it comes straight off `obtener_re_quote_profit()` ([app/Quote/Rfq.inc.php:398-399](../app/Quote/Rfq.inc.php#L398-L399)) as a phantom cost (~3% of the services total, ~$33 on this quote).

### Bonus finding: three different profit formulas, three different numbers
- Items table TOTAL row: `totalGanado - (itemsCost + CC flat fee + shipping)` — [js/reQuote.js:4-27](../js/reQuote.js#L4-L27).
- Bottom summary bar: `obtener_quote_total_price() - obtener_re_quote_total_cost()` — [app/Quote/Rfq.inc.php:398-399](../app/Quote/Rfq.inc.php#L398-L399).
- PDF export: `obtener_total_price() - get_total_cost()`, ignoring services entirely — `herramientas/pdfTemplates/re_quote.inc.php:209-226`.

Also noted: three different CC-fee constants for the conceptually-same 3% fee: `0.029` (items, additive, `js/reQuote.js:6`), `1.0299` (services display, `js/reQuote.js:70`), `1.03` (services persist, `ReQuoteServiceRepository.inc.php:187`), vs. main quote's `1.0298661174047374` (items) / `1.03` (services).

## Fix Plan
1. **Make the bottom summary bar live**, mirroring `js/quote.js`'s `#bar-total-price`/`#bar-total-profit`/`#bar-profit-pct` pattern on the main Quote Edit page. Add `id`s to the spans in `re_quote.inc.php:66-79`, extend `js/reQuote.js` to recompute them every 100ms (or on the existing polling ticks) from the live items + services totals instead of leaving them as page-load-only PHP.
2. **Fix the CC asymmetry** so services stay margin-neutral on the re-quote page, matching the main Quote page (confirmed intent — no difference between the two). Likely approach: make `obtener_re_quote_profit()`/`obtener_quote_total_price()` compare against the same services source consistently for the re-quote context (e.g. both sides read `re_quote_services`, or apply the identical CC markup to whichever table feeds the price side too) — needs care not to change behavior for quotes that were never re-quoted.
3. Unify the three CC-fee constants into one shared value (or one server-side source of truth) used by items and services calc on both the main Quote and Re-Quote pages.
4. Align the PDF export's profit formula (`herramientas/pdfTemplates/re_quote.inc.php:209-226`) with the corrected formula so it doesn't diverge from the on-screen number.

**Test:** PHP integration test that creates a re-quote, sets services payment term to Net 30/CC, saves, and asserts `obtener_re_quote_profit()` equals the Net-30 baseline profit (services cancel out, no phantom cost) — add to `tests/php/`.

## Open Questions
- Exact scope of fix #2 (which table should be the source of truth for the price-side services figure on a re-quote) needs a careful look at how `re_quote_services` gets seeded when a re-quote is created, to avoid double-counting or losing edits already made on that table. `/build` should verify this before changing the formula.
