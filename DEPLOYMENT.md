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
| `PARTNER_DEFAULT_ASSIGNEE_EMAIL` | Partner Opportunity Sync: `usuarios.email` that owns quotes created from a partner record when the partner's assignee is unknown (marks them *Needs assignment*). Must exist and be active; if it does not, incoming records are rejected and listed under **Needs review**, never dropped |
| `PARTNER_DEFAULT_CHANNEL` | Channel for partner-created quotes (default `FBO` if undefined) |
| `PARTNER_DEFAULT_TYPE_OF_BID` | Type of Bid for partner-created quotes — must exist in `type_of_bids` (default `Services` if undefined) |

> `config.inc.php` is **gitignored** and is a secret on production. A constant added to the code
> is *not* deployed with `git push` — add the `PARTNER_*` constants above to the production file by hand.

## 2. Database

Create the schema, then apply every migration in `sql/` once, in date order:

```bash
# base schema
docker compose exec -T database mysql -uroot -p"$DB_PASS" elogicnewdb < sql/elogic.sql

# incremental migrations (run each once per environment, in date order — see sql/)
for f in sql/sheet_sync_migration.sql \
         sql/notifications_migration.sql \
         sql/bid_requirement_fields_migration.sql \
         sql/audit_trails_action_type_migration.sql \
         sql/type_of_bids_migration.sql \
         sql/pipeline_sync_controls_migration.sql \
         sql/bid_pipeline_metrics_migration.sql \
         sql/quote_created_at_migration.sql \
         sql/shared_notification_mailbox_migration.sql \
         sql/commercial_moving_payment_term_migration.sql \
         sql/services_net30cc_rounding_fix.sql \
         sql/daily_digest_migration.sql \
         sql/quote_watchers_drop.sql \
         sql/partner_opportunity_sync_migration.sql; do
  docker compose exec -T database mysql -uroot -p"$DB_PASS" elogicnewdb < "$f"
done
```

On production only, also run `sql/quote_created_at_revert_backfill.sql` after
`quote_created_at_migration.sql` (see that file's header — local keeps a historical
backfill that prod should not).

Also run `sql/rfq_name_unescape_backfill.sql` **once** on production: it decodes quote
names that New Quote used to HTML-escape at save time (`&amp;` → `&`). It decodes one
level per run, so don't re-run it. Rows already written to the SharePoint sheet keep the
old text in column D (sync is write-once) — fix those cells by hand if needed.

`quote_watchers_drop.sql` drops the retired `quote_watchers` table. Skip it if this
environment never ran `quote_watchers_migration.sql`.

`bid_pipeline_metrics_migration.sql` adds `rfq.sources_sought TINYINT(1) DEFAULT 0`
(required by the Bid Pipeline Metrics dashboard). The lost-bid buckets reuse the
existing `rfq.comments` column, so no other schema change is needed.

`partner_opportunity_sync_migration.sql` (Partner Opportunity Sync) is idempotent and additive:
nine `rfq` columns, the `partner_documents` / `partner_webhook_outbox` / `partner_integration` /
`partner_review` tables, and the non-login `partner_integration` service user (status 0 — it can
never sign in). Every existing quote stays unshared and unchanged. The integration is **off**
until an admin enables it.

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

## 4. Cron jobs

Production runs the PHP app directly on the droplet (no Docker there — Docker is local-dev
only, via `docker-compose-lamp`). Add to the droplet's OS crontab:

```
0 6 * * * php /var/www/elogicportal/rfq/scripts/cron/daily_digest.php
```

Sends the Daily RFQ Digest at 6:00am America/New_York to every active Admin-role user.
Requires the Shared Notification Mailbox to be connected (**Admin Settings**) to actually
deliver mail — the script runs and logs `digest_send_log` either way, so a disconnected
mailbox is a silent no-op, not a failure.

Partner Opportunity Sync adds two more jobs (both CLI-only and self-locking, so an overlapping
run exits cleanly). They do nothing while the integration is disabled:

```
* * * * *          php /var/www/elogicportal/rfq/scripts/cron/partner_webhook_dispatch.php
0,15,30,45 * * * * php /var/www/elogicportal/rfq/scripts/cron/partner_pull.php
```

`partner_webhook_dispatch.php` (every minute) notices changes made by any code path and
delivers queued webhooks with a 1m / 5m / 30m / 2h / 12h back-off; `partner_pull.php` (every
15 minutes) is the catch-up for missed webhooks, removals included.

If the job silently never runs, check that cron's minimal environment actually resolves
`php` — swap in the absolute binary path (e.g. `/usr/bin/php`, or a versioned binary like
`/usr/bin/php8.4`) if so.

## 5. Third-party services

- **Microsoft Graph / SharePoint** — `GRAPH_*` constants drive the bid-pipeline
  sheet sync and delegated mention emails. Register the app in Entra ID and set the
  tenant/client/secret + target file id. Optional; the app runs without it.
- **Teams webhooks** — `WEBHOOK_AWARD` / `WEBHOOK_FULFILLMENT` (optional).
- **Partner capture platform** — configured at runtime in **Admin Settings → Partner
  Integration** (admin only), not in `config.inc.php`: partner base URL, partner webhook URL,
  the token the partner issued to us, and a shared signing secret. Then use **Issue token** and
  send the partner the one-time token. Our endpoints (all need `Authorization: Bearer <token>`):
  `GET api/partner/opportunities`, `GET api/partner/opportunities/{solicitation_number}`,
  `GET api/partner/files/{id}`, `POST api/partner/webhook` (HMAC-SHA256 `X-Signature` +
  `X-Timestamp`, 5-minute window). Serve over HTTPS. There is no test environment on our side:
  test in production with one or two designated opportunities.

## 6. Health check

After deploy, confirm the app responds and the new dashboard is wired:

```bash
curl -I  http://<host>/rfq/                                   # 200, login page
curl -s  http://<host>/rfq/quote/pipeline_metrics             # {"error":"Unauthorized"} until logged in
```

A `401` JSON (not `404`) from the metrics endpoint confirms routing + autoloading
are healthy.

Partner API (works whether or not the integration is enabled — a bare `401` with an empty
body is the healthy answer without a token; `404` means routing is broken):

```bash
curl -s -o /dev/null -w "%{http_code}\n" http://<host>/rfq/api/partner/opportunities   # 401
``` Logged in, visit **Reports → Bid Pipeline Metrics** and confirm the
KPI cards and charts render for the current year.
