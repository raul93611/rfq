# Items table scroll resets to top after adding a provider

Status: planned

## Description

On the Quote Edit page, adding a provider to an item (or any items/subitems/providers edit) resets the page's scroll position back to the top instead of staying where the user was working.

## Steps to Reproduce

1. Open a quote with a long items list (long enough that `.content-wrapper` scrolls).
2. Scroll down to one of the later items.
3. Add a new provider to that item and save.

## Expected Behavior

Scroll position stays put — the user should still be looking at the item they were working on.

## Actual Behavior

The view snaps back to the top of the items table/page.

## Severity

Partially disruptive — slows the user down, especially on long quotes when adding several providers in a row.

## When It Started

Always been there. It became more noticeable recently because the Add Provider modal now closes automatically on save (see "Add modals now close on save" in CLAUDE.md) instead of staying open — before that change, users stayed inside the modal after saving and didn't immediately see the reset.

## Investigation

- The page uses AdminLTE's `layout-fixed` body class ([plantillas/utilities/documento_declaracion.inc.php:38](../plantillas/utilities/documento_declaracion.inc.php#L38)), which makes `.content-wrapper` its own independently-scrolling pane with a height pinned to the viewport — that's the "static height" scrollbar, not the plain window scroll.
- Every items-table mutation (add/edit/delete item, subitem, provider, provider-subitem) calls `refreshItemsTable()` in [js/quote.js:327-337](../js/quote.js#L327-L337):
  ```js
  function refreshItemsTable() {
    $.get('/rfq/quote/get_items_table/' + rfqId(), function (html) {
      $('#items-section-wrapper').html(html);
      if (typeof window.iemReinitCalc === 'function') window.iemReinitCalc();
      syncIdInputs();
    });
  }
  ```
  This does a full HTML replace of `#items-section-wrapper` with no scroll-position handling — nothing captures `.content-wrapper`'s scroll offset before the swap or restores it after.
- Root cause: missing scroll-preservation around this AJAX swap. `refreshItemsTable()` is the single shared choke point for all items-table mutations, so this affects all of them (add/edit/delete item, subitem, provider), not just Add Provider — it's just most noticeable there now that the modal auto-closes and drops the user right back onto the table.

## Fix Plan

In `refreshItemsTable()` ([js/quote.js:327](../js/quote.js#L327)):
1. Capture `$('.content-wrapper').scrollTop()` before the `$.get()` call.
2. After `.html()` replaces the section and `iemReinitCalc()`/`syncIdInputs()` run, restore that scroll offset with `$('.content-wrapper').scrollTop(savedValue)`.

Single fix point — fixes it for every caller (add/edit/delete item, subitem, provider, provider-subitem) at once.

**Test:** Extend `tests/specs/03-quote-editing-items.spec.js` — scroll `.content-wrapper` down to a later item, add a provider, save, and assert the scroll position is preserved (within a small tolerance, since row heights can shift slightly when a provider list grows).

## Open Questions

None — self-contained frontend fix, no backend/data model involved.
