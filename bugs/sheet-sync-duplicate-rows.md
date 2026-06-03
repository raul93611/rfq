# Bug: duplicate rows appended to the SharePoint sheet (PRIMARY)

One-line: after "Break Sync" (or any state where a quote's `sheet_row` is NULL while its row still exists in the sheet), re-syncing appends a **second** row instead of re-attaching to the existing one — because the append-vs-update decision is made by scanning the eventually-consistent sheet, which can miss the orphaned row.

**Status:** fixed
**Observed:** in production (2026-06-02).
**Fixed on branch `fix/sheet-sync-duplicates`:** Break Sync now retains `sheet_row` (only flips status to `never`), auto-sync on info-save is gated on status `synced` (not on having a row), and `syncRow` self-heals a stale pointer. Re-sync after Break Sync therefore takes the `syncRow` path and re-attaches to the same row — no append, no duplicate. The dedup read was also lightened (~4MB → ~12KB). Verified against the live sheet.

## Steps to reproduce
1. Sync a qualifying quote to the sheet (it gets a row R, `sheet_row = R`, status `synced`).
2. Click **Break Sync** → DB now has `sheet_row = NULL`, status `never`; the row R **stays** in the sheet (by design — see modal copy).
3. Click **Sync to Sheet** again.
4. If the column-A scan doesn't re-find row R (Graph eventual consistency right after a write, or — before the read was lightened — the heavy ~4 MB `usedRange` read timing out / returning incomplete), a **new row** is appended → two rows with the same proposal id in column A.

A second (rarer) trigger: two near-simultaneous syncs while `sheet_row` is NULL (both take the `appendRow` path before either persists `sheet_row`). The Sync button is disabled during a request in `js/sheet_sync.js`, which mostly prevents the double-click case, but there is no server-side guard.

## Expected vs actual
- **Expected:** re-syncing a quote that already has a row updates that one row. One quote ⇒ one row, always.
- **Actual:** a duplicate row is appended; the quote now has two rows in the sheet.

## Severity
High (data integrity in the shared pipeline sheet). Happened in production. Not constant — depends on the scan missing the existing row — which is exactly why it's intermittent and hard to catch.

## Investigation
- The dedup in [`appendRow`](app/Quote/SheetSyncService.inc.php#L68-L85) (commit `b2074f41`) is **logically correct** — verified against the live sheet and with a deterministic end-to-end test (seeded the next id into a middle row via the Graph API; creating the quote correctly overwrote that row, no append). So the matching logic is not the bug.
- The bug is that the dedup is the **only** thing preventing a duplicate once a row is orphaned, and it reads an **eventually-consistent** source. The DB (`sheet_row`) is strongly consistent and should be the source of truth, but it gets thrown away.
- `sheet_row` is set to NULL in exactly one place: [`SheetSyncRepository::clearSheetRow`](app/Quote/SheetSyncRepository.inc.php#L21-L30), called only by [break_sheet_sync.php:21](scripts/quote/break_sheet_sync.php#L21). So **Break Sync is the orphaning path.** (A failed `updateSyncStatus` after a successful append is a secondary, rarer way to end up row-exists-but-pointer-NULL.)
- Break Sync intent (from [the modal](plantillas/quote/modals/break_sheet_sync_modal.inc.php#L13-L18)): stop auto-syncing, **keep the row**. So the row must stay — which means re-sync must reliably re-attach to it.
- Append paths: creation ([validacion_registro_cotizacion.inc.php:118](plantillas/quote/validacion_registro_cotizacion.inc.php#L118)) and manual sync ([sync_to_sheet.php:46](scripts/quote/sync_to_sheet.php#L46)). `save_information.php` only ever calls `syncRow` when `sheet_row` is set, so it never appends.

## Fix Plan
Make a same-id duplicate impossible by keeping the DB as the source of truth and self-correcting the pointer.

1. **Break Sync stops orphaning.** In `break_sheet_sync.php`, do **not** call `clearSheetRow`. Keep `sheet_row`; only set the status to a disconnected state (`never`). The UI already keys off status (`js/sheet_sync.js` tones), so it still shows "Sync to Sheet" and hides the Break button.
2. **Gate auto-sync on status, not on `sheet_row` presence.** In [save_information.php:56](scripts/quote/save_information.php#L56), change the condition from `$updatedQuote->getSheetRow()` to "status is `synced`" (still also: not multi-year, syncable bid). So edits after Break Sync do not auto-sync (preserves the modal's promise), but a freshly-synced quote still auto-syncs.
3. **Manual re-sync re-attaches deterministically.** With `sheet_row` retained, [sync_to_sheet.php:40](scripts/quote/sync_to_sheet.php#L40) takes the `syncRow($sheet_row)` branch → updates the same row, sets status back to `synced`. No scan, no append, no duplicate.
4. **Make `syncRow` self-healing** (covers manual row moves/deletes AND the stale-row-after-delete bug): before writing, read column A of the stored `sheet_row`; if it doesn't equal the quote id, re-resolve via the column-A scan (and persist the corrected `sheet_row`); only append if truly not found. See [bugs/sheet-sync-stale-row-after-delete.md](bugs/sheet-sync-stale-row-after-delete.md).
5. **(Done this session) Lighter dedup read.** `getUsedRange` now reads row count + column A (~12 KB) instead of the full ~4 MB grid, so the one remaining scan path (genuinely pointer-less legacy rows) is fast, fresh, and not prone to timeout/truncation.

Optional hardening: a short-lived server-side guard (or a unique check) so two concurrent syncs for the same id can't both append.

Files to touch: `scripts/quote/break_sheet_sync.php`, `scripts/quote/save_information.php`, `app/Quote/SheetSyncService.inc.php` (self-healing `syncRow`), possibly `app/Quote/SheetSyncRepository.inc.php`.

## Test
- Sync a quote → Break Sync → Sync again → assert it re-attached (same `sheet_row`, no new row; `rowCount` unchanged).
- Edit + save after Break Sync → assert it did **not** auto-sync (row unchanged).
- Manually delete the row in the sheet, then re-sync → assert `syncRow` re-resolved and didn't clobber a neighbor.

## Open Questions
- Acceptable to keep `sheet_row` internally after Break Sync (UX still says "disconnected", just re-attachable)? This is the crux of the recommended approach and matches the modal's "the row remains as-is."
- Preferred status value for the disconnected state — reuse `never`, or add an explicit `disconnected`?
