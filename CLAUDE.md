# CLAUDE.md

Guidance for Claude Code when working in this repository.

## Feature Inventory

All built. Detail for the non-obvious ones is in the `###` sections below.

Quote Inline Editing · SharePoint Sheet Sync (write-once) · Comment Mentions & Notifications · Bid Requirement Fields · Bid Pipeline Sync Controls · Pipeline Metrics Dashboard · Pipeline Table View (Internal Due / End Date / Submitted Date / Type of Contract filters, Status by User) · 3-Year Annual Awards · Quote Lifecycle Audit Events · Advanced Quote Search · Commercial Moving + 50/50 term · Shared Notification Mailbox · Daily RFQ Digest · Checklist & Info Drawer (+ Period of Performance) · Documents Drawer · Import Items Enhancements · Items & Services Table Redesign · Working on It status · **Partner Opportunity Sync**

## Environment

PHP app on a LAMP stack inside Docker (`docker-compose-lamp`), served at `http://localhost/rfq/`.

- **Setup:** create `/tmp` at project root; disable MySQL strict mode (`sql_mode=""` under `[mysqld]`, restart); prod also needs the `xmlwriter` PHP module. `composer install`. Users: `/genera_usuario`.
- **Database:** `elogicnewdb` on `database:3306`, schema [sql/elogic.sql](sql/elogic.sql), migrations in `sql/` (run once per environment — list in [DEPLOYMENT.md](DEPLOYMENT.md)). There is **no `.env`**: config constants live in `app/Bootstrap/config.inc.php`, which is **gitignored** — a new constant must be added by hand on every environment, so code must degrade safely when one is undefined.
- **Tests:** PHP `tests/php/` (`docker exec lamp-php83 php /var/www/html/rfq/tests/php/<file>`; DB-backed, transaction-rolled-back, so **no DDL inside a test**); Node `tests/js/` (`node --test`); Playwright `tests/specs/` (`cd tests && PW_CHANNEL=chrome npx playwright test`). No lint. PHP is not on the host.
- **Line endings:** many files are CRLF (`Rfq.inc.php`, `index.php`, `estilos.css`, several quote templates). A script that reads/writes in text mode silently rewrites the whole file to LF and produces a 1000-line diff — check `git diff --stat` after scripted edits.

## Architecture

Custom PHP MVC-like app, no framework.

**Request lifecycle:** every request hits [index.php](index.php), which autoloads from a **whitelist map** (a new class must be added to `$subfolder` there or it will not load), then `switch`es the URL to a **script** in `scripts/` (AJAX/JSON) or a **view** in `vistas/` (`plantillas/` partials). `vistas/perfil.php` echoes the page shell *before* dispatching to a template, so a redirect from inside a `plantillas/` view must use `Redireccion::redirigir1()` (client `<script>`), not `header()`.

**Directories:** `app/{Domain}/` (`{Entity}.inc.php` + `Repositorio{Entity}`/`{Entity}Repository` static PDO methods; `Bootstrap/` config/routes/session; `Utilities/` PDF, Excel, Email, Input) · `scripts/` handlers (`cron/` CLI jobs, `api/` token-auth partner API) · `vistas/` pages · `plantillas/` partials · `forms/` · `js/` per-module jQuery · `css/estilos.css` single stylesheet, ported designs use their own `--{prefix}-*` token block and namespace (`pm-`, `qed-`, `it-`, `at-`, `ac-`, `pp-`).

Local CSS/JS go through `asset_url('js/file.js')` (cache-busts on mtime). Never bare `RUTA_CSS`/`RUTA_JS`.

**DB access** via the `Conexion` singleton (`Conexion::abrir_conexion(); …obtener_conexion()`); persistent PDO, `cerrar_conexion()` is a no-op.

**Domains:** Quote, ReQuote, Fulfillment, Tracking, Invoice, SalesCommission, Projection, Task, Personnel, Service, Provider, PaymentTerm, Room, TypeOfBid/Contract/Project, Partner.

**Routes:** constants in [app/Bootstrap/routes.inc.php](app/Bootstrap/routes.inc.php) + a `case` in `index.php`.

**Quote status flow:** `Rfq` Created → Completed → Submitted → Award → Fulfillment → Invoice (terminal). `comments` encodes No Bid / Cancelled / Not submitted / Working on it. `Rfq::getSheetStatus()` is the canonical bucket logic.

### SharePoint Sheet Sync — write-once create-or-link

May create a missing row, never overwrites/deletes one. Every path goes through `SheetSyncService::createOrLink()`: presence is decided by **scanning column A** (never `sheet_row`); persists + audits only on **establishment**; no-op = zero Graph writes. **`sync_to_sheet` is the sole auto-sync gate.** App owns columns A,B,C,D,G,H,J,L,M,N,Q,T; E,F,I,K,O,P,R,S are human-owned. **`rfq.name` is stored raw** (`trim()` only) — never `htmlspecialchars()` at save, escape at render; legacy rows: run `sql/rfq_name_unescape_backfill.sql` once on prod.

### Unified Audit Trail

Quote / re-quote / fulfillment audit tables merged by `quote/load_unified_audit_trail`; `js/audit_trail.js`. Sync-group types: `sheet_row_created/linked`, `break_sync`, legacy `sync_to_sheet`, and `partner_link/unlink/sync_in/removed` — rendered as compact single rows (`syncType`), never folded into "N automatic syncs". Audit messages are rendered as raw HTML: escape any dynamic value you put in one.

### Comment Mentions, Mailbox, Digest

`@mention` → `NotificationRepository::parseMentions()` → notification rows + SSE bell (`notifications_stream.php`, 3s poll) + email. **One admin-connected MS mailbox sends all system email** (`NotificationMailboxRepository`, OAuth reused via `ms_oauth_target='mailbox'`; admin UI `perfil/admin/settings`). Daily Digest (`scripts/cron/daily_digest.php`, 6am America/New_York, `digest_send_log` dedupes per date) goes to every active Admin via that mailbox.

### Pipeline Metrics, Table View, Annual Awards, Advanced Search

All aggregation is **SQL**, never loaded `Rfq` objects. Cohort date is `rfq.created_at` (prod also runs `sql/quote_created_at_revert_backfill.sql`). `PipelineMetricsRepository::STATUS_CASE` must mirror `Rfq::getSheetStatus()` (11 buckets); every consumer reads `STATUSES` dynamically. Win/loss denominator = submitted+award+lost, sources-sought excluded; dollar value = product total **+ services subtotal**. Table view (`PipelineTableRepository`, `js/pipeline_table.js`): server-paginated, filters AND-combined, a blank date never matches once a bound is set, no quote watchers (removed deliberately). `rfq.end_date` is a `VARCHAR` `MM/DD/YYYY HH:mm` — needs `STR_TO_DATE(NULLIF(end_date,''),'%m/%d/%Y %H:%i')`. Drill-down `byUser` needs `usuario_designado` in `getDrillDown()`'s inner SELECT; the Type of Contract bucket is the raw string (blank/null → `Uncategorized`). Annual Awards count by **award date** (`fecha_award`), not issue date, on purpose. Advanced Search reuses `STATUS_CASE` verbatim.

### Quote Edit drawer (Checklist / Information / Documents)

Slide-over drawer `qed-*`; all tab forms stay mounted (hidden), ids prefixed `cl_`. Save endpoints always return JSON and the client must append `save_checklist=1` / `save_information=1` (FormData omits the submit button's name). Internal Due Date and End Date are required in New Quote and the Information drawer (New Quote's `#end_date` picker needs its own `autoUpdateInput: false`). Period of Performance lives in the Checklist drawer only (`Rfq::getPeriodOfPerformanceDisplay()` is shared by the page render and the AJAX repaint). Documents use `js/document_widget.js` (`doc-*`; `immediate` mode on edit, `deferred` on New Quote) and must load in `<head>` before `checklist_info_drawer.js`.

### Pricing notes

Commercial Moving + `50% Upfront / 50% on Completion` are literal strings, no calc change (50/50 is a schedule, ×1 like Net 30; mirrored all-or-nothing in `js/quote.js`/`reQuote.js`). Proposal PDF terms: `ProposalRepository::resolve_payment_terms()` uses `services_payment_term` only for a services-type quote with at least one service line. **Re-quote services are their own re-solicited cost line**, not margin-neutral — don't "fix" them to cancel to zero. Re-quote CC: the submit handler and `calcServices()` preview must both select `$('[name=payment_terms]')` and use the same `1.03` constant as `ReQuoteServiceRepository::calc_items_with_CC()`.

### Items & Import

Items table rows stay real `<table>` rows for `js/quote.js`'s `td:eq()` loop (12 columns — indices in `calcQuoteTable()`/`populateCalcArrays()`); `refreshItemsTable()` is the single choke point for every mutation (capture/restore `scrollTop`). Provider/item names store raw, escaped at render (`sql/provider_name_unescape_backfill.sql` once on prod); re-quote provider paths are deliberately left as-is. Import (`scripts/quote/import_items.php`): `import_mode` append|replace; replace deletes subitems **before** items (FK).

### Partner Opportunity Sync

Two-way, token-authenticated sync of shared opportunities + solicitation files with the partner platform. Code in `app/Partner/`; the one place the partner payload is mapped is `PartnerSyncService::buildPayload()` / `normalizeIncoming()` / `applyToQuote()` (**field list is provisional until the partner's sample payload arrives**).

- **Gate:** `rfq.share_with_partner` is the sole outbound gate; Solicitation # is required only while it is on; its normalized key (`trim + UPPER + collapse spaces`) must be unique among non-deleted quotes. Partner state is never written through `insertar_cotizacion` — `PartnerShareRepository::saveShareSettings()` + `PartnerSyncService::reconcile()` run after the main save.
- **`reconcile($c, $idRfq)`** is the one idempotent outbound call (hash of the shared payload detects real change, tombstones on unshare/delete, restores, shared-file content changes). Called from save/delete/restore/upload hooks (never allowed to fail the user action) and from `scripts/cron/partner_webhook_dispatch.php` (`scanAll`, every minute) as the safety net. Unrelated edits (pricing, items) enqueue nothing.
- **Inbound** (`applyIncoming`/`applyRemoval`): pinned `partner_record_id` → else key match (1 = link, >1 = review) → else unkeyed quote whose Code equals the key = review `possible` → else create (service user `partner_integration`, assignee by email else default + `partner_needs_assignment`). Latest save wins on `partner_shared_updated_at` (UTC; incoming `<=` ours is ignored; we adopt *their* timestamp). **Applying incoming never enqueues a notice (no echo).** Removal detaches the link and hides the quote only if still untouched (Created, unpriced, no items, not in the sheet). Only pre-Award inbound statuses No Bid / Cancelled / Not submitted are applied.
- **API** (`api/partner/*`, `PartnerApi::handle`, pure function): Bearer token compared by hash; any auth failure is a **bare 401** (also when disabled). Webhook additionally needs `X-Signature` = HMAC-SHA256(`{timestamp}.{body}`) and a timestamp within 5 min; the same `signing_secret` signs both directions. Only `shared=1` non-generated, non-partner-origin files are listable/downloadable.
- **Files:** per-file marks in `partner_documents` (rows appear lazily). App-generated PDFs (`*(proposal|checklist|re_quote_items_table|trackings).pdf`) and partner-origin files can never be shared — enforced in `setShared()`, not just the UI. Inbound downloads send the partner credential **only to the partner's own host**, verify sha256, sanitize names, and never overwrite our files.
- **Delivery:** `partner_webhook_outbox`, notices coalesce per (quote, event); back-off 1m/5m/30m/2h/12h then `failed_at` (admin Retry = one immediate attempt). `scripts/cron/partner_pull.php` (every 15 min) is the catch-up; first pull starts at enable time so enabling never imports history. Both cron scripts are CLI-only and lock against overlap (`PartnerSyncService::acquireLock`; an unopenable lock is an error, not "still running"). Run them by hand as the web user (`docker exec -u www-data …`) so files they write stay deletable from the UI.
- **Needs assignment:** the owner field exists on **both** the Checklist and Information tabs (`Input::print_designated_user`), so both save scripts clear `partner_needs_assignment` on an owner change; an owner outside the RFQ-role list is injected as the selected option so an unchanged save doesn't swap it for the first user. `Rfq::getPartnerSummary()` is the repaint payload both saves return.
- **Admin** (`perfil/admin/settings`, `admin/partner/{settings,token,retry,review}`, POST + `X-Requested-With`): issued token is stored hashed and shown once; stored secrets are never echoed to the page (blank field = keep). `partner_review` holds ambiguous / possible / rejected incoming records.
- **Known limits:** permanently destroying a deleted shared quote loses its tombstone; a shared quote's "last synced" is the last shared-field save (ours or theirs), detected within a minute of the change for edits made outside the save hooks.

Tests: `tests/php/partner_opportunity_sync_test.php`, `tests/specs/15-partner-sync.spec.js`.
