# Pipeline Table — Submitted Date Filter

Adds a "Submitted Date" range filter to the Bid Pipeline **Table** view so users can find what a designated user submitted within a specific window (e.g. "what did this user submit last week").

**Status:** planned

## User Flow

1. User opens `perfil/reports/pipeline_metrics`, switches to the **Table** view.
2. User picks a **Designated User** from the existing filter (or leaves it blank).
3. User sets the new **Submitted Date** range — a "From" and "To" date picker.
4. Table re-fetches (same debounce/immediate pattern as other filters) and shows only quotes whose `fecha_submitted` falls within `[from, to]`, still AND-combined with every other active filter and the top period selector.
5. Result: rows where that user's quote was submitted in the chosen window — visible in a new **Submitted** column alongside Created/Internal Due Date/End Date.

Single user role (any Pipeline Table viewer) — no new roles involved.

## UI Changes

**`plantillas/utilities/pipeline_metrics.inc.php`** (table view filter bar):
- New `.pt-field` block, placed immediately after **Designated User** and before **Internal Due Date**: label "Submitted Date", two native `<input type="date">` elements (from/to) styled like the existing `#pm-date-from`/`#pm-date-to` custom-period pair, with a "to" separator between them.
- Table `<colgroup>`/`<thead>`: add a new **Submitted** column, grouped with the other date columns — order becomes Quote ID, Created, Internal Due Date, End Date, **Submitted**, Channel, Code, Status, Type of Bid, Designated User (9 → 10 columns; empty-state `colspan` updates to match).

**`js/pipeline_table.js`:**
- `EMPTY_FILTERS()` gains `submittedFrom`/`submittedTo` (empty string default).
- `countFilters()` treats the pair as **one** active filter (counted if either value is set) — same convention as Internal Due Date/End Date being one dropdown each.
- `buildQuery()` appends `submittedFrom`/`submittedTo` when set.
- `wireFilters()`/clear-filters button wire up the two new date inputs (immediate re-fetch on change, like Internal Due Date/End Date).
- `renderTable()` renders the new Submitted column using the row's `submitted` field (formatted `m/d/Y`, em dash `—` when null), same pattern as `created`/`internalDueDate`/`endDate`.

No changes to the Quote Summary modal, Advanced Quote Search, or export.

## Data Model Changes

None — reuses the existing `rfq.fecha_submitted` DATE column (already stamped via `UPDATE rfq SET status = 1, fecha_submitted = NOW()` on submit). No migration needed.

## Backend Changes

**`app/Report/PipelineTableRepository.inc.php`:**
- `getPage()`'s inner SELECT adds `rfq.fecha_submitted` and the output mapper adds a `submitted` field (formatted `m/d/Y`, or `—` when null) — same treatment as `internalDueDate`.
- `buildWhere()` adds handling for `filters['submittedFrom']`/`filters['submittedTo']`: when either is present, adds `rfq.fecha_submitted IS NOT NULL` plus the applicable bound(s) (`>= :sfrom`, `<= :sto`, or both). An inverted range (`from > to`) is not specially validated — it naturally yields zero matching rows, no error, consistent with the existing custom-period-range convention.
- Filter is a straight AND on the raw column — independent of derived status bucket, and AND-combined with the top period's `created_at` cohort (no special-casing).

**`scripts/quote/pipeline_table.php`:**
- Reads and validates `submittedFrom`/`submittedTo` GET params (`^\d{4}-\d{2}-\d{2}$` format check, same as the custom period's `from`/`to`), passes them into the `$filters` array.

## External Dependencies

None.

## Acceptance Criteria

- Setting only "From" returns quotes submitted on/after that date (within the period cohort).
- Setting only "To" returns quotes submitted on/before that date.
- Setting both returns quotes submitted within the inclusive range.
- Rows with `fecha_submitted IS NULL` (never submitted) never match when the filter is active.
- An inverted range (From after To) returns zero rows, no error.
- The new Submitted Date filter is AND-combined with every existing filter (Designated User, Status, etc.) and with the top period selector, matching the rest of this page's filters.
- The filter counts as exactly one entry in the "N filters applied" badge, active if either date is set.
- "Clear filters" resets both date inputs.
- The new **Submitted** table column shows `m/d/Y` or `—`, positioned after End Date.
- Existing filters, pagination, and the Quote Summary modal are unaffected.

## Out of Scope

- Advanced Quote Search page (`perfil/search_quotes`) — not touched.
- Quick-select shortcut buttons ("Last week", "Last 30 days", etc.) — plain from/to pickers only.
- Adding Submitted Date to the Quote Summary modal's Details section.
- Changes to CSV/Excel export.
- Any change to when/how `fecha_submitted` itself gets set.

## Decisions

- **Period interaction:** Submitted Date is AND-combined with the existing Created-date period selector (same as End Date/Internal Due Date). Chosen for consistency — every other filter on this page works this way; the alternative (making it override the period) would require special-casing the query builder for one filter.
- **UI style:** plain from/to date pickers, no quick-select shortcuts. Chosen for simplicity and to reuse the one range-picker pattern already in the codebase (`#pm-date-from`/`#pm-date-to`).
- **Filter bar placement:** right after Designated User. Chosen because that's the filter it's most commonly paired with per the user's own example ("select this user, then filter their submitted date").
- **Table column placement:** new "Submitted" column grouped with the other date columns (after End Date) rather than at the far right. Author's call — keeps all date-based columns visually together.
- **Null handling:** rows never submitted are excluded when the filter is active. Author's call — matches the filter's intent.
- **Inverted range:** returns zero rows, no error. Author's call — matches the existing documented convention for this repository's other date filters.
- **Filter count badge:** the from/to pair counts as one active filter. Author's call — mirrors how Internal Due Date/End Date (single dropdowns) each count as one.
