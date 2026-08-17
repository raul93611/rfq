# Items/Services table description columns too narrow on small laptops

Status: fixed

## Description

On smaller laptop screens (~13-14"), the "Brand / Part #" description columns in the
Items and Services tables on the Quote Edit page shrink to unreadable widths, while
the numeric/provider columns stay fine.

## Steps to Reproduce

1. Open a quote with items on `perfil/quote/editar_cotizacion/{id}`.
2. View on a small laptop screen (~13-14", e.g. 1366px-class viewport). Reported not
   reproducible on a 16" laptop or 27" desktop monitor.
3. Look at the Items table's "Brand / Part #" columns (Project Specifications +
   E-Logic Proposal) and the Services table's description column.

## Expected vs Actual

- **Expected:** description text has enough width to be legible, even if that means
  the table scrolls horizontally.
- **Actual:** description columns compress to a sliver (see screenshot — brand names
  and part numbers truncate hard, e.g. "AMP16US da…"), while every other column
  (Qty, Providers, Additional, Best Unit, Total Cost, For Client, Total Price) keeps
  its full fixed width.

## Severity

Minor / cosmetic — table is still usable, just cramped and hard to read on that
screen size.

## Screenshot

User-provided screenshot of the Items table row showing the squeezed "Brand / Part #"
columns next to full-width Qty/Providers/Cost/Price columns.

## Investigation

Both tables already have a horizontal-scroll wrapper — it's just inert.

- `#tabla_items` ([app/Quote/RepositorioItem.inc.php:376-382](../app/Quote/RepositorioItem.inc.php#L376-L382))
  and the services table ([app/Service/ServiceRepository.inc.php:105-109](../app/Service/ServiceRepository.inc.php#L105-L109))
  both sit inside a `.it-table-scroll` div with `overflow-x: auto` already set
  ([css/estilos.css:3797](../css/estilos.css#L3797)).
- `.it-table` is `table-layout: fixed; width: 100%`
  ([css/estilos.css:3798](../css/estilos.css#L3798)), with a `<colgroup>` giving every
  column an explicit pixel width **except** the description column(s), which are left
  auto (`<col>` with no `width`).
- Fixed-width columns sum to ~918px on the items table (12 columns), ~466px on the
  services table (6 columns). The remaining space is split across 2 auto columns
  (items) / 1 auto column (services).
- Because `.it-table` has no `min-width`, the table never grows past its container —
  on a wide screen there's plenty of leftover space so the auto columns look fine; on
  a narrower container the auto columns get squeezed toward zero **instead of the
  table overflowing**, so the existing `overflow-x: auto` wrapper never actually
  activates.

**Root cause:** missing `min-width` on `.it-table` / `.it-table.is-services` — the
scroll behavior the redesign already anticipated is dead code without it.

## Fix Plan

1. In `css/estilos.css`, add a `min-width` to `.it-table` (~3798) — fixed-column sum
   (918px) + a reasonable floor per description column (e.g. 220px × 2) ≈ 1360px.
2. Add a separate, smaller `min-width` to `.it-table.is-services` — fixed-column sum
   (466px) + one description-column floor (e.g. 280px) ≈ 750px.
3. Verify `.it-table-scroll`'s `overflow-x: auto` then produces a real horizontal
   scrollbar on a narrow viewport (e.g. resize browser to ~1300px or use devtools
   responsive mode) instead of squeezed columns, on both the Items and Services
   tables, with sticky header (`.it-thead th { position: sticky; top: ... }`) still
   working during horizontal scroll.
4. Spot-check the kebab menu / provider dropdown popovers (`.it-menu`, positioned
   `fixed`) still anchor correctly when the table is horizontally scrolled.

No JS changes expected — this is CSS-only.

## Open Questions

- Exact `min-width` floor per description column (220px items / 280px services) is a
  judgment call based on the fixed-column budget — adjust during `/build` if it looks
  cramped or excessive against a real small-laptop viewport.
- Confirm 1024px-and-below is the practical breakpoint where this becomes visible
  (there's already a `@media (max-width: 1024px)` block for `.it-card-head`/`.it-ship`
  at [css/estilos.css:3956](../css/estilos.css#L3956) that could host this if a
  narrower-only min-width is preferred over an always-on one).
