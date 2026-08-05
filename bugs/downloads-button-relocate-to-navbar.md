# Downloads button relocate to bottom navbar

Move the "Downloads" dropdown off the Items table header into the bottom sticky action bar.

**Status:** fixed

## Steps to Reproduce
1. Open any quote: `perfil/quote/editar_cotizacion/{id}`.
2. Look above the Items table — the "Downloads" dropdown button sits right-aligned next to the "Items" section title.

## Expected vs Actual
- **Expected:** The Downloads button lives in the bottom sticky action bar, as the rightmost button (after Back · Save · Add item · Add Comment · Rooms · Actions · **Downloads**).
- **Actual:** It's currently positioned above the Items table, right-aligned against the "Items" title, styled as a `dropdown dropleft` (opens leftward) rather than matching the navbar's `dropup` dropdowns (Rooms, Actions).

## Severity
Cosmetic / layout only — no functional defect, purely a relocation request.

## Investigation

**Current implementation:** `RepositorioItem::escribir_items($id_rfq)` in [app/Quote/RepositorioItem.inc.php:245-267](../app/Quote/RepositorioItem.inc.php#L245-L267) renders the button inside `.quote-section-header` (flex row, `justify-content: space-between`, holds the "Items" title on the left and this dropdown on the right). Markup:

```php
<div class="dropdown dropleft">
  <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <i class="fas fa-download mr-1"></i> Downloads
  </button>
  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
    <a target="_blank" href="<?php echo PDF_TABLA_ITEMS . $id_rfq; ?>" class="dropdown-item">PDF - Items table</a>
    <?php if ($re_quote_exists): ?>
      <a target="_blank" href="<?php echo EXCEL_ITEMS_TABLE . $id_rfq; ?>" class="dropdown-item">EXCEL - Quote &amp; Re-quote</a>
    <?php endif; ?>
    <a class="dropdown-item" href="<?php echo PROPOSAL . '/' . $cotizacion->obtener_id(); ?>" target="_blank">Proposal</a>
    <?php if ($cotizacion->obtener_canal() == 'GSA-Buy'): ?>
      <a class="dropdown-item" href="<?php echo PROPOSAL_GSA . '/' . $cotizacion->obtener_id(); ?>" target="_blank">GSA Proposal</a>
    <?php endif; ?>
    <?php if (count($rooms)): ?>
      <a class="dropdown-item" href="<?php echo PROPOSAL_ROOM . '/' . $cotizacion->obtener_id(); ?>" target="_blank">Proposal by Room</a>
    <?php endif; ?>
  </div>
</div>
```

`escribir_items()` self-fetches `$cotizacion`, `$re_quote_exists`, `$rooms` via its own `Conexion`/repository calls (lines 237-243) — these are local to that method, not reused from the caller.

**Target location:** the bottom sticky bar is built in [forms/quote/edicion_cotizacion_recuperada.inc.php:137-182](../forms/quote/edicion_cotizacion_recuperada.inc.php#L137-L182). Its `.quote-action-bar__right` div (lines 144-154) already includes two dropdown buttons via self-contained templates in `forms/quote/templates/`:
- `rooms_button.inc.php` — fetches its own `$rooms` via `RoomRepository::getAll(..., $id_rfq)`, wraps markup in `<div class="btn-group dropup">`.
- `actions_button.inc.php` — same `btn-group dropup` wrapper, adds `dropdown-menu-right` (it's the rightmost item today).

`$id_rfq` is available throughout this whole include chain — it's set once in the router (`vistas/perfil.php`, e.g. line 66/294) and stays in scope across `include`s (no functions/isolated scope involved). `$cotizacion_recuperada` (the full Rfq object for this quote) is also already in scope the entire time `edicion_cotizacion_recuperada.inc.php` runs, so a new template can reuse it directly instead of re-querying `RepositorioRfq` for `$cotizacion`.

**Root cause:** not a defect — the button was simply built as part of the Items section header rather than the action bar when the feature was first added; no prior discussion tied it to that specific location.

## Fix Plan
1. **`app/Quote/RepositorioItem.inc.php`** — remove the `<div class="dropdown dropleft">…</div>` block (lines 249-266) from `escribir_items()`. The `.quote-section-header` keeps just the "Items" title.
2. **New file `forms/quote/templates/downloads_button.inc.php`** — self-contained template following the `rooms_button.inc.php` convention:
   - Fetch `$re_quote_exists` (`ReQuoteRepository::re_quote_exists`) and `$rooms` (`RoomRepository::getAll`) using `$id_rfq`, wrapped in `Conexion::abrir_conexion()`/`cerrar_conexion()`.
   - Reuse `$cotizacion_recuperada` (already in scope) for `obtener_id()`/`obtener_canal()` instead of re-querying `$cotizacion`.
   - Same 5 conditional menu items/URL constants (`PDF_TABLA_ITEMS`, `EXCEL_ITEMS_TABLE`, `PROPOSAL`, `PROPOSAL_GSA`, `PROPOSAL_ROOM`).
   - Markup wrapper changed from `dropdown dropleft` to `<div class="btn-group dropup">` + `<div class="dropdown-menu dropdown-menu-right">`, matching Rooms/Actions.
3. **`forms/quote/edicion_cotizacion_recuperada.inc.php:153`** — add `<?php include_once 'forms/quote/templates/downloads_button.inc.php'; ?>` immediately after the Actions include, inside `.quote-action-bar__right`, so it renders last (rightmost).
4. No JS changes needed — Bootstrap's native `data-toggle="dropdown"` handles behavior, same as Rooms/Actions today.
5. No CSS changes needed — existing `.quote-action-bar` / `.dropdown-menu` rules in `css/estilos.css` already cover spacing, sizing, and the 200px scroll cap.

**Test:** manual check on a quote page — Downloads button no longer appears above the Items table; appears rightmost in the bottom bar; dropdown opens upward (`dropup`) and right-aligned (`dropdown-menu-right`) without clipping off-screen; all conditional menu items (EXCEL, GSA Proposal, Proposal by Room) still show/hide correctly based on re-quote/channel/rooms state.

## Open Questions
None — placement (rightmost, after Actions) was confirmed with the user.
