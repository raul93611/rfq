<?php
class YearlyProjectionRepository {
  public static function array_to_object($sentence) {
    $objects = [];
    while ($row = $sentence->fetch(PDO::FETCH_ASSOC)) {
      $objects[] = new YearlyProjection($row['id'], $row['year']);
    }

    return $objects;
  }

  public static function single_result_to_object($sentence) {
    $row = $sentence->fetch(PDO::FETCH_ASSOC);
    $object = new YearlyProjection($row['id'], $row['year']);

    return $object;
  }

  public static function getById($connection, $id) {
    $item = null;
    if (isset($connection)) {
      try {
        $sql = 'SELECT * FROM yearly_projections WHERE id = :id';
        $sentence = $connection->prepare($sql);
        $sentence->bindValue(':id', $id, PDO::PARAM_STR);
        $sentence->execute();
        $item = self::single_result_to_object($sentence);
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $item;
  }

  public static function projectionExists($connection) {
    if (isset($connection)) {
      try {
        $sql = "
        SELECT COUNT(*) AS projection_count
        FROM yearly_projections
        WHERE year = YEAR(CURDATE());
        ";
        $sentence = $connection->prepare($sql);
        $sentence->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $sentence->fetchColumn();
  }

  public static function save($connection) {
    if (isset($connection)) {
      try {
        $sql = "
        SET @year := YEAR(CURDATE());
        INSERT INTO yearly_projections (year) VALUES (@year);
        SET @yearly_projection_id := LAST_INSERT_ID();
        INSERT INTO monthly_projections (yearly_projection_id, month)
        VALUES
          (@yearly_projection_id, 1),
          (@yearly_projection_id, 2),
          (@yearly_projection_id, 3),
          (@yearly_projection_id, 4),
          (@yearly_projection_id, 5),
          (@yearly_projection_id, 6),
          (@yearly_projection_id, 7),
          (@yearly_projection_id, 8),
          (@yearly_projection_id, 9),
          (@yearly_projection_id, 10),
          (@yearly_projection_id, 11),
          (@yearly_projection_id, 12);
        ";
        $sentence = $connection->prepare($sql);
        $sentence->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function getProjections($connection, $start, $length, $search, $sort_column_index, $sort_direction) {
    $data = [];
    $search = '%' . $search . '%';
    switch ($sort_column_index) {
      case 1:
        $sort_column = 'year';
        break;
      default:
        $sort_column = 'year';
        break;
    }
    if (isset($connection)) {
      try {
        $sql = "
        SELECT id, 
        year, 
        NULL AS options  
        FROM yearly_projections
        WHERE 
        (year LIKE :search)
        ORDER BY {$sort_column} {$sort_direction} LIMIT {$start}, {$length}
        ";
        $sentence = $connection->prepare($sql);
        $sentence->bindValue(':search', $search, PDO::PARAM_STR);
        $sentence->execute();
        while ($row = $sentence->fetch(PDO::FETCH_ASSOC)) {
          $data[] = $row;
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $data;
  }

  public static function getTotalProjectionsCount($connection) {
    if (isset($connection)) {
      try {
        $sql = "
        SELECT COUNT(id)
        FROM yearly_projections 
        ";
        $sentence = $connection->prepare($sql);
        $sentence->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $sentence->fetchColumn();
  }

  public static function getTotalFilteredProjectionsCount($connection, $search) {
    $search = '%' . $search . '%';
    if (isset($connection)) {
      try {
        $sql = "
        SELECT COUNT(id) 
        FROM yearly_projections
        WHERE 
        (year LIKE :search)
        ";
        $sentence = $connection->prepare($sql);
        $sentence->bindValue(':search', $search, PDO::PARAM_STR);
        $sentence->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $sentence->fetchColumn();
  }

  public static function getMonthTotals($connection, $id) {
    $data = [];
    if (isset($connection)) {
      try {
        $sql = "
        SELECT 
          FORMAT(SUM(total_price), 2) AS sum_total_price,
          FORMAT(SUM(sales_commission), 2) AS sum_sales_commission,
          FORMAT(SUM(total_profit), 2) AS sum_total_profit,
          FORMAT(100 * SUM(total_profit) / SUM(total_price), 2) AS sum_total_profit_percentage
        FROM
        (
          SELECT 
            total_price,
            profit,
            profit_percentage,
            sales_commission,
            profit - COALESCE(sales_commission, 0) AS total_profit,
            100 * ((profit - COALESCE(sales_commission, 0)) / (total_price)) AS total_profit_percentage
          FROM
          (
            (
              SELECT 
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
              WHERE deleted = 0
                AND invoice = 1
                AND r.fulfillment_pending = 0
                AND MONTH(invoice_date) = (
                  SELECT m.month
                  FROM monthly_projections as m
                  WHERE m.id = {$id}
                )
                AND YEAR(invoice_date) = (
                  SELECT y.year
                  FROM monthly_projections as m
                    LEFT JOIN yearly_projections y ON m.yearly_projection_id = y.id
                  WHERE m.id = {$id}
                )
              GROUP BY r.id
            )
            UNION
            (
              SELECT 
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
                  DATE_FORMAT(i.created_at, '%m/%d/%Y') as invoice_date,
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
                            AND fullfillment = 1
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
                                AND fullfillment = 1
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
                            AND fullfillment = 1
                        )
                        AND fs.id_invoice IS NOT NULL
                      GROUP BY s.id,
                        fs.id_invoice
                    )
                  ) AS invoices_result
                  RIGHT JOIN invoices i ON invoices_result.id_invoice = i.id
                  RIGHT JOIN rfq r ON r.id = i.id_rfq
                WHERE MONTH(i.created_at) = (
                    SELECT m.month
                    FROM monthly_projections as m
                    WHERE m.id = {$id}
                  )
                  AND YEAR(i.created_at) = (
                    SELECT y.year
                    FROM monthly_projections as m
                      LEFT JOIN yearly_projections y ON m.yearly_projection_id = y.id
                    WHERE m.id = {$id}
                  )
                GROUP BY invoices_result.id_invoice, i.name
              ) as invoices_result_with_sales_commission
              LEFT JOIN rfq r ON r.id = invoices_result_with_sales_commission.id_quote
              LEFT JOIN re_quotes rq ON r.id = rq.id_rfq
              GROUP BY invoices_result_with_sales_commission.id
            )
          ) AS final_result
        )AS total_month
        ";
        $sentence = $connection->prepare($sql);
        $sentence->execute();
        while ($row = $sentence->fetch(PDO::FETCH_ASSOC)) {
          $data[] = $row;
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $data;
  }

  public static function getMonthly($connection, $start, $length, $search, $sort_column_index, $sort_direction, $id) {
    $data = [];
    $search = '%' . $search . '%';
    switch ($sort_column_index) {
      case 1:
        $sort_column = 'month';
        break;
      default:
        $sort_column = 'month';
        break;
    }
    if (isset($connection)) {
      try {
        $sql = "
        SELECT
          monthly_projections.id,
          monthly_projections.month,
          monthname(str_to_date(monthly_projections.month, '%m')) as month_name,
          FORMAT(monthly_projections.projected_amount, 2) AS projected_amount,
          FORMAT(SUM(total_final_result.total_profit) - monthly_projections.projected_amount, 2) AS projected_result,
          FORMAT(SUM(total_final_result.total_price), 2) AS total_price,
          FORMAT(SUM(total_final_result.total_cost), 2) AS total_cost,
          FORMAT(SUM(total_final_result.total_profit), 2) AS profit,
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
                            AND fullfillment = 1
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
                                AND fullfillment = 1
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
                            AND fullfillment = 1
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
        ORDER BY {$sort_column} {$sort_direction} LIMIT {$start}, {$length}
        ";
        $sentence = $connection->prepare($sql);
        $sentence->execute();
        while ($row = $sentence->fetch(PDO::FETCH_ASSOC)) {
          $data[] = $row;
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $data;
  }

  public static function getTotalMonthlyCount($connection, $id) {
    if (isset($connection)) {
      try {
        $sql = "
        SELECT COUNT(id)
        FROM monthly_projections 
        WHERE yearly_projection_id = {$id}
        ";
        $sentence = $connection->prepare($sql);
        $sentence->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $sentence->fetchColumn();
  }

  public static function getTotalFilteredMonthlyCount($connection, $search, $id) {
    $search = '%' . $search . '%';
    if (isset($connection)) {
      try {
        $sql = "
        SELECT COUNT(id) 
        FROM monthly_projections
        WHERE yearly_projection_id = {$id}
        ";
        $sentence = $connection->prepare($sql);
        $sentence->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $sentence->fetchColumn();
  }

  public static function getYearTotals($connection, $id) {
    $data = [];
    if (isset($connection)) {
      try {
        $sql = "
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
                              AND fullfillment = 1
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
                                  AND fullfillment = 1
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
                              AND fullfillment = 1
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
        ";
        $sentence = $connection->prepare($sql);
        $sentence->execute();
        while ($row = $sentence->fetch(PDO::FETCH_ASSOC)) {
          $data[] = $row;
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $data;
  }

  public static function getMonth($connection, $start, $length, $search, $sort_column_index, $sort_direction, $id) {
    $data = [];
    $search = '%' . $search . '%';
    switch ($sort_column_index) {
      case 0:
        $sort_column = 'rfq_id';
        break;
      case 1:
        $sort_column = 'invoice_date';
        break;
      case 2:
        $sort_column = 'id';
        break;
      case 3:
        $sort_column = 'type_of_contract';
        break;
      case 4:
        $sort_column = 'total_cost';
        break;
      case 5:
        $sort_column = 'total_price';
        break;
      case 6:
        $sort_column = 'profit';
        break;
      case 7:
        $sort_column = 'profit_percentage';
        break;
      case 8:
        $sort_column = 'sales_commission';
        break;
      default:
        $sort_column = 'invoice_date';
        break;
    }
    if (isset($connection)) {
      try {
        $sql = "
        SELECT 
          id_quote,
          invoice_date,
          id,
          type_of_contract,
          FORMAT(COALESCE(total_price, 0), 2) AS total_price,
          FORMAT(COALESCE(total_cost, 0), 2) AS total_cost,
          FORMAT(COALESCE(profit, 0), 2) AS profit,
          FORMAT(COALESCE(profit_percentage, 0), 2) AS profit_percentage,
          COALESCE(sales_commission, 0) AS sales_commission,
          FORMAT(COALESCE(profit - COALESCE(sales_commission, 0), 0), 2) AS total_profit,
          FORMAT(COALESCE(100 * ((profit - COALESCE(sales_commission, 0)) / (total_price)), 0), 2) AS total_profit_percentage,
          invoice_acceptance,
          partial_invoice
        FROM
        (
          (
            SELECT r.id AS id_quote,
              DATE_FORMAT(r.invoice_date, '%m/%d/%Y') as invoice_date,
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
              END as sales_commission,
              r.invoice_acceptance,
              NULL AS partial_invoice
            FROM rfq r
              LEFT JOIN services s ON r.id = s.id_rfq
              LEFT JOIN re_quotes rq ON r.id = rq.id_rfq
            WHERE deleted = 0
              AND invoice = 1
              AND r.fulfillment_pending = 0
              AND MONTH(invoice_date) = (
                SELECT m.month
                FROM monthly_projections as m
                WHERE m.id = {$id}
              )
              AND YEAR(invoice_date) = (
                SELECT y.year
                FROM monthly_projections as m
                  LEFT JOIN yearly_projections y ON m.yearly_projection_id = y.id
                WHERE m.id = {$id}
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
              END as sales_commission,
              invoices_result_with_sales_commission.invoice_acceptance,
              true AS partial_invoice
            FROM (
              SELECT r.id as id_quote,
                DATE_FORMAT(i.created_at, '%m/%d/%Y') as invoice_date,
                i.name AS id,
                r.type_of_contract as type_of_contract,
                SUM(invoices_result.item_total_price) AS total_price,
                SUM(invoices_result.sum_real_cost) AS total_cost,
                SUM(invoices_result.profit) AS profit,
                100 * ((SUM(invoices_result.profit)) / (SUM(invoices_result.item_total_price))) as profit_percentage,
                i.sales_commission AS attached_sales_commission,
                i.invoice_acceptance
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
                          AND fullfillment = 1
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
                              AND fullfillment = 1
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
                          AND fullfillment = 1
                      )
                      AND fs.id_invoice IS NOT NULL
                    GROUP BY s.id,
                      fs.id_invoice
                  )
                ) AS invoices_result
                RIGHT JOIN invoices i ON invoices_result.id_invoice = i.id
                RIGHT JOIN rfq r ON r.id = i.id_rfq
              WHERE MONTH(i.created_at) = (
                  SELECT m.month
                  FROM monthly_projections as m
                  WHERE m.id = {$id}
                )
                AND YEAR(i.created_at) = (
                  SELECT y.year
                  FROM monthly_projections as m
                    LEFT JOIN yearly_projections y ON m.yearly_projection_id = y.id
                  WHERE m.id = {$id}
                )
              GROUP BY invoices_result.id_invoice, i.name
            ) as invoices_result_with_sales_commission
            LEFT JOIN rfq r ON r.id = invoices_result_with_sales_commission.id_quote
            LEFT JOIN re_quotes rq ON r.id = rq.id_rfq
            GROUP BY invoices_result_with_sales_commission.id
          )
        ) AS final_result
        ORDER BY {$sort_column} {$sort_direction} LIMIT {$start}, {$length}
        ";
        $sentence = $connection->prepare($sql);
        $sentence->execute();
        while ($row = $sentence->fetch(PDO::FETCH_ASSOC)) {
          $data[] = $row;
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $data;
  }

  public static function getTotalMonthCount($connection, $id) {
    if (isset($connection)) {
      try {
        $sql = "
        SELECT COUNT(*) 
        FROM 
        (
          (
            SELECT  
              DATE_FORMAT(r.invoice_date, '%m/%d/%Y') as invoice_date, 
              r.id,
              COALESCE(COALESCE(r.total_price, 0) + SUM(COALESCE(s.total_price, 0)), 0) AS total_price,
              COALESCE(r.total_fulfillment, 0) + COALESCE(r.total_services_fulfillment, 0) AS total_cost,
              COALESCE(COALESCE(r.total_price, 0) + SUM(COALESCE(s.total_price, 0)), 0) - (COALESCE(r.total_fulfillment, 0) + COALESCE(r.total_services_fulfillment, 0)) as profit,
              r.type_of_contract
            FROM rfq r
            LEFT JOIN services s ON r.id = s.id_rfq
            WHERE deleted = 0 AND
              invoice = 1 AND
              r.fulfillment_pending = 0 AND
              MONTH(invoice_date) = 
              (
                SELECT  m.month
                FROM monthly_projections as m
                WHERE m.id = {$id}
              ) AND 
              YEAR(invoice_date) = 
              (
                SELECT  y.year
                FROM monthly_projections as m
                LEFT JOIN yearly_projections y ON m.yearly_projection_id = y.id
                WHERE m.id = {$id}
              )
            GROUP BY r.id
          )
          UNION
          (
            SELECT 
              DATE_FORMAT(i.created_at, '%m/%d/%Y') as invoice_date,
              i.name AS id,
              SUM(combined.item_total_price) AS total_price,
              SUM(combined.sum_real_cost) AS total_cost,
              SUM(combined.profit) AS profit,
              NULL as type_of_contract
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
                  WHERE fulfillment_pending = 1 AND
                  invoice = 1 
                )
                AND fi.id_invoice IS NOT NULL
                GROUP BY i.id, fi.id_invoice
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
                    WHERE fulfillment_pending = 1 AND
                  invoice = 1
                  )
                )
                AND fsi.id_invoice IS NOT NULL
                GROUP BY si.id, fsi.id_invoice
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
                  WHERE fulfillment_pending = 1 AND
                  invoice = 1
                )
                AND fs.id_invoice IS NOT NULL
                GROUP BY s.id, fs.id_invoice
              )
            ) AS combined
            JOIN invoices i ON combined.id_invoice = i.id
            WHERE 
            MONTH(i.created_at) = 
            (
              SELECT  m.month
              FROM monthly_projections as m
              WHERE m.id = {$id}
            ) AND 
            YEAR(i.created_at) = 
            (
              SELECT  y.year
              FROM monthly_projections as m
              LEFT JOIN yearly_projections y ON m.yearly_projection_id = y.id
              WHERE m.id = {$id}
            )  
            GROUP BY combined.id_invoice, i.name
          )
        ) AS combined_result
        ";
        $sentence = $connection->prepare($sql);
        $sentence->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $sentence->fetchColumn();
  }

  public static function getTotalFilteredMonthCount($connection, $search, $id) {
    $search = '%' . $search . '%';
    if (isset($connection)) {
      try {
        $sql = "
        SELECT COUNT(*) 
        FROM 
        (
          (
            SELECT  
              DATE_FORMAT(r.invoice_date, '%m/%d/%Y') as invoice_date, 
              r.id,
              COALESCE(COALESCE(r.total_price, 0) + SUM(COALESCE(s.total_price, 0)), 0) AS total_price,
              COALESCE(r.total_fulfillment, 0) + COALESCE(r.total_services_fulfillment, 0) AS total_cost,
              COALESCE(COALESCE(r.total_price, 0) + SUM(COALESCE(s.total_price, 0)), 0) - (COALESCE(r.total_fulfillment, 0) + COALESCE(r.total_services_fulfillment, 0)) as profit,
              r.type_of_contract
            FROM rfq r
            LEFT JOIN services s ON r.id = s.id_rfq
            WHERE deleted = 0 AND
              invoice = 1 AND
              r.fulfillment_pending = 0 AND
              MONTH(invoice_date) = 
              (
                SELECT  m.month
                FROM monthly_projections as m
                WHERE m.id = {$id}
              ) AND 
              YEAR(invoice_date) = 
              (
                SELECT  y.year
                FROM monthly_projections as m
                LEFT JOIN yearly_projections y ON m.yearly_projection_id = y.id
                WHERE m.id = {$id}
              )
            GROUP BY r.id
          )
          UNION
          (
            SELECT 
              DATE_FORMAT(i.created_at, '%m/%d/%Y') as invoice_date,
              i.name AS id,
              SUM(combined.item_total_price) AS total_price,
              SUM(combined.sum_real_cost) AS total_cost,
              SUM(combined.profit) AS profit,
              NULL as type_of_contract
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
                  WHERE fulfillment_pending = 1 AND
                  invoice = 1 
                )
                AND fi.id_invoice IS NOT NULL
                GROUP BY i.id, fi.id_invoice
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
                    WHERE fulfillment_pending = 1 AND
                  invoice = 1
                  )
                )
                AND fsi.id_invoice IS NOT NULL
                GROUP BY si.id, fsi.id_invoice
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
                  WHERE fulfillment_pending = 1 AND
                  invoice = 1
                )
                AND fs.id_invoice IS NOT NULL
                GROUP BY s.id, fs.id_invoice
              )
            ) AS combined
            JOIN invoices i ON combined.id_invoice = i.id
            WHERE 
            MONTH(i.created_at) = 
            (
              SELECT  m.month
              FROM monthly_projections as m
              WHERE m.id = {$id}
            ) AND 
            YEAR(i.created_at) = 
            (
              SELECT  y.year
              FROM monthly_projections as m
              LEFT JOIN yearly_projections y ON m.yearly_projection_id = y.id
              WHERE m.id = {$id}
            )  
            GROUP BY combined.id_invoice, i.name
          )
        ) AS combined_result
        ";
        $sentence = $connection->prepare($sql);
        $sentence->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $sentence->fetchColumn();
  }
}
