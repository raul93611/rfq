# Description required (partner sync drops quotes with a blank Description)

A quote can be saved with a blank Description; shared with the partner, it is silently not created on their side.

**Status:** planned

## Steps to reproduce

1. New Quote → leave **Description** blank.
2. Tick **Share with partner**, fill **Solicitation #** and the other required fields.
3. Save.

## Expected vs actual

- **Expected:** the form refuses to save until Description is filled (on every quote, shared or not — same treatment as End Date / Internal Due Date).
- **Actual:** the quote saves with `rfq.name = NULL`; the partner never creates the opportunity. Their API returned **no error**, so nothing in our outbox flagged it — it was only noticed in prod by the opportunity being missing on their side.

## Severity

Partially working — partner sync silently fails for the affected quote; everything else works. One prod quote was affected and was already fixed by filling in the Description.

## Investigation

- No validation anywhere on Description:
  - Client: no `required` on the textarea in [forms/quote/registro_cotizacion_vacio.inc.php:4](../forms/quote/registro_cotizacion_vacio.inc.php), [forms/quote/registro_cotizacion_validado.inc.php:8](../forms/quote/registro_cotizacion_validado.inc.php), [forms/quote/information.inc.php:12](../forms/quote/information.inc.php).
  - Server: `ValidadorCotizacionRegistro` ([app/Quote/ValidadorCotizacionRegistro.inc.php](../app/Quote/ValidadorCotizacionRegistro.inc.php)) validates code/dates only; [scripts/quote/save_information.php](../scripts/quote/save_information.php) checks only End Date / Internal Due Date.
- A blank Description is stored as `NULL`: `'name' => !empty($_POST['name']) ? trim($_POST['name']) : null` in [plantillas/quote/validacion_registro_cotizacion.inc.php:111](../plantillas/quote/validacion_registro_cotizacion.inc.php).
- `PartnerSyncService::buildPayload()` sends it as-is: `'name' => $row['name']` ([app/Partner/PartnerSyncService.inc.php:142](../app/Partner/PartnerSyncService.inc.php)). The partner apparently requires a name and drops the record without an error.
- The Information drawer can also blank the Description of an already-shared quote, which would break its later updates the same way.
- The inbound path already guards this: a partner record with no name falls back to the Solicitation # ([PartnerSyncService.inc.php:407](../app/Partner/PartnerSyncService.inc.php)).
- Other creation paths are fine: partner inbound create (fallback above) and `RepositorioRfq::copyRfq()` (copies the source's name; copies are not shared by default).

## Fix Plan

1. **Server validation, New Quote** — add `validar_name($name)` to `ValidadorCotizacion` (blank / whitespace-only → `'Must be fill out.'`), a `name` constructor arg + `error_name` in `ValidadorCotizacionRegistro`, include it in `registro_cotizacion_valida()`, and `mostrar_error_name()`. Pass `$_POST['name'] ?? ''` from `validacion_registro_cotizacion.inc.php`; render the error under the textarea in `registro_cotizacion_validado.inc.php` (value is already re-populated there). Update the existing callers of the constructor (`tests/php/quote_name_escaping_test.php`, `tests/php/internal_due_date_test.php`).
2. **Server validation, Information drawer** — in `save_information.php`, before anything is written, return `{"success": false, "message": "Description must be filled out.", "field": "name"}` when `trim($_POST['name'] ?? '') === ''` (mirror the End Date / Internal Due Date guard).
3. **Client** — add `required` to the Description textarea in all three forms; keep the label/help text. Check `js/checklist_info_drawer.js` surfaces the server message (it should, same as End Date).
4. **Payload fallback** — in `PartnerSyncService::buildPayload()`, send `trim(name)` or, when blank, the `solicitation_number` (mirrors the inbound fallback). Note this changes the payload hash only for blank-name rows.
5. **Tests**
   - PHP: blank / whitespace Description fails `ValidadorCotizacionRegistro`; filled passes. `buildPayload()` with `name = NULL` returns the Solicitation # as `name`.
   - Playwright: New Quote with blank Description stays on the form with the error; Information drawer save with blank Description shows the error and does not save (`11-checklist-info-drawer.spec.js`).
6. Docs: one line under Partner Opportunity Sync / Quote Edit drawer in `CLAUDE.md` that Description is required.

## Behavior note (confirmed with user)

Because Description becomes required on every quote, **any Information drawer save on an older quote with a blank Description is blocked** until it is filled — including turning on Share with partner. The user gets the error message and keeps their unsaved changes. Checklist and Documents saves are unaffected (no Description field).

## Open Questions

- None blocking. No backfill / outbox cleanup needed (the one affected prod quote is already fixed). The partner's exact rejection behavior is unknown (no error returned) — the fix does not depend on it.
