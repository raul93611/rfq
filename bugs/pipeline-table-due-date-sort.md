# Pipeline Table: sort by Internal Due Date / End Date

The Bid Pipeline Metrics → Table view can't be sorted by due date; the team needs oldest → newest (and back) on both date columns.

**Status:** fixed

## Steps to reproduce

1. Open Bid Pipeline Metrics, switch to **Table** view.
2. Try to order the quotes by **Internal Due Date** or **End Date**.

## Expected

Clicking the Internal Due Date or End Date header sorts every quote in the period (all pages, not just the visible 25) oldest → newest; clicking again sorts newest → oldest; a third click returns to the default order.

## Actual

No sort control exists. Rows are always newest quote first (`id DESC`); dates can only be filtered.

## Severity

Minor — missing capability, not a regression. Requested by the team for checking bid metrics.

## Investigation

- Order is hard-coded: `ORDER BY id DESC` in [app/Report/PipelineTableRepository.inc.php:51](../app/Report/PipelineTableRepository.inc.php#L51). `getPage($conexion, $period, $filters, $page)` has no sort input.
- The endpoint [scripts/quote/pipeline_table.php](../scripts/quote/pipeline_table.php) reads no sort param; `PipelineTableView.buildQuery()` in [js/pipeline_table.js](../js/pipeline_table.js) emits none.
- Paging is server-side (25/page), so a client-side sort would only reorder the current page — **must be SQL**.
- Column storage differs:
  - `rfq.internal_due_date` — `DATE NULL` ([sql/bid_requirement_fields_migration.sql:8](../sql/bid_requirement_fields_migration.sql#L8)).
  - `rfq.end_date` — `VARCHAR` `MM/DD/YYYY HH:mm`, `NOT NULL`, blank = `''`. Needs `STR_TO_DATE(NULLIF(end_date, ''), '%m/%d/%Y %H:%i')` — same expression the End Date range filter already uses in `buildWhere()`.
- Both columns are already selected in the inner query (`rfq.internal_due_date`, `rfq.end_date`), so the outer `ORDER BY` can reference `t.internal_due_date` / `t.end_date`.
- Headers: [plantillas/utilities/pipeline_metrics.inc.php:310](../plantillas/utilities/pipeline_metrics.inc.php#L310) (`<th class="pt-th-wrap">Internal Due Date</th><th>End Date</th>`). Header CSS: `.pt-table th` in [css/estilos.css](../css/estilos.css) (~line 3428).
- History: date columns added in `40e5065b`; filters in `b7052e81`, `4aa54b49`, `fbbbd0e7`. No sorting was ever implemented.

## Fix Plan

1. **Repository** — `PipelineTableRepository::getPage(..., $page = 0, array $sort = [])`. Map whitelisted keys to SQL expressions (never interpolate input):
   - `internalDueDate` → `t.internal_due_date`
   - `endDate` → `STR_TO_DATE(NULLIF(t.end_date, ''), '%m/%d/%Y %H:%i')`
   - `dir` → `ASC` | `DESC` (anything else → `ASC`).
   - Build `ORDER BY <expr> IS NULL, <expr> <DIR>, id DESC` — undated rows last in both directions, `id DESC` tiebreak for stable paging. Unknown/empty col → `ORDER BY id DESC` (unchanged default).
2. **Endpoint** — read `sort` (`internalDueDate|endDate`, else `''`) and `dir` (`asc|desc`, else `asc`); pass to `getPage()`. Update the docblock param list.
3. **JS** (`js/pipeline_table.js`):
   - `st.sort = { col: '', dir: 'asc' }`.
   - `buildQuery(p, f, page, sort)` appends `sort=…&dir=…` only when `sort.col` is set (keep it pure/exported for tests).
   - Delegated click on `th[data-sort]`: cycle none → asc → desc → none; reset `st.page = 0`; `fetchRows()`; update header arrow / `aria-sort`.
   - **Clear filters does not reset sort** (sort isn't a filter; `countFilters()` unchanged).
4. **Markup** — add `data-sort="internalDueDate"` / `data-sort="endDate"`, a `pt-th-sort` class, and an arrow span to those two `<th>`s. Keep the 18-col `<thead>` intact.
5. **CSS** — `.pt-th-sort` (pointer cursor, hover color, focusable via `tabindex="0"` + Enter/Space) and `.pt-sort-ico` arrow states (`is-asc`, `is-desc`) in the `pt-` namespace.
6. **Tests**
   - `tests/php/pipeline_table_test.php`: seed quotes in the 2099 cohort with distinct/blank internal due dates and end dates; assert asc and desc order for each column, blanks last in both, invalid col → `id DESC`.
   - `tests/js/pipeline_table.test.js`: `buildQuery()` emits `sort`/`dir` when set, omits them when not.
   - Optional: `tests/specs/09-pipeline-metrics.spec.js` — click End Date header, assert first row's end date ≤ second's.

## Open Questions

- Legacy `end_date` values in a different format (e.g. date without time) may parse to NULL and land with the blanks at the end — acceptable; pin the actual behaviour in the PHP test.
- Only these two columns are sortable; extending later is a whitelist addition.
