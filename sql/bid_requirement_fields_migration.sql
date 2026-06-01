-- Add bid requirement fields: site_visit, resumes, qa (YES/NO toggles),
-- qa_deadline (datetime), internal_due_date (date, syncs to sheet col F)
ALTER TABLE rfq
  ADD COLUMN site_visit        TINYINT(1) NULL DEFAULT NULL,
  ADD COLUMN resumes           TINYINT(1) NULL DEFAULT NULL,
  ADD COLUMN qa_deadline       DATETIME   NULL DEFAULT NULL,
  ADD COLUMN qa                TINYINT(1) NULL DEFAULT NULL,
  ADD COLUMN internal_due_date DATE       NULL DEFAULT NULL;
