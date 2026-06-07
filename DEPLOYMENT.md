# Deployment

The RFQ app is a framework-less PHP application on a LAMP stack (the
`docker-compose-lamp` project provides Apache + PHP + MySQL). It is served from
`/<app-root>/` (locally `http://localhost/rfq/`).

## 1. Configuration

Credentials and the public URL are hardcoded constants in
[app/Bootstrap/config.inc.php](app/Bootstrap/config.inc.php) — there is **no `.env`**.
Set, at minimum, for the target environment:

| Constant | Purpose |
|---|---|
| `NOMBRE_SERVIDOR_DB` | DB host:port (`database:3306` in Docker) |
| `NOMBRE_USUARIO` / `PASSWORD` / `NOMBRE_BD` | MySQL user / password / database |
| `SERVIDOR` | Public base URL (must end with `/`) — every route constant builds on it |
| `GRAPH_*` | Microsoft Graph creds for SharePoint sheet sync (leave blank to disable) |

> Treat `config.inc.php` as a secret on production; do not commit real credentials.

## 2. Database

Create the schema, then apply every migration in `sql/` once, in date order:

```bash
# base schema
docker compose exec -T database mysql -uroot -p"$DB_PASS" elogicnewdb < sql/elogic.sql

# incremental migrations (run each once per environment)
for f in sql/sheet_sync_migration.sql \
         sql/notifications_migration.sql \
         sql/bid_requirement_fields_migration.sql \
         sql/audit_trails_action_type_migration.sql \
         sql/type_of_bids_migration.sql \
         sql/pipeline_sync_controls_migration.sql \
         sql/bid_pipeline_metrics_migration.sql; do
  docker compose exec -T database mysql -uroot -p"$DB_PASS" elogicnewdb < "$f"
done
```

`bid_pipeline_metrics_migration.sql` adds `rfq.sources_sought TINYINT(1) DEFAULT 0`
(required by the Bid Pipeline Metrics dashboard). The lost-bid buckets reuse the
existing `rfq.comments` column, so no other schema change is needed.

Disable MySQL strict mode (the app relies on it): add `sql_mode = ""` under
`[mysqld]` in `my.cnf` and restart the database container.

## 3. Application

```bash
docker compose up -d                       # bring up Apache + PHP + MySQL
docker compose exec webserver composer install   # vendor/ (PhpSpreadsheet, mPDF)
mkdir -p tmp                                # writable scratch dir at app root
```

Production also requires the **`xmlwriter`** PHP module enabled.

Front-end libraries (jQuery, AdminLTE, ApexCharts, Chart.js, FontAwesome) load
from CDNs — no build step.

## 4. Third-party services

- **Microsoft Graph / SharePoint** — `GRAPH_*` constants drive the bid-pipeline
  sheet sync and delegated mention emails. Register the app in Entra ID and set the
  tenant/client/secret + target file id. Optional; the app runs without it.
- **Teams webhooks** — `WEBHOOK_AWARD` / `WEBHOOK_FULFILLMENT` (optional).

## 5. Health check

After deploy, confirm the app responds and the new dashboard is wired:

```bash
curl -I  http://<host>/rfq/                                   # 200, login page
curl -s  http://<host>/rfq/quote/pipeline_metrics             # {"error":"Unauthorized"} until logged in
```

A `401` JSON (not `404`) from the metrics endpoint confirms routing + autoloading
are healthy. Logged in, visit **Reports → Bid Pipeline Metrics** and confirm the
KPI cards and charts render for the current year.
