# Bug: stale `sheet_row` after a synced quote is deleted

One-line: deleting a synced quote shifts every sheet row below it up by one, but the app never updates the stored `sheet_row` of the other quotes — so their next re-sync overwrites the wrong row.

**Status:** fixed
**Fixed on branch `fix/sheet-sync-duplicates`:** delete now calls `SheetSyncRepository::shiftRowsAfterDelete()` (decrements `sheet_row` for all quotes below the deleted row), and `syncRow` self-heals by verifying column A before writing and re-resolving if the pointer drifted. Verified against the live sheet (a deliberately stale pointer re-resolved to the correct row without clobbering the neighbour).

## Steps to reproduce
1. Sync two qualifying quotes to the sheet so they occupy adjacent rows, e.g. quote A at `sheet_row = 100`, quote B at `sheet_row = 101`.
2. Delete quote A (soft delete via `delete_quote`, or hard delete via `destroy_quote`).
3. Edit quote B and save, or hit "Sync to sheet" on quote B.
4. Look at the sheet.

## Expected vs actual
- **Expected:** quote B keeps its correct row; deleting A only affects A.
- **Actual:** `deleteRow` shifts B up to physical row 100, but B's DB `sheet_row` still says 101. Re-syncing B writes B's data to row 101 — which is now occupied by whatever used to be at row 102 — corrupting that quote's row and leaving B's real row (100) stale. The drift is cumulative: every deletion makes one more stored `sheet_row` wrong for all rows below it.

## Severity
Partial / data-integrity. The app keeps working, but sheet rows silently drift out of alignment after deletions, causing wrong-row overwrites (and apparent duplicates/mismatches) over time. Low frequency (only on delete of a synced quote) but corrupts other quotes' data when it triggers.

## Investigation

Root cause — [app/Quote/SheetSyncService.inc.php](app/Quote/SheetSyncService.inc.php#L107-L116):
```php
public static function deleteRow($sheetRow) {
  ...
  GraphApiClient::post(self::wsPath('/range(address=\'' . self::rowAddress($sheetRow) . '\')/delete'), [
    'shift' => 'Up',
  ]);
}
```
`shift: 'Up'` physically removes the row and pulls everything below it up by one. Nothing reconciles the `sheet_row` column afterward.

Callers (neither updates other quotes' `sheet_row`):
- [scripts/quote/delete_quote.php:13](scripts/quote/delete_quote.php#L13) (soft delete)
- [scripts/quote/destroy_quote.php:11](scripts/quote/destroy_quote.php#L11) (hard delete)

The stale `sheet_row` is then trusted blindly by [`syncRow`](app/Quote/SheetSyncService.inc.php#L87-L95), called from [scripts/quote/sync_to_sheet.php:42](scripts/quote/sync_to_sheet.php#L42) and [scripts/quote/save_information.php:58](scripts/quote/save_information.php#L58) whenever `getSheetRow()` is set — so it writes to the wrong physical row.

Live evidence at time of filing: quotes `118983` and `118984` both have `sheet_row = 1244` (two quotes mapping to one row). That specific instance was induced by manual cell edits during this investigation, but it is the same failure mode this bug produces on every delete.

## Fix Plan
Pick one (option 2 recommended):

1. **Don't shift on delete — clear the row instead.** Replace the `range/delete` `shift: 'Up'` with a `PATCH` that writes blanks to `A:T` of the row. Other quotes' `sheet_row` stays valid. Downside: leaves blank rows that accumulate (could be compacted separately).
2. **Decrement after delete (recommended).** Keep `shift: 'Up'`, but right after a successful `deleteRow($deletedRow)`, run `UPDATE rfq SET sheet_row = sheet_row - 1 WHERE sheet_row > :deletedRow`. Keeps the sheet compact and all indices correct. Add a `SheetSyncRepository` method for this and call it from both delete scripts. Also `NULL` the deleted quote's own `sheet_row` (there's already `clearSheetRow`).
3. **Re-resolve on every write (safety net).** Have `syncRow` scan column A for the quote id (same dedup as `appendRow`) instead of trusting the stored row. Most robust, costs one extra small read per sync (now cheap — see related note). Can layer on top of option 2.

Files to touch: `app/Quote/SheetSyncService.inc.php`, `app/Quote/SheetSyncRepository.inc.php`, `scripts/quote/delete_quote.php`, `scripts/quote/destroy_quote.php`.

Test: seed two quotes at adjacent rows (can be done via the Graph API as in this session), delete the upper one, re-sync the lower one, and assert it wrote to its own (shifted) row, not a neighbor's.

## Related — production duplicate investigation (mitigation already applied)
The reason this was investigated: **duplicate rows appeared in production.** Findings:
- The duplicate-prevention dedup in [`appendRow`](app/Quote/SheetSyncService.inc.php#L68-L85) (added in `b2074f41`) is **logically correct** — verified repeatedly against the live sheet and with a deterministic end-to-end test (seeded the next id into a middle row via the Graph API, created the quote, and it correctly overwrote that row instead of appending).
- The dedup's only failure mode is **reading a stale/incomplete `usedRange`**: it previously downloaded the **entire ~4MB used range** (all 23 columns × all properties) on every sync. A heavy read is more exposed to timeouts and to Graph's read-after-write/edit eventual consistency, which can make the scan miss an existing id and append a duplicate. A manual cell edit immediately before creating a quote reproduces it; in production the same race can occur under load or right after a recent write.
- **Mitigation applied this session:** `getUsedRange` now reads only the row count (~350 B) plus column A (~11 KB) instead of the full ~4MB grid — ~350× smaller and faster, narrowing the stale-read window. Logic/return shape unchanged.

## Open Questions
- Preferred delete strategy: option 1 (clear/blank) vs option 2 (decrement). Option 2 keeps the sheet compact and is recommended, but confirm there's no external consumer that depends on stable row positions.
- Should `syncRow` also re-resolve the row by column-A scan (option 3) as a belt-and-suspenders safeguard, now that the read is cheap?
- Concurrency: deletes/syncs are assumed low-concurrency (single-office app). Confirm before relying on the decrement approach.
