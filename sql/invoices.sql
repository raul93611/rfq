SELECT combined.id_invoice,
  i.name AS invoice_name,
  SUM(combined.item_total_price) AS total_item_price,
  SUM(combined.sum_real_cost) AS total_real_cost,
  SUM(combined.profit) AS total_profit
FROM (
    (
      SELECT fi.id_invoice,
        i.total_price AS item_total_price,
        SUM(fi.real_cost) AS sum_real_cost,
        i.total_price - SUM(fi.real_cost) AS profit
      FROM item i
        JOIN fulfillment_items fi ON i.id = fi.id_item
      WHERE i.id_rfq = 76061
        AND fi.id_invoice IS NOT NULL
      GROUP BY i.id,
        fi.id_invoice
    )
    UNION ALL
    (
      SELECT fsi.id_invoice,
        si.total_price AS item_total_price,
        SUM(fsi.real_cost) AS sum_real_cost,
        si.total_price - SUM(fsi.real_cost) AS profit
      FROM subitems si
        JOIN fulfillment_subitems fsi ON si.id = fsi.id_subitem
      WHERE si.id_item IN (
          SELECT id
          FROM item
          WHERE id_rfq = 76061
        )
        AND fsi.id_invoice IS NOT NULL
      GROUP BY si.id,
        fsi.id_invoice
    )
    UNION ALL
    (
      SELECT fs.id_invoice,
        s.total_price AS item_total_price,
        SUM(fs.real_cost) AS sum_real_cost,
        s.total_price - SUM(fs.real_cost) AS profit
      FROM services s
        JOIN fulfillment_services fs ON s.id = fs.id_service
      WHERE s.id_rfq = 76061
        AND fs.id_invoice IS NOT NULL
      GROUP BY s.id,
        fs.id_invoice
    )
  ) AS combined
  JOIN invoices i ON combined.id_invoice = i.id
GROUP BY combined.id_invoice,
  i.name;