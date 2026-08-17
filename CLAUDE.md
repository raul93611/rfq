# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Feature Inventory

All built. Detail for most lives in the matching `###` section below.

Quote Inline Editing · SharePoint Sheet Sync · Comment Mentions & Notifications · Bid Requirement Fields (Site Visit/Q&A Deadline/Resumes) · Bid Pipeline Sync Controls · Bid Pipeline Metrics Dashboard · Pipeline Table View · 3-Year Annual Awards Comparison · Quote Lifecycle Audit Events · Write-Once Sheet Sync · Advanced Quote Search · Commercial Moving bid type + 50/50 payment term · Shared Notification Mailbox · Daily RFQ Digest Email · Quote Checklist & Info Drawer · Documents Drawer Tab + Custom File Widget · Import Items Enhancements (template download, append/replace mode, provider import) · Items & Services Table Redesign · Internal Due Date Table Filter + Required Field · Pipeline Status by User + Wider Drill-down Drawer · End Date Required + Pipeline Table Due-Date Columns

## Environment

PHP app on a LAMP stack inside Docker (`docker-compose-lamp`), served at `http://localhost/rfq/`.

**Setup (from README):** create a `/tmp` dir at project root; disable MySQL strict mode (`sql_mode= ""` under `[mysqld]` in `my.cnf`, restart MySQL); on production enable the `xmlwriter` PHP module.

**Install:** `composer install`

**Database:** `elogicnewdb` on `database:3306` (Docker service). Schema in [sql/elogic.sql](sql/elogic.sql). Credentials/server URL hardcoded in [app/Bootstrap/config.inc.php](app/Bootstrap/config.inc.php) (`SERVIDOR` constant).

**Generate users:** visit `/genera_usuario`.

**Tests:** PHP integration tests in `tests/php/` (`docker exec lamp-php84 php /var/www/html/rfq/tests/php/<file>`), Node unit tests in `tests/js/` (`node --test`), Playwright E2E in `tests/specs/` (`cd tests && PW_CHANNEL=chrome npx playwright test`). No lint commands.

## Architecture

Custom PHP MVC-like app, no framework.

**Request lifecycle:** every request hits [index.php](index.php), which autoloads `app/{Domain}/{ClassName}.inc.php` and matches the URL via a large `switch` to either a **script** in `scripts/` (form/AJAX handlers, return JSON/HTML fragments) or a **view** in `vistas/` (full pages, include partials from `plantillas/`).

**Directories:** `app/` domain classes (`{Entity}.inc.php` plain object + `Repositorio{Entity}.inc.php`/`{Entity}Repository.inc.php` static PDO methods; `Bootstrap/` has `Conexion` singleton, `ControlSesion`, `Redireccion`, `config.inc.php`, `routes.inc.php`; `Utilities/` has `PDFGenerator` (mPDF), `ExcelRepository` (PhpSpreadsheet), `ProposalRepository`, `Email`, `TeamsIntegration`, `Input`) · `scripts/` action handlers by domain · `vistas/` entry points (`home.php` login, `perfil.php` dashboard) · `plantillas/` template partials · `js/` per-module vanilla JS/jQuery · `css/estilos.css` single stylesheet.

Local CSS/JS must go through `asset_url('js/file.js')` (`routes.inc.php`) — appends `?v=<filemtime>` so caches drop stale copies after deploy. Don't use bare `RUTA_CSS`/`RUTA_JS`.

**DB access** always through the `Conexion` singleton:
```php
Conexion::abrir_conexion();
$result = SomeRepository::some_method(Conexion::obtener_conexion(), $param);
Conexion::cerrar_conexion();
```

**Domains:** Quote (RFQ lifecycle), ReQuote (vendor re-quoting), Fulfillment, Tracking, Invoice, SalesCommission, Projection, Task, Personnel, Service, Provider, PaymentTerm, Room, TypeOfBid, TypeOfContract, TypeOfProject.

**Routes:** all URL constants in [app/Bootstrap/routes.inc.php](app/Bootstrap/routes.inc.php); new routes need a constant there plus a `case` in `index.php`.

**Quote status flow:** `Rfq` progresses Created → Completed → Submitted → Award → Fulfillment → Invoice. `comments` encodes special statuses (No Bid, Cancelled, Not submitted). `isEnabledToFulfillment()`/`isEnabledToInvoice()` on `Rfq` enforce transition prerequisites.

### SharePoint Sheet Sync — write-once create-or-link

Non-destructive to the sheet: may create a missing row but never overwrites/deletes an existing one. Every sync path routes through `SheetSyncService::createOrLink($quote, $designatedUsername)` → `['row','outcome']`:
- Presence decided by **scanning column A** (PROPOSAL = quote id), never the stored `sheet_row`. Found → pointer only, write nothing, `outcome='linked'`. Absent → append a fresh row (app columns filled, human columns blank), `outcome='created'`. No Graph secret → `outcome=null`.
- Persist only on **establishment** (created, or linked to a row not already pointed at, or prior status ≠ `synced`): status update + audit event. A no-op edit of an already-linked quote makes zero Graph writes and no audit row. Old overwrite/delete sync paths are retired; quote delete never touches the sheet.
- **`sync_to_sheet` flag is the sole auto-sync gate** (not bid type, not child/master-link). Creation checkbox sets it (JS smart-defaults from a syncable bid-type list, user can override). `Sync to Sheet` btn create-or-links + flag=1; `Break Sync` → flag=0 (keeps `sheet_row`); `copyRfq` → 0.
- **Column ownership:** app owns A,B,C,D,G,H,J,L,M,N,Q,T; **E,F,I,K,O,P,R,S are human-owned**, blanked only on a brand-new row — existing rows never touched.

### Unified Audit Trail

Quote, re-quote, and fulfillment each write to their own table (`audit_trails`, `re_quote_audit_trails`, `fulfillment_audit_trails` — all have `action_type`, `id_user`) but surface through one modal + endpoint per page.

Action types: `status_change`, `field_modified`, `item_modified`, `item_created`, `item_deleted`, `invoice_created/updated/deleted`, `document_updated`, `net_30`, `quote_created` (Status group), and Sync group (quote only): `sheet_row_created`/`sheet_row_linked`, `break_sync`, legacy `sync_to_sheet`. Logged **once on establishment** — a no-op sync logs nothing.

`POST quote/load_unified_audit_trail` queries all three (re-quote joined via `id_rfq`), merges, sorts by `created_date DESC`. Frontend: `js/audit_trail.js` (self-contained IIFE, trigger buttons need `data-id`). Filter tabs: All/Status/Edits/Items/Invoices/Sync — Sync rows get a per-outcome color+glyph, 3+ consecutive sync events collapse into one "N automatic syncs" run. `at-*` CSS namespace.

### Comment Mentions & Notifications

`sql/notifications_migration.sql` adds MS token columns to `usuarios` + a `notifications` table. `js/mentions.js` (@mention autocomplete on `#comment_rfq`), `NotificationRepository::parseMentions()`, an SSE stream (`notifications_stream.php`, polls every 3s) driving the navbar bell, My Account (profile + MS OAuth connect) and Notifications pages. `guardar_comment.php` parses @mentions → inserts notifications → emails via the Shared Notification Mailbox. `nf-*`/`ac-*`/`cm-*` CSS namespaces.

**Routes:** `perfil/account`, `perfil/notifications`, `user/microsoft/{connect,callback,disconnect}`, `user/account/{update_profile,update_password}`, `quote/notifications/{stream,list,mark_read,users_for_mention}`.

### Bid Pipeline Metrics Dashboard

`perfil/reports/pipeline_metrics` — ApexCharts report reproducing the SharePoint METRICS 2026 tab. **All aggregation is in SQL**, never by loading Rfq objects.

`rfq.created_at` (added by `sql/quote_created_at_migration.sql`, auto-stamped on insert) is the cohort date, replacing hand-typed `issue_date` whose unparseable values silently dropped rows. Local keeps a backfill from `issue_date` for history; **on prod also run `sql/quote_created_at_revert_backfill.sql`** to NULL those out so prod tracks forward from the migration only.

`PipelineMetricsRepository::STATUS_CASE` is a SQL `CASE` mirroring `Rfq::getSheetStatus()` exactly — keep the two in sync (10 buckets: tbd, bid, no_bid, submitted, submitted_ss, award, no_award_pricing, no_award_technical, cancelled, not_submitted).

**Win/Loss gotcha:** denominator = `submitted` + `award` + lost (`no_award_*`); sources-sought excluded. **Dollar-value gotcha:** every money figure = product total + services subtotal via `SERVICES_JOIN`/`VALUE_EXPR`, never `rfq.total_price` alone (count-only aggregations skip the join).

Two listing pages mirror this: Sources Sought (`quote/sources_sought`) and No Award (`quote/no_award`, with a Reason column). Tests: `tests/php/pipeline_metrics_test.php`, `tests/specs/09-pipeline-metrics.spec.js`.

**Status by user** — full-width card under Status Distribution: one horizontal stacked bar per designated user with ≥1 quote in the period (10-bucket colors, alphabetical, `PipelineMetricsRepository::getStatusByUser()` — INNER JOIN to `usuarios` so a dangling/unassigned `usuario_designado` drops out naturally, no LEFT JOIN needed since the column is `NOT NULL`). Percent mode uses ApexCharts native `stackType:'100%'`. Drill-down spec type `byUser` (`user` + `key`) needs `usuario_designado` added to `getDrillDown()`'s *inner* subquery SELECT only — outer SELECT/columns unchanged. Export sheet added the same way as other chart keys. `.pm-drawer` is `50vw` (was fixed `430px`) for every drill-down on the page, not just this chart.

### Pipeline Table View

`Charts | Table` toggle on `perfil/reports/pipeline_metrics` (`#pm-view`) swaps to a filterable, server-paginated (25/row) quote table over the same `created_at` cohort as the charts (`PipelineTableRepository`, `js/pipeline_table.js`). Clicking a row opens a Quote Summary modal (real attached files from `quote/get_quote_files/<id>`, not the `rfq.file_document` checklist field; quick-comment reuses `#comment_rfq`/`mentions.js`).

**No Quote Watchers** — that subsystem (per-quote watch subscriptions + notification fan-out) was built alongside this Table View, then removed; don't reintroduce a `watched` field/join without deliberately re-adding it. Test: `tests/php/pipeline_table_test.php`.

**Internal Due Date filter** — preset dropdown (Today/Tomorrow/Next 7 days/Overdue), purely date-based via MySQL `CURDATE()` in `PipelineTableRepository::dueDateClause()` — no status-aware logic, no custom range, AND-combines with every other filter and the period. Also **required everywhere it's entered**: New Quote form (`required` + `ValidadorCotizacionRegistro`/`ValidadorCotizacion::validar_internal_due_date()`, "Must be fill out." pattern) and the Information drawer tab (`save_information.php` rejects a blank value pre-DB-write via the drawer's existing error banner — no client-side validation). Tests: `tests/php/pipeline_table_test.php`, `tests/php/internal_due_date_test.php`.

**End Date filter + columns** — same 4-preset pattern as Internal Due Date, in `PipelineTableRepository::endDateClause()`. `rfq.end_date` is `VARCHAR` (`MM/DD/YYYY HH:mm`), not a DATE column: date math needs `STR_TO_DATE(rfq.end_date, "%m/%d/%Y %H:%i")`, and since the column is `NOT NULL` with no default, an unset value is `''` rather than SQL NULL — `STR_TO_DATE('', ...)` parses that as the zero-date `0000-00-00` (which satisfies `< CURDATE()`), so the clause wraps it in `NULLIF(rfq.end_date, '')` first or every blank row shows as permanently "Overdue". Also **required in the Information drawer** now, mirroring Internal Due Date exactly (blank-check guard in `save_information.php`, no client-side validation). Table columns `Internal Due Date`/`End Date` sit right after `Created`; End Date renders the raw stored string (not reformatted). New Quote form's `#end_date` picker needed its own `autoUpdateInput: false` override in `js/main.js` (inline, not the shared `dateTimeOptions` object `#qa_deadline` also uses) — without it, daterangepicker silently fills today's date/time on init. Tests: `tests/php/pipeline_table_test.php`, `tests/specs/09-pipeline-metrics.spec.js`, `tests/specs/11-checklist-info-drawer.spec.js`, `tests/specs/02-quote-list.spec.js`.

### Charts Tab — Annual Awards (3-year)

`perfil/charts` (Chart.js, not the ApexCharts pipeline page). Two Annual Awards cards compare a **rolling** window (current year + 2 prior) as grouped monthly columns, from `RepositorioRfq::getAnnualAwardsDataByMonthForYears()`. Per-user Completed/Awards cards hide users with no activity in either month (`activeUserSeries()` in `js/main_charts.js`, unit-tested).

**Deliberately differs from Pipeline Metrics:** counts awards by **award date** (`fecha_award`, "when we won" — leadership decision), not issue date — the two pages answer different questions and don't need to reconcile. Awards without `fecha_award` don't appear. Test: `tests/php/annual_awards_test.php`.

### Advanced Quote Search

`perfil/search_quotes` **Advanced** toggle expands a filter panel (status multi-select over the 10 pipeline buckets, designated user, type of bid/contract, date range with field selector, price range, client, state — AND-combined, keyword optional) and adds a Status pill column. Off = identical to basic. Empty advanced search = all non-deleted quotes; inverted ranges = empty state, no error.

Separate backend pair (`getAdvancedSearchedQuotes`/`...Count`) keeps basic-mode queries untouched; derived status reuses `PipelineMetricsRepository::STATUS_CASE` verbatim so filter counts match the pipeline chart. `js/searchQuotes.js` swaps DataTable column sets on toggle. Tests: `tests/php/advanced_search_test.php`, `tests/specs/10-advanced-search.spec.js` (use `PW_CHANNEL=chrome` where no bundled Chromium).

### Commercial Moving + 50/50 Payment Term

New bid type "Commercial Moving" + a third payment term `50% Upfront / 50% on Completion`, stored as the literal string in `rfq.payment_terms`/`services_payment_term` (no schema change; split computed on the fly). Not in `SYNCABLE_BID_TYPES`, so pipeline sync auto-defaults off.

**No calc change:** 50/50 is a schedule, ×1 like Net 30 — every calc/PDF path already special-cased only `Net 30/CC`, so non-CC terms were already ×1. Items/services payment-term controls are a 3-option select; quote-wide **all-or-nothing** mirroring in `js/quote.js`/`js/reQuote.js` (50/50 on either sets both; Net 30/Net 30-CC stay independent). Split shown via `js/payment_split.js` (bottom bar totals + two PDF rows under TOTAL); re-quote internal cost sheet gets no split block. Tests: `tests/js/payment_split.test.js`, `tests/php/commercial_moving_test.php`.

### Shared Notification Mailbox

One admin-connected MS mailbox sends **all** system notification emails (mentions + Daily RFQ Digest), replacing the per-user delegated connection whose emails silently failed when the actor hadn't connected. `NotificationMailboxRepository` (refresh-on-expiry token storage); `NotificationEmail::send()` (own branded template, e.g. mentions) and `::sendCustom()` (caller-supplied HTML, e.g. the digest) both funnel through the same Graph `/me/sendMail` call and the same silent-no-op-when-disconnected gate.

**OAuth reuse:** admin Connect sets `$_SESSION['ms_oauth_target']='mailbox'`, and `microsoft_callback.php` branches on that flag to store in `notification_mailbox` instead of the user row — no new Azure app needed. Admin-only UI at `perfil/admin/settings`. Not connected → email is a safe no-op, in-app notifications unaffected. Test: `tests/php/shared_mailbox_test.php`.

### Daily RFQ Digest Email

`scripts/cron/daily_digest.php` — droplet crontab, 6:00am America/New_York (crontab line in the file's docblock); no in-app UI or config screen.

One HTML email (`DigestEmailTemplate`) to every active Admin-role user (`RepositorioUsuario::getActiveAdminUsers`, cross-checked with `Usuario::is_admin()`) via the Shared Notification Mailbox. Four sections, always shown even when empty: Created/Submitted/Awarded scope to the **previous calendar day**, Due Today scopes to **today's** Internal Due Date (`DigestRepository`, all excluding `deleted=1`). `date_default_timezone_set('America/New_York')` is set explicitly — never rely on server/container default (droplets commonly default to UTC).

`digest_send_log` (unique per date) dedupes same-day re-triggers regardless of mailbox connectivity — written after every completed run, not just successful sends. Test: `tests/php/daily_digest_test.php`.

### Quote Checklist & Info Drawer

Contract-info cards on `perfil/quote/editar_cotizacion/{id}` are denser (same fields, less padding); the old Checklist/Information nav buttons are now two `.qed-status-card` status-strip cards that open a slide-over drawer (`plantillas/quote/modals/checklist_information_drawer.inc.php`, `js/checklist_info_drawer.js`, `qed-*` CSS namespace scoped to `.qed-status-row, .qed-drawer, .qed-drawer-scrim, .qed-confirm-overlay`). Both tabs' `forms/quote/checklist.inc.php`/`information.inc.php` stay mounted in the DOM the whole time (CSS-hidden, not removed) so switching tabs never loses edits — required prefixing checklist.inc.php's colliding ids (`cl_` prefix) since both forms render simultaneously.

**Checklist completeness** is `Rfq::getChecklistCompletionCount()` (10 fields incl. File Document/Accounting checkbox groups) — Set Aside/GSA only count once moved off their default dropdown option (`'Full & Open'`/`'na'`), not merely non-empty.

**Save endpoints** (`scripts/quote/save_checklist.php`/`save_information.php`) always return JSON now (no redirect). Client must manually append `save_checklist=1`/`save_information=1` to the AJAX payload since `FormData`/`.serialize()` never include the submitting button's `name`, which is what these scripts gate on.

**Old `/quote/checklist/{id}` and `/quote/information/{id}` URLs** redirect via `Redireccion::redirigir1` (client-side `<script>`), not `redirigir`/`header()` — `vistas/perfil.php` echoes the page shell before reaching those routing cases, so a header-based redirect fails silently ("headers already sent"). `editar_cotizacion.inc.php`'s not-found guard uses the same pattern for the same reason.

Test: `tests/php/checklist_info_drawer_test.php`, `tests/specs/11-checklist-info-drawer.spec.js`.

### Documents Drawer Tab + Custom File Widget

Documents moved off the quote edit page into a third `qed-drawer` tab (`data-tab="documents"`), matching the Checklist/Information status-card → drawer pattern. kartik-v bootstrap-fileinput is fully retired and replaced by `js/document_widget.js` (`doc-*` CSS namespace, same bare-tokens-scoped-to-root pattern as `qed-*`, self-contained with or without a `qed-drawer` ancestor).

`DocumentWidget.init(root, opts)` runs in two modes from one implementation: **`immediate`** (edit page, in-drawer) loads existing files via `get_quote_files`, uploads each dropped file on its own XHR (live progress, independent success/failure), deletes via an inline confirm row (not the site-wide modal); live count feeds the drawer's tab badge + status card via `onCountChange`. **`deferred`** (create-quote page, no `id_rfq` yet) stages dropped files client-side, mirrored onto the real `name="documentos[]"` input via `new DataTransfer()` so the page's native multipart submit is untouched.

Backend untouched (`get_quote_files.php`/`load_img.php`/`delete_document.php`/`download_all.php` reused as-is; upload field stays `archivos_ejemplo[]`). `document_widget.js` must load before `js/checklist_info_drawer.js` (which calls `DocumentWidget.init` synchronously) — tagged in the `<head>` (`documento_declaracion.inc.php`) rather than alongside `main.js` because of `vistas/perfil.php`'s include order.

Test: `tests/php/documents_drawer_test.php`, `tests/specs/12-documents-drawer.spec.js`.

### Import Items Enhancements

Import Items modal (Items table toolbar → Actions → Import items) gained a template download, an Append/Replace mode, and provider import — `scripts/quote/import_items.php` + `scripts/quote/import_items_template.php`.

- **Template**: header-only `.xlsx`, existing 10 item columns at positions 0-9 (Room at 9), plus 5 new Provider Name/Price pairs at 10-19 (20 columns total). `processCsv`/`processExcel` read the pairs via `parseProviderPairs()`: skipped when Name is blank; Price defaults to 0 when Name present but Price blank/non-numeric.
- **`import_mode`** (`append` default | `replace`). Replace deletes all existing items — and each item's subitems first — before inserting the file's rows; Rooms are left in place. Whole file is parsed before any deletion, so a malformed upload can't partially wipe a quote. **Gotcha:** `RepositorioItem::delete_item()` throws an FK-violation on any item with a subitem — always delete subitems first.
- Modal UI reuses the `iem-*` shell with new `ii-*` controls: single-file dropzone, Add/Replace radio group, a collapse-warning shown only for Replace when `obtener_fullfillment()` is true. JS: `js/import_items_modal.js`.

Test: `tests/php/import_items_test.php`, `tests/specs/13-import-items.spec.js`.

### Items & Services Table Redesign

Visual/structural redesign of Items and Services on `perfil/quote/editar_cotizacion/{id}` (Quote Edit only — Re-Quote/Fulfillment untouched). `it-*` CSS namespace. Rows stay real `<table>`/`<tr>`/`<td>` so `js/quote.js`'s `td:eq()`-based calc loop keeps working — only column count changed (13→12: Website folded into the E-Logic Proposal description cell, shifting `td:eq()` indices in `calcQuoteTable()`/`populateCalcArrays()`).

- **Kebab menu**: `RepositorioItem::renderKebab()` wraps the same edit/delete buttons (same classes/handlers) inside a `.it-menu` popover — `js/items_table.js` only toggles open state. Playwright specs call `tests/helpers/kebab.js`'s `openKebabFor()` first. `.iem-edit-provider`/`-subitem` stay directly visible (not behind the kebab).
- **Descriptions**: server-side truncation is gone — `RepositorioItem::renderDescBlock()` sends full text; CSS (`grid-template-rows: 0fr/1fr`) does condensed-by-default, `[data-toggle-desc]` expands in place.
- **Providers**: `RepositorioItem::renderProvidersList()` sorts cheapest-first, highlights ties at lowest price (mirrors existing `provider_menor` behavior). Shared by items/subitems via `$isSubitem` flag.
- **Subitems**: `.it-row.is-sub` + legacy `fila_subitem` class (kept for `js/quote.js`'s calc branch). `[data-toggle-subitems]` caret shows/hides via `hidden` — expanded by default.
- **Empty state**: moved inside `RepositorioItem::escribir_items()` — `.it-card` chrome always renders, only the table area swaps for `.it-empty` (dual-classed `section-empty-state`).
- Dropped from the design: per-row provider name under Best Unit Cost, and items/subitems/quote counts in the card subtitle — both needed JS calc-loop changes or N+1 queries for a cosmetic label.

Test: `tests/php/commercial_moving_test.php`, `tests/js/payment_split.test.js`, Playwright specs `03`–`08`/`13`.

### Items/Services Table Min-Width + Provider Name Escaping (fixes)

`.it-table`/`.it-table.is-services` needed a `min-width` (1360px/750px — fixed-column budget plus a per-description-column floor) so the pre-existing `overflow-x: auto` wrapper actually engages on narrow (~13-14") laptop screens instead of squeezing the auto description columns toward zero.

Provider names double-HTML-escaped on display (`FILTER_SANITIZE_FULL_SPECIAL_CHARS` at write time in `guardar_add_provider.php`/`guardar_edit_provider.php`/`guardar_add_provider_subitem.php`/`guardar_edit_provider_subitem.php`, then `RepositorioItem::renderProvidersList()` escaping again at render) — now stored raw, escaped only at render. `sql/provider_name_unescape_backfill.sql` (idempotent) decodes already-double-escaped rows; **run on production**. Re-quote provider paths (`save_re_quote_provider.php` etc.) were checked and intentionally left alone — their render sites have no output-side escaping at all, so matching this fix there would trade a cosmetic bug for a stored-XSS hole. Tests: `tests/specs/03-quote-editing-items.spec.js`, `tests/specs/06-services.spec.js`, `tests/php/provider_name_escaping_test.php`, `tests/specs/04-quote-editing-providers.spec.js`.
