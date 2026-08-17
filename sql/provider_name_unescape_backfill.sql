-- One-time data fix: undo the write-time double-HTML-escape of provider names
-- (bugs/provider-name-double-html-escaping.md).
--
-- guardar_add_provider.php / guardar_edit_provider.php / guardar_add_provider_subitem.php /
-- guardar_edit_provider_subitem.php used to sanitize the `provider` field with
-- FILTER_SANITIZE_FULL_SPECIAL_CHARS, HTML-encoding it before storage (e.g. "D&H" ->
-- "D&amp;H"). RepositorioItem::renderProvidersList() then escapes again at render time,
-- so already-encoded rows show as literal "D&amp;H" on screen instead of "D&H". The code
-- fix stops encoding at write time going forward; this backfill decodes rows written
-- under the old (buggy) code so they display correctly too.
--
-- &amp; is decoded last so it can't interfere with decoding the other entities first.
-- Idempotent: a name with no entities is left untouched, safe to run more than once,
-- and safe on both local and production.

UPDATE provider
SET provider = REPLACE(
  REPLACE(
    REPLACE(
      REPLACE(
        REPLACE(provider, '&lt;', '<'),
        '&gt;', '>'
      ),
      '&quot;', '"'
    ),
    '&#039;', ''''
  ),
  '&amp;', '&'
)
WHERE provider LIKE '%&amp;%' OR provider LIKE '%&lt;%' OR provider LIKE '%&gt;%' OR provider LIKE '%&quot;%' OR provider LIKE '%&#039;%';

UPDATE provider_subitems
SET provider = REPLACE(
  REPLACE(
    REPLACE(
      REPLACE(
        REPLACE(provider, '&lt;', '<'),
        '&gt;', '>'
      ),
      '&quot;', '"'
    ),
    '&#039;', ''''
  ),
  '&amp;', '&'
)
WHERE provider LIKE '%&amp;%' OR provider LIKE '%&lt;%' OR provider LIKE '%&gt;%' OR provider LIKE '%&quot;%' OR provider LIKE '%&#039;%';
