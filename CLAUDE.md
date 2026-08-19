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

Non-destructive: may create a missing row but never overwrites/deletes an existing one. Every sync path routes through `SheetSyncService::createOrLink($quote, $designatedUsername)` → `['row','outcome']`:
- Presence decided by **scanning column A** (PROPOSAL = quote id), never the stored `sheet_row`. Found → pointer only, `outcome='linked'`. Absent → append fresh row, `outcome='created'`. No Graph secret → `outcome=null`.
- Persist only on **establishment** (created, linked to a new row, or prior status ≠ `synced`): status update + audit event. No-op edit = zero Graph writes, no audit row. Quote delete never touches the sheet.
- **`sync_to_sheet` flag is the sole auto-sync gate** (not bid type, not child/master-link). `Sync to Sheet` btn create-or-links + flag=1; `Break Sync` → flag=0 (keeps `sheet_row`); `copyRfq` → 0.
- **Column ownership:** app owns A,B,C,D,G,H,J,L,M,N,Q,T; **E,F,I,K,O,P,R,S are human-owned**, blanked only on a brand-new row.

### Unified Audit Trail

Quote/re-quote/fulfillment each write to their own table (`audit_trails`, `re_quote_audit_trails`, `fulfillment_audit_trails`), surfaced through one modal + endpoint: `POST quote/load_unified_audit_trail` merges all three (re-quote joined via `id_rfq`), sorts `created_date DESC`. `js/audit_trail.js`.

Action types: `status_change`, `field_modified`, `item_modified/created/deleted`, `invoice_created/updated/deleted`, `document_updated`, `net_30`, `quote_created` (Status group); Sync group (quote only): `sheet_row_created`/`sheet_row_linked`, `break_sync`, legacy `sync_to_sheet`. Logged **once on establishment only** — a no-op sync logs nothing. Filter tabs All/Status/Edits/Items/Invoices/Sync — 3+ consecutive sync events collapse into "N automatic syncs". `at-*` CSS namespace.

### Comment Mentions & Notifications

`sql/notifications_migration.sql` adds MS token columns to `usuarios` + a `notifications` table. `js/mentions.js` (@mention autocomplete on `#comment_rfq`), `NotificationRepository::parseMentions()`, SSE stream (`notifications_stream.php`, polls 3s) drives the navbar bell. `guardar_comment.php` parses @mentions → notifications → emails via the Shared Notification Mailbox. `nf-*`/`ac-*`/`cm-*` CSS namespaces.

**Routes:** `perfil/account`, `perfil/notifications`, `user/microsoft/{connect,callback,disconnect}`, `user/account/{update_profile,update_password}`, `quote/notifications/{stream,list,mark_read,users_for_mention}`.

### Bid Pipeline Metrics Dashboard

`perfil/reports/pipeline_metrics` — ApexCharts report reproducing the SharePoint METRICS 2026 tab. **All aggregation is in SQL**, never by loading Rfq objects.

`rfq.created_at` (auto-stamped on insert) is the cohort date, replacing hand-typed `issue_date` whose unparseable values silently dropped rows. **On prod also run `sql/quote_created_at_revert_backfill.sql`** to NULL the local `issue_date` backfill so prod tracks forward from the migration only.

`PipelineMetricsRepository::STATUS_CASE` is a SQL `CASE` mirroring `Rfq::getSheetStatus()` exactly — keep in sync (10 buckets: tbd, bid, no_bid, submitted, submitted_ss, award, no_award_pricing, no_award_technical, cancelled, not_submitted).

**Win/Loss gotcha:** denominator = `submitted` + `award` + lost (`no_award_*`); sources-sought excluded. **Dollar-value gotcha:** every money figure = product total + services subtotal via `SERVICES_JOIN`/`VALUE_EXPR`, never `rfq.total_price` alone.

Two listing pages mirror this: Sources Sought (`quote/sources_sought`) and No Award (`quote/no_award`, with a Reason column). **Status by user** card: one horizontal stacked bar per designated user (`PipelineMetricsRepository::getStatusByUser()`, INNER JOIN `usuarios` since the column is `NOT NULL`). Drill-down spec type `byUser` needs `usuario_designado` in `getDrillDown()`'s *inner* subquery SELECT only. `.pm-drawer` is `50vw` for every drill-down on the page. Tests: `tests/php/pipeline_metrics_test.php`, `tests/specs/09-pipeline-metrics.spec.js`.

### Pipeline Table View

`Charts | Table` toggle on `perfil/reports/pipeline_metrics` (`#pm-view`) swaps to a filterable, server-paginated (25/row) quote table over the same `created_at` cohort (`PipelineTableRepository`, `js/pipeline_table.js`). Row click opens a Quote Summary modal (real attached files from `quote/get_quote_files/<id>`; quick-comment reuses `#comment_rfq`/`mentions.js`).

**No Quote Watchers** — that subsystem (per-quote watch subscriptions + notification fan-out) was built alongside this Table View, then removed; don't reintroduce a `watched` field/join without deliberately re-adding it.

**Internal Due Date filter** — preset dropdown (Today/Tomorrow/Next 7 days/Overdue), date-based via `CURDATE()` in `dueDateClause()`. **Required everywhere entered**: New Quote form + Information drawer tab (`save_information.php` rejects blank pre-write).

**End Date filter + columns** — same 4-preset pattern, `endDateClause()`. `rfq.end_date` is `VARCHAR` (`MM/DD/YYYY HH:mm`), **not a DATE column**: date math needs `STR_TO_DATE(rfq.end_date, "%m/%d/%Y %H:%i")`, and since it's `NOT NULL` with no default, an unset value is `''` not SQL NULL — `STR_TO_DATE('', ...)` parses to the zero-date `0000-00-00` (satisfies `< CURDATE()`), so wrap in `NULLIF(rfq.end_date, '')` first or every blank row shows "Overdue". Also required in the Information drawer. Table columns sit right after `Created`; End Date renders the raw stored string. New Quote form's `#end_date` picker needs `autoUpdateInput: false` (its own override, not the shared `dateTimeOptions`) or daterangepicker silently fills today's date/time on init. Tests: `tests/php/pipeline_table_test.php`, `tests/php/internal_due_date_test.php`, `tests/specs/09-pipeline-metrics.spec.js`, `tests/specs/11-checklist-info-drawer.spec.js`, `tests/specs/02-quote-list.spec.js`.

### Charts Tab — Annual Awards (3-year)

`perfil/charts` (Chart.js, not ApexCharts). Two Annual Awards cards compare a **rolling** window (current year + 2 prior) as grouped monthly columns (`RepositorioRfq::getAnnualAwardsDataByMonthForYears()`). Per-user cards hide users with no activity in either month (`activeUserSeries()` in `js/main_charts.js`).

**Deliberately differs from Pipeline Metrics:** counts awards by **award date** (`fecha_award`, "when we won") not issue date — the two pages answer different questions and don't need to reconcile. Awards without `fecha_award` don't appear. Test: `tests/php/annual_awards_test.php`.

### Advanced Quote Search

`perfil/search_quotes` **Advanced** toggle expands a filter panel (status multi-select over the 10 pipeline buckets, designated user, bid/contract type, date range + field selector, price range, client, state — AND-combined) and adds a Status pill column. Off = identical to basic. Empty advanced search = all non-deleted quotes; inverted ranges = empty state, no error.

Separate backend pair (`getAdvancedSearchedQuotes`/`...Count`); derived status reuses `PipelineMetricsRepository::STATUS_CASE` verbatim. `js/searchQuotes.js` swaps DataTable column sets. Tests: `tests/php/advanced_search_test.php`, `tests/specs/10-advanced-search.spec.js` (use `PW_CHANNEL=chrome` where no bundled Chromium).

### Commercial Moving + 50/50 Payment Term

Bid type "Commercial Moving" + payment term `50% Upfront / 50% on Completion`, stored as the literal string in `rfq.payment_terms`/`services_payment_term` (no schema change). Not in `SYNCABLE_BID_TYPES`, so pipeline sync auto-defaults off.

**No calc change:** 50/50 is a schedule, ×1 like Net 30 — every calc/PDF path already special-cased only `Net 30/CC`. Quote-wide **all-or-nothing** mirroring in `js/quote.js`/`js/reQuote.js` (50/50 on either sets both). Split shown via `js/payment_split.js`; re-quote internal cost sheet gets no split block. Tests: `tests/js/payment_split.test.js`, `tests/php/commercial_moving_test.php`.

### Shared Notification Mailbox

One admin-connected MS mailbox sends **all** system notification emails (mentions + Daily RFQ Digest), replacing the per-user delegated connection that silently failed when the actor hadn't connected. `NotificationEmail::send()` (branded template) and `::sendCustom()` (caller HTML) both funnel through the same Graph `/me/sendMail` call and silent-no-op-when-disconnected gate.

**OAuth reuse:** admin Connect sets `$_SESSION['ms_oauth_target']='mailbox'`; `microsoft_callback.php` branches on that flag to store in `notification_mailbox` instead of the user row — no new Azure app needed. Admin-only UI at `perfil/admin/settings`. Test: `tests/php/shared_mailbox_test.php`.

### Daily RFQ Digest Email

`scripts/cron/daily_digest.php` — droplet crontab, 6:00am America/New_York; no in-app UI. One HTML email (`DigestEmailTemplate`, 680px container) to every active Admin-role user via the Shared Notification Mailbox. Four sections, always shown even empty, Due Today listed first: Due Today scopes to **today's End Date** (`getDueOn()` — `end_date` is `VARCHAR`, so `STR_TO_DATE(NULLIF(rfq.end_date, ''), '%m/%d/%Y %H:%i')`, mirroring `PipelineTableRepository::endDateClause()`), Created/Submitted/Awarded scope to the **previous calendar day** (`DigestRepository`, all excluding `deleted=1`). Row names truncate at 70 chars server-side (PHP, not CSS — classic Outlook doesn't reliably support `text-overflow`). `date_default_timezone_set('America/New_York')` set explicitly — never rely on server/container default.

`digest_send_log` (unique per date) dedupes same-day re-triggers regardless of mailbox connectivity. Test: `tests/php/daily_digest_test.php`.

### Quote Checklist & Info Drawer

Contract-info cards on `perfil/quote/editar_cotizacion/{id}` are denser; the old Checklist/Information nav buttons are now two `.qed-status-card` status-strip cards opening a slide-over drawer (`qed-*` CSS namespace). Both tabs' forms stay mounted the whole time (CSS-hidden, not removed) — required prefixing `checklist.inc.php`'s colliding ids (`cl_` prefix).

**Checklist completeness** is `Rfq::getChecklistCompletionCount()` (10 fields) — Set Aside/GSA only count once moved off their default dropdown option, not merely non-empty.

**Save endpoints** always return JSON (no redirect). Client must manually append `save_checklist=1`/`save_information=1` to the AJAX payload since `FormData`/`.serialize()` never include the submitting button's `name`, which these scripts gate on.

**Old `/quote/checklist/{id}` and `/quote/information/{id}` URLs** redirect via `Redireccion::redirigir1` (client-side `<script>`), not `header()` — `vistas/perfil.php` echoes the page shell before reaching those routing cases, so a header-based redirect fails silently. Test: `tests/php/checklist_info_drawer_test.php`, `tests/specs/11-checklist-info-drawer.spec.js`.

### Documents Drawer Tab + Custom File Widget

Documents moved into a third `qed-drawer` tab. kartik-v bootstrap-fileinput retired, replaced by `js/document_widget.js` (`doc-*` CSS namespace, self-contained with or without a `qed-drawer` ancestor).

`DocumentWidget.init(root, opts)` runs in two modes: **`immediate`** (edit page, in-drawer) loads existing files via `get_quote_files`, uploads each dropped file on its own XHR, deletes via inline confirm row; live count feeds the tab badge via `onCountChange`. **`deferred`** (create-quote page, no `id_rfq` yet) stages files client-side, mirrored onto `name="documentos[]"` via `new DataTransfer()`.

Backend untouched (`get_quote_files.php`/`load_img.php`/`delete_document.php`/`download_all.php`; upload field stays `archivos_ejemplo[]`). `document_widget.js` must load before `js/checklist_info_drawer.js` (which calls `DocumentWidget.init` synchronously) — tagged in `<head>`, not alongside `main.js`. Test: `tests/php/documents_drawer_test.php`, `tests/specs/12-documents-drawer.spec.js`.

### Import Items Enhancements

Import Items modal gained a template download, Append/Replace mode, provider import — `scripts/quote/import_items.php` + `import_items_template.php`.

- **Template**: header-only `.xlsx`, 10 item columns at positions 0-9, plus 5 Provider Name/Price pairs at 10-19 (20 columns). `parseProviderPairs()`: skipped when Name blank; Price defaults to 0 when Name present but Price blank/non-numeric.
- **`import_mode`** (`append` default | `replace`). Replace deletes existing items — each item's subitems first — before inserting; Rooms left in place. Whole file parsed before any deletion. **Gotcha:** `RepositorioItem::delete_item()` throws an FK-violation on any item with a subitem — delete subitems first.

Test: `tests/php/import_items_test.php`, `tests/specs/13-import-items.spec.js`.

### Items & Services Table Redesign

Visual/structural redesign on Quote Edit only (Re-Quote/Fulfillment untouched). `it-*` CSS namespace. Rows stay real `<table>`/`<tr>`/`<td>` so `js/quote.js`'s `td:eq()`-based calc loop keeps working — column count changed 13→12 (Website folded into the description cell), shifting `td:eq()` indices in `calcQuoteTable()`/`populateCalcArrays()`.

- **Kebab menu**: `RepositorioItem::renderKebab()` wraps edit/delete buttons in a `.it-menu` popover. Playwright specs call `tests/helpers/kebab.js`'s `openKebabFor()` first.
- **Descriptions**: server-side truncation gone — CSS (`grid-template-rows: 0fr/1fr`) does condensed-by-default, `[data-toggle-desc]` expands in place.
- **Providers**: `renderProvidersList()` sorts cheapest-first, highlights ties at lowest price.
- Dropped from the design: per-row provider name under Best Unit Cost, items/subitems/quote counts in the card subtitle — both needed JS calc-loop changes or N+1 queries for a cosmetic label.

Test: `tests/php/commercial_moving_test.php`, `tests/js/payment_split.test.js`, Playwright specs `03`–`08`/`13`.

### Items/Services Table Min-Width + Provider Name Escaping (fixes)

`.it-table`/`.it-table.is-services` needed a `min-width` (1360px/750px) so the pre-existing `overflow-x: auto` wrapper engages on narrow (~13-14") laptop screens instead of squeezing description columns toward zero.

Provider names were double-HTML-escaped on display — now stored raw, escaped only at render (`RepositorioItem::renderProvidersList()`). `sql/provider_name_unescape_backfill.sql` (idempotent) decodes already-double-escaped rows; **run on production**. Re-quote provider paths were checked and intentionally left alone — matching this fix there would trade a cosmetic bug for a stored-XSS hole. Tests: `tests/specs/03-quote-editing-items.spec.js`, `tests/specs/06-services.spec.js`, `tests/php/provider_name_escaping_test.php`, `tests/specs/04-quote-editing-providers.spec.js`.
