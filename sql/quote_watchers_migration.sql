-- Quote Watchers
-- Per-quote subscription so any staff member can "watch" a quote and receive an in-app
-- notification (and an email, if the shared notification mailbox is connected) when its
-- status, Type of Bid, Designated User, or Comments field changes. The quote's Designated
-- User is auto-subscribed on creation and on reassignment.
--
-- Idempotent: safe to run on local and prod.

CREATE TABLE IF NOT EXISTS quote_watchers (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  id_rfq     INT NOT NULL,
  id_user    INT NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uniq_rfq_user (id_rfq, id_user),
  INDEX idx_rfq (id_rfq),
  INDEX idx_user (id_user)
);
