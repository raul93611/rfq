# Remove "Submitted Invoice" status

Eliminate the "Submitted Invoice" status entirely and migrate all quotes currently in it back to "Invoice."

Status: planned

## Steps to Reproduce (current behavior)

1. Open a quote that has `invoice = 1`.
2. Check the "Submitted Invoice" checkbox on the edit page.
3. Quote now shows a purple "Submitted Invoice" badge and moves from `Accounting > Invoice` to `Accounting > Submitted Invoice`.

## Expected vs Actual

- **Expected:** "Submitted Invoice" should not exist as a selectable/visible status anywhere. Any quote currently in that status should show and list as "Invoice" instead.
- **Actual:** "Submitted Invoice" exists as a distinct terminal status past "Invoice," with its own checkbox, badge color, Accounting sidebar page, and remove-status link.

## Severity

Minor / administrative — not a broken-app bug, a requested status consolidation. No urgency beyond normal prioritization.

## Investigation

`submitted_invoice` (+ `submitted_invoice_date`) is a boolean flag on `rfq`, one step past `invoice`. Checkbox only appears once `invoice = 1` ([status_checkbox.inc.php](../forms/quote/templates/status_checkbox.inc.php)). The Invoice accounting list already excludes `submitted_invoice = 1` rows ([RepositorioRfq.inc.php](../app/Quote/RepositorioRfq.inc.php) `getTotalInvoiceQuotesCount`/`getTotalFilteredInvoiceQuotesCount`/invoice search query), and status/back-button logic check `submitted_invoice` before `invoice` ([status_title.inc.php](../plantillas/quote/templates/status_title.inc.php), [go_back_button.inc.php](../forms/quote/templates/go_back_button.inc.php)). So a one-time backfill (`submitted_invoice = 0`) is sufficient to make existing "Submitted Invoice" quotes render/list as "Invoice" everywhere — no query changes needed for that part.

Full surface area (checkbox, save handler, badge/color, remove-link, sidebar nav, dedicated route/page, DataTable, repository methods) enumerated below. `getSheetStatus()`'s `award || fullfillment || invoice || submitted_invoice` OR-check and `PipelineMetricsRepository::STATUS_CASE`'s equivalent OR-check are unaffected by the backfill (those rows still have `invoice = 1`), so they're left untouched — same for the `submitted_invoice = 0` filter clauses in `ExcelRepository.inc.php`/`Report.inc.php` (become permanently-true no-ops, harmless) and existing `audit_trails` rows logged with action `'Submitted Invoice'` (immutable history).

Root cause of the request isn't a code defect — it's a deliberate scope reduction. Confirmed with user: **full removal** (not just hiding nav), but DB columns/model getters/report filter clauses stay to avoid an unnecessary schema migration and to keep historical audit trail rows intact.

## Fix Plan

**1. Data migration** — new `sql/submitted_invoice_removal_backfill.sql`:
```sql
UPDATE rfq SET submitted_invoice = 0, submitted_invoice_date = NULL WHERE submitted_invoice = 1;
```
Run on prod (same pattern as other committed backfill scripts, e.g. `sql/provider_name_unescape_backfill.sql`).

**2. Remove the checkbox / save handler**
- `forms/quote/templates/status_checkbox.inc.php` — drop the "Submitted Invoice" checkbox branch (the `if ($cotizacion_recuperada->obtener_invoice())` block); Invoice becomes terminal, no checkbox renders after it.
- `scripts/quote/guardar_editar_cotizacion.php` — remove the `submitted_invoice` POST read (~line 27) and the `elseif (!obtener_submitted_invoice() && $submitted_invoice === 'si')` handler branch (~lines 175-185).

**3. Remove status display**
- `plantillas/quote/templates/status_title.inc.php` — drop `'Submitted Invoice'` from `$statusColors`, drop `'submitted_invoice' => REMOVE_SUBMITTED_INVOICE` from `$statusUrls`, drop the `if ($cotizacion_recuperada->obtener_submitted_invoice())` branch (the following `elseif ($invoice)` becomes the new first `if`).
- `forms/quote/templates/go_back_button.inc.php` — drop the `elseif ($cotizacion_recuperada->obtener_submitted_invoice())` branch.

**4. Remove navigation/routes**
- `plantillas/utilities/accounting_sidebar.inc.php` — drop the "Submitted Invoice" `<li>` nav item.
- `app/Bootstrap/routes.inc.php` — drop `REMOVE_SUBMITTED_INVOICE` and `SUBMITTED_INVOICE_QUOTES` constants.
- `index.php` — drop the `case 'remove_submitted_invoice':` block and the whole `case 'submitted_invoice':` outer switch block.
- `vistas/perfil.php` — drop the `case 'submitted_invoice_quotes':` block.

**5. Delete files**
- `scripts/quote/remove_submitted_invoice.php`
- `scripts/submitted_invoice/submitted_invoice_quotes_table.php` (+ resulting empty dir)
- `plantillas/submitted_invoice/submitted_invoice_quotes.inc.php` (+ resulting empty dir)

**6. JS** — `js/main.js` — remove the `#submitted_invoice_quotes_table` DataTable init block.

**7. Repository cleanup** — `app/Quote/RepositorioRfq.inc.php`:
- Remove `check_submitted_invoice_and_date()`, `remove_submitted_invoice()`, `getSubmittedInvoiceQuotes()`, `getTotalSubmittedInvoiceQuotesCount()`, `getTotalFilteredSubmittedInvoiceQuotesCount()`.
- Simplify the Invoice list queries by dropping the now-redundant `(submitted_invoice IS NULL OR submitted_invoice = 0) AND` clauses.

**Test:** update/add a case to an existing PHP test (or a small new one) asserting a backfilled quote (formerly `submitted_invoice=1`) shows up via the Invoice list repository methods and renders the "Invoice" badge, not "Submitted Invoice." Check `tests/php/` fixtures that set `submitted_invoice => 0` for anything relying on the now-removed repository methods (none found on initial scan — confirm during build).

**Explicitly out of scope** (no behavior change either way, left alone to limit blast radius):
- `rfq.submitted_invoice` / `submitted_invoice_date` DB columns and `Rfq` model properties/getters.
- `getSheetStatus()`'s and `PipelineMetricsRepository::STATUS_CASE`'s OR-checks including `submitted_invoice`.
- The `submitted_invoice = 0` filter clauses in `ExcelRepository.inc.php` / `Report.inc.php` (6 occurrences).
- Existing `audit_trails` rows with action `'Submitted Invoice'`.

## Open Questions

None blocking. If during `/build` any test fixture turns out to depend on the removed repository methods, adjust the fixture rather than keeping the method around.
