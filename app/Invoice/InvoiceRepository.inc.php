<?php
class InvoiceRepository {
  public static function array_to_object($sentence) {
    $objects = [];
    while ($row = $sentence->fetch(PDO::FETCH_ASSOC)) {
      $objects[] = new Invoice($row['id'], $row['id_rfq'], $row['name'], $row['created_at']);
    }

    return $objects;
  }

  public static function single_result_to_object($sentence) {
    $row = $sentence->fetch(PDO::FETCH_ASSOC);
    if (empty($row)) return null;
    $object = new Invoice($row['id'], $row['id_rfq'], $row['name'], $row['created_at']);

    return $object;
  }

  public static function save($connection, $invoice) {
    if (isset($connection)) {
      try {
        $sql = 'INSERT INTO invoices(id_rfq, name, created_at) VALUES(:id_rfq, :name, STR_TO_DATE(:created_at, "%m/%d/%Y"))';
        $sentence = $connection->prepare($sql);
        $sentence->bindValue(':id_rfq', $invoice->get_id_rfq(), PDO::PARAM_STR);
        $sentence->bindValue(':name', $invoice->get_name(), PDO::PARAM_STR);
        $sentence->bindValue(':created_at', $invoice->get_created_at(), PDO::PARAM_STR);
        $sentence->execute();
        return true;
      } catch (PDOException $ex) {
        // You can handle the exception here if needed, or just return false
        return false;
      }
    }
    return false;
  }

  public static function get_all_by_id_rfq($connection, $id_rfq) {
    $invoices = [];
    if (isset($connection)) {
      try {
        $sql = 'SELECT * FROM invoices WHERE id_rfq = :id_rfq ORDER BY created_at ASC';
        $sentence = $connection->prepare($sql);
        $sentence->bindValue(':id_rfq', $id_rfq, PDO::PARAM_STR);
        $sentence->execute();
        $invoices = self::array_to_object($sentence);
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $invoices;
  }

  public static function isNameUnique($connection, $name) {
    if (isset($connection)) {
      try {
        $sql = "SELECT COUNT(*) FROM invoices WHERE name = :name";
        $sentence = $connection->prepare($sql);
        $sentence->bindValue(':name', $name, PDO::PARAM_STR);
        $sentence->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }

    return !$sentence->fetchColumn();
  }

  public static function getInvoiceAcceptance($connection, $name) {
    if (isset($connection)) {
      try {
        $sql = "SELECT invoice_acceptance FROM invoices WHERE name = :name";
        $sentence = $connection->prepare($sql);
        $sentence->bindValue(':name', $name, PDO::PARAM_STR);
        $sentence->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }

    return $sentence->fetchColumn();
  }

  public static function isNameEditable($connection, $name, $id) {
    if (isset($connection)) {
      try {
        $sql = "SELECT COUNT(*) FROM invoices WHERE name = :name AND id != :id";
        $sentence = $connection->prepare($sql);
        $sentence->bindValue(':name', $name, PDO::PARAM_STR);
        $sentence->bindValue(':id', $id, PDO::PARAM_STR);
        $sentence->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return !$sentence->fetchColumn();
  }

  public static function isSalesCommissionAttached($connection, $id_rfq) {
    $invoices = [];
    if (isset($connection)) {
      try {
        $sql = "SELECT COUNT(*) as commission_count FROM invoices WHERE id_rfq = $id_rfq AND sales_commission = 1";
        $sentence = $connection->prepare($sql);
        $sentence->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $sentence->fetchColumn();
  }

  public static function get_one($connection, $id_invoice) {
    $item = null;
    if (isset($connection)) {
      try {
        $sql = 'SELECT * FROM invoices WHERE id = :id_invoice';
        $sentence = $connection->prepare($sql);
        $sentence->bindValue(':id_invoice', $id_invoice, PDO::PARAM_STR);
        $sentence->execute();
        $item = self::single_result_to_object($sentence);
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $item;
  }

  public static function delete($connection, $id_invoice) {
    if (isset($connection)) {
      try {
        $sql = 'DELETE FROM invoices WHERE id = :id_invoice';
        $sentence = $connection->prepare($sql);
        $sentence->bindValue(':id_invoice', $id_invoice, PDO::PARAM_STR);

        // Execute the statement and return true if successful
        return $sentence->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }

    // Return false if the connection is not set or an error occurred
    return false;
  }

  public static function update($connection, $name, $created_at, $id_invoice) {
    if (!isset($connection)) {
      return false;
    }

    try {
      $sql = 'UPDATE invoices SET name = :name, created_at = STR_TO_DATE(:created_at, "%m/%d/%Y") WHERE id = :id_invoice';
      $sentence = $connection->prepare($sql);
      $sentence->bindValue(':name', $name, PDO::PARAM_STR);
      $sentence->bindValue(':created_at', $created_at, PDO::PARAM_STR);
      $sentence->bindValue(':id_invoice', $id_invoice, PDO::PARAM_STR);

      // Execute the query and check if any rows were affected
      $success = $sentence->execute();
      return $success && $sentence->rowCount() > 0;
    } catch (PDOException $ex) {
      // Log the error if needed, or handle it in a way appropriate for your application
      // For debugging purposes, you might use: error_log($ex->getMessage());
      return false;
    }
  }

  public static function updateInvoiceAcceptance($connection, $name, $invoice_acceptance) {
    if (isset($connection)) {
      try {
        $sql = 'UPDATE invoices SET invoice_acceptance = :invoice_acceptance WHERE name = :name';
        $sentence = $connection->prepare($sql);
        $sentence->bindValue(':name', $name, PDO::PARAM_STR);
        $sentence->bindValue(':invoice_acceptance', $invoice_acceptance, PDO::PARAM_STR);
        $sentence->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function attachSalesCommission($connection, $id_invoice, $id_rfq) {
    if (isset($connection)) {
      try {
        $sql = "
            UPDATE invoices
            SET sales_commission = 
              CASE 
                WHEN id = :id_invoice THEN 1
                ELSE NULL
              END
            WHERE id_rfq = :id_rfq
            ";
        $sentence = $connection->prepare($sql);
        // Bind parameters to avoid SQL injection
        $sentence->bindValue(':id_invoice', $id_invoice, PDO::PARAM_INT);
        $sentence->bindValue(':id_rfq', $id_rfq, PDO::PARAM_INT);

        // Execute the query and check if it affected any rows
        $result = $sentence->execute();

        // Return true if the query was successful and affected rows
        return $result && $sentence->rowCount() > 0;
      } catch (PDOException $ex) {
        // Optionally log or handle the error
        print 'ERROR:' . $ex->getMessage() . '<br>';
        return false;
      }
    }
    return false;
  }


  public static function listInvoices($connection, $id_rfq) {
    $data = [];
    if (isset($connection)) {
      try {
        $sql = "
        SELECT combined.id_invoice,
          i.name AS invoice_name,
          DATE_FORMAT(i.created_at, '%m/%d/%Y') as invoice_date,
          SUM(combined.item_total_price) AS total_item_price,
          SUM(combined.sum_real_cost) AS total_real_cost,
          SUM(combined.profit) AS total_profit,
          CASE i.sales_commission
            WHEN 1 THEN 'Attached'
          END as sales_commission
        FROM (
            (
              SELECT fi.id_invoice,
                i.total_price AS item_total_price,
                SUM(fi.real_cost) AS sum_real_cost,
                i.total_price - SUM(fi.real_cost) AS profit
              FROM item i
                JOIN fulfillment_items fi ON i.id = fi.id_item
              WHERE i.id_rfq = {$id_rfq}
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
                  WHERE id_rfq = {$id_rfq}
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
              WHERE s.id_rfq = {$id_rfq}
                AND fs.id_invoice IS NOT NULL
              GROUP BY s.id,
                fs.id_invoice
            )
          ) AS combined
          JOIN invoices i ON combined.id_invoice = i.id
        GROUP BY combined.id_invoice,
          i.name;
        ";
        $sentencia = $connection->prepare($sql);
        $sentencia->execute();
        while ($row = $sentencia->fetch(PDO::FETCH_ASSOC)) {
          $data[] = $row;
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $data;
  }
}
