# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Feature Inventory

All built. Detail for most of these lives in the matching `###` section below.

Quote Inline Editing · SharePoint Sheet Sync · Comment Mentions & Notifications · Bid Requirement Fields (Site Visit/Q&A Deadline/Resumes) · Bid Pipeline Sync Controls · Bid Pipeline Metrics Dashboard · 3-Year Annual Awards Comparison · Quote Lifecycle Audit Events · Write-Once Sheet Sync · Advanced Quote Search · Commercial Moving bid type + 50/50 payment term · Pipeline Table View + Quote Watchers · Shared Notification Mailbox

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

One admin-connected MS mailbox sends **all** system notification emails (mentions + watcher alerts), replacing the per-user delegated connection whose emails silently failed when the actor hadn't connected. `NotificationMailboxRepository` (refresh-on-expiry token storage); `NotificationEmail::send()` centralizes the Graph `/me/sendMail` call + HTML template.

**OAuth reuse:** admin Connect sets `$_SESSION['ms_oauth_target']='mailbox'`, and the existing `microsoft_callback.php` branches on that flag to store in `notification_mailbox` instead of the user row — no new Azure app needed. Admin-only UI at `perfil/admin/settings`. Not connected → email is a safe no-op, in-app notifications unaffected. Test: `tests/php/shared_mailbox_test.php`.

### Pipeline Table View + Quote Watchers

`Charts | Table` toggle on `perfil/reports/pipeline_metrics` swaps to a filterable, server-paginated (25/row) quote table over the same `created_at` cohort, plus per-quote watch subscriptions (`quote_watchers`, unique on `id_rfq,id_user`).

Designated user auto-subscribed on create and on reassignment. `WatcherNotificationService::notify()` fans one in-app notification per watcher (never the actor) + a shared-mailbox email. Triggers: status changes, and Type of Bid/Designated User/Comments edits. No audit trail for watch/unwatch. Modal's attached documents are the real uploaded files from `quote/get_quote_files/<id>` (not the `rfq.file_document` checklist field). Quick-comment reuses `#comment_rfq`/`mentions.js`.

**Watch UI currently hidden** (email-notifications feature on hold): table Watch column, modal Watch button, and Admin Settings sidebar link are commented out — backend + route still work, re-enabling is markup-only. Test: `tests/php/quote_watchers_test.php`.
