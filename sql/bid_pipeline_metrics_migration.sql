-- Bid Pipeline Metrics migration
-- Adds the Sources Sought submission sub-type flag used by the Bid Pipeline Metrics
-- dashboard. It is a *positive submission* marker (coexists with status = 1) that the
-- win/loss math deliberately excludes, so it gets its own boolean rather than reusing
-- the comments field. Existing submitted quotes default to 0 (regular SUBMITTED).
--
-- The two new "lost bid" buckets (No Award - Pricing / No Award - Technical) are stored
-- in the existing rfq.comments VARCHAR column and need no schema change.
--
-- Run once on each environment (local, staging, production).

ALTER TABLE rfq
  ADD COLUMN sources_sought TINYINT(1) NOT NULL DEFAULT 0;
