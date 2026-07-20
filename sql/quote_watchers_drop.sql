-- Pipeline Table View + Quote Watchers removal
-- Drops the quote_watchers table (see quote_watchers_migration.sql for the original schema).
-- The feature — per-quote watch subscriptions and their in-app/email fan-out — has been
-- fully retired; the shared Microsoft mailbox and in-app notification infrastructure stay,
-- they also power @mention comment notifications.
--
-- Idempotent: safe to run on local and prod.

DROP TABLE IF EXISTS quote_watchers;
