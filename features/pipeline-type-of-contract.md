# Pipeline Type of Contract Breakdown

Add the existing `rfq.type_of_contract` field (RFQ, RFP Maintenance, RFP Installation, Professional Services, Moving and Logistics) as a new breakdown dimension on the Bid Pipeline page: a donut chart in Charts view, and a column + filter in Table view.

**Status:** planned

## Why

Type of Contract is already captured on every quote and already used in the quote form and Advanced Search, but the Bid Pipeline page has no way to see the pipeline broken down by it or to filter the table on it — users currently have to fall back to Advanced Search for a one-off count.

## User Flow

1. User opens `perfil/reports/pipeline_metrics` (Bid Pipeline page), on the Charts tab.
2. A new "Quotes by type of contract" donut card renders directly after the existing "Status distribution" card, showing every quote in the selected period broken down by `type_of_contract` (blank values grouped as "Uncategorized").
3. User clicks a slice → the existing quote-list drawer opens, filtered to that type of contract, same interaction as clicking a Status Distribution slice.
4. User switches to the Table tab. A new "Type of Contract" column appears immediately after the "Type of Bid" column on every row.
5. In the filter panel, a new "Type of Contract" single-select dropdown appears immediately after the existing "Type of Bid" filter, populated from `TypeOfContractRepository::get_all` plus an "Uncategorized" option.
6. Selecting a value re-filters the table (AND-combined with any other active filters), same as every other Pipeline Table filter; it also counts toward the active-filters count and is cleared by "Clear filters".

Single role (any user with Pipeline Metrics access) — no new permissions.

## UI Changes

**`perfil/reports/pipeline_metrics` — Charts view**
- New full-width-or-half donut card "Quotes by type of contract" inserted right after "Status distribution", same visual/interaction style (center total, legend, click-to-drill, count/percent toggle).

**`perfil/reports/pipeline_metrics` — Table view**
- New "Type of Contract" column after "Type of Bid".
- New "Type of Contract" filter (single-select dropdown) after the "Type of Bid" filter, in its own `.pt-field`.
- Quote Summary modal / row detail gains a "Type of Contract" field alongside the existing "Type of Bid" field.

No changes to the quote create/edit form — `type_of_contract` is already editable there.

## Data Model Changes

None. Reuses the existing `rfq.type_of_contract` column and `type_of_contracts` lookup table (`TypeOfContractRepository::get_all`) — no migration needed.

## External Dependencies

None.

## Acceptance Criteria

- Charts view: new donut chart renders quotes for the selected period grouped by `type_of_contract`; blank/null values group under "Uncategorized"; chart respects the existing period picker (year/quarter/month/custom) and count/percent toggle, same as other charts.
- Clicking a donut slice opens the existing drilldown drawer showing exactly the quotes matching that type of contract (and period).
- Table view: "Type of Contract" column shows the correct value per row, "Uncategorized" when blank, positioned right after "Type of Bid".
- Table filter: dropdown lists all values from `type_of_contracts` plus "Uncategorized"; selecting one returns only matching rows; AND-combines correctly with every other active filter (e.g. Type of Contract + Bid Type together narrows further, not either/or).
- Filter counts toward the "N filters applied" indicator and is reset by "Clear filters".
- Empty state: a period with zero quotes shows the chart's existing empty state (no chart drawn), consistent with other cards.
- No regression to existing Type of Bid column/filter/chart or any other Pipeline Metrics chart.

## Out of Scope

- Sources Sought (`quote/sources_sought`) and No Award (`quote/no_award`) pages — not extended with this field in this pass, even though they mirror Pipeline Metrics elsewhere.
- Any change to how `type_of_contract` is entered/edited on the quote itself.
- Multi-select filtering for Type of Contract (kept single-select to match its sibling Bid Type filter).
- Advanced Quote Search — already has its own Type of Contract-adjacent filtering (bid/contract type) and is untouched here.

## Decisions

- **Field mapping:** "type of project" (RFQ, VAR/Professional Services, etc.) maps to the existing `rfq.type_of_contract` field, not the unrelated `TypeOfProject`/`types_of_projects` entity (which belongs to Fulfillment → Personnel and is unrelated to quotes), and not the sibling `type_of_bid` field. Confirmed directly with the user after finding both candidate fields in the codebase.
- **Chart type/scope:** Donut chart over ALL quotes in the period (not just won/submitted) — chosen to mirror the existing "Status distribution" chart, which answers the same kind of "what does my whole pipeline look like by X" question this feature is after.
- **Chart position:** Placed immediately after "Status distribution" — both are full-pipeline donuts, so grouping them keeps chart types visually consistent before the narrower award/submitted breakdown charts.
- **Table column/filter position:** Both placed immediately after their Type of Bid counterparts — the two "type" fields are closely related and should read together.
- **Blank value handling:** Grouped as "Uncategorized" everywhere (chart, column, filter) — matches the existing precedent set by Type of Bid rather than silently dropping rows.
- **Drilldown:** Reuses the existing pipeline_metrics_drilldown drawer — keeps interaction consistent with every other chart on the page rather than introducing a new pattern.
- **Scope (Sources Sought / No Award):** Excluded from this pass per user request — kept the feature tightly scoped to the main Pipeline Metrics Charts/Table view; can be added later as a follow-up.
- **Filter control type:** Single-select dropdown, chosen to match Bid Type (its closest sibling field) rather than the multi-select pattern used for Status.
