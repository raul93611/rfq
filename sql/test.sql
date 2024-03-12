SELECT
  *
FROM
  (
    SELECT
      r.id,
      DATE_FORMAT(r.invoice_date, '%m/%d/%Y') as invoice_date,
      r.contract_number,
      u.nombre_usuario,
      r.state,
      r.client,
      COALESCE(
        COALESCE(r.total_cost, 0) + SUM(COALESCE(s.total_price, 0)),
        0
      ) AS total_cost,
      COALESCE(
        COALESCE(r.total_price, 0) + SUM(COALESCE(s.total_price, 0)),
        0
      ) AS total_price,
      COALESCE(
        COALESCE(r.total_price, 0) + SUM(COALESCE(s.total_price, 0)),
        0
      ) - COALESCE(
        COALESCE(r.total_cost, 0) + SUM(COALESCE(s.total_price, 0)),
        0
      ) as profit,
      COALESCE(r.total_price, 0) - COALESCE(rq.total_cost, 0) as profit_equipment_requote,
      COALESCE(r.total_fulfillment, 0) + COALESCE(r.total_services_fulfillment, 0) AS total_cost_fulfillment,
      COALESCE(
        COALESCE(r.total_price, 0) + SUM(COALESCE(s.total_price, 0)),
        0
      ) AS total_price_fulfillment,
      COALESCE(r.total_price, 0) - COALESCE(r.total_fulfillment, 0) as profit_equipment_fulfillment,
      SUM(COALESCE(s.total_price, 0)) - COALESCE(r.total_services_fulfillment, 0) as profit_service_fulfillment,
      r.type_of_contract,
      CASE
        r.sales_commission
        WHEN 'Other commission' THEN ROUND(
          (
            COALESCE(r.total_price, 0) - COALESCE(r.total_fulfillment, 0)
          ) * 0.03,
          2
        )
        WHEN 'Same commission' THEN ROUND(
          (
            COALESCE(r.total_price, 0) - COALESCE(rq.total_cost, 0)
          ) * 0.03,
          2
        )
        WHEN 'No commission' THEN 0
      END as sales_commission
    FROM
      rfq r
      LEFT JOIN services s ON r.id = s.id_rfq
      LEFT JOIN usuarios u ON r.usuario_designado = u.id
      LEFT JOIN re_quotes rq ON r.id = rq.id_rfq
    WHERE
      deleted = 0
      AND invoice = 1
      AND MONTH(invoice_date) = 2
      AND YEAR(invoice_date) = 2024
    GROUP BY
      r.id
    ORDER BY
      `r`.`id` ASC
  ) as quotes
  LEFT JOIN (
    SELECT
      rq.id_rfq,
      COALESCE(
        COALESCE(rq.total_cost, 0) + SUM(COALESCE(rqs.total_price, 0)),
        0
      ) AS total_cost_requote,
      SUM(COALESCE(s.total_price, 0)) - SUM(COALESCE(rqs.total_price, 0)) as profit_service_requote,
    FROM
      re_quotes rq
      LEFT JOIN rfq r ON rq.id_rfq = r.id
      LEFT JOIN re_quote_services rqs ON rq.id = rqs.id_re_quote
    WHERE
      r.deleted = 0
      AND r.invoice = 1
      AND MONTH(r.invoice_date) = 2
      AND YEAR(r.invoice_date) = 2024
    GROUP BY
      rq.id_rfq
  ) AS requotes ON quotes.id = requotes.main_id;