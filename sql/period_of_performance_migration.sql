-- Period of Performance: optional date range on a quote representing the period during
-- which the awarded contract is active. Editable from the Information drawer only; both
-- sides may be set independently (partial range allowed). No backfill — existing quotes
-- correctly have no Period of Performance.
ALTER TABLE rfq
  ADD COLUMN pop_start_date DATE NULL,
  ADD COLUMN pop_end_date DATE NULL;
