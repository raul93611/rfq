# Documents drawer — delete confirm button is white-on-white until hover

Status: fixed

## Description

In the Documents tab of the quote edit slideover, clicking the trash icon on an existing file shows an inline "Delete this file?" confirm row with Cancel/Delete buttons. The Delete button renders with white text on a white background, making it look empty/invisible. Hovering it turns the background red, at which point the white text becomes readable.

## Steps to Reproduce

1. Open a quote's edit page (`perfil/quote/editar_cotizacion/{id}`).
2. Open the Documents tab in the checklist/info drawer.
3. Click the trash icon next to an already-uploaded file.
4. Observe the inline confirm row's Delete button.

## Expected Behavior

The Delete button should be readable by default — red background with white text (matching its hover state).

## Actual Behavior

The Delete button is white background / white text by default (invisible/blank), only becoming readable (red bg, white text) on hover.

## Severity

Minor — cosmetic, doesn't block the delete action (button is still clickable), but looks broken.

## Screenshot

User-provided screenshot of `perfil/quote/editar_cotizacion/118063`, Documents tab, showing the blank white confirm-delete button next to a black-bordered Cancel button.

## Investigation

Root cause: CSS specificity conflict in [css/estilos.css:3693-3696](../css/estilos.css#L3693-L3696).

```css
.doc-file-confirm-actions button { height: 26px; padding: 0 10px; border-radius: 6px; font-size: 11.5px; font-weight: 600; border: 1px solid var(--line); background: #fff; cursor: pointer; }
.doc-file-confirm-cancel:hover { background: var(--navy-50); }
.doc-file-confirm-delete { background: var(--red); border-color: var(--red); color: #fff; }
.doc-file-confirm-delete:hover { background: #b91c1c; }
```

- `.doc-file-confirm-actions button` has specificity `(0,1,1)` (one class + one type selector) and sets `background: #fff`.
- `.doc-file-confirm-delete` has specificity `(0,1,0)` (one class) and sets `background: var(--red)`.
- CSS resolves conflicting declarations by specificity, not source order — the higher-specificity `(0,1,1)` rule wins, so `background` stays white regardless of declaration order. `border-color` and `color` aren't contested by the shared rule, so those apply normally (red border, white text).
- `.doc-file-confirm-delete:hover` has specificity `(0,2,0)` (two classes), which *does* beat `(0,1,1)`, so hover correctly turns the background red — masking the bug during casual testing.

This CSS was introduced in commit `1410405b` ("feat: add Documents drawer tab, retire bootstrap-fileinput", 2026-07-30) as part of the Documents Drawer Tab feature (see CLAUDE.md) — it has been broken since that feature shipped, not a recent regression.

The button markup itself is fine: [js/document_widget.js:172-176](../js/document_widget.js#L172-L176) just sets class `doc-file-confirm-delete` with no inline styles.

## Fix Plan

1. In [css/estilos.css](../css/estilos.css), remove `background: #fff` from the shared `.doc-file-confirm-actions button` rule (line 3693) and add it to `.doc-file-confirm-cancel` specifically instead, since Cancel is the only button that needs a white background by default. This removes the specificity conflict — `.doc-file-confirm-delete`'s `background: var(--red)` will then apply unopposed.
2. Verify visually: open the Documents tab, click delete on a file, confirm the Delete button shows red background / white text immediately (no hover needed), and hover still darkens it to `#b91c1c`.
3. Extend `tests/specs/12-documents-drawer.spec.js` with an assertion on the confirm-delete button's computed `background-color` before hover (should not be white/`rgb(255, 255, 255)`).

## Open Questions

None — fix is a self-contained CSS change.
