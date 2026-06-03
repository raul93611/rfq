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
      $quote->getInternalDueDate() ? date('m/d/Y', strtotime($quote->getInternalDueDate())) : '', // F: INTERNAL DUE DATE
      $endDate,                       // G: CLIENT DUE DATE
      $endTime,                       // H: DUE TIME
      '',                             // I: TYPE (manual)
      $quote->obtener_type_of_bid() ?? '', // J: CATEGORY
      '',                             // K: TEAMING PARTNER (manual)
      is_null($quote->getQa()) ? '' : ($quote->getQa() ? 'YES' : 'NO'), // L: Q&A
      $quote->getQaDeadline() ? date('m/d/Y', strtotime($quote->getQaDeadline())) : '', // M: Q & A DEADLINE
      is_null($quote->getResumes()) ? '' : ($quote->getResumes() ? 'YES' : 'NO'), // N: RESUMES
      '',                             // O: LETTER OF INTENT (manual)
      '',                             // P: RECRUITMENT (manual)
      is_null($quote->getSiteVisit()) ? '' : ($quote->getSiteVisit() ? 'YES' : 'NO'), // Q: SITE VISIT
      '',                             // R: SUBMIT BY (manual)
      '',                             // S: PRICING (manual)
      $designatedUsername,            // T: PROPOSAL WRITER
    ];
  }

  private static function getUsedRange() {
    // Only the row count is needed up front (tiny payload vs. the full ~4MB grid that
    // usedRange returns for every column/property).
    $dims = GraphApiClient::get(self::wsPath('/usedRange(valuesOnly=true)?$select=rowCount'));
    $rowCount = (int)($dims['rowCount'] ?? 1);

    // Dedup only inspects column A, so read just that column (~350x smaller than the full
    // used range). A smaller, faster read also narrows the window where a slow/heavy
    // response or Graph's eventual consistency could yield stale values and a duplicate row.
    $colA = GraphApiClient::get(self::wsPath('/range(address=\'A1:A' . $rowCount . '\')?$select=values'));
    return [
      'rowCount' => $rowCount,
      'values'   => $colA['values'] ?? [],
    ];
  }

  private static function findRowByQuoteId($quoteId, array $usedValues) {
    foreach ($usedValues as $i => $row) {
      if (isset($row[0]) && (string)$row[0] === (string)$quoteId) {
        return $i + 1; // 1-based sheet row index
      }
    }
    return null;
  }

  private static function rowAddress($rowIndex) {
    return 'A' . $rowIndex . ':T' . $rowIndex;
  }

  public static function appendRow(Rfq $quote, $designatedUsername) {
    if (empty(GRAPH_CLIENT_SECRET)) {
      return null;
    }

    $range  = self::getUsedRange();
    $values = self::buildRowValues($quote, $designatedUsername);

    // Prevent duplicates: if this quote ID already exists in column A, overwrite that row
    $existingRow = self::findRowByQuoteId($quote->obtener_id(), $range['values']);
    $targetRow   = $existingRow ?? ($range['rowCount'] + 1);

    GraphApiClient::patch(self::wsPath('/range(address=\'' . self::rowAddress($targetRow) . '\')'), [
      'values' => [$values],
    ]);

    return $targetRow;
  }

  public static function syncRow($sheetRow, Rfq $quote, $designatedUsername) {
    if (empty(GRAPH_CLIENT_SECRET) || !$sheetRow) {
      return $sheetRow;
    }

    // Guard against a stale pointer: rows shift up when another quote is deleted
    // (deleteRow uses shift='Up'), and a row can be moved/removed manually. If the stored
    // row no longer holds this quote's id in column A, don't clobber whatever quote now
    // occupies it — re-resolve via appendRow() (which scans column A and overwrites the
    // real row, or appends if it's genuinely gone) and return the corrected row so the
    // caller can persist it.
    $quoteId = (string)$quote->obtener_id();
    $cellA   = GraphApiClient::get(self::wsPath('/range(address=\'A' . (int)$sheetRow . '\')?$select=values'));
    $cellVal = $cellA['values'][0][0] ?? null;
    if ($cellVal === null || (string)$cellVal !== $quoteId) {
      return self::appendRow($quote, $designatedUsername);
    }

    GraphApiClient::patch(self::wsPath('/range(address=\'' . self::rowAddress($sheetRow) . '\')'), [
      'values' => [self::buildRowValues($quote, $designatedUsername)],
    ]);

    return $sheetRow;
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
