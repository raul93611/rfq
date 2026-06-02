# Child Quote Sync Controls

**One-line description:** Child quotes (multi-year project links) must never sync to the SharePoint sheet; add master-link to the create form and a "Break Sync" action for any synced quote.

**Status:** fixed

---

## Steps to Reproduce

1. Create a quote, or open an existing one.
2. Link it to a master quote via the "Set Master Proposal" modal (sets `multi_year_project`).
3. Save the Information tab while `type_of_bid` is "Audio Visual" or "Services" — the auto-sync fires.
4. Alternatively, click "Sync to Sheet" manually on any child quote.
5. Alternatively, advance the quote's status — the status-cell update fires.

## Expected Behavior

- Child quotes (where `multi_year_project` is set) are **never** synced to the SharePoint sheet at any point.
- When creating a quote, the user can designate it as a child of a master quote upfront, so it is never synced from the moment it exists.
- On any synced quote (child or not), the user can "Break Sync" — this disconnects the app from the existing sheet row without deleting it, so future edits stop being reflected in the sheet.

## Actual Behavior

- Child quotes are auto-synced when saving Information and when status changes.
- The "Sync to Sheet" button appears and works on child quotes.
- There is no way to break the link between a quote and its sheet row short of deleting the row from the sheet manually.
- The master-link option only exists after quote creation (via a modal on the edit page).

## Severity

Medium — data integrity issue: child quotes pollute the bid pipeline sheet with entries that should not be there, and there is no self-service recovery path.

---

## Investigation

### Child-quote detection
`rfq.multi_year_project` is the marker. When non-null it holds the master quote's ID. `Rfq::obtener_multi_year_project()` exposes it. A child quote → `obtener_multi_year_project() !== null`.

### Sync entry points (all lack a child-quote guard)

| File | Trigger | Lines |
|---|---|---|
| `scripts/quote/sync_to_sheet.php` | Manual "Sync to Sheet" button | 34–44 |
| `scripts/quote/save_information.php` | Auto-sync on Info save for AV/Services | 52–70 |
| `scripts/quote/guardar_editar_cotizacion.php` | Auto status-cell update on state transitions | ~98–112 |

### Sync UI
`plantillas/quote/editar_cotizacion.inc.php` renders the sync block unconditionally (lines 56–97). It checks `$ss_status` to decide button label/style but never checks `multi_year_project`.

### Create-quote form
`plantillas/quote/validacion_registro_cotizacion.inc.php` hardcodes `'multi_year_project' => null` on line 68. The form (`forms/quote/registro_cotizacion_vacio.inc.php` / `registro_cotizacion_validado.inc.php`) has no master-link field. The link can only be set post-creation via `scripts/quote/link_quote.php`.

### Break Sync — no mechanism exists
`SheetSyncRepository` only has `updateSyncStatus()` (writes status + timestamp) and `clearSheetRow()` (nulls `sheet_row`). There is no "disabled" state and no endpoint that clears the row reference without deleting the sheet row.

---

## Fix Plan

### 1. Add master-link field to the create-quote form

**`forms/quote/registro_cotizacion_vacio.inc.php`** and **`forms/quote/registro_cotizacion_validado.inc.php`**
- Add an optional "Master Proposal" select (or Select2 search, same pattern as `link_quote_modal`) below the existing fields, in its own collapsible/optional section.
- Name: `multi_year_project` (matches the existing DB column and Rfq constructor key).
- Use a `<select>` populated from a new lightweight repository method that fetches master-eligible quotes (where `multi_year_project IS NULL` and `deleted = 0`), or replicate the AJAX search pattern from the link modal.

**`plantillas/quote/validacion_registro_cotizacion.inc.php` line 68**
- Change `'multi_year_project' => null` to `'multi_year_project' => !empty($_POST['multi_year_project']) ? (int)$_POST['multi_year_project'] : null`.

### 2. Guard all auto-sync paths against child quotes

**`scripts/quote/save_information.php` lines 52–70**
```php
// Inside the syncable_bid_types block, after fetching $updatedQuote:
if ($updatedQuote && $updatedQuote->obtener_multi_year_project() === null) {
    // existing sync logic
}
```

**`scripts/quote/sync_to_sheet.php` after fetching `$quote` (line ~22)**
```php
if ($quote->obtener_multi_year_project() !== null) {
    echo json_encode(['success' => false, 'message' => 'Child quotes are not synced to the sheet.']);
    exit;
}
```

**`scripts/quote/guardar_editar_cotizacion.php` — `$updateSheetStatus` closure (~line 98)**
- Pass `$cotizacion_recuperada` into the closure (or check before calling it) and return early if `obtener_multi_year_project() !== null`.

### 3. Update sync UI for child quotes

**`plantillas/quote/editar_cotizacion.inc.php`**
- At the top of the sync block, check `$cotizacion_recuperada->obtener_multi_year_project()`:
  - If non-null → replace entire sync block with a static message, e.g.:
    ```
    <span class="ss-block-meta"><i class="fas fa-info-circle mr-1"></i>Child quotes are not synced to the sheet.</span>
    ```
  - If null → render the existing sync button / status as today.
- Mutually exclusive with "Break Sync" (see below): show Break Sync only when `sheet_row` is set.

### 4. Add "Break Sync" action

**New file: `scripts/quote/break_sheet_sync.php`**
- Accepts `POST id_rfq`.
- Session-gated.
- Calls `SheetSyncRepository::clearSheetRow($conexion, $id_rfq)` (already exists) **and** `SheetSyncRepository::updateSyncStatus($conexion, $id_rfq, 'never')` — does NOT call `SheetSyncService::deleteRow()`.
- Returns JSON `{success: true}`.

**`plantillas/quote/editar_cotizacion.inc.php`**
- When `$ss_status === 'synced'` (quote has a sheet row), show a "Break Sync" button alongside or instead of "Re-sync".
- Clicking it opens a confirmation modal:
  > "The existing row in the sheet will remain. Future edits to this quote will no longer update the sheet. If you want to remove the data from the sheet, you can do so manually."
- On confirm: AJAX POST to `break_sheet_sync`, then reload or update UI to show the "Sync to Sheet" button (status reset to never).

**`app/Bootstrap/routes.inc.php`**
- Add `define('BREAK_SHEET_SYNC', RUTA . 'quote/break_sheet_sync');`

**`index.php`**
- Add `case 'quote/break_sheet_sync': include SCRIPTS . 'quote/break_sheet_sync.php'; break;`

**`js/sheet_sync.js`**
- Wire up the Break Sync button click → confirmation modal → AJAX → UI reset.

---

## Open Questions

1. **Master-link select on create form — search or full list?** The existing post-creation modal uses Select2 AJAX (search by ID, min 4 chars). For the create form, a simpler static `<select>` populated server-side may be sufficient if the quote count is manageable, or the same AJAX pattern can be reused.
2. **After Break Sync on a child quote** — the sync block would show "Child quotes are not synced" (because `multi_year_project` is still set). The sheet row reference is gone and the button never reappears. This is the correct remediation path for accidentally-synced children. Confirm this is the intended UX.
3. **`sheet_sync_status = 'never'` vs a dedicated `'unlinked'` status** — using `'never'` resets the UI cleanly to the initial "Sync to Sheet" state. If there's ever a need to audit which quotes were intentionally de-synced, a distinct status value (e.g. `'unlinked'`) would make that queryable. Decision can be deferred to build time.
