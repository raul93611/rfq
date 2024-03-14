SELECT
  quotes.id,
  quotes.fulfillment_date,
  quotes.contract_number,
  quotes.email_code,
  quotes.nombre_usuario,
  quotes.canal,
  quotes.type_of_bid,
  quotes.total_cost,
  quotes.total_price,
  quotes.profit,
  requotes.total_cost_requote,
  quotes.total_price AS total_price_requote,
  quotes.profit_equipment_requote + quotes.total_service_price - requotes.total_requote_service AS profit_requote,
  quotes.type_of_contract
FROM
  (
    SELECT
      r.id,
      DATE_FORMAT(r.fulfillment_date, '%m/%d/%Y') as fulfillment_date,
      r.contract_number,
      r.email_code,
      u.nombre_usuario,
      r.canal,
      r.type_of_bid,
      SUM(COALESCE(s.total_price, 0)) AS total_service_price,
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
      r.type_of_contract
    FROM
      rfq r
      LEFT JOIN services s ON r.id = s.id_rfq
      LEFT JOIN usuarios u ON r.usuario_designado = u.id
      LEFT JOIN re_quotes rq ON r.id = rq.id_rfq
    WHERE
      deleted = 0
      AND invoice = 1
      AND MONTH(fulfillment_date) = 2
      AND YEAR(fulfillment_date) = 2024
    GROUP BY
      r.id
    ORDER BY
      r.id ASC
  ) as quotes
  LEFT JOIN (
    SELECT
      rq.id_rfq,
      COALESCE(
        COALESCE(rq.total_cost, 0) + SUM(COALESCE(rqs.total_price, 0)),
        0
      ) AS total_cost_requote,
      SUM(COALESCE(rqs.total_price, 0)) as total_requote_service
    FROM
      re_quotes rq
      LEFT JOIN rfq r ON rq.id_rfq = r.id
      LEFT JOIN re_quote_services rqs ON rq.id = rqs.id_re_quote
    WHERE
      r.deleted = 0
      AND r.invoice = 1
      AND MONTH(r.fulfillment_date) = 2
      AND YEAR(r.fulfillment_date) = 2024
    GROUP BY
      rq.id_rfq
  ) AS requotes ON quotes.id = requotes.id_rfq
WHERE
  (
    id LIKE '%%'
    OR DATE_FORMAT(fulfillment_date, '%m/%d/%Y') LIKE '%%'
    OR contract_number LIKE '%%'
    OR email_code LIKE '%%'
    OR nombre_usuario LIKE '%%'
    OR canal LIKE '%%'
    OR type_of_bid LIKE '%%'
    OR type_of_contract LIKE '%%'
  )