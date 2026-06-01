-- Add missing type_of_bid options
INSERT INTO type_of_bids (type_of_bid)
SELECT val FROM (
  SELECT 'A/V' AS val UNION ALL
  SELECT 'A/V - INSTALLATION' UNION ALL
  SELECT 'A/V - SERVICES' UNION ALL
  SELECT 'IT' UNION ALL
  SELECT 'MOVING & LOGISTICS' UNION ALL
  SELECT 'PROFESSIONAL SERVICES'
) AS new_values
WHERE val NOT IN (SELECT type_of_bid FROM type_of_bids);
