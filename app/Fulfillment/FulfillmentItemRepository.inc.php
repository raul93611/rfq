<?php
class FulfillmentItemRepository {
  public static function array_to_object($sentence) {
    $objects = [];
    while ($row = $sentence->fetch(PDO::FETCH_ASSOC)) {
      $objects[] = new FulfillmentItem($row['id'], $row['id_item'], $row['provider'], $row['quantity'], $row['unit_cost'], $row['other_cost'], $row['real_cost'], $row['payment_term'], $row['comments'], $row['reviewed'], $row['created_at'], $row['id_invoice'], $row['transaction_date']);
    }

    return $objects;
  }

  public static function single_result_to_object($sentence) {
    $row = $sentence->fetch(PDO::FETCH_ASSOC);
    $object = new FulfillmentItem($row['id'], $row['id_item'], $row['provider'], $row['quantity'], $row['unit_cost'], $row['other_cost'], $row['real_cost'], $row['payment_term'], $row['comments'], $row['reviewed'], $row['created_at'], $row['id_invoice'], $row['transaction_date']);

    return $object;
  }

  public static function get_all_by_id_item($connection, $id_item) {
    $items = [];
    if (isset($connection)) {
      try {
        $sql = 'SELECT * FROM fulfillment_items WHERE id_item = :id_item';
        $sentence = $connection->prepare($sql);
        $sentence->bindValue(':id_item', $id_item, PDO::PARAM_STR);
        $sentence->execute();
        $items = self::array_to_object($sentence);
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $items;
  }

  public static function get_all_by_id_item_from_to($connection, $id_item, $from, $to) {
    $items = [];
    if (isset($connection)) {
      try {
        $sql = 'SELECT * FROM fulfillment_items WHERE id_item = :id_item AND created_at BETWEEN :from AND :to';
        $sentence = $connection->prepare($sql);
        $sentence->bindValue(':id_item', $id_item, PDO::PARAM_STR);
        $sentence->bindValue(':from', $from, PDO::PARAM_STR);
        $sentence->bindValue(':to', $to, PDO::PARAM_STR);
        $sentence->execute();
        $items = self::array_to_object($sentence);
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $items;
  }

  public static function insert($connection, $fulfillment_item) {
    if (isset($connection)) {
      try {
        $sql = "
        INSERT INTO fulfillment_items(
          id_item, 
          provider, 
          quantity, 
          unit_cost, 
          other_cost, 
          real_cost, 
          payment_term, 
          comments, 
          created_at,
          id_invoice,
          transaction_date
        ) VALUES(
          :id_item, 
          :provider, 
          :quantity, 
          :unit_cost, 
          :other_cost, 
          :real_cost, 
          :payment_term, 
          :comments, 
          NOW(),
          :id_invoice,
          STR_TO_DATE(:transaction_date, '%m/%d/%Y')
        )
        ";
        $sentence = $connection->prepare($sql);
        $sentence->bindValue(':id_item', $fulfillment_item->get_id_item(), PDO::PARAM_STR);
        $sentence->bindValue(':provider', $fulfillment_item->get_provider(), PDO::PARAM_STR);
        $sentence->bindValue(':quantity', $fulfillment_item->get_quantity(), PDO::PARAM_STR);
        $sentence->bindValue(':unit_cost', $fulfillment_item->get_unit_cost(), PDO::PARAM_STR);
        $sentence->bindValue(':other_cost', $fulfillment_item->get_other_cost(), PDO::PARAM_STR);
        $sentence->bindValue(':real_cost', $fulfillment_item->get_real_cost(), PDO::PARAM_STR);
        $sentence->bindValue(':payment_term', $fulfillment_item->get_payment_term(), PDO::PARAM_STR);
        $sentence->bindValue(':comments', $fulfillment_item->get_comments(), PDO::PARAM_STR);
        $sentence->bindValue(':id_invoice', $fulfillment_item->getIdInvoice(), PDO::PARAM_STR);
        $sentence->bindValue(':transaction_date', $fulfillment_item->getTransactionDate(), PDO::PARAM_STR);
        $sentence->execute();
        $id = $connection->lastInsertId();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $id;
  }

  public static function update(
    $connection,
    $id_fulfillment_item,
    $provider,
    $quantity,
    $unit_cost,
    $other_cost,
    $real_cost,
    $payment_term,
    $comment,
    $id_invoice,
    $transaction_date
  ) {
    if (isset($connection)) {
      try {
        $sql = "
        UPDATE fulfillment_items SET 
        provider = :provider, 
        quantity = :quantity, 
        unit_cost = :unit_cost, 
        other_cost = :other_cost, 
        real_cost = :real_cost, 
        payment_term = :payment_term, 
        comments = :comments,
        id_invoice = :id_invoice,
        transaction_date = STR_TO_DATE(:transaction_date, '%m/%d/%Y')
        WHERE 
        id = :id_fulfillment_item
        ";
        $sentence = $connection->prepare($sql);
        $sentence->bindValue(':provider', $provider, PDO::PARAM_STR);
        $sentence->bindValue(':quantity', $quantity, PDO::PARAM_STR);
        $sentence->bindValue(':unit_cost', $unit_cost, PDO::PARAM_STR);
        $sentence->bindValue(':other_cost', $other_cost, PDO::PARAM_STR);
        $sentence->bindValue(':real_cost', $real_cost, PDO::PARAM_STR);
        $sentence->bindValue(':payment_term', $payment_term, PDO::PARAM_STR);
        $sentence->bindValue(':comments', $comment, PDO::PARAM_STR);
        $sentence->bindValue(':id_fulfillment_item', $id_fulfillment_item, PDO::PARAM_STR);
        $sentence->bindValue(':id_invoice', $id_invoice, PDO::PARAM_STR);
        $sentence->bindValue(':transaction_date', $transaction_date, PDO::PARAM_STR);
        $sentence->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function mark_as_reviewed($connection, $id_fulfillment_item) {
    if (isset($connection)) {
      try {
        $sql = 'UPDATE fulfillment_items SET reviewed = 1 - reviewed WHERE id = :id_fulfillment_item';
        $sentence = $connection->prepare($sql);
        $sentence->bindValue(':id_fulfillment_item', $id_fulfillment_item, PDO::PARAM_STR);
        $sentence->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function delete($connection, $id_fulfillment_item) {
    if (isset($connection)) {
      try {
        $sql = 'DELETE FROM fulfillment_items WHERE id = :id_fulfillment_item';
        $sentence = $connection->prepare($sql);
        $sentence->bindValue(':id_fulfillment_item', $id_fulfillment_item, PDO::PARAM_STR);
        $sentence->execute();
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
  }

  public static function get_total_cost($connection, $id_item) {
    $total = 0;
    if (isset($connection)) {
      try {
        $sql = 'SELECT SUM(real_cost) as total_cost FROM fulfillment_items WHERE id_item = :id_item';
        $sentence = $connection->prepare($sql);
        $sentence->bindValue(':id_item', $id_item, PDO::PARAM_STR);
        $sentence->execute();
        $result = $sentence->fetch(PDO::FETCH_ASSOC);
        if (!empty($result)) {
          $total = $result['total_cost'];
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $total;
  }

  public static function get_total_cost_from_to($connection, $id_item, $from, $to) {
    $total = 0;
    if (isset($connection)) {
      try {
        $sql = 'SELECT SUM(real_cost) as total_cost FROM fulfillment_items WHERE id_item = :id_item AND created_at BETWEEN :from AND :to';
        $sentence = $connection->prepare($sql);
        $sentence->bindValue(':id_item', $id_item, PDO::PARAM_STR);
        $sentence->bindValue(':from', $from, PDO::PARAM_STR);
        $sentence->bindValue(':to', $to, PDO::PARAM_STR);
        $sentence->execute();
        $result = $sentence->fetch(PDO::FETCH_ASSOC);
        if (!empty($result)) {
          $total = $result['total_cost'];
        }
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $total;
  }

  public static function get_one($connection, $id_fulfillment_item) {
    $item = null;
    if (isset($connection)) {
      try {
        $sql = 'SELECT * FROM fulfillment_items WHERE id = :id_fulfillment_item';
        $sentence = $connection->prepare($sql);
        $sentence->bindValue(':id_fulfillment_item', $id_fulfillment_item, PDO::PARAM_STR);
        $sentence->execute();
        $item = self::single_result_to_object($sentence);
      } catch (PDOException $ex) {
        print 'ERROR:' . $ex->getMessage() . '<br>';
      }
    }
    return $item;
  }
}
