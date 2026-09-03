# Period of Performance

Optional date range on a quote representing the period during which the awarded contract is active, editable from the Information drawer and shown on the Quote Edit page's info card only when set.

**Status:** built

## User Flow

1. User opens an existing quote's edit page (`perfil/quote/editar_cotizacion/{id}`).
2. User clicks the "Information" status card to open the Information drawer (same entry point as Issue Date, End Date, Internal Due Date, etc.).
3. User sees a new "Period of Performance" field with a date-range picker, blank by default.
4. User picks a start date, an end date, or both (either side can be left blank).
5. User saves the drawer (`save_information=1`).
6. Drawer closes; the Quote Edit page's top info card re-renders. If a date was set, a new "Period of Performance" cell appears in the secondary field row showing the range (or the single date that was set). If nothing was set, no cell appears at all.
7. To change or clear it later, the user reopens the Information drawer, adjusts the range (or clears it via the picker's clear control), and saves again.

Single user role — same staff who edit any other quote field today; no new roles or permissions.

## UI Changes

**Quote Edit page — top info card** (`forms/quote/edicion_cotizacion_recuperada.inc.php`)
- New cell in the **secondary** `quote-info-grid` row (alongside Client, Bill To, Ship To, Reference URL).
- Rendered conditionally: only appears when at least one of start/end is set. No dash placeholder for the unset case — the cell is omitted entirely (unlike other secondary fields, which show a gray "—").
- Value display: full range when both dates are set (e.g. "1/1/2027 – 12/31/2027"); just the one date when only start or only end is set.

**Information drawer** (`forms/quote/information.inc.php`)
- New "Period of Performance" field using a single date-range-picker input (same interaction pattern as the Pipeline Table's existing `pt-daterange` filters), placed near the other date fields (Issue Date/End Date/Internal Due Date group).
- Includes a clear control so a previously-set range can be reset back to empty.
- Not required — form saves fine with it blank, and partial (only start or only end) is valid.

**New Quote creation form:** unchanged — this field is not part of initial quote creation.

## Data Model Changes

- `rfq` table: two new nullable columns, `pop_start_date DATE NULL` and `pop_end_date DATE NULL`. No default backfill needed since existing quotes correctly have no Period of Performance.
- `Rfq` domain class: add properties + accessors (`obtener_pop_start_date()`, `obtener_pop_end_date()`), following the same pattern as `fecha_submitted`.
- `RepositorioRfq::save_information()`: extend to accept and persist the two new values.
- `AuditTrailRepository::information_events()`: extend with before/after params for both columns so changes log as `field_modified` events, consistent with every other Information drawer field.

## External Dependencies

None. No new APIs, credentials, or third-party services.

## Acceptance Criteria

- A quote with no Period of Performance set shows no Period of Performance cell on its Quote Edit info card.
- Setting only a start date (leaving end blank) saves successfully and displays just the start date on the card.
- Setting only an end date (leaving start blank) saves successfully and displays just the end date on the card.
- Setting both dates saves successfully and displays the full range on the card.
- The range picker prevents selecting an end date earlier than the start date.
- Clearing a previously-set range via the drawer's clear control removes the cell from the card on next save.
- Editing Period of Performance produces a `field_modified` audit trail entry, visible in the unified audit trail's Edits tab.
- The New Quote creation form is unchanged — no Period of Performance field there.
- The SharePoint sheet sync (`SheetSyncService::createOrLink`) is untouched — no new column mapping, no new writes triggered by this field.
- The Pipeline Table view, Pipeline Metrics dashboard, and Advanced Quote Search are all unaffected — no new column, filter, or status logic tied to this field.

## Out of Scope

- Pipeline Table column or filter for Period of Performance.
- Pipeline Metrics dashboard / Charts tab integration.
- SharePoint sheet sync mapping for this field.
- Adding the field to the New Quote creation form.
- Any reporting, search, or sorting by Period of Performance.
- Backfilling historical quotes with Period of Performance data.

## Decisions

- **Display location: Quote Edit info card, not Pipeline Table/dashboard** — user confirmed this is what "main cards in the quote page" refers to; keeps scope tight to a single surface.
- **Field structure: single range-picker input, two DB columns underneath** — matches the existing `pt-daterange` UX pattern already used elsewhere in the app for optional ranges, while keeping the data model simple (two plain nullable dates).
- **Card placement: secondary row** — Primary row is reserved for always-present identity fields (Contract Number, Code, Channel, Designated User); this field is optional by nature, so it belongs with the other contextual/optional secondary fields.
- **Empty state: hide the cell entirely rather than showing a dash** — since most quotes won't have this data, showing a dash on every card would just be noise; other secondary fields keep the dash because they're expected to eventually be filled for every quote.
- **Edit location: Information drawer only, not the New Quote form** — Period of Performance is typically unknown at the time a quote is created (it reflects contract award terms), so forcing it into initial creation would add friction for no benefit.
- **Partial range allowed** — either date can be set independently, since one side may become known before the other.
- **Sheet sync: out of scope** — the sheet's column layout (A–T) is fixed and largely spoken for; syncing a new field is a separate, deliberate change if ever needed, not a side effect of this feature.
- **Audit trail: logged as `field_modified`, same as every other Information field** — decided without asking since it's a consistency call, not a UX tradeoff; every other field in this drawer is already logged this way.
- **Range validation: enforced by the picker widget, no extra server-side rule** — decided without asking; this mirrors how the app's other range pickers already behave, and Period of Performance carries no downstream calculation that would need a stronger guarantee.
- **Clear control on the picker** — decided without asking; since the field is optional and editable after the fact, users need a way to unset it, not just overwrite it.
