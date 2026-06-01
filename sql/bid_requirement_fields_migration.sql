-- Add bid requirement fields: site_visit, resumes (YES/NO toggles), qa_deadline (datetime string)
ALTER TABLE rfq
  ADD COLUMN site_visit  TINYINT(1)   NULL DEFAULT NULL,
  ADD COLUMN resumes     TINYINT(1)   NULL DEFAULT NULL,
  ADD COLUMN qa_deadline VARCHAR(100) NULL DEFAULT NULL;
