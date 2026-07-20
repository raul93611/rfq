-- Daily RFQ Digest Email
-- Dedup guard for the once-a-day cron send (scripts/cron/daily_digest.php). One row per
-- calendar day the digest completed a run — a second trigger the same day no-ops instead
-- of double-sending, regardless of whether the shared mailbox was connected.
--
-- Idempotent: safe to run on local and prod.

CREATE TABLE IF NOT EXISTS digest_send_log (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  digest_date DATE NOT NULL UNIQUE,
  sent_at     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);
