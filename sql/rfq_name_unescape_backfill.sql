-- One-time data fix: undo the write-time HTML-escape of quote names
-- (bugs/sheet-sync-html-escaped-name.md).
--
-- The New Quote submit (plantillas/quote/validacion_registro_cotizacion.inc.php) used to run
-- `name` through htmlspecialchars() before storage (e.g. "Acme & Sons" -> "Acme &amp; Sons").
-- Every consumer of Rfq::getName() then got the pre-escaped string: the SharePoint sheet
-- (column D, plain text) showed the literal entity, and the render sites that escape on
-- output (Quote Edit description, Daily Digest, Pipeline tables) double-escaped it. The code
-- fix stores raw text going forward; this backfill decodes rows written under the old code.
--
-- Mirrors sql/provider_name_unescape_backfill.sql. &amp; is decoded last so it can't
-- interfere with decoding the other entities first. A name with no entities is left
-- untouched, so re-running is harmless for every normal name. RUN IT ONCE per database,
-- though: it decodes one level per run, so a name someone deliberately typed with a literal
-- entity (e.g. "&amp;" stored as "&amp;amp;") would be decoded a second level on a re-run.
-- Fixes rfq.name directly, so quotes duplicated by copyRfq from an affected source are
-- covered too.
--
-- Rows already synced to the SharePoint sheet keep the old text in column D: sync is
-- write-once and never overwrites an existing row, so fix those cells by hand if needed.

UPDATE rfq
SET name = REPLACE(
  REPLACE(
    REPLACE(
      REPLACE(
        REPLACE(name, '&lt;', '<'),
        '&gt;', '>'
      ),
      '&quot;', '"'
    ),
    '&#039;', ''''
  ),
  '&amp;', '&'
)
WHERE name LIKE '%&amp;%' OR name LIKE '%&lt;%' OR name LIKE '%&gt;%' OR name LIKE '%&quot;%' OR name LIKE '%&#039;%';
