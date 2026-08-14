# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Feature Inventory

All built. Detail for most of these lives in the matching `###` section below.

Quote Inline Editing · SharePoint Sheet Sync · Comment Mentions & Notifications · Bid Requirement Fields (Site Visit/Q&A Deadline/Resumes) · Bid Pipeline Sync Controls · Bid Pipeline Metrics Dashboard · Pipeline Table View · 3-Year Annual Awards Comparison · Quote Lifecycle Audit Events · Write-Once Sheet Sync · Advanced Quote Search · Commercial Moving bid type + 50/50 payment term · Shared Notification Mailbox · Daily RFQ Digest Email · Quote Checklist & Info Drawer · Documents Drawer Tab + Custom File Widget · Import Items Enhancements (template download, append/replace mode, provider import) · Items & Services Table Redesign · Internal Due Date Table Filter + Required Field · Pipeline Status by User + Wider Drill-down Drawer

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

Strictly non-destructive to the sheet: may create a missing pipeline row but never overwrites/deletes an existing one. Every sync path routes through `SheetSyncService::createOrLink($quote, $designatedUsername)` → `['row','outcome']`:
- Presence decided by **scanning column A** (PROPOSAL = quote id), never the stored `sheet_row`. Found → that row becomes the pointer, write nothing, `outcome='linked'`. Absent → append a fresh row (app columns filled, human columns blank), `outcome='created'`. No Graph secret → `outcome=null`.
- Persist only on **establishment** (created, or linked to a row not already pointed at, or prior status ≠ `synced`): status update + matching audit event. A no-op edit of an already-linked quote makes zero Graph writes and no audit row. Old overwrite/delete sync paths are retired/unused; quote delete never touches the sheet.
- **Per-quote `sync_to_sheet` flag is the sole auto-sync gate** (not bid type, not child/master-link). Creation checkbox sets it (JS smart-defaults on from a syncable bid-type list, user can override). `Sync to Sheet` btn create-or-links + flag=1; `Break Sync` → flag=0 (keeps `sheet_row`); `copyRfq` → 0.
- **Column ownership:** app owns A,B,C,D,G,H,J,L,M,N,Q,T; **E,F,I,K,O,P,R,S are human-owned**, written blank only on a brand-new row — existing rows never touched.

### Unified Audit Trail

Quote, re-quote, and fulfillment each write to their own table (`audit_trails`, `re_quote_audit_trails`, `fulfillment_audit_trails` — all have `action_type`, `id_user`) but are surfaced through one modal + endpoint per page.

Action types: `status_change`, `field_modified`, `item_modified`, `item_created`, `item_deleted`, `invoice_created/updated/deleted`, `document_updated`, `net_30`, `quote_created` (Status group), and Sync group (quote only): `sheet_row_created`/`sheet_row_linked`, `break_sync`, legacy `sync_to_sheet`. Logged **once on establishment** — a no-op sync logs nothing.

Endpoint `POST quote/load_unified_audit_trail` queries all three (re-quote joined via `id_rfq`), merges, sorts by `created_date DESC`. Frontend: `js/audit_trail.js` (self-contained IIFE, trigger buttons need `data-id`). Filter tabs: All/Status/Edits/Items/Invoices/Sync — Sync rows get a per-outcome color+glyph, and 3+ consecutive sync events collapse into one "N automatic syncs" run. `at-*` CSS namespace.

### Comment Mentions & Notifications

`sql/notifications_migration.sql` adds MS token columns to `usuarios` + a `notifications` table. `js/mentions.js` (@mention autocomplete on `#comment_rfq`), `NotificationRepository::parseMentions()`, an SSE stream (`notifications_stream.php`, polls every 3s) driving the navbar bell, My Account (profile + MS OAuth connect) and Notifications pages. `guardar_comment.php` parses @mentions → inserts notifications → emails via the Shared Notification Mailbox. `nf-*`/`ac-*`/`cm-*` CSS namespaces.

**Routes:** `perfil/account`, `perfil/notifications`, `user/microsoft/{connect,callback,disconnect}`, `user/account/{update_profile,update_password}`, `quote/notifications/{stream,list,mark_read,users_for_mention}`.

### Bid Pipeline Metrics Dashboard

`perfil/reports/pipeline_metrics` — ApexCharts report reproducing the SharePoint METRICS 2026 tab. **All aggregation is in SQL**, never by loading Rfq objects.

`rfq.created_at` (added by `sql/quote_created_at_migration.sql`, auto-stamped on insert) is the cohort date, replacing the hand-typed `issue_date` whose unparseable values silently dropped rows. Local keeps a backfill from `issue_date` so history still shows; **on prod also run `sql/quote_created_at_revert_backfill.sql`** to NULL those out, so prod tracks forward from the migration only.

`PipelineMetricsRepository::STATUS_CASE` is a SQL `CASE` mirroring `Rfq::getSheetStatus()` exactly — keep the two in sync (10 buckets: tbd, bid, no_bid, submitted, submitted_ss, award, no_award_pricing, no_award_technical, cancelled, not_submitted).

**Win/Loss gotcha:** denominator = `submitted` + `award` + lost (`no_award_*`); sources-sought is excluded. **Dollar-value gotcha:** every money figure = product total + services subtotal via `SERVICES_JOIN`/`VALUE_EXPR`, never `rfq.total_price` alone (count-only aggregations skip the join).

Two listing pages mirror this: Sources Sought (`quote/sources_sought`) and No Award (`quote/no_award`, with a Reason column). Tests: `tests/php/pipeline_metrics_test.php`, `tests/specs/09-pipeline-metrics.spec.js`.

**Status by user** — full-width card directly under Status Distribution: one horizontal stacked bar per designated user with ≥1 quote in the period (10-bucket colors, alphabetical, `PipelineMetricsRepository::getStatusByUser()` — INNER JOIN to `usuarios` so a dangling/unassigned `usuario_designado` naturally drops out, no LEFT JOIN needed since the column is `NOT NULL`). Percent mode uses ApexCharts native `stackType:'100%'` (per-user-normalized). Drill-down spec type `byUser` (`user` + `key`) needs `usuario_designado` added to `getDrillDown()`'s *inner* subquery SELECT list only — the outer SELECT/columns stay unchanged, WHERE can reference any inner-subquery column. Export sheet added the same way as the other chart keys. `.pm-drawer` is `50vw` (was a fixed `430px`) for every drill-down on the page, not just this chart.

### Pipeline Table View

`Charts | Table` toggle on `perfil/reports/pipeline_metrics` (`#pm-view`) swaps to a filterable, server-paginated (25/row) quote table over the same `created_at` cohort as the charts (`PipelineTableRepository`, `js/pipeline_table.js`). Clicking a row opens a Quote Summary modal (real attached files from `quote/get_quote_files/<id>`, not the `rfq.file_document` checklist field; quick-comment reuses `#comment_rfq`/`mentions.js`).

**No Quote Watchers** — that subsystem (per-quote watch subscriptions + notification fan-out) was built alongside this Table View, then removed; don't reintroduce a `watched` field/join without deliberately re-adding that whole feature. Test: `tests/php/pipeline_table_test.php`.

**Internal Due Date filter** — 7th filter field (after Designated User), preset dropdown only (Today/Tomorrow/Next 7 days/Overdue), purely date-based via MySQL `CURDATE()` in `PipelineTableRepository::dueDateClause()` — no status-aware "exclude Awarded" logic, no custom range. AND-combines with every other filter and the period exactly like the rest. Also **required now everywhere it's entered**: New Quote form (`required` + `ValidadorCotizacionRegistro`/`ValidadorCotizacion::validar_internal_due_date()`, same "Must be fill out." pattern as End Date, no cross-field constraint) and the Information drawer tab (`save_information.php` rejects a blank value pre-DB-write, surfaced through the drawer's existing error banner — no new client-side validation). Tests: `tests/php/pipeline_table_test.php`, `tests/php/internal_due_date_test.php`.

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

One admin-connected MS mailbox sends **all** system notification emails (mentions + the Daily RFQ Digest), replacing the per-user delegated connection whose emails silently failed when the actor hadn't connected. `NotificationMailboxRepository` (refresh-on-expiry token storage); `NotificationEmail::send()` (own branded template, e.g. mentions) and `NotificationEmail::sendCustom()` (caller-supplied HTML, e.g. the digest) both funnel through the same Graph `/me/sendMail` call and the same silent-no-op-when-disconnected gate.

**OAuth reuse:** admin Connect sets `$_SESSION['ms_oauth_target']='mailbox'`, and the existing `microsoft_callback.php` branches on that flag to store in `notification_mailbox` instead of the user row — no new Azure app needed. Admin-only UI at `perfil/admin/settings`. Not connected → email is a safe no-op, in-app notifications unaffected. Test: `tests/php/shared_mailbox_test.php`.

### Daily RFQ Digest Email

`scripts/cron/daily_digest.php` — droplet crontab, 6:00am America/New_York (crontab line in the file's docblock); no in-app UI or config screen.

One HTML email (`DigestEmailTemplate`) to every active Admin-role user (`RepositorioUsuario::getActiveAdminUsers`, cross-checked with `Usuario::is_admin()`) via the Shared Notification Mailbox. Four sections, always shown even when empty: Created/Submitted/Awarded scope to the **previous calendar day**, Due Today scopes to **today's** Internal Due Date (`DigestRepository`, all excluding `deleted=1`). `date_default_timezone_set('America/New_York')` is set explicitly — never rely on server/container default (droplets commonly default to UTC).

`digest_send_log` (unique per date) dedupes same-day re-triggers regardless of mailbox connectivity — written after every completed run, not just successful sends. Test: `tests/php/daily_digest_test.php`.

### Quote Checklist & Info Drawer

Contract-info cards on `perfil/quote/editar_cotizacion/{id}` are denser (same fields, less padding); the old Checklist/Information nav buttons are now two `.qed-status-card` status-strip cards (styled on the `.ss-block` pattern) that open a slide-over drawer (`plantillas/quote/modals/checklist_information_drawer.inc.php`, `js/checklist_info_drawer.js`, `qed-*` CSS namespace — bare design tokens scoped to `.qed-status-row, .qed-drawer, .qed-drawer-scrim, .qed-confirm-overlay`, same pattern as `#pm-table-view, .qs-scrim`). Both tabs' `forms/quote/checklist.inc.php`/`information.inc.php` stay mounted in the DOM the whole time the drawer is open (CSS-hidden, not removed) so switching tabs never loses edits — required prefixing checklist.inc.php's ids that collide with information.inc.php's (`cl_` prefix) since both forms render simultaneously.

**Checklist completeness** is `Rfq::getChecklistCompletionCount()` (10 fields incl. File Document/Accounting checkbox groups) — Set Aside/GSA only count once moved off their default dropdown option (`'Full & Open'`/`'na'`), not merely non-empty.

**Save endpoints** (`scripts/quote/save_checklist.php`/`save_information.php`) always return JSON now (no redirect) — reused as-is otherwise. The client must manually append `save_checklist=1`/`save_information=1` to the AJAX payload since `FormData`/`.serialize()` never include the submitting button's `name`, which is what these scripts gate on.

**Old `/quote/checklist/{id}` and `/quote/information/{id}` URLs** redirect via `Redireccion::redirigir1` (client-side `<script>` redirect), not `redirigir` (`header()`) — `vistas/perfil.php` already echoes the page shell (`documento_declaracion.inc.php`) before reaching those routing cases, so a header-based redirect fails silently with "headers already sent". `editar_cotizacion.inc.php`'s own not-found guard uses the same `redirigir1` pattern for the same reason.

Test: `tests/php/checklist_info_drawer_test.php`, `tests/specs/11-checklist-info-drawer.spec.js`.

### Documents Drawer Tab + Custom File Widget

Documents moved off the quote edit page into a third `qed-drawer` tab (`data-tab="documents"`), matching the Checklist/Information status-card → drawer pattern; the old inline block and its `#download-all` button are gone from `edicion_cotizacion_recuperada.inc.php`. kartik-v bootstrap-fileinput is fully retired (CDN CSS/JS removed from `documento_declaracion.inc.php`/`documento_cierre.inc.php`) and replaced everywhere by `js/document_widget.js` (`doc-*` CSS namespace, same bare-tokens-scoped-to-root pattern as `qed-*`, self-contained so it works with or without a `qed-drawer` ancestor).

`DocumentWidget.init(root, opts)` runs in two modes from one shared implementation:
- **`immediate`** (edit page, inside the drawer): loads existing files via `get_quote_files`, uploads each dropped file with its own XHR (live progress, independent success/failure per file in a batch), deletes go through an inline confirm row (not the site-wide modal) via `delete_document`. Live count feeds both the drawer's tab badge and the status card (`#qed-documents-status-value`) through an `onCountChange` callback — same wiring as the Checklist tab's live count.
- **`deferred`** (create-quote page, no `id_rfq` yet): dropped files are staged client-side only, mirrored onto the real `name="documentos[]"` file input via `new DataTransfer()` so the page's existing native multipart submit is untouched — no upload request fires until the form itself is submitted.

Backend is untouched (`get_quote_files.php`/`load_img.php`/`delete_document.php`/`download_all.php` all reused as-is); the field name uploads go out under (`archivos_ejemplo[]`) still matches what `load_img.php`/`Input::save_files()` expect. `document_widget.js` must load before `js/checklist_info_drawer.js` (which calls `DocumentWidget.init` synchronously at parse time) — since page-specific scripts run *before* `documento_cierre.inc.php`'s global closing scripts in `vistas/perfil.php`'s include order, `document_widget.js` is tagged in the `<head>` (`documento_declaracion.inc.php`) rather than alongside `main.js`.

Test: `tests/php/documents_drawer_test.php`, `tests/specs/12-documents-drawer.spec.js`.

### Import Items Enhancements

Import Items modal (Items table toolbar → Actions → Import items) gained a template download, an Append/Replace mode, and provider import — `scripts/quote/import_items.php` + new `scripts/quote/import_items_template.php`.

- **Template** streams a header-only `.xlsx`: the existing 10 item columns unchanged at positions 0-9 (including Room at 9), plus 5 new Provider Name/Price pairs appended at 10-19 (20 columns total — not 9/19 as an earlier planning doc miscounted, since Room was already the importer's 10th column).
- `processCsv`/`processExcel` read those 5 pairs per row via `parseProviderPairs()`: a pair is skipped when Name is blank; Price defaults to 0 when Name is present but Price is blank/non-numeric.
- New `import_mode` (`append` default | `replace`). Replace deletes all existing items — and, first, each item's subitems — before inserting the file's rows; Rooms are left in place. The whole file is parsed before any deletion, so a malformed upload can't partially wipe a quote. **Gotcha:** `RepositorioItem::delete_item()` alone throws an FK-violation exception on any item with a subitem (`subitems.id_item` → `item.id`) — always delete subitems first.
- Modal UI reuses the `iem-*` shell (same chrome as Add/Edit Item) with new `ii-*` controls: single-file dropzone, Add/Replace radio group, and a height-collapse Fulfillment warning shown only for Replace on a quote where `obtener_fullfillment()` is already true. Upload stays disabled until a file is chosen. JS: `js/import_items_modal.js`.

Test: `tests/php/import_items_test.php`, `tests/specs/13-import-items.spec.js`.

### Items & Services Table Redesign

Visual/structural redesign of the Items and Services tables on `perfil/quote/editar_cotizacion/{id}` (Quote Edit only — Re-Quote/Fulfillment share the old CSS and are untouched). `it-*` CSS namespace (bare tokens scoped to `.it-card`, same pattern as `qed-*`/`doc-*`). Rows stay real `<table>`/`<tr>`/`<td>` (not a CSS-grid div layout) so `#tabla_items`/`<tbody id="items">` and `js/quote.js`'s `td:eq()`-based calc loop keep working — only column count changed (13→12: the standalone Website column was folded into the E-Logic Proposal description cell, shifting `td:eq()` indices in `calcQuoteTable()`/`populateCalcArrays()`).

- **Kebab action menu**: `RepositorioItem::renderKebab()` wraps the *same* `.iem-edit-item`/`.iem-delete-item`/etc. buttons (same classes, same `data-*`, same click handlers) inside a `.it-menu` popover — `js/items_table.js` only toggles `hidden`/open state, no handler changes. Playwright specs that used to click those buttons directly now call `tests/helpers/kebab.js`'s `openKebabFor()` first. `.iem-edit-provider`/`.iem-edit-provider-subitem` stay directly visible in the providers list (not behind the kebab).
- **Descriptions**: `RepositorioItem::formatDescription()` (100-char server truncation) is gone — `RepositorioItem::renderDescBlock()` always sends the full text; CSS (`grid-template-rows: 0fr/1fr`) does the condensed-by-default look, `[data-toggle-desc]` click expands in place. Applies to items, subitems, and services (services have no brand/part split, so the condensed line is the description itself, CSS-ellipsized).
- **Providers**: `RepositorioItem::renderProvidersList()` sorts cheapest-first and highlights ties at the lowest price (mirrors the existing `provider_menor` tie behavior — multiple providers can be "best" if tied). Shared by items and subitems (`$isSubitem` flag swaps the `-subitem` action classes).
- **Subitem nesting**: `.it-row.is-sub` + `fila_subitem` (legacy class kept for `js/quote.js`'s `hasClass('fila_subitem')` calc branch). Disclosure caret (`[data-toggle-subitems]`) shows/hides child rows via the `hidden` attribute — expanded by default, matching pre-redesign behavior (subitems always visible).
- **Empty items state**: moved *inside* `RepositorioItem::escribir_items()` — the `.it-card` (header/controls/shipping) always renders now; only the table area swaps for a `.it-empty` placeholder (dual-classed `section-empty-state` for the existing Playwright selector). `forms/quote/edicion_cotizacion_recuperada.inc.php` and `scripts/quote/get_items_table.php` no longer render their own duplicate empty-state block.
- **Not ported from the design**: per-row "provider name under Best Unit Cost" and the items/subitems/provider-quote counts in the card subtitle were dropped — both would've needed either JS changes to the calc-loop's plain-text `.html()` writes or extra N+1 queries for a cosmetic label, not worth it for a restyle pass.

Test: existing `tests/php/commercial_moving_test.php`, `tests/js/payment_split.test.js`, and Playwright specs `03`–`08`/`13` (specs `06`/`08`'s services-payment-term tests were previously broken — asserting a radio-button UI that no longer existed — fixed to use the real `<select id="services_payment_term">` while in the area).
