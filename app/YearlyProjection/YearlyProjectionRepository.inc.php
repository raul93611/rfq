<?php
class YearlyProjectionRepository {
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
        SELECT monthly_projections.id,
          monthly_projections.month,
          monthname(str_to_date(monthly_projections.month, '%m')) as month_name,
          monthly_projections.projected_amount,
          SUM(total_price) AS total_price,
          SUM(total_cost) AS total_cost,
          SUM(profit) AS profit,
          NULL as options
        FROM (
            (
              SELECT r.id,
                r.invoice_date,
                r.contract_number,
                COALESCE(
                  COALESCE(r.total_price, 0) + SUM(COALESCE(s.total_price, 0)),
                  0
                ) AS total_price,
                COALESCE(r.total_fulfillment, 0) + COALESCE(r.total_services_fulfillment, 0) AS total_cost,
                COALESCE(
                  COALESCE(r.total_price, 0) + SUM(COALESCE(s.total_price, 0)),
                  0
                ) - (
                  COALESCE(r.total_fulfillment, 0) + COALESCE(r.total_services_fulfillment, 0)
                ) as profit,
                r.type_of_contract
              FROM rfq r
                LEFT JOIN services s ON r.id = s.id_rfq
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
              SELECT i.name AS id,
                i.created_at as invoice_date,
                NULL as contract_number,
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
                ) AS combined
                JOIN invoices i ON combined.id_invoice = i.id
              WHERE YEAR(i.created_at) = (
                  SELECT y.year
                  FROM yearly_projections as y
                  WHERE y.id = {$id}
                )
              GROUP BY combined.id_invoice,
                i.name
            )
          ) AS combined_result
          RIGHT JOIN monthly_projections ON MONTH(invoice_date) = monthly_projections.month
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

  public static function getMonth($connection, $start, $length, $search, $sort_column_index, $sort_direction, $id) {
    $data = [];
    $search = '%' . $search . '%';
    switch ($sort_column_index) {
      case 0:
        $sort_column = 'id';
        break;
      case 1:
        $sort_column = 'invoice_date';
        break;
      case 2:
        $sort_column = 'contract_number';
        break;
      case 3:
        $sort_column = 'total_cost';
        break;
      case 4:
        $sort_column = 'total_price';
        break;
      case 5:
        $sort_column = 'profit';
        break;
      case 6:
        $sort_column = 'type_of_contract';
        break;
      default:
        $sort_column = 'invoice_date';
        break;
    }
    if (isset($connection)) {
      try {
        $sql = "
        (
          SELECT  
            r.id,
            DATE_FORMAT(r.invoice_date, '%m/%d/%Y') as invoice_date, 
            r.contract_number, 
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
            i.name AS id,
            DATE_FORMAT(i.created_at, '%m/%d/%Y') as invoice_date,
            NULL as contract_number,
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
              r.id,
              DATE_FORMAT(r.invoice_date, '%m/%d/%Y') as invoice_date, 
              r.contract_number, 
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
              i.name AS id,
              DATE_FORMAT(i.created_at, '%m/%d/%Y') as invoice_date,
              NULL as contract_number,
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
              r.id,
              DATE_FORMAT(r.invoice_date, '%m/%d/%Y') as invoice_date, 
              r.contract_number, 
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
              i.name AS id,
              DATE_FORMAT(i.created_at, '%m/%d/%Y') as invoice_date,
              NULL as contract_number,
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
