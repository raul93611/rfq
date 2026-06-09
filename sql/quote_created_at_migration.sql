-- Quote creation timestamp.
--
-- Adds an automatic, always-present creation date for quotes and makes it the cohort
-- date for the Bid Pipeline Metrics page. issue_date is a hand-typed VARCHAR (MM/DD/YYYY)
-- that can be blank or malformed — when it doesn't parse, those rows silently drop out
-- of the period views. created_at is set by the DB on insert, so it's reliable and is a
-- better proxy for "when the bid entered our pipeline".
--
-- New rows get CURRENT_TIMESTAMP automatically (the INSERT omits the column).
-- The column stays NULL-able so the backfill below can leave unparseable legacy rows
-- NULL — they keep dropping out of period views exactly as they did before.

ALTER TABLE rfq ADD COLUMN created_at DATETIME NULL DEFAULT CURRENT_TIMESTAMP;

-- Backfill existing rows from the typed issue_date (NULL where it can't be parsed).
UPDATE rfq SET created_at = STR_TO_DATE(issue_date, '%m/%d/%Y');
