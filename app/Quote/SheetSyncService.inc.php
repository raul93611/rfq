<?php
class SheetSyncService {
  private static function fileBase() {
    return '/drives/' . GRAPH_SHAREPOINT_FILE_ID . '/items/' . GRAPH_SHAREPOINT_FILE_ID;
  }

  private static function worksheetBase() {
    return '/me/drive/items/' . GRAPH_SHAREPOINT_FILE_ID
      . '/workbook/worksheets/' . rawurlencode(GRAPH_SHEET_NAME);
  }

  private static function wsPath($suffix = '') {
    return '/drives/' . GRAPH_SHAREPOINT_FILE_ID . '/items/' . GRAPH_SHAREPOINT_FILE_ID
      . '/workbook/worksheets/' . rawurlencode(GRAPH_SHEET_NAME) . $suffix;
  }

  private static function buildRowValues(Rfq $quote, $designatedUsername) {
    return [
      $quote->obtener_id(),
      $quote->getVehicleForSheet(),
      $quote->obtener_email_code(),
      $quote->getName() ?? '',
      $quote->getSheetStatus(),
      $quote->obtener_issue_date() ?? '',
      $quote->obtener_end_date() ?? '',
      $quote->obtener_type_of_bid() ?? '',
      $designatedUsername,
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
    return 'A' . $rowIndex . ':I' . $rowIndex;
  }

  public static function appendRow(Rfq $quote, $designatedUsername) {
    if (empty(GRAPH_CLIENT_SECRET) || empty(GRAPH_SHAREPOINT_FILE_ID)) {
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
    if (empty(GRAPH_CLIENT_SECRET) || empty(GRAPH_SHAREPOINT_FILE_ID) || !$sheetRow) {
      return;
    }

    GraphApiClient::patch(self::wsPath('/range(address=\'E' . $sheetRow . '\')'), [
      'values' => [[$status]],
    ]);
  }

  public static function deleteRow($sheetRow) {
    if (empty(GRAPH_CLIENT_SECRET) || empty(GRAPH_SHAREPOINT_FILE_ID) || !$sheetRow) {
      return;
    }

    GraphApiClient::post(self::wsPath('/range(address=\'' . self::rowAddress($sheetRow) . '\')/delete'), [
      'shift' => 'Up',
    ]);
  }
}
