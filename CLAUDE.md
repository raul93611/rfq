# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Feature Inventory

All built. Detail for most lives in the matching `###` section below.

Quote Inline Editing · SharePoint Sheet Sync · Comment Mentions & Notifications · Bid Requirement Fields (Site Visit/Q&A Deadline/Resumes) · Bid Pipeline Sync Controls · Bid Pipeline Metrics Dashboard · Pipeline Table View · 3-Year Annual Awards Comparison · Quote Lifecycle Audit Events · Write-Once Sheet Sync · Advanced Quote Search · Commercial Moving bid type + 50/50 payment term · Shared Notification Mailbox · Daily RFQ Digest Email · Quote Checklist & Info Drawer · Documents Drawer Tab + Custom File Widget · Import Items Enhancements (template download, append/replace mode, provider import) · Items & Services Table Redesign · Internal Due Date Table Filter + Required Field · Pipeline Status by User + Wider Drill-down Drawer · End Date Required + Pipeline Table Due-Date Columns

## Environment

PHP app on a LAMP stack inside Docker (`docker-compose-lamp`), served at `http://localhost/rfq/`.

**Setup (README):** create `/tmp` at project root; disable MySQL strict mode (`sql_mode=""` under `[mysqld]` in `my.cnf`, restart MySQL); prod also needs the `xmlwriter` PHP module.

**Install:** `composer install`

**Database:** `elogicnewdb` on `database:3306`. Schema: [sql/elogic.sql](sql/elogic.sql). Credentials/server URL hardcoded in [app/Bootstrap/config.inc.php](app/Bootstrap/config.inc.php) (`SERVIDOR`).

**Generate users:** `/genera_usuario`.

**Tests:** PHP (`tests/php/`, `docker exec lamp-php83 php /var/www/html/rfq/tests/php/<file>`); Node (`tests/js/`, `node --test`); Playwright (`tests/specs/`, `cd tests && PW_CHANNEL=chrome npx playwright test`). No lint.

## Architecture

Custom PHP MVC-like app, no framework.

**Request lifecycle:** every request hits [index.php](index.php), which autoloads `app/{Domain}/{ClassName}.inc.php` and `switch`es the URL to a **script** in `scripts/` (AJAX handlers, JSON/HTML fragments) or a **view** in `vistas/` (full pages, `plantillas/` partials).

**Directories:** `app/` domain classes (`{Entity}.inc.php` + `Repositorio{Entity}.inc.php`/`{Entity}Repository.inc.php` static PDO methods; `Bootstrap/` = `Conexion`, `ControlSesion`, `Redireccion`, config/routes; `Utilities/` = `PDFGenerator` (mPDF), `ExcelRepository` (PhpSpreadsheet), `ProposalRepository`, `Email`, `TeamsIntegration`, `Input`) · `scripts/` action handlers · `vistas/` entry points (`home.php` login, `perfil.php` dashboard) · `plantillas/` template partials · `js/` per-module jQuery · `css/estilos.css` single stylesheet.

Local CSS/JS go through `asset_url('js/file.js')` — appends `?v=<filemtime>` to bust stale caches after deploy. Never bare `RUTA_CSS`/`RUTA_JS`.

**DB access** always via the `Conexion` singleton:
```php
Conexion::abrir_conexion();
$result = SomeRepository::some_method(Conexion::obtener_conexion(), $param);
Conexion::cerrar_conexion();
```

**Domains:** Quote, ReQuote (vendor re-quoting), Fulfillment, Tracking, Invoice, SalesCommission, Projection, Task, Personnel, Service, Provider, PaymentTerm, Room, TypeOfBid, TypeOfContract, TypeOfProject.

**Routes:** constants in [app/Bootstrap/routes.inc.php](app/Bootstrap/routes.inc.php); new routes need a constant there + a `case` in `index.php`.

**Quote status flow:** `Rfq` progresses Created → Completed → Submitted → Award → Fulfillment → Invoice; `comments` encodes special statuses (No Bid, Cancelled, Not submitted). `isEnabledToFulfillment()`/`isEnabledToInvoice()` enforce transition prerequisites.

### SharePoint Sheet Sync — write-once create-or-link

Non-destructive: may create a missing row, never overwrites/deletes an existing one. Every path routes through `SheetSyncService::createOrLink($quote, $designatedUsername)` → `['row','outcome']`:
- Presence decided by **scanning column A** (PROPOSAL = quote id), never `sheet_row`. Found → `outcome='linked'`. Absent → append row, `outcome='created'`. No Graph secret → `outcome=null`.
- Persists only on **establishment** (created, linked to a new row, or prior status ≠ `synced`): status update + audit event. No-op = zero Graph writes, no audit row. Delete never touches the sheet.
- **`sync_to_sheet` is the sole auto-sync gate** (not bid type, not child/master-link). `Sync to Sheet` → create-or-link + flag=1; `Break Sync` → flag=0 (keeps `sheet_row`); `copyRfq` → 0.
- **Columns:** app owns A,B,C,D,G,H,J,L,M,N,Q,T; **E,F,I,K,O,P,R,S are human-owned**, blanked only on a brand-new row.

### Unified Audit Trail

Quote/re-quote/fulfillment each write their own table (`audit_trails`, `re_quote_audit_trails`, `fulfillment_audit_trails`), merged by `POST quote/load_unified_audit_trail` (re-quote joined via `id_rfq`, sorted `created_date DESC`). `js/audit_trail.js`.

Action types: `status_change`, `field_modified`, `item_modified/created/deleted`, `invoice_created/updated/deleted`, `document_updated`, `net_30`, `quote_created` (Status group); Sync group (quote only): `sheet_row_created`/`sheet_row_linked`, `break_sync`, legacy `sync_to_sheet`. Logged **once on establishment only**. Filter tabs All/Status/Edits/Items/Invoices/Sync — 3+ consecutive sync events collapse to "N automatic syncs". `at-*` CSS namespace.

### Comment Mentions & Notifications

`sql/notifications_migration.sql` adds MS token columns to `usuarios` + a `notifications` table. `js/mentions.js` (@mention autocomplete on `#comment_rfq`), `NotificationRepository::parseMentions()`, SSE stream (`notifications_stream.php`, 3s poll) drives the navbar bell. `guardar_comment.php` parses @mentions → notifications → emails via the Shared Notification Mailbox. `nf-*`/`ac-*`/`cm-*` namespaces.

**Routes:** `perfil/account`, `perfil/notifications`, `user/microsoft/{connect,callback,disconnect}`, `user/account/{update_profile,update_password}`, `quote/notifications/{stream,list,mark_read,users_for_mention}`.

### Bid Pipeline Metrics Dashboard

`perfil/reports/pipeline_metrics` — ApexCharts report reproducing the SharePoint METRICS 2026 tab. **All aggregation is SQL**, never loaded `Rfq` objects.

`rfq.created_at` (auto-stamped) is the cohort date, replacing hand-typed `issue_date` whose unparseable values silently dropped rows. **On prod also run `sql/quote_created_at_revert_backfill.sql`** to NULL the local `issue_date` backfill.

`PipelineMetricsRepository::STATUS_CASE` mirrors `Rfq::getSheetStatus()` — keep in sync (10 buckets: tbd, bid, no_bid, submitted, submitted_ss, award, no_award_pricing, no_award_technical, cancelled, not_submitted).

**Win/Loss:** denominator = `submitted`+`award`+lost (`no_award_*`); sources-sought excluded. **Dollar-value:** every figure = product total + services subtotal (`SERVICES_JOIN`/`VALUE_EXPR`), never `rfq.total_price` alone.

Mirrored by Sources Sought (`quote/sources_sought`) and No Award (`quote/no_award`, + Reason column). **Status by user**: one stacked bar per designated user (`getStatusByUser()`, INNER JOIN `usuarios`). Drill-down `byUser` needs `usuario_designado` in `getDrillDown()`'s inner subquery SELECT only. `.pm-drawer` is `50vw`. Tests: `tests/php/pipeline_metrics_test.php`, `tests/specs/09-pipeline-metrics.spec.js`.

### Pipeline Table View

`Charts | Table` toggle (`#pm-view`) swaps to a filterable, server-paginated (25/row) table over the same `created_at` cohort (`PipelineTableRepository`, `js/pipeline_table.js`). Row click opens a Quote Summary modal (real files via `quote/get_quote_files/<id>`; quick-comment reuses `#comment_rfq`/`mentions.js`).

**No Quote Watchers** — built alongside this, then removed; don't reintroduce a `watched` field/join without deliberately re-adding it.

**Internal Due Date filter** — preset dropdown (Today/Tomorrow/Next 7 days/Overdue) via `CURDATE()` in `dueDateClause()`. Required everywhere entered: New Quote form + Information drawer (`save_information.php` rejects blank).

**End Date filter + columns** — same pattern, `endDateClause()`. `rfq.end_date` is `VARCHAR` (`MM/DD/YYYY HH:mm`), not a DATE column: needs `STR_TO_DATE(rfq.end_date, "%m/%d/%Y %H:%i")`, and since it's `NOT NULL` with no default, an unset value is `''` — parses to zero-date `0000-00-00` (satisfies `< CURDATE()`), so wrap in `NULLIF(rfq.end_date, '')` or blanks show "Overdue". Also required in the Information drawer; columns sit right after `Created`. New Quote form's `#end_date` picker needs its own `autoUpdateInput: false` (not the shared `dateTimeOptions`) or daterangepicker fills today's date/time on init. Tests: `tests/php/pipeline_table_test.php`, `tests/php/internal_due_date_test.php`, `tests/specs/09-pipeline-metrics.spec.js`, `tests/specs/11-checklist-info-drawer.spec.js`, `tests/specs/02-quote-list.spec.js`.

### Charts Tab — Annual Awards (3-year)

`perfil/charts` (Chart.js). Two Annual Awards cards compare a **rolling** window (current year + 2 prior) as grouped monthly columns (`getAnnualAwardsDataByMonthForYears()`). Per-user cards hide users with no activity in either month (`activeUserSeries()` in `js/main_charts.js`).

**Differs from Pipeline Metrics on purpose:** counts by **award date** (`fecha_award`) not issue date — the two pages answer different questions, no need to reconcile. Awards without `fecha_award` don't appear. Test: `tests/php/annual_awards_test.php`.

### Advanced Quote Search

`perfil/search_quotes` **Advanced** toggle expands a filter panel (status multi-select over the 10 pipeline buckets, designated user, bid/contract type, date range + field, price range, client, state — AND-combined) + a Status pill column. Off = identical to basic. Empty search = all non-deleted quotes; inverted ranges = empty state, no error.

Separate backend pair (`getAdvancedSearchedQuotes`/`...Count`); status reuses `STATUS_CASE` verbatim. `js/searchQuotes.js` swaps DataTable column sets. Tests: `tests/php/advanced_search_test.php`, `tests/specs/10-advanced-search.spec.js` (`PW_CHANNEL=chrome`).

### Commercial Moving + 50/50 Payment Term

Bid type "Commercial Moving" + payment term `50% Upfront / 50% on Completion`, stored as the literal string in `rfq.payment_terms`/`services_payment_term` (no schema change). Not in `SYNCABLE_BID_TYPES`, so pipeline sync defaults off.

**No calc change:** 50/50 is a schedule, ×1 like Net 30 — every calc/PDF path only special-cases `Net 30/CC`. Quote-wide **all-or-nothing** mirroring in `js/quote.js`/`js/reQuote.js`. Split shown via `js/payment_split.js`; re-quote cost sheet gets no split block. Tests: `tests/js/payment_split.test.js`, `tests/php/commercial_moving_test.php`.

### Shared Notification Mailbox

One admin-connected MS mailbox sends **all** system emails (mentions + Daily Digest), replacing the per-user connection that silently failed when the actor hadn't connected. `NotificationEmail::send()`/`::sendCustom()` both funnel through the same Graph `/me/sendMail` call and silent-no-op-when-disconnected gate.

**OAuth reuse:** admin Connect sets `$_SESSION['ms_oauth_target']='mailbox'`; `microsoft_callback.php` branches on that flag to store in `notification_mailbox` instead of the user row — no new Azure app needed. Admin-only UI at `perfil/admin/settings`. Test: `tests/php/shared_mailbox_test.php`.

### Daily RFQ Digest Email

`scripts/cron/daily_digest.php` — droplet crontab, 6am America/New_York, no in-app UI. One HTML email (`DigestEmailTemplate`, 680px) to every active Admin via the Shared Mailbox. Four sections, always shown even empty, Due Today first: Due Today scopes to **today's End Date** (`getDueOn()`, `STR_TO_DATE(NULLIF(rfq.end_date, ''), '%m/%d/%Y %H:%i')`, mirrors `endDateClause()`); Created/Submitted/Awarded scope to the **previous day** (`DigestRepository`, excludes `deleted=1`). Row names truncate at 70 chars server-side (Outlook doesn't support CSS `text-overflow`). `date_default_timezone_set('America/New_York')` set explicitly.

`digest_send_log` (unique per date) dedupes same-day re-triggers. Test: `tests/php/daily_digest_test.php`.

### Quote Checklist & Info Drawer

Contract-info cards on `perfil/quote/editar_cotizacion/{id}` are denser; old Checklist/Information nav buttons are now `.qed-status-card` strips opening a slide-over drawer. Both tabs' forms stay mounted (CSS-hidden, not removed) — required prefixing `checklist.inc.php`'s colliding ids (`cl_`).

**Checklist completeness** = `Rfq::getChecklistCompletionCount()` (10 fields) — Set Aside/GSA count only once moved off their default option, not merely non-empty.

**Save endpoints** always return JSON (no redirect). Client must manually append `save_checklist=1`/`save_information=1` — `FormData`/`.serialize()` never include the submitting button's `name`, which these scripts gate on.

**Old `/quote/checklist/{id}`/`/quote/information/{id}`** redirect via `Redireccion::redirigir1` (client `<script>`), not `header()` — `perfil.php` echoes the page shell first, so a header redirect fails silently. Test: `tests/php/checklist_info_drawer_test.php`, `tests/specs/11-checklist-info-drawer.spec.js`.

### Documents Drawer Tab + Custom File Widget

Documents moved into a third `qed-drawer` tab. kartik-v bootstrap-fileinput retired for `js/document_widget.js` (`doc-*` namespace, self-contained with or without a `qed-drawer` ancestor).

`DocumentWidget.init(root, opts)`: **`immediate`** (edit page) loads via `get_quote_files`, uploads each drop on its own XHR, deletes via inline confirm; live count feeds the tab badge via `onCountChange`. **`deferred`** (create-quote, no `id_rfq` yet) stages client-side, mirrored onto `name="documentos[]"` via `new DataTransfer()`.

Backend untouched. `document_widget.js` must load before `js/checklist_info_drawer.js` (calls `DocumentWidget.init` synchronously) — tagged in `<head>`. Test: `tests/php/documents_drawer_test.php`, `tests/specs/12-documents-drawer.spec.js`.

### Import Items Enhancements

Template download, Append/Replace mode, provider import — `scripts/quote/import_items.php` + `import_items_template.php`.

- **Template**: header-only `.xlsx`, 10 item columns (0-9) + 5 Provider Name/Price pairs (10-19). `parseProviderPairs()`: skipped when Name blank; Price defaults 0 when Name present but Price blank/non-numeric.
- **`import_mode`** (`append` default | `replace`). Replace deletes existing items (subitems first) before inserting; Rooms stay. Whole file parsed before deletion. **Gotcha:** `delete_item()` throws an FK-violation on any item with a subitem — delete subitems first.

Test: `tests/php/import_items_test.php`, `tests/specs/13-import-items.spec.js`.

### Items & Services Table Redesign

Quote Edit only (Re-Quote/Fulfillment untouched). `it-*` namespace. Rows stay real `<table>`/`<tr>`/`<td>` for `js/quote.js`'s `td:eq()` calc loop — column count 13→12 (Website folded into description), shifting indices in `calcQuoteTable()`/`populateCalcArrays()`.

- **Kebab menu**: `renderKebab()` wraps edit/delete in a `.it-menu` popover. Specs call `openKebabFor()` (`tests/helpers/kebab.js`) first.
- **Descriptions**: server truncation gone — CSS (`grid-template-rows: 0fr/1fr`) condenses by default, `[data-toggle-desc]` expands.
- **Providers**: `renderProvidersList()` sorts cheapest-first, highlights ties.

Test: `tests/php/commercial_moving_test.php`, `tests/js/payment_split.test.js`, Playwright `03`–`08`/`13`.

### Post-launch fixes: table min-width, provider escaping, re-quote bottom bar

`.it-table`/`.it-table.is-services` needed `min-width` (1360px/750px) so the existing `overflow-x: auto` wrapper engages on ~13-14" laptops instead of squeezing description columns to zero.

Provider names were double-HTML-escaped — now stored raw, escaped only at render (`renderProvidersList()`). `sql/provider_name_unescape_backfill.sql` (idempotent) decodes already-double-escaped rows; **run on production**. Re-quote provider paths intentionally left alone — fixing them there would trade a cosmetic bug for a stored-XSS hole. Tests: `tests/specs/03-quote-editing-items.spec.js`, `tests/specs/06-services.spec.js`, `tests/php/provider_name_escaping_test.php`, `tests/specs/04-quote-editing-providers.spec.js`.

**Re-quote services are their own re-solicited cost line, not margin-neutral** — `obtener_re_quote_total_cost()` deliberately sums `re_quote_services` (not the original quote's `services`), unchanged since 2022 (`b3fb2904`): mirrors items, where client price stays pinned to the original quote while cost gets re-solicited, so Net 30/CC on re-quote services is a real cost, same as it is for items. Don't "fix" this into cancelling to zero — that was tried and reverted. The bottom bar (`#rq-bar-*`) previously never updated after page load (frozen server-rendered PHP) even though the Items table above it recalculated live; now `js/reQuote.js`'s existing polling loop keeps it current, combining live items cost with `#total_service` (kept current by `calcServices()` on the same tick). Test: `tests/php/re_quote_cc_profit_test.php`.
