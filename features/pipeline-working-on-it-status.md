# Working on It Pipeline Status

Add "Working on it" (an existing `comments` field value) as its own filterable status bucket, shared across the Pipeline Table view filter, the Bid Pipeline Metrics Charts status distribution, and Advanced Quote Search.

**Status:** planned

## User Flow

1. A user sets a quote's Comments field to "Working on it" via the existing dropdown (New Quote form, Information drawer, or the Pipeline Table quick-comment modal) — this already works today, no change.
2. Wherever pipeline status is shown or filtered (Bid Pipeline Metrics → Table view filter, Charts status distribution donut, Advanced Quote Search), that quote now appears under its own "Working on it" bucket instead of being silently absorbed into TBD/Bid.
3. In the Table view, the user opens the Status multi-select filter and checks "Working on it" (alongside any other statuses) to see just those quotes.

Single user role — no new roles or permissions involved.

## UI Changes

- **Pipeline Table view status filter** (`STATUS` dropdown, `perfil/reports/pipeline_metrics` → Table): new checkbox option "Working on it" with a violet dot, inserted between TBD and Bid, matching the existing multi-select AND/OR filter behavior of every other status.
- **Bid Pipeline Metrics Charts status distribution**: new slice "Working on it" (violet, `#8b5cf6`), same position in the legend/ordering.
- **Advanced Quote Search** status pill/filter: automatically inherits the new option (shared source list) — no separate UI work.
- No new screens, forms, or inputs — the comments dropdown already has this value.

## Data Model Changes

None — no schema change. This is purely a new derived bucket over the existing `rfq.comments` column.

- `PipelineMetricsRepository::STATUS_CASE`: new `WHEN rfq.comments = 'Working on it' THEN 'working_on_it'`, placed **after** the `completado = 1 THEN 'bid'` clause (so pricing wins once a quote is priced) and before the final `ELSE 'tbd'`.
- `PipelineMetricsRepository::STATUSES`: new entry `['key' => 'working_on_it', 'label' => 'Working on it', 'color' => '#8b5cf6']`, positioned between `tbd` and `bid`.
- `PipelineMetricsRepository::PENDING_KEYS`: add `working_on_it` (in-progress, not yet submitted/decided — same treatment as tbd/bid/submitted/submitted_ss). Not added to `SUBMITTED_KEYS`, `WON_KEYS`, or `LOST_KEYS` — no change to Win/Loss denominator math.
- `Rfq::getSheetStatus()`: add a matching `'WORKING ON IT'` clause in the same position, to keep the documented "mirrors STATUS_CASE" invariant true (this method currently has zero call sites — updated for consistency, not because anything consumes it today).

## External Dependencies

None.

## Acceptance Criteria

- Table view Status filter shows a "Working on it" checkbox; selecting it (alone or combined with other statuses) returns exactly the quotes where `comments = 'Working on it' AND completado = 0`, and none of the higher-precedence conditions apply (not awarded/no-awarded/submitted/not-submitted/cancelled/no-bid).
- A quote priced (`completado = 1`) after being marked "Working on it" shows as "Bid", not "Working on it" — pricing status always wins once set.
- Charts status distribution donut renders "Working on it" as its own violet slice, sized correctly.
- Advanced Quote Search status filter/pill includes "Working on it" as a selectable option, reusing `STATUS_CASE` verbatim (no separate backend logic).
- `tests/php/pipeline_metrics_test.php`: bucket-count assertion updated 10 → 11; new dataset row + per-bucket count assertion for `working_on_it`.
- `tests/php/pipeline_table_test.php`: new filter assertion proving `statuses => ['working_on_it']` returns the right row set.

## Out of Scope

- Changing the comments dropdown itself, or adding new ways to set a quote's comments — the existing dropdown/value is reused as-is.
- Any change to Sources Sought (`quote/sources_sought`) or No Award (`quote/no_award`) mirror pages — they scope to different bucket subsets (`submitted_ss`, `no_award_*`) and are unaffected by this bucket.
- Any change to Win/Loss or dollar-value KPI math beyond including `working_on_it` in `PENDING_KEYS`.
- Wiring up any new caller for `Rfq::getSheetStatus()` — it stays dead code, just kept accurate.

## Decisions

- **Bucket scope: shared across Table filter, Charts, and Advanced Search** — chosen over a Table-view-only carve-out because all three already read the same `STATUS_CASE`/`STATUSES` source; splitting it would create a second, divergent status taxonomy for no benefit.
- **Precedence: pricing (`completado=1`) wins over the "Working on it" comment** — per the user's stated lifecycle (`TBD → Working on it → Bid → Submitted...`), so the new `WHEN` clause sits after the `bid` check in the CASE, not before it.
- **Color: `#8b5cf6` (violet)** — the only unused hue among the 10 existing status colors (grays, blues, teal, green, reds, orange, amber all taken); chosen for clear visual distinction, not for any semantic reason.
- **KPI classification: added to `PENDING_KEYS`, excluded from `SUBMITTED_KEYS`/`WON_KEYS`/`LOST_KEYS`** — it's a pre-bid in-progress state like TBD/Bid, so it shouldn't affect the Win/Loss denominator.
- **`Rfq::getSheetStatus()` updated despite having no callers** — the file's own doc comment declares it must stay in sync with `STATUS_CASE`; fixing it costs nothing and keeps that invariant true for whoever reads it next.
- **No new UI for setting the status** — the comments dropdown already offers "Working on it" on every form that edits comments; this feature only makes the existing value filterable/visible as a first-class bucket.
