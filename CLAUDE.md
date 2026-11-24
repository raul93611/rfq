# CLAUDE.md

## Feature Inventory

All built; non-obvious ones detailed in `###` below.

Quote Inline Editing · Comment Mentions & Notifications · Bid Requirement Fields · Pipeline Metrics Dashboard · Pipeline Table View · 3-Year Annual Awards · Quote Lifecycle Audit Events · Advanced Quote Search · Commercial Moving + 50/50 term · Shared Notification Mailbox · Daily RFQ Digest · Checklist & Info Drawer · Documents Drawer · Import Items Enhancements · Items & Services Table Redesign · Working on It status · Partner Opportunity Sync · Automatic Sales Commission · Retire Tracking Sheet

## Environment

PHP LAMP app in Docker (`docker-compose-lamp`), served at `http://localhost/rfq/`.

- **Setup:** create `/tmp` at project root; disable MySQL strict mode (`sql_mode=""` under `[mysqld]`, restart); prod also needs the `xmlwriter` PHP module. `composer install`. Users: `/genera_usuario`.
- **Database:** `elogicnewdb` on `database:3306`, schema [sql/elogic.sql](sql/elogic.sql), migrations in `sql/` (run once per environment — list in [DEPLOYMENT.md](DEPLOYMENT.md)). There is **no `.env`**: config constants live in `app/Bootstrap/config.inc.php`, **gitignored** — add new constants by hand per environment; code must degrade safely when one is undefined. **Mixed charsets:** `subitems.brand/brand_project/part_number*/website/additional` and `invoices.name` are latin1 — a `LIKE` on them needs `CONVERT(col USING utf8mb4)`, or a non-latin1 term fails with MySQL 1267.
- **Tests:** PHP `tests/php/` (`docker exec lamp-php84 php /var/www/html/rfq/tests/php/<file>`; DB-backed, transaction-rolled-back, so **no DDL inside a test**); Node `tests/js/` (`node --test tests/js/*.test.js`); Playwright `tests/specs/` (`cd tests && PW_CHANNEL=chrome npx playwright test`). No lint. PHP is not on the host.
- **Line endings:** many files are CRLF (`Rfq.inc.php`, `index.php`, `estilos.css`, quote templates); scripted edits can silently convert to LF — check `git diff --stat`.

## Architecture

**Request lifecycle:** every request hits [index.php](index.php), which autoloads from a **whitelist map** (a new class must be added to `$subfolder` there or it will not load), then `switch`es the URL to a **script** in `scripts/` (AJAX/JSON) or a **view** in `vistas/` (`plantillas/` partials). `vistas/perfil.php` echoes the page shell *before* dispatching to a template, so a redirect from inside a `plantillas/` view must use `Redireccion::redirigir1()` (client `<script>`), not `header()`.

**Directories:** `app/{Domain}/` (`Repositorio{Entity}`/`{Entity}Repository` = static PDO methods) · `scripts/` handlers (`cron/` CLI jobs, `api/` token-auth partner API) · `vistas/` pages · `plantillas/` partials · `forms/` · `js/` per-module jQuery · `css/estilos.css` single stylesheet, ported designs use their own `--{prefix}-*` token block and namespace (`pm-`, `qed-`, `it-`, `at-`, `ac-`, `pp-`, `ic-`/`cs-`).

Local CSS/JS go through `asset_url('js/file.js')`, never bare `RUTA_CSS`/`RUTA_JS`.

**DB access:** `Conexion` singleton (`abrir_conexion()`/`obtener_conexion()`); persistent PDO, `cerrar_conexion()` is a no-op.

**Routes:** constants in [app/Bootstrap/routes.inc.php](app/Bootstrap/routes.inc.php) + a `case` in `index.php`.

**Quote status flow:** `Rfq` Created → Completed → Submitted → Award → Fulfillment → Invoice (terminal). `comments` encodes No Bid / Cancelled / Not submitted / Working on it. `Rfq::getSheetStatus()` is the canonical bucket logic (historical name — keep it). **`rfq.name` (Description) is stored raw** (`trim()` only), escaped at render.

### Unified Audit Trail

Sync-group types: `partner_link/unlink/sync_in/removed`, plus legacy sheet types (`sheet_row_*`, `break_sync`, `sync_to_sheet`; render-only) — rendered as compact single rows (`syncType`), never folded. Audit messages are rendered as raw HTML: escape any dynamic value you put in one.

### Comment Mentions, Mailbox, Digest

**One admin-connected MS mailbox sends all system email** (`NotificationMailboxRepository`, OAuth reused via `ms_oauth_target='mailbox'`). Daily Digest (6am America/New_York, `digest_send_log` dedupes per date) goes to every active Admin via that mailbox.

### Pipeline Metrics, Table View, Annual Awards, Advanced Search

All aggregation is **SQL**, never loaded `Rfq` objects. Cohort date is `rfq.created_at`. `PipelineMetricsRepository::STATUS_CASE` must mirror `Rfq::getSheetStatus()` (11 buckets); every consumer reads `STATUSES` dynamically. Win/loss denominator = submitted+award+lost, sources-sought excluded; dollar value = product total **+ services subtotal**. Table view: server-paginated, filters AND-combined, sort only via the `SORT_COLUMNS` whitelist (Internal Due Date / End Date headers; undated last both ways, `id DESC` tiebreak; not a filter, Clear keeps it), a blank date never matches once a bound is set, no quote watchers (removed deliberately). Keep `PipelineTableView` cells in step with the 18-col `<thead>`. `rfq.end_date` is a `VARCHAR` `MM/DD/YYYY HH:mm` — needs `STR_TO_DATE(NULLIF(end_date,''),'%m/%d/%Y %H:%i')`. Drill-down `byUser` needs `usuario_designado` in `getDrillDown()`'s inner SELECT. Annual Awards count by **award date** (`fecha_award`), not issue date, on purpose. Advanced Search reuses `STATUS_CASE` verbatim. Every search term (basic, advanced, invoices) goes through `RepositorioRfq::likeSearchTerm()` (pasted dashes/NBSP/curly quotes → ASCII); search methods rethrow so the endpoint returns `{error}` JSON.

### Quote Edit drawer (Checklist / Information / Documents)

Slide-over drawer `qed-*`; all tab forms stay mounted (hidden), ids prefixed `cl_`. Save endpoints always return JSON and the client must append `save_checklist=1` / `save_information=1` (FormData omits the submit button's name). Description, Internal Due Date and End Date are required in New Quote and the Information drawer (blank Description would make the partner silently drop the quote; `buildPayload()` falls back to the Solicitation # for legacy blank rows) (New Quote's `#end_date` picker needs its own `autoUpdateInput: false`). Letter of Intent / Submission Method options live only in `Rfq::*_OPTIONS`; anything else → NULL (`Rfq::pickOption`). Period of Performance lives in the Checklist drawer only. `js/document_widget.js` must load in `<head>` before `checklist_info_drawer.js`.

### Pricing notes

Commercial Moving + `50% Upfront / 50% on Completion` are literal strings, no calc change (50/50 is a schedule, ×1 like Net 30; mirrored all-or-nothing in `js/quote.js`/`reQuote.js`). Proposal PDF terms: `ProposalRepository::resolve_payment_terms()` uses `services_payment_term` only for a services-type quote with at least one service line. **Re-quote services are their own re-solicited cost line**, not margin-neutral — don't "fix" them to cancel to zero. Re-quote CC: the submit handler and `calcServices()` preview must both select `$('[name=payment_terms]')` and use the same `1.03` constant as `ReQuoteServiceRepository::calc_items_with_CC()`.

### Items & Import

Items table rows stay real `<table>` rows for `js/quote.js`'s `td:eq()` loop (12 columns — indices in `calcQuoteTable()`/`populateCalcArrays()`); `refreshItemsTable()` is the single choke point for every mutation (capture/restore `scrollTop`). Provider/item names store raw, escaped at render (`sql/provider_name_unescape_backfill.sql` once on prod); re-quote provider paths are deliberately left as-is. Import: replace mode deletes subitems **before** items (FK).

### Partner Opportunity Sync

Two-way sync of opportunities + files (`app/Partner/`); the payload is mapped only in `PartnerSyncService::buildPayload()` / `normalizeIncoming()` / `applyToQuote()` (**field list provisional until the partner's sample payload arrives**).

- **Gate:** `rfq.share_with_partner` is the sole outbound gate; Solicitation # is required only while on, its normalized key (trim + UPPER + collapse spaces) unique among non-deleted quotes. Partner state is never written via `insertar_cotizacion` — `PartnerShareRepository::saveShareSettings()` + `PartnerSyncService::reconcile()` run after the main save.
- **`reconcile($c, $idRfq)`** is the one idempotent outbound call (payload hash detects real change; tombstones on unshare/delete; restores; shared-file changes). Called from save/delete/restore/upload hooks (must never fail the user action) and the every-minute `scripts/cron/partner_webhook_dispatch.php`. Unrelated edits enqueue nothing.
- **Inbound** (`applyIncoming`/`applyRemoval`): pinned `partner_record_id` → key match (1 = link, >1 = review) → unkeyed quote whose Code equals the key = review `possible` → create (service user `partner_integration`; assignee by email else default + `partner_needs_assignment`). Latest save wins on `partner_shared_updated_at` (UTC; incoming `<=` ours ignored; we adopt *their* timestamp). **Applying incoming never enqueues a notice (no echo).** Removal detaches the link and hides the quote only if untouched (Created, unpriced, no items). Only pre-Award statuses No Bid / Cancelled / Not submitted are applied. **All inbound entry points (incl. `resolveReview`) run under a per-record MySQL lock (`withRecordLock`)** — twins were created without it; inside a lock call only the `*Unlocked` bodies. Lock timeout → `busy`: webhook 503, `pull()` keeps `last_pull_at`, review 409.
- **API**: Bearer token compared by hash; any auth failure is a **bare 401** (also when disabled). Webhooks also need `X-Signature` = HMAC-SHA256(`{timestamp}.{body}`) within 5 min; one `signing_secret` signs both directions. Only `shared=1`, non-generated, non-partner-origin files are listable.
- **Files:** marks in `partner_documents` (lazy rows). App-generated PDFs and partner-origin files can never be shared — enforced in `setShared()`. Inbound downloads send the partner credential **only to the partner's host**, verify sha256, sanitize names, never overwrite ours.
- **Delivery:** outbox coalesced per (quote, event); back-off 1m/5m/30m/2h/12h then `failed_at`. `partner_pull.php` (15 min) catches up from enable time. Crons are CLI-only, locked; run by hand as `docker exec -u www-data …`.
- **Needs assignment:** the owner field is on **both** Checklist and Information tabs, so both saves clear `partner_needs_assignment` on owner change.
- **Admin** (`perfil/admin/settings`, `admin/partner/*`, POST + `X-Requested-With`): issued token stored hashed, shown once; stored secrets never echoed (blank = keep). `partner_review` holds ambiguous/possible/rejected records. **Known limits:** destroying a deleted shared quote loses its tombstone; "last synced" = last shared-field save (detected within a minute for edits outside the hooks).

### Automatic Sales Commission

Calculated on the server at invoice time and **frozen** on the quote; the client posts nothing about it. `SalesCommissionCalculator::forQuote()` → `RepositorioRfq::set_sales_commission()` writes `rfq.sales_commission_{amount,basis_profit,fulfillment_pct,reason}` plus the legacy `sales_commission` string so report filters work. **`sales_commission_amount IS NULL` = legacy quote** (manual choice + old CASE, never backfilled): check with `Rfq::obtener_saved_sales_commission()` in PHP, `SalesCommissionCalculator::amountSql('r','rq')` in SQL (queries declare `$commissionSql` — a heredoc can't call a static). Rate, the 6.00% threshold (on the *rounded* fulfillment %) and ineligible types live only in the calculator; profits are equipment-only. Re-invoicing recalculates. Comment stored raw, escaped at render.

## Known Bugs

- **noted, not specced** — `utilities/search_quotes` + `search_invoices` answer without a session; their `{error}` JSON echoes the raw SQL message.
- **noted, not specced** — `order[0][dir]` is interpolated unvalidated into `ORDER BY` (search endpoints + many `*ByChannel` methods) → SQL injection; unauthenticated via the two endpoints above.
