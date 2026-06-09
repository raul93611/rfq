<?php
class SheetSyncService {
  private static function wsPath($suffix = '') {
    return '/users/' . rawurlencode(GRAPH_SHAREPOINT_OWNER)
      . '/drive/items/' . GRAPH_SHAREPOINT_FILE_ID
      . '/workbook/worksheets/' . rawurlencode(GRAPH_SHEET_NAME) . $suffix;
  }

  // Columns the app owns and rewrites on every sync. Everything NOT listed here is
  // human-owned (E STATUS, F INTERNAL DUE DATE, I TYPE, K TEAMING PARTNER, O LETTER OF
  // INTENT, P RECRUITMENT, R SUBMIT BY, S PRICING) and is preserved via read-merge-write —
  // see mergeAppOwned().
  // 0-based indices: A=0 B=1 C=2 D=3 G=6 H=7 J=9 L=11 M=12 N=13 Q=16 T=19.
  private static $humanOwnedIndices = [4, 5, 8, 10, 14, 15, 17, 18]; // E, F, I, K, O, P, R, S

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
      '',                             // E: STATUS (human-owned — preserved on merge)
      '',                             // F: INTERNAL DUE DATE (human-owned — preserved on merge)
      $endDate,                       // G: CLIENT DUE DATE
      $endTime,                       // H: DUE TIME
      '',                             // I: TYPE (human-owned)
      $quote->obtener_type_of_bid() ?? '', // J: CATEGORY
      '',                             // K: TEAMING PARTNER (human-owned)
      is_null($quote->getQa()) ? '' : ($quote->getQa() ? 'YES' : 'NO'), // L: Q&A
      $quote->getQaDeadline() ? date('m/d/Y', strtotime($quote->getQaDeadline())) : '', // M: Q & A DEADLINE
      is_null($quote->getResumes()) ? '' : ($quote->getResumes() ? 'YES' : 'NO'), // N: RESUMES
      '',                             // O: LETTER OF INTENT (human-owned)
      '',                             // P: RECRUITMENT (human-owned)
      is_null($quote->getSiteVisit()) ? '' : ($quote->getSiteVisit() ? 'YES' : 'NO'), // Q: SITE VISIT
      '',                             // R: SUBMIT BY (human-owned)
      '',                             // S: PRICING (human-owned)
      $designatedUsername,            // T: PROPOSAL WRITER
    ];
  }

  // Read the full A:T values of a sheet row (or [] if it doesn't exist / is empty).
  private static function readRow($rowIndex) {
    $resp = GraphApiClient::get(self::wsPath('/range(address=\'' . self::rowAddress($rowIndex) . '\')?$select=values'));
    return $resp['values'][0] ?? [];
  }

  // Overlay app-owned values onto the existing row so human-owned cells survive the write.
  // $existing is whatever Graph currently holds for that row ([] for a brand-new row, in
  // which case the human-owned cells stay blank).
  private static function mergeAppOwned(array $appValues, array $existing) {
    foreach (self::$humanOwnedIndices as $idx) {
      $appValues[$idx] = $existing[$idx] ?? '';
    }
    return $appValues;
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

  // Write-once create-or-link. Presence is decided by scanning column A (the stored
  // sheet_row pointer is never trusted as a license to write). If the quote id is already
  // in the sheet, that row becomes the pointer and NOTHING is written (link). If it's
  // absent, a fresh row is appended with the app-owned columns filled and human-owned
  // columns left blank (create). The app never overwrites or deletes an existing row.
  //
  // Returns ['row' => int|null, 'outcome' => 'created'|'linked'|null]. When Graph is not
  // configured, returns a null outcome so callers treat it as "sync not performed".
  public static function createOrLink(Rfq $quote, $designatedUsername) {
    if (empty(GRAPH_CLIENT_SECRET)) {
      return ['row' => null, 'outcome' => null];
    }

    $range       = self::getUsedRange();
    $existingRow = self::findRowByQuoteId($quote->obtener_id(), $range['values']);

    // Found in the sheet — link only, write nothing (human-owned and app-owned cells stay
    // exactly as they are).
    if ($existingRow) {
      return ['row' => $existingRow, 'outcome' => 'linked'];
    }

    // Absent — create. A genuinely new row has no existing values, so human-owned cells
    // stay blank (buildRowValues already emits them empty); no read-merge needed.
    $targetRow = $range['rowCount'] + 1;
    $values    = self::buildRowValues($quote, $designatedUsername);
    GraphApiClient::patch(self::wsPath('/range(address=\'' . self::rowAddress($targetRow) . '\')'), [
      'values' => [$values],
    ]);

    return ['row' => $targetRow, 'outcome' => 'created'];
  }

  // --- Superseded by createOrLink() under the write-once model (kept for reference /
  //     historical callers). appendRow/syncRow could overwrite an existing row; the app no
  //     longer does that. deleteRow has no callers — the app never removes a sheet row. ---
  public static function appendRow(Rfq $quote, $designatedUsername) {
    if (empty(GRAPH_CLIENT_SECRET)) {
      return null;
    }

    $range  = self::getUsedRange();
    $values = self::buildRowValues($quote, $designatedUsername);

    // Prevent duplicates: if this quote ID already exists in column A, overwrite that row
    $existingRow = self::findRowByQuoteId($quote->obtener_id(), $range['values']);
    $targetRow   = $existingRow ?? ($range['rowCount'] + 1);

    // When overwriting an existing row, read it first so human-owned columns survive.
    // A genuinely new (appended) row has no existing values, so those cells stay blank.
    $existingValues = $existingRow ? self::readRow($targetRow) : [];
    $values = self::mergeAppOwned($values, $existingValues);

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
    // Read the whole row up front: column A (index 0) verifies the pointer, and the rest
    // supplies the human-owned cell values to preserve on write.
    $quoteId  = (string)$quote->obtener_id();
    $existing = self::readRow($sheetRow);
    $cellVal  = $existing[0] ?? null;
    if ($cellVal === null || (string)$cellVal !== $quoteId) {
      return self::appendRow($quote, $designatedUsername);
    }

    $values = self::mergeAppOwned(self::buildRowValues($quote, $designatedUsername), $existing);
    GraphApiClient::patch(self::wsPath('/range(address=\'' . self::rowAddress($sheetRow) . '\')'), [
      'values' => [$values],
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
