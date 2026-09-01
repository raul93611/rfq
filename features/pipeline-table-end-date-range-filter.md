# Pipeline Table End Date Range Filter

Replace the Pipeline Table's End Date preset dropdown with a custom From/To date range filter.

**Status:** planned

## User Flow

1. User opens the Pipeline Table view (`perfil/reports/pipeline_metrics` → Table tab).
2. In the filter panel, the End Date field shows two date inputs (From / To) instead of a preset dropdown.
3. User sets either or both bounds. Table re-filters (same live-filter behavior as the other fields) to rows whose End Date falls within the range, AND-combined with any other active filters.
4. Clearing both bounds (or hitting "Clear filters") removes the End Date filter entirely.

Single user role — no change to who can use this.

## UI Changes

**`plantillas/utilities/pipeline_metrics.inc.php` (Table tab filter panel):**
- Remove the End Date `<select>` (`pt-f-endDate`, options: Any/Today/Tomorrow/Next 7 days/Overdue).
- Replace with a `.pt-field.pt-field-wide` block containing a `.pt-daterange` of two `.pt-date` inputs (`pt-f-endDateFrom`, `pt-f-endDateTo`) — identical structure/styling to the existing Submitted Date field directly above it.

No changes to the End Date **column** in the results table — it keeps showing the raw value as today.

## Data Model Changes

None. `rfq.end_date` already exists (`VARCHAR`, `MM/DD/YYYY HH:mm`).

## Logic Changes

**`app/Report/PipelineTableRepository.inc.php`:**
- Remove `endDateClause()` (preset switch).
- Add range handling in `applyFilters()`, mirroring the existing `submittedFrom`/`submittedTo` block: when either `endDateFrom` or `endDateTo` is set, require `STR_TO_DATE(NULLIF(rfq.end_date, ''), '%m/%d/%Y %H:%i') IS NOT NULL`, then AND in `>= :efrom` / `<= :eto` as applicable (comparing `DATE(...)` against the bound).

**`js/pipeline_table.js`:**
- `EMPTY_FILTERS`: replace `endDate: ''` with `endDateFrom: '', endDateTo: ''`.
- Filter-count logic: `if (f.endDateFrom || f.endDateTo) n++;` (replaces `if (f.endDate) n++;`).
- Query string building: replace the single `endDate` param with `endDateFrom`/`endDateTo`, same pattern as `submittedFrom`/`submittedTo`.
- Element-id arrays (change listeners, read-on-submit, clear-filters reset): replace `pt-f-endDate` with `pt-f-endDateFrom` and `pt-f-endDateTo`.

No changes to `DigestRepository`, the Charts tab, or the Internal Due Date filter — none of them touch `endDateClause()`.

## External Dependencies

None.

## Acceptance Criteria

- End Date field in the Pipeline Table filter panel shows From/To date inputs, no dropdown.
- Setting only From filters to End Date >= From. Setting only To filters to End Date <= To. Setting both filters the inclusive range.
- A row with a blank/unset End Date never matches once either bound is set.
- From > To returns zero rows, no error shown.
- End Date range is AND-combined with every other active filter (channel, bid type, user, Internal Due Date, Submitted Date, status).
- Filter count badge counts the End Date range as one active filter regardless of whether one or both bounds are set.
- "Clear filters" resets both End Date inputs.
- Existing End Date column values and sort are unchanged.

## Out of Scope

- Any change to the Internal Due Date filter (keeps its Today/Tomorrow/Next 7 days/Overdue presets).
- Quick-select shortcut buttons (e.g. "Overdue") for the new End Date range — explicitly declined.
- Charts tab — it has no End Date filter today and none is being added.
- Daily Digest "Due Today" email logic (`DigestRepository::getDueOn()`) — separate, independent query; untouched.
- Any change to the End Date column display or the New Quote form's End Date picker.

## Decisions

- **Scope/location:** Pipeline Table view only (`perfil/reports/pipeline_metrics` → Table tab). Reasoning: the End Date filter only exists there today — Charts has no equivalent field, so "bid pipeline page and the table view" refers to one location, not two.
- **UI pattern:** Replace the preset dropdown entirely with a From/To range, reusing the Submitted Date field's exact pattern (`.pt-daterange`/`.pt-date`, `.pt-field-wide`). User's explicit choice over keeping presets alongside a range, or a "Custom range" dropdown option.
- **No quick-select shortcuts:** Pure From/To inputs only — user accepted losing the one-click Today/Tomorrow/Next-7-days/Overdue shortcuts for End Date specifically, in favor of the simplest implementation.
- **Range semantics (inclusive, AND-combined):** Mirrors the existing Submitted Date filter's behavior exactly, for consistency.
- **Blank End Date exclusion once a bound is set:** Mirrors Submitted Date's rule; reuses the existing `STR_TO_DATE(NULLIF(rfq.end_date,''),...)` handling already required for this VARCHAR column.
- **Inverted range = empty result, no error:** Matches the established convention already used by Advanced Quote Search elsewhere in the app.
- **Filter count = 1 regardless of which bound(s) are set:** Mirrors Submitted Date's counting behavior.
