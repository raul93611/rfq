# Sheet Sync Shows HTML-Escaped Characters in Opportunity Name

Quote names containing `&` (or other HTML-special characters) sync to the SharePoint pipeline sheet as their HTML entity (`&amp;`) instead of the literal character.

**Status:** planned

## Steps to Reproduce

1. Go to New Quote.
2. Enter a Name containing `&` (e.g. "Acme & Sons Contract").
3. Check "Sync to pipeline" and submit.
4. Open the linked SharePoint sheet row, column D (opportunity name).

## Expected vs Actual

- **Expected:** Column D shows `Acme & Sons Contract`.
- **Actual:** Column D shows `Acme &amp; Sons Contract`.

## Severity

Minor — cosmetic only, doesn't block any workflow, but makes the sheet data look wrong to anyone reading it.

## When It Started

Since the Name field itself was added (2026-05-25, commit `d02180a0`) — always been there, not a recent regression.

## Investigation

Root cause: [plantillas/quote/validacion_registro_cotizacion.inc.php:99](../plantillas/quote/validacion_registro_cotizacion.inc.php#L99) HTML-escapes the name **at save time**:

```php
'name' => !empty($_POST['name']) ? htmlspecialchars(trim($_POST['name']), ENT_QUOTES, 'UTF-8') : null,
```

`rfq.name` is stored already escaped (`Acme &amp; Sons Contract`). Every consumer of `Rfq::getName()` then gets the pre-escaped string, including [SheetSyncService::buildRowValues()](../app/Quote/SheetSyncService.inc.php#L26) (column D), which writes it verbatim to the Graph API — a plain-text context, so the entity is never decoded back to `&`.

This is the opposite of the convention used everywhere else in the codebase (e.g. `RepositorioItem.inc.php`), which escapes **on render**, not on save. The later edit path ([scripts/quote/save_information.php:58](../scripts/quote/save_information.php#L58)) already does it correctly — just `trim()`, no escaping — so editing the name after creation does not have this problem; only the initial New Quote submission does.

This also silently affects other consumers of the same tainted `rfq.name`:
- Quote Edit page description line ([plantillas/quote/editar_cotizacion.inc.php:108](../plantillas/quote/editar_cotizacion.inc.php#L108)) — re-escapes on top, so the browser shows the literal text `Acme &amp; Sons Contract`.
- Pipeline Table / Pipeline Metrics drill-down (`PipelineTableRepository::getDrillDown()`, `PipelineMetricsRepository`).
- Daily Digest email rows (`app/Utilities/DigestEmailTemplate.inc.php:156`) — same double-escape.

There is project precedent for this exact class of bug and its fix: `sql/provider_name_unescape_backfill.sql` was written earlier to decode legacy double-escaped provider names (see Items & Services Table Redesign in CLAUDE.md).

## Fix Plan

1. Remove the `htmlspecialchars()` call in `plantillas/quote/validacion_registro_cotizacion.inc.php:99` — store the raw trimmed name, matching `save_information.php`'s edit path.
2. Add an idempotent backfill SQL script (mirroring `sql/provider_name_unescape_backfill.sql`), e.g. `sql/rfq_name_unescape_backfill.sql`, to decode already-affected `rfq.name` values — run on production.
3. Add a PHP test asserting a quote created with `&`/`<`/`'` in the name stores the raw value, and that `SheetSyncService::buildRowValues()` emits it unescaped.
4. No change needed at render sites — they already escape correctly (e.g. `editar_cotizacion.inc.php:108`, `DigestEmailTemplate.inc.php:156`) once the source stops double-escaping.

## Open Questions

- Should the backfill also account for names copied via `copyRfq` (which copies `name` as-is from an already-affected source quote)? Default: no separate handling needed — the backfill fixes `rfq.name` directly regardless of how the bad value got there, and `copyRfq` just reads whatever's in the source row at copy time.
