-- Shared Notification Mailbox
-- One admin-connected Microsoft mailbox (e.g. portal@e-logic.us) that sends all system
-- notification emails (@mention comments + quote-watcher alerts). Replaces the per-user
-- delegated connection as the email sender. Single-row table, keyed id = 1.
--
-- Idempotent: safe to run on local and prod. The existing per-user usuarios.ms_* columns
-- are intentionally left in place (unused after this migration) — cleanup is a separate follow-up.

CREATE TABLE IF NOT EXISTS notification_mailbox (
  id               TINYINT UNSIGNED NOT NULL PRIMARY KEY,
  ms_refresh_token TEXT NULL,
  ms_access_token  TEXT NULL,
  ms_token_expiry  INT NULL,
  ms_email         VARCHAR(255) NULL,
  connected_by     INT NULL,
  updated_at       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
