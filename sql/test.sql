SELECT 
  FORMAT(SUM(projected_amount), 2) AS total_projected_amount,
  FORMAT(SUM(projected_result), 2) AS total_projected_result,
  FORMAT(SUM(total_price), 2) AS total_total_price,
  FORMAT(SUM(profit), 2) AS total_profit
FROM
(
  SELECT
    monthly_projections.id,
    monthly_projections.month,
    monthname(str_to_date(monthly_projections.month, '%m')) as month_name,
    monthly_projections.projected_amount AS projected_amount,
    SUM(total_final_result.total_profit) - monthly_projections.projected_amount AS projected_result,
    SUM(total_final_result.total_price) AS total_price,
    SUM(total_final_result.total_cost) AS total_cost,
    SUM(total_final_result.total_profit) AS profit,
    NULL as options
  FROM
  (
    SELECT 
      invoice_date,
      total_price,
      total_cost,
      profit,
      sales_commission,
      (profit - COALESCE(sales_commission, 0)) AS total_profit
    FROM
    (
      (
        SELECT r.id AS id_quote,
          r.invoice_date as invoice_date,
          r.id,
          r.type_of_contract,
          COALESCE(COALESCE(r.total_price, 0) + SUM(COALESCE(s.total_price, 0)), 0) AS total_price,
          COALESCE(r.total_fulfillment, 0) + COALESCE(r.total_services_fulfillment, 0) AS total_cost,
          COALESCE(COALESCE(r.total_price, 0) + SUM(COALESCE(s.total_price, 0)), 0) - (COALESCE(r.total_fulfillment, 0) + COALESCE(r.total_services_fulfillment, 0)) as profit,
          100 * ((COALESCE(COALESCE(r.total_price, 0) + SUM(COALESCE(s.total_price, 0)), 0) - (COALESCE(r.total_fulfillment, 0) + COALESCE(r.total_services_fulfillment, 0))) / (COALESCE(COALESCE(r.total_price, 0) + SUM(COALESCE(s.total_price, 0)), 0))) AS profit_percentage,
          CASE r.sales_commission
            WHEN 'Other commission' THEN ROUND((COALESCE(r.total_price, 0) - COALESCE(r.total_fulfillment, 0)) * 0.03, 2)
            WHEN 'Same commission' THEN ROUND((COALESCE(r.total_price, 0) - COALESCE(rq.total_cost, 0)) * 0.03, 2)
            WHEN 'No commission' THEN 0
          END as sales_commission
        FROM rfq r
          LEFT JOIN services s ON r.id = s.id_rfq
          LEFT JOIN re_quotes rq ON r.id = rq.id_rfq
          LEFT JOIN re_quote_services rqs ON rq.id = rqs.id_re_quote
        WHERE deleted = 0
          AND invoice = 1
          AND r.fulfillment_pending = 0
          AND YEAR(invoice_date) = (
            SELECT y.year
            FROM yearly_projections as y
            WHERE y.id = {$id}
          )
        GROUP BY r.id
      )
      UNION
      (
        SELECT 
          invoices_result_with_sales_commission.id_quote,
          invoices_result_with_sales_commission.invoice_date,
          invoices_result_with_sales_commission.id,
          invoices_result_with_sales_commission.type_of_contract,
          invoices_result_with_sales_commission.total_price,
          invoices_result_with_sales_commission.total_cost,
          invoices_result_with_sales_commission.profit,
          invoices_result_with_sales_commission.profit_percentage,
          CASE invoices_result_with_sales_commission.attached_sales_commission
            WHEN 1 THEN 
              CASE r.sales_commission
                WHEN 'Other commission' THEN ROUND((COALESCE(r.total_price, 0) - COALESCE(r.total_fulfillment, 0)) * 0.03, 2)
                WHEN 'Same commission' THEN ROUND((COALESCE(r.total_price, 0) - COALESCE(rq.total_cost, 0)) * 0.03, 2)
                WHEN 'No commission' THEN 0
              END
            ELSE NULL
          END as sales_commission
        FROM (
          SELECT r.id as id_quote,
            i.created_at as invoice_date,
            i.name AS id,
            r.type_of_contract as type_of_contract,
            SUM(invoices_result.item_total_price) AS total_price,
            SUM(invoices_result.sum_real_cost) AS total_cost,
            SUM(invoices_result.profit) AS profit,
            100 * ((SUM(invoices_result.profit)) / (SUM(invoices_result.item_total_price))) as profit_percentage,
            i.sales_commission AS attached_sales_commission
          FROM (
              (
                SELECT fi.id_invoice,
                  i.total_price AS item_total_price,
                  SUM(fi.real_cost) AS sum_real_cost,
                  i.total_price - SUM(fi.real_cost) AS profit
                FROM item i
                  JOIN fulfillment_items fi ON i.id = fi.id_item
                WHERE i.id_rfq IN (
                    SELECT id
                    FROM rfq
                    WHERE fulfillment_pending = 1
                      AND invoice = 1
                  )
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
                    WHERE id_rfq IN (
                        SELECT id
                        FROM rfq
                        WHERE fulfillment_pending = 1
                          AND invoice = 1
                      )
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
                WHERE s.id_rfq IN (
                    SELECT id
                    FROM rfq
                    WHERE fulfillment_pending = 1
                      AND invoice = 1
                  )
                  AND fs.id_invoice IS NOT NULL
                GROUP BY s.id,
                  fs.id_invoice
              )
            ) AS invoices_result
            RIGHT JOIN invoices i ON invoices_result.id_invoice = i.id
            RIGHT JOIN rfq r ON r.id = i.id_rfq
          WHERE 
            YEAR(i.created_at) = (
              SELECT y.year
              FROM yearly_projections as y
              WHERE y.id = {$id}
            )
          GROUP BY invoices_result.id_invoice, i.name
        ) as invoices_result_with_sales_commission
        LEFT JOIN rfq r ON r.id = invoices_result_with_sales_commission.id_quote
        LEFT JOIN re_quotes rq ON r.id = rq.id_rfq
        GROUP BY invoices_result_with_sales_commission.id
      )
    ) AS final_result
  ) AS total_final_result
  RIGHT JOIN monthly_projections ON MONTH(total_final_result.invoice_date) = monthly_projections.month
  WHERE monthly_projections.yearly_projection_id = {$id}
  GROUP BY monthly_projections.month
) AS totals