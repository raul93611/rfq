# Period of Performance doesn't refresh on info card after saving

Status: fixed

Setting Period of Performance in the Checklist drawer saves correctly, but the quote's info card keeps showing the old value (or nothing) until the page is reloaded.

## Steps to Reproduce

1. Open a quote's edit page (`perfil/quote/editar_cotizacion/{id}`).
2. Open the Checklist drawer.
3. Set Period of Performance start/end date(s) and save.
4. Close the drawer.

## Expected Behavior

The info card's secondary row immediately shows the Period of Performance duration (e.g. "3 months") and date sub-line, without needing a page reload.

## Actual Behavior

The info card does not update — it still shows the prior state (missing, or stale dates) until the page is manually reloaded.

## Severity

Partially working — confusing rather than blocking. Users may think the save didn't take and re-enter data, or simply not notice the save succeeded.

## Has this always been broken?

Yes — not a regression from the recent move of Period of Performance into the Checklist drawer (`03eff755`). It was introduced with the original feature (`f157e78e`, "feat: add Period of Performance to the Information drawer") and carried over unchanged.

## Investigation

Root cause confirmed directly in the code, three compounding gaps:

1. **AJAX response doesn't include PoP data.** [scripts/quote/save_checklist.php:88-89](scripts/quote/save_checklist.php#L88-L89):
   ```php
   echo json_encode(['success' => true, 'checklistCount' => $savedQuote->getChecklistCompletionCount()]);
   ```
   Only `checklistCount` is returned — no saved `pop_start_date`/`pop_end_date` or computed duration.

2. **JS save handler has no PoP branch.** [js/checklist_info_drawer.js:231-241](js/checklist_info_drawer.js#L231-L241) — the success handler has a branch for `checklistCount` (`updateChecklistCount()`) and one for `sheetSync` (calls `window.ssRepaint()`), but nothing for Period of Performance at all.

3. **No DOM hook to update, and the cell may not exist yet.** [forms/quote/edicion_cotizacion_recuperada.inc.php:49-72](forms/quote/edicion_cotizacion_recuperada.inc.php#L49-L72) — the PoP cell is built into `$secondaryFields[]` only when at least one date is already set (lines 51-59), and rendered via a plain `foreach` with no `id` on the wrapper or any individual `.quote-info-cell` (lines 61-71). There is nothing for JS to target, and the cell is conditionally absent from the DOM on first save.

**Existing precedent for the fix:** Sheet Sync had the identical class of bug (an AJAX save silently changed server state; the on-page block was static from initial page load and went stale). It was fixed in commit `028dd3e6` via:
- `save_information.php` re-fetching the quote and returning computed values (`sheetSync: { status, syncAt, row }`) in the JSON response.
- `js/sheet_sync.js` exposing `window.ssRepaint(tone, syncAtText)`, which updates the element in place **or creates it if missing** (the PHP template omits `.ss-block-meta` entirely in the "never synced" state).
- `checklist_info_drawer.js` calling `window.ssRepaint(...)` when `result.data.sheetSync` is present.

This same pattern was never applied to Period of Performance.

## Fix Plan

1. Extract `formatPopDuration()` (currently a bare function defined inline at [forms/quote/edicion_cotizacion_recuperada.inc.php:41-48](forms/quote/edicion_cotizacion_recuperada.inc.php#L41-L48)) into a shared location (e.g. a helper on `Rfq` or a small utility include) so both the initial page render and the new AJAX response use identical formatting logic.
2. In `scripts/quote/save_checklist.php`, extend the JSON success payload (alongside `checklistCount`) with a `periodOfPerformance` key, `null` when both dates are blank, otherwise an object with the formatted duration and date sub-line (mirroring the server-rendered markup's two cases: both dates set vs. only one).
3. Add a stable `id` (e.g. `id="qed-pop-cell"`) to the Period of Performance `.quote-info-cell` in `edicion_cotizacion_recuperada.inc.php` so JS has something to find, and something to insert next to when the cell doesn't exist yet.
4. In `js/checklist_info_drawer.js`'s `saveTab()` success branch, add a `which === 'checklist'` case (parallel to the existing `sheetSync` case) that:
   - Updates `#qed-pop-cell`'s duration/subline text in place if `result.data.periodOfPerformance` is present and the cell already exists.
   - Constructs and inserts the cell into `.quote-info-secondary` if it doesn't exist yet (first time a date is set — same "element missing from initial DOM" case `ssRepaint` already solves for `.ss-block-meta`).
   - Removes `#qed-pop-cell` from the DOM if `result.data.periodOfPerformance` is `null` (both dates cleared), so clearing also updates live.
5. Extend `tests/php/period_of_performance_test.php` (currently only asserts `success: true`) to assert the AJAX response includes the new `periodOfPerformance` data, and add/extend a Playwright case in `tests/specs/11-checklist-info-drawer.spec.js` asserting the info card updates without a reload.

## Open Questions

None blocking — this looks like a straightforward gap (the info-card live-refresh was simply never wired up for this field), not an intentional design decision. `/build` can proceed directly with the fix plan above.
