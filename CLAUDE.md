# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

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
