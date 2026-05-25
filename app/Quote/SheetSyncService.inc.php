<?php
class SheetSyncService {
  private static function wsPath($suffix = '') {
    return '/users/' . rawurlencode(GRAPH_SHAREPOINT_OWNER)
      . '/drive/items/' . GRAPH_SHAREPOINT_FILE_ID
      . '/workbook/worksheets/' . rawurlencode(GRAPH_SHEET_NAME) . $suffix;
  }

  private static function buildRowValues(Rfq $quote, $designatedUsername) {
    $endDateRaw  = $quote->obtener_end_date() ?? '';
    $endParts    = explode(' ', $endDateRaw, 2);
    $endDate     = $endParts[0] ?? '';
    $endTime     = $endParts[1] ?? '';

    return [
      $quote->obtener_id(),           // A: PROPOSAL
      $quote->getVehicleForSheet(),   // B: VEHICLE
      $quote->obtener_email_code(),   // C: ID
      $quote->getName() ?? '',        // D: (opportunity name)
      $quote->getSheetStatus(),       // E: STATUS
      $quote->obtener_issue_date() ?? '', // F: INTERNAL DUE DATE
      $endDate,                       // G: CLIENT DUE DATE
      $endTime,                       // H: DUE TIME
      '',                             // I: TYPE (manual)
      $quote->obtener_type_of_bid() ?? '', // J: CATEGORY
      '', '', '', '', '', '', '', '', '', // K–S: blank
      $designatedUsername,            // T: PROPOSAL WRITER
    ];
  }

  private static function getUsedRowCount() {
    $data = GraphApiClient::get(self::wsPath('/usedRange(valuesOnly=true)'));
    if (isset($data['rowCount'])) {
      return (int)$data['rowCount'];
    }
    return 1;
  }

  private static function rowAddress($rowIndex) {
    return 'A' . $rowIndex . ':T' . $rowIndex;
  }

  public static function appendRow(Rfq $quote, $designatedUsername) {
    if (empty(GRAPH_CLIENT_SECRET)) {
      return null;
    }

    $nextRow = self::getUsedRowCount() + 1;
    $values = self::buildRowValues($quote, $designatedUsername);

    GraphApiClient::patch(self::wsPath('/range(address=\'' . self::rowAddress($nextRow) . '\')'), [
      'values' => [$values],
    ]);

    return $nextRow;
  }

  public static function updateStatusCell($sheetRow, $status) {
    if (empty(GRAPH_CLIENT_SECRET) || !$sheetRow) {
      return;
    }

    GraphApiClient::patch(self::wsPath('/range(address=\'E' . $sheetRow . '\')'), [
      'values' => [[$status]],
    ]);
  }

  public static function deleteRow($sheetRow) {
    if (empty(GRAPH_CLIENT_SECRET) || !$sheetRow) {
      return;
    }

    GraphApiClient::post(self::wsPath('/range(address=\'' . self::rowAddress($sheetRow) . '\')/delete'), [
      'shift' => 'Up',
    ]);
  }
}
