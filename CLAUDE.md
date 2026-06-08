# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Feature Inventory

| Feature | Status | Spec |
|---|---|---|
| Quote Inline Editing (modals for item/provider/subitem CRUD) | built | — |
| SharePoint Sheet Sync (portal → E-LOGIC BID PIPELINE xlsx via Graph API) | built | — |
| Comment Mentions & Notifications (@mention users in comments, in-app bell + email via per-user MS OAuth) | built | — |
| Bid Requirement Fields (Site Visit, Q&A Deadline, Resumes on quotes + sheet sync) | built | — |
| Bid Pipeline Sync Controls (`sync_to_sheet` flag, bid-type smart default, human-owned sheet columns, master-linked quotes keep syncing) | built | — |
| Bid Pipeline Metrics Dashboard (interactive ApexCharts report reproducing the SharePoint METRICS 2026 tab from app data) | built | — |
| 3-Year Annual Awards Comparison (Charts tab annual cards: rolling current + 2 prior years) | built | — |

## Environment

This is a PHP application running on a LAMP stack inside Docker (`docker-compose-lamp`). The app is served at `http://localhost/rfq/`.

**Setup requirements (from README):**
- Create a `/tmp` directory at the project root
- Disable MySQL strict mode: add `sql_mode= ""` under `[mysqld]` in `my.cnf`, then restart MySQL
- On production: enable the `xmlwriter` PHP module

**Install dependencies:**
```bash
composer install
```

**Database:** `elogicnewdb` on `database:3306` (Docker service). Schema is in [sql/elogic.sql](sql/elogic.sql).

**Configuration:** Database credentials and server URL are hardcoded in [app/Bootstrap/config.inc.php](app/Bootstrap/config.inc.php). Update `SERVIDOR` constant to match your environment.

**Generate users:** Visit `/genera_usuario` route.

There are no automated tests or lint commands.

## Architecture

This is a custom PHP MVC-like application with no framework.

### Request Lifecycle

1. All requests hit [index.php](index.php) (single entry point)
2. `index.php` registers a custom autoloader mapping class names to `app/{Domain}/{ClassName}.inc.php`
3. URL path is parsed and matched via a large `switch` statement to either:
   - A **script** in `scripts/` (handles form submissions and AJAX/data requests, returns JSON or HTML fragments)
   - A **view** in `vistas/` (renders full pages)
4. Views include template partials from `plantillas/`

### Directory Structure

- `app/` — Domain classes, organized by module. Each module typically has:
  - `{Entity}.inc.php` — Plain PHP object with getters
  - `Repositorio{Entity}.inc.php` or `{Entity}Repository.inc.php` — Static methods using PDO
  - `Bootstrap/` — Core infrastructure: `Conexion` (PDO singleton), `ControlSesion` (session), `Redireccion`, `config.inc.php` (constants), `routes.inc.php` (URL constants)
  - `Utilities/` — Shared utilities: `PDFGenerator` (mPDF), `ExcelRepository` (PhpSpreadsheet), `ProposalRepository`, `Email`, `TeamsIntegration`, `Input`
- `scripts/` — Action handlers organized by domain (quote, re_quote, fulfillment, tracking, etc.)
- `vistas/` — Page entry points: `home.php` (login), `perfil.php` (main dashboard with sub-routing)
- `plantillas/` — HTML template partials included by views, organized by module
- `js/` — Per-module JavaScript files (vanilla JS / jQuery)
- `css/estilos.css` — Single application stylesheet

### Database Access Pattern

All DB access goes through the `Conexion` singleton:
```php
Conexion::abrir_conexion();
$result = SomeRepository::some_method(Conexion::obtener_conexion(), $param);
Conexion::cerrar_conexion();
```

### Domain Modules

The main business domains are: **Quote** (RFQ lifecycle), **ReQuote** (vendor re-quoting), **Fulfillment** (order fulfillment tracking), **Tracking**, **Invoice**, **SalesCommission**, **Projection** (yearly/monthly financials), **Task**, **Personnel**, **Service**, **Provider**, **PaymentTerm**, **Room**, **TypeOfBid**, **TypeOfContract**, **TypeOfProject**.

### URL / Route Constants

All URL constants are defined in [app/Bootstrap/routes.inc.php](app/Bootstrap/routes.inc.php) and used throughout templates and scripts to build links and form actions. When adding new routes, define the constant there and add the corresponding `case` in `index.php`.

### Quote Status Flow

A quote (`Rfq`) progresses through: Created → Completed → Submitted → Award → Fulfillment → Invoice. The `comments` field encodes special statuses (No Bid, Cancelled, Not submitted). The `isEnabledToFulfillment()` and `isEnabledToInvoice()` methods on the `Rfq` class enforce prerequisites for state transitions.

### SharePoint Sheet Sync — row-pointer invariants

The DB column `rfq.sheet_row` is the source of truth for which sheet row a quote owns; the sheet itself (Graph `usedRange`) is only **eventually consistent**, so never decide append-vs-update by scanning it when a pointer exists.
- `syncRow($sheetRow,…)` is **self-healing**: it reads the full `A:T` of `$sheetRow`, verifies column A equals the quote id before writing; if it drifted it re-resolves via `appendRow` (column-A scan) and returns the corrected row — callers must persist the returned value.
- `appendRow` reads row-count + column A to find/append; before overwriting an existing row it reads that row's `A:T` so human columns survive.
- After `deleteRow` (shift='Up'), call `SheetSyncRepository::shiftRowsAfterDelete()` to decrement pointers below the deleted row.
- **Per-quote `sync_to_sheet` flag is the sole auto-sync gate** (not bid type, not child/master-link). Creation form's "Sync to pipeline" checkbox sets it (JS smart-defaults it from bid type via the syncable list — all A/V variants + `IT`/`MOVING & LOGISTICS`/`PROFESSIONAL SERVICES`/`Services` — but the user can override). `Sync to Sheet` btn sets flag=1 (+status `synced`); `Break Sync` sets flag=0 (keeps `sheet_row`, status `never`). `copyRfq` sets copies to 0.
- **Read-merge-write column ownership** (`SheetSyncService::mergeAppOwned`): app rewrites A,B,C,D,G,H,J,L,M,N,Q,T each sync; **E (STATUS), F (INTERNAL DUE DATE), I, K, O, P, R, S are human-owned** and preserved (blank on brand-new rows). Status transitions in `guardar_editar_cotizacion.php` no longer push STATUS (that helper is now a no-op).

### Unified Audit Trail

All three domains write to their own audit table but are surfaced through a single modal and endpoint.

**Tables:** `audit_trails` (quote), `re_quote_audit_trails` (re-quote), `fulfillment_audit_trails` (fulfillment). All three have `action_type VARCHAR(50) NULL` and `id_user INT NULL` columns added (nullable, no impact on legacy rows).

**Repositories:**
- `app/Quote/AuditTrailRepository.inc.php` — writes quote audit events; helper methods per action type
- `app/ReQuote/ReQuoteAuditTrailRepository.inc.php` — same pattern for re-quote
- `app/Fulfillment/FulfillmentAuditTrailRepository.inc.php` — adds `status_event()`, `invoice_event()`, `net_30_event()` helpers

**Action types** (stored in `action_type` column): `status_change`, `field_modified`, `item_modified`, `item_created`, `item_deleted`, `invoice_created`, `invoice_updated`, `invoice_deleted`, `document_updated`, `net_30`.

**Unified endpoint:** `POST /rfq/quote/load_unified_audit_trail` (`scripts/quote/load_unified_audit_trail.php`) — accepts `id_rfq`, queries all three tables (re-quote joined via `re_quotes.id_rfq`), merges and sorts by `created_date DESC`, returns JSON array. Each entry has: `id, username, action_type, audit_trail, created_date, scope` (scope values: `quote`, `requote`, `fulfillment`).

**Frontend:** `js/audit_trail.js` — self-contained IIFE; handles open/load/filter/render for all three pages. Included in `editar_cotizacion.inc.php`, `fulfillment.inc.php`, and `re_quote.inc.php`. The trigger buttons (`#audit_trails_button`, `#fulfillment_audit_trails_button`) must have `data-id="<id_rfq>"`.

**Modal templates** (all identical unified shell, `id="audit_trails_modal"`):
- `plantillas/quote/modals/audit_trails_modal.inc.php`
- `plantillas/fulfillment/modals/audit_trails_modal.inc.php` (referenced as `modals/audit_trails_modal.inc.php` from fulfillment.inc.php)
- `plantillas/re_quote/modals/audit_trails_modal.inc.php`

**CSS:** All `at-*` namespace styles live at the bottom of `css/estilos.css`.

### Comment Mentions & Notifications

**DB changes:** Run `sql/notifications_migration.sql` — adds `ms_refresh_token`, `ms_access_token`, `ms_token_expiry`, `ms_email` to `usuarios`; creates `notifications` table.

**New files:**
- `app/Quote/NotificationRepository.inc.php` — CRUD for notifications + `parseMentions()` helper
- `js/mentions.js` — @mention autocomplete IIFE, attaches to `#comment_rfq` on every page
- `plantillas/user/my_account.inc.php` — My Account page (profile edit + MS OAuth connect/disconnect)
- `plantillas/user/notifications.inc.php` — Notifications page (paginated list + mark-all-read)
- `scripts/quote/notifications_stream.php` — SSE endpoint (polls DB every 3s, pushes on count change)
- `scripts/quote/notifications_list.php` — JSON: recent 5 notifications
- `scripts/quote/notifications_mark_read.php` — POST: mark one or all as read
- `scripts/quote/notifications_users_for_mention.php` — JSON: all active users for autocomplete
- `scripts/user/microsoft_connect.php` — initiate MS OAuth redirect
- `scripts/user/microsoft_callback.php` — exchange code → tokens, store in DB
- `scripts/user/microsoft_disconnect.php` — clear MS tokens
- `scripts/user/account_update_profile.php` — JSON: update name/email
- `scripts/user/account_update_password.php` — JSON: update password

**Modified files:**
- `plantillas/utilities/navbar.inc.php` — bell icon + SSE-driven dropdown
- `plantillas/utilities/barra_lateral.inc.php` — My Account sidebar link
- `plantillas/utilities/documento_cierre.inc.php` — includes `mentions.js` + sets `NOTIFICATIONS_USERS_FOR_MENTION_URL`
- `scripts/utilities/guardar_comment.php` — parses @mentions, inserts notifications, sends delegated MS email
- `app/Comment/RepositorioComment.inc.php` — `render_comment_text()` renders @mention chips
- `app/User/RepositorioUsuario.inc.php` — MS token methods + `getAllActiveUsers()` + `getByUsername()`
- `app/Bootstrap/routes.inc.php` — new route constants
- `index.php` — new case handlers for notifications/* and user/microsoft/* and user/account/*
- `vistas/perfil.php` — routes `perfil/account` and `perfil/notifications`

**CSS:** All `nf-*`, `ac-*`, `cm-*` namespace styles appended to `css/estilos.css`.

**Routes:**
| URL | Purpose |
|---|---|
| `perfil/account` | My Account page |
| `perfil/notifications` | Notifications page |
| `user/microsoft/connect` | Start OAuth |
| `user/microsoft/callback` | OAuth callback |
| `user/microsoft/disconnect` | Clear tokens |
| `user/account/update_profile` | JSON profile update |
| `user/account/update_password` | JSON password update |
| `quote/notifications/stream` | SSE stream |
| `quote/notifications/list` | Recent 5 JSON |
| `quote/notifications/mark_read` | Mark read |
| `quote/notifications/users_for_mention` | @mention user list |

### Bid Pipeline Metrics Dashboard

Interactive ApexCharts report at `perfil/reports/pipeline_metrics` (sidebar entry in `reports_sidebar.inc.php`). Computes the 5 METRICS-2026 reports from app data — **all aggregation is in SQL**, never by loading Rfq objects.

**DB:** Run `sql/bid_pipeline_metrics_migration.sql` — adds `rfq.sources_sought TINYINT(1) DEFAULT 0`. Lost-bid buckets reuse `rfq.comments` (`No Award - Pricing` / `No Award - Technical`) — no schema change. Report-only: SharePoint sync is untouched.

**Status derivation:** `PipelineMetricsRepository::STATUS_CASE` is a SQL `CASE` that mirrors `Rfq::getSheetStatus()` exactly (and in order) — keep the two in sync. 10 buckets: tbd, bid, no_bid, submitted, submitted_ss, award, no_award_pricing, no_award_technical, cancelled, not_submitted. Period filter parses the VARCHAR `issue_date` via `STR_TO_DATE(...,'%m/%d/%Y')` (unparseable rows drop out of period views). Category = `type_of_bid`; priced = `completado = 1`.

**Win/Loss (logic owned by spec, not the design's 2-slice donut):** 3-way — denominator = regular `submitted` + `award` + lost (`no_award_*`); **sources-sought excluded**. Donut shows Awarded/No Award/Pending summing to 100%; center = Awarded/denominator (N/A when 0).

**Dollar values (gotcha):** every money figure = product total + services subtotal. `getMetrics`/`getDrillDown` join services via `PipelineMetricsRepository::SERVICES_JOIN` and sum `VALUE_EXPR` (never `rfq.total_price` alone) so the page reconciles with the Charts tab. Count-only aggregations (`categoryBreakdown`, `pricingEffort`, win/loss) deliberately skip the join.

**Key files:** `app/Report/PipelineMetricsRepository.inc.php` (autoloaded — registered under `Report` in `index.php`); endpoints `quote/pipeline_metrics` + `quote/pipeline_metrics_drilldown` + `quote/pipeline_metrics_export` (xlsx via PhpSpreadsheet — `chart=` for one report, omit for the full workbook); view `plantillas/utilities/pipeline_metrics.inc.php`; `js/pipeline_metrics.js` (vanilla, ApexCharts via CDN — Chart.js pages untouched); `pm-*` CSS in `estilos.css`. Capture: checking "Submitted" opens the Sources Sought modal (`js/sources_sought.js`) → hidden `sources_sought` → persisted in `guardar_editar_cotizacion.php`; lost reasons are comments-select options in `information.inc.php`.

**Surfacing the new statuses:** the quote-page status pill (`status_title.inc.php`) shows Sources Sought / No Award - Pricing / No Award - Technical; two listing pages mirror the cancelled/not-submitted pattern — **Sources Sought** (`quote/sources_sought`, `status=1 AND sources_sought=1`) and **No Award** (`quote/no_award`, the two No-Award comments, with a Reason column). Repo list/count uses shared private helpers `getQuotesByCondition` / `countQuotesByCondition`; DataTable inits in `js/main.js`; sidebar links in `sales_sidebar.inc.php`.

**Tests:** `tests/php/pipeline_metrics_test.php` (47 assertions, `docker exec lamp-php83 php …`, transaction-isolated); `tests/specs/09-pipeline-metrics.spec.js` (Playwright E2E).

### Charts Tab — Annual Awards (3-year)

Dashboard `perfil/charts` (`plantillas/utilities/charts.inc.php` + `js/main_charts.js`, **Chart.js** — not the ApexCharts pipeline page). The two Annual Awards cards (by Count `#ganados_anuales_chart`, by Amount `#monto_ganados_anual_chart`) compare a **rolling** window — current year + 2 prior — as grouped monthly columns. Endpoint `scripts/utilities/main_charts.php` builds `[Y-2, Y-1, Y]` and returns `annual_awards_years` (per year: totals + 12 monthly points) from `RepositorioRfq::getAnnualAwardsDataByMonthForYears($conn, $years)`; award value = product + services subtotal (same canon as Pipeline Metrics). JS builds the 3 datasets and the 3-row legend (newest first) into `#annual_awards_*_legend`. Ramp oldest→newest: `#aebccb`, `#5e83a4`, `#13A8F0`. The per-user Completed/Awards cards are separate (current vs last month, unchanged). Test: `tests/php/annual_awards_test.php`.
