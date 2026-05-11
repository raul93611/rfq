# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Feature Inventory

| Feature | Status | Spec |
|---|---|---|
| Quote Inline Editing (modals for item/provider/subitem CRUD) | built | [features/quote-inline-editing.md](features/quote-inline-editing.md) |

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
