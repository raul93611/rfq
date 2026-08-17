# Provider names with &, <, > double-escape on display

Status: fixed

## Resolution

Fixed in the four confirmed culprits (`guardar_add_provider.php`, `guardar_edit_provider.php`,
`guardar_add_provider_subitem.php`, `guardar_edit_provider_subitem.php`): `provider` is now
`trim((string) filter_input(INPUT_POST, 'provider', FILTER_UNSAFE_RAW))` instead of
`FILTER_SANITIZE_FULL_SPECIAL_CHARS`, so raw text is stored and `RepositorioItem::renderProvidersList()`
is the only place that escapes. Test: `tests/php/provider_name_escaping_test.php` (repository-layer
round trip; `filter_input(INPUT_POST, ...)` isn't exercisable from a CLI test) plus a real-HTTP
regression in `tests/specs/04-quote-editing-providers.spec.js`.

**Existing data:** backfilled via `sql/provider_name_unescape_backfill.sql` (idempotent) — applied
locally, decoded 3,613 already-double-escaped rows in `provider` (e.g. "B&amp;H" -> "B&H") and 1 in
`provider_subitems`. **Must also be run on production.**

**Re-quote paths checked, not touched:** `scripts/re_quote/save_re_quote_provider.php` and
`save_re_quote_subitem_provider.php` also `htmlspecialchars()` the provider name at write time, but
their render sites (`ReQuoteItemRepository.inc.php`, `ReQuoteRepository.inc.php`,
`ReQuoteSubitemRepository.inc.php`) echo it back with **no output-side escaping at all** — so today
they only single-escape overall and do not reproduce this bug. Removing their write-time escaping
without first adding output-side escaping would turn a cosmetic bug into a stored-XSS hole, and
those render sites also leave brand/description fields fully unescaped, which is a separate,
larger pre-existing issue outside this ticket's scope. `scripts/providers/save_provider.php`
(master Providers list) was also checked and is already correct — it stores raw text
(`Input::test_input()`, no HTML-encoding) and escapes only at output (`load_providers_table.php`).

## Description

Provider names containing special characters (e.g. `D&H`) render literally as
`D&amp;H` in the Items table instead of `D&H`. Found incidentally while
investigating a separate column-width bug — visible in the same user screenshot
(provider "D&amp;H" shown in the Providers column).

## Steps to Reproduce

1. Add a provider to a quote item with a name containing `&` (e.g. "D&H") via the
   Add/Edit Provider form.
2. View the item's Providers column on `perfil/quote/editar_cotizacion/{id}`.

## Expected vs Actual

- **Expected:** provider name displays as `D&H`.
- **Actual:** displays as `D&amp;H` (double-encoded).

## Severity

Minor / cosmetic — display-only, doesn't affect pricing/calc logic.

## Investigation

Double HTML-escaping: the value is encoded once at input time and again at
output time.

- **Input time:** `scripts/quote/guardar_add_provider.php:5` and
  `scripts/quote/guardar_edit_provider.php:6` sanitize the incoming `provider`
  POST field with `FILTER_SANITIZE_FULL_SPECIAL_CHARS`, which HTML-encodes it
  (`D&H` → `D&amp;H`) **before it's stored in the DB** via
  `RepositorioProvider::insertar_provider()`.
- **Output time:** `app/Quote/RepositorioItem.inc.php:234` applies
  `htmlspecialchars($provider->obtener_provider(), ENT_QUOTES, 'UTF-8')` again when
  rendering the Providers column, turning the already-encoded `D&amp;H` into
  `D&amp;amp;H` in the HTML source — which the browser renders as literal text
  `D&amp;H`.

**Root cause:** provider name is escaped for HTML at write time (storage should
hold raw text) instead of only at read time.

## Fix Plan

1. In `scripts/quote/guardar_add_provider.php` and
   `scripts/quote/guardar_edit_provider.php`, stop using
   `FILTER_SANITIZE_FULL_SPECIAL_CHARS` on the `provider` field — use a plain
   trim/`FILTER_UNSAFE_RAW` + length/emptiness validation instead, so the raw
   name (`D&H`) is what gets stored.
2. Leave the output-side `htmlspecialchars()` in
   `app/Quote/RepositorioItem.inc.php:234` as-is — that's the correct place to
   escape for HTML.
3. Check other provider write paths for the same pattern before calling this
   done: `scripts/re_quote/save_re_quote_provider.php`,
   `scripts/re_quote/save_re_quote_subitem_provider.php`,
   `scripts/quote/guardar_add_provider_subitem.php`,
   `scripts/quote/guardar_edit_provider_subitem.php`, and
   `scripts/providers/save_provider.php` (master Providers list) — not
   inspected yet, only the two items-table paths that produced the reported
   symptom.
4. Add/extend a PHP test asserting a provider named `D&H` round-trips as `D&H`
   through insert + render.

## Open Questions

- **Existing data:** any provider already saved with an encoded name (e.g.
  `D&amp;H` sitting in the `providers` table right now) will keep rendering
  double-escaped even after the code fix, since the fix only stops *future*
  double-encoding. Needs a decision at `/build` time: leave existing rows as
  known-bad, or write a one-time cleanup script that HTML-decodes any provider
  name currently containing an entity pattern (`&amp;`, `&lt;`, `&gt;`, etc.).
- Not yet confirmed whether the same `FILTER_SANITIZE_FULL_SPECIAL_CHARS`
  pattern exists on the re-quote provider paths or the master Provider list
  save path (`scripts/providers/save_provider.php`) — listed above as
  paths to check, not confirmed culprits.
