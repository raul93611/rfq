# Remove SBA 8(a) case info from GSA proposal PDF

The GSA proposal PDF still prints the SBA 8(a) case number and entrance/exit dates. Those two lines should be removed.

**Status:** `planned`

## Steps to Reproduce

1. Open any quote.
2. Generate the **GSA proposal** PDF (route `perfil/quote/proposal_gsa/{id}`).
3. Look at the first highlighted row of the items table.

## Expected vs Actual

- **Expected:** The row shows only:
  - **Open Market Pricing Proposal**
  - E-Logic is an SBA 8(a) and HUBZone Certified SB
- **Actual:** The row also shows:
  - **SBA 8(a) Case No:** C0069X
  - **Entrance:** 09/30/2016 · **Exit:** 09/30/2026

## Severity

Minor. This is a content change, and nothing is broken.

## Investigation

- [index.php:327-331](../index.php#L327-L331): the `proposal_gsa` route sets `$encabezado = 1`. The regular `proposal` route sets it to `0`. Both routes render `scripts/utilities/proposal.php`.
- [herramientas/pdfTemplates/proposal.inc.php:261-270](../herramientas/pdfTemplates/proposal.inc.php#L261-L270): when `$encabezado` is set, the template prints a `.sba-row` table row. The unwanted text is hardcoded at lines 267–268.
- The string `C0069X` appears nowhere else in the codebase.
- The file is UTF-8 with LF line endings, so editing it does not risk a CRLF rewrite.

## Fix Plan

1. In `herramientas/pdfTemplates/proposal.inc.php`, delete lines 267–268 (the Case No line and the Entrance/Exit line).
2. Remove the trailing `<br>` from line 266 ("E-Logic is an SBA 8(a) and HUBZone Certified SB"), which is now the last line of the cell.
3. Keep "Open Market Pricing Proposal" and "E-Logic is an SBA 8(a) and HUBZone Certified SB" exactly as they are. The user confirmed the certification line stays.
4. Run `git diff --stat` and confirm that only those lines changed.
5. To verify, generate a GSA proposal PDF and a regular proposal PDF and look at both. The regular one should be unchanged. No automated test is needed for a text deletion.

## Open Questions

None.
