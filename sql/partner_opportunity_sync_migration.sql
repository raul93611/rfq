-- Partner Opportunity Sync
-- Two-way, token-authenticated sync of shared opportunities + solicitation documents with the
-- partner capture platform. Additive only: every existing quote stays unshared and unchanged.
--
-- Idempotent (INFORMATION_SCHEMA guards / IF NOT EXISTS / INSERT IGNORE): safe to re-run on
-- local and prod. All partner timestamps are stored in UTC (written from PHP, never NOW()).

-- ---------------------------------------------------------------------------
-- rfq: sharing gate, matching key, pinned partner link, conflict + echo state
-- ---------------------------------------------------------------------------

SET @s = IF(NOT EXISTS(SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='rfq' AND COLUMN_NAME='share_with_partner'),
  'ALTER TABLE rfq ADD COLUMN share_with_partner TINYINT(1) NOT NULL DEFAULT 0', 'SELECT 1');
PREPARE stmt FROM @s; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @s = IF(NOT EXISTS(SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='rfq' AND COLUMN_NAME='solicitation_number'),
  'ALTER TABLE rfq ADD COLUMN solicitation_number VARCHAR(255) NULL', 'SELECT 1');
PREPARE stmt FROM @s; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @s = IF(NOT EXISTS(SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='rfq' AND COLUMN_NAME='solicitation_key'),
  'ALTER TABLE rfq ADD COLUMN solicitation_key VARCHAR(255) NULL', 'SELECT 1');
PREPARE stmt FROM @s; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @s = IF(NOT EXISTS(SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='rfq' AND COLUMN_NAME='partner_record_id'),
  'ALTER TABLE rfq ADD COLUMN partner_record_id VARCHAR(100) NULL', 'SELECT 1');
PREPARE stmt FROM @s; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @s = IF(NOT EXISTS(SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='rfq' AND COLUMN_NAME='partner_linked_at'),
  'ALTER TABLE rfq ADD COLUMN partner_linked_at DATETIME NULL', 'SELECT 1');
PREPARE stmt FROM @s; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @s = IF(NOT EXISTS(SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='rfq' AND COLUMN_NAME='partner_shared_updated_at'),
  'ALTER TABLE rfq ADD COLUMN partner_shared_updated_at DATETIME NULL', 'SELECT 1');
PREPARE stmt FROM @s; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @s = IF(NOT EXISTS(SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='rfq' AND COLUMN_NAME='partner_payload_hash'),
  'ALTER TABLE rfq ADD COLUMN partner_payload_hash CHAR(64) NULL', 'SELECT 1');
PREPARE stmt FROM @s; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @s = IF(NOT EXISTS(SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='rfq' AND COLUMN_NAME='partner_removed_at'),
  'ALTER TABLE rfq ADD COLUMN partner_removed_at DATETIME NULL', 'SELECT 1');
PREPARE stmt FROM @s; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @s = IF(NOT EXISTS(SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='rfq' AND COLUMN_NAME='partner_needs_assignment'),
  'ALTER TABLE rfq ADD COLUMN partner_needs_assignment TINYINT(1) NOT NULL DEFAULT 0', 'SELECT 1');
PREPARE stmt FROM @s; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @s = IF(NOT EXISTS(SELECT 1 FROM information_schema.STATISTICS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='rfq' AND INDEX_NAME='idx_rfq_solicitation_key'),
  'CREATE INDEX idx_rfq_solicitation_key ON rfq (solicitation_key)', 'SELECT 1');
PREPARE stmt FROM @s; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @s = IF(NOT EXISTS(SELECT 1 FROM information_schema.STATISTICS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='rfq' AND INDEX_NAME='idx_rfq_partner_record_id'),
  'CREATE INDEX idx_rfq_partner_record_id ON rfq (partner_record_id)', 'SELECT 1');
PREPARE stmt FROM @s; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- ---------------------------------------------------------------------------
-- partner_documents: per-file share mark + stable file ID (rows appear lazily, shared = 0)
-- partner_file_id is set only for files that ORIGINATED at the partner (never re-offered back).
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS partner_documents (
  id                INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  id_rfq            INT NOT NULL,
  file_name         VARCHAR(255) NOT NULL,
  shared            TINYINT(1) NOT NULL DEFAULT 0,
  partner_file_id   VARCHAR(100) NULL,
  size_bytes        BIGINT UNSIGNED NULL,
  sha256            CHAR(64) NULL,
  shared_updated_at DATETIME NULL,
  removed_at        DATETIME NULL,
  UNIQUE KEY uq_partner_doc (id_rfq, file_name),
  KEY idx_partner_doc_shared (id_rfq, shared)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------------
-- partner_webhook_outbox: light "X changed" notices, drained by the dispatcher cron
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS partner_webhook_outbox (
  id              INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  id_rfq          INT NOT NULL,
  event           VARCHAR(40) NOT NULL,
  payload         TEXT NOT NULL,
  attempts        INT NOT NULL DEFAULT 0,
  next_attempt_at DATETIME NOT NULL,
  delivered_at    DATETIME NULL,
  failed_at       DATETIME NULL,
  last_error      VARCHAR(255) NULL,
  created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY idx_outbox_due (delivered_at, failed_at, next_attempt_at),
  KEY idx_outbox_rfq (id_rfq, event)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------------
-- partner_integration: single row (id = 1), same idea as notification_mailbox
-- issued_token_hash is what the partner presents to us (stored hashed, shown once);
-- partner_token / signing_secret are credentials WE use to call / sign for the partner.
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS partner_integration (
  id                      TINYINT UNSIGNED NOT NULL PRIMARY KEY,
  enabled                 TINYINT(1) NOT NULL DEFAULT 0,
  issued_token_hash       CHAR(64) NULL,
  issued_token_last4      CHAR(4) NULL,
  token_created_at        DATETIME NULL,
  token_created_by        INT NULL,
  token_revoked_at        DATETIME NULL,
  token_last_used_at      DATETIME NULL,
  partner_base_url        VARCHAR(500) NULL,
  partner_token           TEXT NULL,
  partner_webhook_url     VARCHAR(500) NULL,
  signing_secret          TEXT NULL,
  last_pull_at            DATETIME NULL,
  last_webhook_success_at DATETIME NULL,
  updated_at              DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO partner_integration (id, enabled) VALUES (1, 0);

-- ---------------------------------------------------------------------------
-- partner_review: incoming records that could not be applied automatically
-- kind: possible (Code equals an unkeyed quote) | ambiguous (>1 match) | rejected (e.g. no assignee)
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS partner_review (
  id                  INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  kind                VARCHAR(20) NOT NULL,
  partner_record_id   VARCHAR(100) NULL,
  solicitation_number VARCHAR(255) NULL,
  candidates          TEXT NULL,
  payload             MEDIUMTEXT NOT NULL,
  reason              VARCHAR(255) NULL,
  created_at          DATETIME NOT NULL,
  resolved_at         DATETIME NULL,
  resolved_by         INT NULL,
  resolution          VARCHAR(40) NULL,
  KEY idx_review_open (resolved_at, kind),
  KEY idx_review_record (partner_record_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------------
-- Seed: the non-login "Partner Integration" service user. Used as rfq.id_usuario on incoming
-- quotes so the audit trail states plainly where they came from. password is not a valid hash
-- (password_verify can never match) and status = 0 blocks login and every `status = 1` list.
-- ---------------------------------------------------------------------------
INSERT INTO usuarios (nombre_usuario, password, nombres, apellidos, cargo, email, status, hash_recover_email)
SELECT 'partner_integration', '!', 'Partner', 'Integration', '0', 'partner-integration@system.invalid', 0, ''
WHERE NOT EXISTS (SELECT 1 FROM usuarios WHERE nombre_usuario = 'partner_integration');
