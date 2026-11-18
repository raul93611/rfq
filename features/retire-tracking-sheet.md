# Retire the Tracking Sheet

Replace the SharePoint "E-LOGIC BID PIPELINE" spreadsheet with the portal: add two optional bid fields, turn the Pipeline Table View into the tracking view, and remove the sheet sync from the app completely.

**Status:** `planned`

## Background

The business (Anass, Imane and Luis) asked to retire the tracking spreadsheet and keep everything on the portal. Every sheet column was reviewed on 2026-11-17 and confirmed by email on 2026-11-18:

| Sheet column | Outcome |
|---|---|
| Proposal | `rfq.id` (also serves as the ID) |
| Vehicle | `canal` (display mapping FBO→SAM, FedBid→Unison) |
| ID | Not a separate column. `rfq.id` is the identifier. |
| Title / Description | `rfq.name` (labelled **Description** in the forms) |
| Status | Derived: `Rfq::getSheetStatus()` / `PipelineMetricsRepository::STATUS_CASE` |
| Internal Due Date | `internal_due_date` |
| Client Due Date + Due Time | `end_date` (`MM/DD/YYYY HH:mm`) |
| Type | The `sources_sought` flag (shown through the Submitted–Sources Sought status) |
| Category | `type_of_bid` |
| Q&A / Q&A Deadline / Resumes / Site Visit | `qa` / `qa_deadline` / `resumes` / `site_visit` |
| Pricing | Covered by the quote statuses |
| Proposal Writer | `usuario_designado` |
| **Letter of Intent** | **New field** |
| **Submit By** | **New field** (called "Submission Method") |
| Technical Writer, Teaming Partner, Recruitment, Notes, Follow Up | Dropped |

## User Flow

1. **New Quote:** the user fills in the form as today. Next to Site Visit / Resumes there are two new optional dropdowns, **Letter of Intent** and **Submission Method**. Leaving them blank is fine. There is no longer a "Sync to sheet" checkbox.
2. **Edit quote → Information drawer:** the same two dropdowns appear next to Site Visit / Resumes, prefilled with the saved value or blank. Saving records an audit event for any change to the six bid fields.
3. **Pipeline Metrics → Table view:** the user sees every tracking column, including Description and the bid requirement fields. The table scrolls horizontally with Quote ID pinned. They can filter by Description text, Letter of Intent and Submission Method, together with the existing filters.
4. **Quote edit page:** the sheet sync badge and the Break Sync button and modal are gone.

Single role: any user who can create or edit quotes today. The Table view keeps its current access rules.

## UI Changes

### New Quote (`forms/quote/registro_cotizacion_vacio.inc.php`, `registro_cotizacion_validado.inc.php`)
- Add **Letter of Intent** `<select name="letter_of_intent">`: `—` (blank, default) / YES / NO / TBD.
- Add **Submission Method** `<select name="submission_method">`: `—` (blank, default) / PLATFORM / EMAIL / MAIL.
- Put both in the same row group as Site Visit / Resumes and match their markup. The validated form re-selects the posted value.
- **Remove** the "Sync to sheet" checkbox (`#sync_to_sheet`, `label.sync-check`) and the `isSyncable` toggle script in `plantillas/quote/nueva_cotizacion.inc.php`.

### Information drawer (`forms/quote/information.inc.php`)
- Add the same two selects (`form-control-sm`, matching Site Visit / Resumes), prefilled from the quote.

### Quote edit page (`plantillas/quote/editar_cotizacion.inc.php`)
- **Remove** the sheet sync badge (`$ss_status` / `$ss_at` block), `plantillas/quote/modals/break_sheet_sync_modal.inc.php`, and the `js/sheet_sync.js` include.
- `js/checklist_info_drawer.js`: remove the `result.data.sheetSync` / `window.ssRepaint` repaint branch.

### Quote list tables (`js/main.js`)
- **Remove** the `sheet_sync_status` column from the 4 DataTables (around lines 219, 316, 402, 488), together with the matching `<th>` in each list template and the `rfq.sheet_sync_status` select in `RepositorioRfq` (around lines 215, 318, 421, 524).

### Pipeline Table View (`plantillas/utilities/pipeline_metrics.inc.php`, `js/pipeline_table.js`, `css/estilos.css` `pt-*`)
- Column order (sheet order where the columns overlap): Quote ID · Created · Description · Channel · Code · Status · Internal Due Date · End Date · Submitted · Type of Bid · Type of Contract · Q&A · Q&A Deadline · Resumes · Letter of Intent · Site Visit · Submission Method · Designated User.
- `.pt-table-wrap` scrolls horizontally. The Quote ID column is `position: sticky; left: 0`, with a background so content doesn't show through.
- Description is clamped to 2 lines (`-webkit-line-clamp: 2`), with the full text in `title`. It is escaped (`rfq.name` is stored raw).
- Yes/no fields render as YES / NO. Q&A Deadline renders as `MM/DD/YYYY HH:mm`. Any unset value renders as `—`.
- Update `<colgroup>`, the empty-state `colspan` (currently hard-coded `11`), and the row template.
- New filters in `.pt-filters-grid`: **Description** (text input, `LIKE`, debounced like Code), **Letter of Intent** (Any / YES / NO / TBD), **Submission Method** (Any / PLATFORM / EMAIL / MAIL). Wire them into `EMPTY_FILTERS`, `countFilters()`, `buildQuery()`, `wireFilters()` and the Clear handler.

## Data Model Changes

Migration `sql/retire_tracking_sheet_migration.sql` (add it to the list in DEPLOYMENT.md):

```sql
ALTER TABLE rfq
  ADD COLUMN letter_of_intent  VARCHAR(10) NULL DEFAULT NULL,
  ADD COLUMN submission_method VARCHAR(10) NULL DEFAULT NULL,
  DROP COLUMN sync_to_sheet,
  DROP COLUMN sheet_row,
  DROP COLUMN sheet_sync_status,
  DROP COLUMN sheet_sync_at;
```

- **Do not drop `rfq.name`.** `sql/sheet_sync_migration.sql` added it, but it is the quote Description.
- Mirror the change in `sql/elogic.sql` if that file is kept in sync with the migrations.
- Allowed values live in one place (constants on `Rfq`, for example `Rfq::LETTER_OF_INTENT_OPTIONS = ['YES','NO','TBD']` and `Rfq::SUBMISSION_METHOD_OPTIONS = ['PLATFORM','EMAIL','MAIL']`). The server keeps a posted value only if it is in the list and stores anything else as `NULL`. The forms render from the same constants.
- `Rfq`: add properties, hydration and getters for the two fields. Remove `sheet_sync_status`, `sheet_sync_at`, `sheet_row`, `sync_to_sheet` and their getters/setters (`getSheetSyncStatus`, `getSheetSyncAt`, `getSheetRow`, `getSyncToSheet`).
- `RepositorioRfq::insertar_cotizacion`: add the two fields and remove `sync_to_sheet` from the INSERT and its bind. Also remove `sync_to_sheet` from the array around line 2618.
- The bid-fields update in `RepositorioRfq` (around line 840, used by `save_information.php`): add the two fields.
- `PipelineTableRepository`: select `rfq.name, rfq.qa, rfq.qa_deadline, rfq.resumes, rfq.site_visit, rfq.letter_of_intent, rfq.submission_method`, map them into the row payload, and add the three filters to `buildWhere()` as parameterized `LIKE` / `=` clauses.
- **Keep `Rfq::getSheetStatus()`.** It is the canonical status-bucket logic used by the Partner sync, metrics parity and more, not sheet-specific code. Do not rename it as part of this feature.

### Audit trail
- `AuditTrailRepository::information_events()` gains six comparisons using `create_audit_trail_modified`: **Letter of Intent, Submission Method, Q&A, Q&A Deadline, Resumes, Site Visit**. Values are shown as display strings (YES / NO / TBD / PLATFORM… / dates), and a blank value shows as `—`. `save_information.php` must pass the original values.
- **Keep** the `sheet_row_created`, `sheet_row_linked`, `sync_to_sheet` and `break_sync` entries in `js/audit_trail.js`, so past events still render. Delete only the PHP writers (`sync_to_sheet_audit_trail`, `sheet_row_created_audit_trail`, `sheet_row_linked_audit_trail`, `break_sync_audit_trail`).

## Sheet Sync Removal

Delete:
- `app/Quote/SheetSyncService.inc.php`, `app/Quote/SheetSyncRepository.inc.php`
- `app/Utilities/GraphApiClient.inc.php` (app-only client-credentials client, used only by the sheet sync)
- `scripts/quote/sync_to_sheet.php`, `scripts/quote/break_sheet_sync.php`, their `case`s in `index.php` (around lines 122–126) and `SYNC_TO_SHEET` / `BREAK_SHEET_SYNC` in `routes.inc.php`
- Their entries in the `index.php` autoload whitelist (`$subfolder`): `SheetSyncService`, `SheetSyncRepository`, `GraphApiClient`
- `js/sheet_sync.js`, `plantillas/quote/modals/break_sheet_sync_modal.inc.php`, and any `ss-*` / `sync-check` CSS that nothing else uses

Edit:
- `plantillas/quote/validacion_registro_cotizacion.inc.php`: remove `sync_to_sheet` from the quote array and the whole `createOrLink` block (around lines 145–162).
- `scripts/quote/save_information.php`: remove `updateNameAndResetSync` (keep the name update itself if it lives in the same call; check this), the sync block (around lines 76–115), and the `sheetSync` repaint payload (around lines 172–190).
- `scripts/quote/delete_quote.php`, `scripts/quote/destroy_quote.php`: remove the `deleteRow` / `shiftRowsAfterDelete` calls.
- `app/Partner/PartnerSyncService.inc.php`: in `isUntouched()`, remove the `sheet_row` check (around line 247). Also remove `'sync_to_sheet' => 0` from the inbound-create array (around line 410).

**Config:** keep `GRAPH_TENANT_ID`, `GRAPH_CLIENT_ID` and `GRAPH_CLIENT_SECRET`, which the notification mailbox OAuth uses (`NotificationMailboxRepository`, `scripts/admin/mailbox_connect.php`, `scripts/user/microsoft_*`). `GRAPH_SHAREPOINT_FILE_ID`, `GRAPH_SHAREPOINT_OWNER` and `GRAPH_SHEET_NAME` are no longer referenced. Update DEPLOYMENT.md (§ config table and § 5 Third-party services) to say so.

**Docs:** remove the "SharePoint Sheet Sync" section and inventory entry from CLAUDE.md. Remove the sheet-related lines in the Partner section ("not in the sheet"), and drop "Bid Pipeline Sync Controls" from the inventory.

## Tests

- **Delete:** `tests/php/sheet_sync_audit_test.php`, `tests/php/quote_name_escaping_test.php` (its subject is the sheet column-D escaping; first move any assertion that `rfq.name` is stored raw into another test), and the `sync_to_sheet` cases in `tests/php/quote_lifecycle_audit_test.php`.
- **Update:** `tests/specs/11-checklist-info-drawer.spec.js` and `15-partner-sync.spec.js` (remove the `#sync_to_sheet` uncheck step), and `16-sales-commission.spec.js` (remove `sync_to_sheet` from the fixture INSERT).
- **Add, PHP** (`tests/php/retire_tracking_sheet_test.php`):
  - Insert and update round-trip both fields.
  - An invalid value is stored as NULL.
  - Blank is stored as NULL.
  - `information_events` writes one audit row per changed bid field and none when nothing changed.
  - `PipelineTableRepository` returns the new columns, and each new filter narrows the results.
  - `isUntouched()` no longer depends on `sheet_row`.
- **Add, Playwright** (`tests/specs/17-retire-tracking-sheet.spec.js`):
  - New Quote with both fields set: the values show in the Information drawer and in the table view.
  - Change a value in the drawer: an audit row appears.
  - The table view's Submission Method filter works.
  - No "Sync to sheet" checkbox and no Break Sync button exist.
- Run the existing PHP + Playwright suites. Nothing may reference `SheetSync*` or `GraphApiClient`: `grep -rn "SheetSync\|GraphApiClient\|sync_to_sheet\|sheet_row\|sheet_sync" --include=*.php --include=*.js .` must return nothing outside `js/audit_trail.js` and the historical `sql/` migrations.

## External Dependencies

None added. One removed: Microsoft Graph app-only access to the SharePoint workbook. The Entra app's `Files.ReadWrite.All` (or equivalent) application permission could be revoked afterwards. Do not revoke the delegated mail permissions.

## Acceptance Criteria

- [ ] Letter of Intent (— / YES / NO / TBD) and Submission Method (— / PLATFORM / EMAIL / MAIL) are on New Quote and in the Information drawer, optional, and saved and prefilled correctly.
- [ ] Posting any other value stores NULL. Leaving them blank stores NULL. Neither blocks a save.
- [ ] Changing any of the six bid fields in the Information drawer writes an audit event with old → new values. A save without changes writes none.
- [ ] The Pipeline Table View shows all 18 columns in the specified order, scrolls horizontally with Quote ID pinned, and clamps Description to 2 lines with the full text on hover. Unset values show `—`.
- [ ] The Description, Letter of Intent and Submission Method filters work and combine (AND) with the existing filters, the filter count and Clear.
- [ ] The New Quote "Sync to sheet" checkbox, the edit-page sync badge, Break Sync, and the list sync-status columns are gone. Creating, editing, deleting and destroying a quote makes no Graph calls.
- [ ] The migration adds the two columns and drops the four sheet columns. `rfq.name` is untouched.
- [ ] Past sheet audit events still render in the unified audit trail.
- [ ] The partner sync still works. Removing an untouched quote hides it, whatever its former sheet state.
- [ ] Notification mailbox and mention emails still send (the `GRAPH_*` OAuth constants are intact).
- [ ] The existing test suites pass. The new PHP and Playwright tests pass.

## Out of Scope

- Importing or backfilling any data from the sheet. Past quotes start with both new fields blank.
- Sending the new fields in the partner sync payload (its field list is provisional).
- Showing the new fields in Advanced Search, the Daily Digest, the Quote Summary modal or the quote list tables.
- Excel/CSV export of the Table view.
- Re-quotes.
- Deleting the unused `GRAPH_SHAREPOINT_*` / `GRAPH_SHEET_NAME` constants from each environment's `config.inc.php` (harmless to leave; documented only).
- Revisiting the dropped sheet fields (Technical Writer, Teaming Partner, Recruitment, Pricing, Notes, Follow Up, Site Visit Optional/Mandatory).
- The known Q&A Deadline auto-fill-to-today behavior (accepted 2026-11-17).

## Decisions

- **Scope includes the tracking view:** the missing sheet columns are added to the Pipeline Table View, because the business asked for a matching table, not just fields.
- **Layout: horizontal scroll with Quote ID pinned:** chosen over a column picker. Users coming from a sheet expect every column visible, and a picker adds UI and state nobody asked for.
- **Description clamped to 2 lines with a hover tooltip:** keeps rows short despite long titles. (Claude's call)
- **Keep the Code column:** it's the solicitation number people search by, and a Code filter already exists.
- **Filters: Description search, Letter of Intent and Submission Method only:** Description replaces Ctrl+F in the sheet. Q&A/Resumes/Site Visit filters are low value and easy to add later.
- **Drop the 4 sheet columns (option A):** the sheet is retired and the app never deleted sheet rows, so keeping them protects nothing. Audit history lives in the audit table.
- **Audit all six bid fields (option B):** with the sheet gone, the portal is the only record of who changed a requirement.
- **Values: LOI = YES / NO / TBD, Submission Method = PLATFORM / EMAIL / MAIL:** confirmed by the business on 2026-11-18. "EMAIL" without the hyphen, as they wrote it.
- **Storage: `VARCHAR(10) NULL` with a server-side whitelist, options in `Rfq` constants:** the literal labels are the values, so no lookup table is needed. Invalid input becomes NULL, so a save never fails. (Claude's call)
- **Unset values render as `—`, and filter dropdowns list only the values plus "Any":** consistent with the existing table conventions. (Claude's call)
- **Column order follows the sheet where the columns overlap:** familiar to people moving off the sheet. (Claude's call)
- **Keep `Rfq::getSheetStatus()` and the historical audit-trail renderers:** the first is core status logic, and the second keeps past events readable.
- **Keep the `GRAPH_TENANT/CLIENT/SECRET` constants:** the notification mailbox OAuth depends on them. Only the three SharePoint-specific constants become unused.
