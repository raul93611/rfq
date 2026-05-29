-- Add Microsoft OAuth token columns to usuarios
ALTER TABLE usuarios
  ADD COLUMN ms_refresh_token TEXT NULL,
  ADD COLUMN ms_access_token  TEXT NULL,
  ADD COLUMN ms_token_expiry  INT NULL,
  ADD COLUMN ms_email         VARCHAR(255) NULL;

-- Notification preference columns
ALTER TABLE usuarios
  ADD COLUMN notif_inapp TINYINT(1) NOT NULL DEFAULT 1,
  ADD COLUMN notif_email TINYINT(1) NOT NULL DEFAULT 1;

-- Notifications table
CREATE TABLE IF NOT EXISTS notifications (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  id_user    INT NOT NULL,
  id_rfq     INT NOT NULL,
  message    VARCHAR(255) NOT NULL,
  url        VARCHAR(500) NOT NULL,
  is_read    TINYINT(1) NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_user_read (id_user, is_read),
  INDEX idx_created (created_at)
);
