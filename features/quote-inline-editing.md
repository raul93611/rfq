# Quote Inline Editing via Modals

**Description:** Convert all item, subitem, provider, and subitem-provider CRUD operations on the quote editing page from full-page redirects to in-page modals with AJAX persistence.

**Status:** built

---

## Why

Users were constantly leaving the quote editing page to add/edit/delete items and providers, then returning. The JS calculation engine (`calcQuoteTable()`) would re-run on return but users frequently forgot to click the general Save button after table changes, leading to lost work. This feature eliminates all redirects for table operations and auto-persists each change immediately.

---

## User Flow

1. User is on `/rfq/perfil/quote/editar_cotizacion/{id}`.
2. User clicks **Add Item**, **Edit** (item row), **Add Provider**, **Edit** (provider link), **Add Subitem**, **Edit** (subitem row), **Add Provider (subitem)**, or **Edit** (subitem provider link).
3. A modal opens. The form is loaded via AJAX into the modal body (same pattern as rooms/services).
4. User fills in the form.
5. User clicks Save inside the modal.
6. AJAX POST fires to the existing backend script (e.g. `guardar_add_item.php`).
7. On success:
   - The items table section is refreshed via AJAX (partial DOM replacement).
   - `calcQuoteTable()` re-runs automatically within 100ms on the new DOM.
   - A toastr success notification appears (e.g. "Item created successfully").
   - The modal stays open with the form reset — ready for another entry.
8. User closes the modal manually when done.
9. If the user tries to close the modal with unsaved changes (dirty form), a warning dialog appears: "You have unsaved changes. Are you sure you want to close?"
10. **Delete flow:** User clicks a delete button (`.delete_item_button`, `.delete_subitem_button`, `.delete_provider_item_button`, `.delete_provider_subitem_button`). The existing `#alert_delete_system` confirmation modal opens. On confirm, an AJAX request fires instead of navigating — table refreshes and toastr fires on success.

---

## UI Changes

### `plantillas/quote/editar_cotizacion.inc.php` (and included partials)
- Edit/Add/Provider/Subitem buttons in the items table: change `href` navigation to `data-*` attributes that trigger modal open.
- Delete buttons: no markup change needed — `main.js` already intercepts them via class selectors.

### New modals (6 total, loaded via AJAX):
| Modal ID | Trigger | Form loaded from |
|---|---|---|
| `add-item-modal` | Add Item button | `plantillas/quote/forms/add_item_form.inc.php` |
| `edit-item-modal` | Edit button on item row | `plantillas/quote/forms/edit_item_form.inc.php` |
| `add-provider-modal` | Provider button on item row | `plantillas/quote/forms/add_provider_form.inc.php` |
| `edit-provider-modal` | Provider name link in providers column | `plantillas/quote/forms/edit_provider_form.inc.php` |
| `add-subitem-modal` | Subitem button on item row | `plantillas/quote/forms/add_subitem_form.inc.php` |
| `edit-subitem-modal` | Edit button on subitem row | `plantillas/quote/forms/edit_subitem_form.inc.php` |
| `add-provider-subitem-modal` | Provider button on subitem row | `plantillas/quote/forms/add_provider_subitem_form.inc.php` |
| `edit-provider-subitem-modal` | Provider name link on subitem provider | `plantillas/quote/forms/edit_provider_subitem_form.inc.php` |

- Modal shells added to `plantillas/quote/modals/` (one per modal above).
- Forms extracted from existing separate-page templates (`add_item.inc.php`, `edit_item.inc.php`, etc.) into reusable form partials.

### `js/quote.js`
- Add open/submit/reset/dirty-check handlers for all 8 modals.
- Delete confirm: modify `main.js` or `quote.js` to fire AJAX on `#continue_button` click when context is a table delete (instead of navigating to the href).
- After each successful save: call a `refreshItemsTable(idRfq)` helper that fetches the rendered table HTML and injects it.

### Existing separate pages (kept, not removed)
- `plantillas/quote/add_item.inc.php`, `edit_item.inc.php`, `add_provider.inc.php`, `edit_provider.inc.php`, `add_subitem.inc.php`, `edit_subitem.inc.php` — remain intact as fallback routes.

---

## Data Model Changes

None. All existing backend save scripts are reused without modification:
- `scripts/quote/guardar_add_item.php`
- `scripts/quote/guarsar_edit_item.php`
- `scripts/quote/delete_item.php`
- `scripts/quote/guarsar_add_provider.php`
- `scripts/quote/guarsar_edit_provider.php`
- `scripts/quote/delete_provider.php`
- `scripts/quote/guarsar_add_subitem.php`
- `scripts/quote/guarsar_edit_subitem.php`
- `scripts/quote/delete_subitem.php`
- `scripts/quote/guarsar_add_provider_subitem.php`
- `scripts/quote/guarsar_edit_provider_subitem.php`
- `scripts/quote/delete_provider_subitem.php`

One new endpoint needed: `GET /rfq/quote/get_items_table/{id_rfq}` — returns the rendered items table HTML for partial DOM refresh.

---

## External Dependencies

None. All required libraries already present:
- Bootstrap 4 modals
- toastr (configured in `js/main.js`)
- jQuery AJAX

---

## Acceptance Criteria

- [ ] Clicking Add Item opens a modal; filling and saving creates the item, refreshes the table, shows a toastr success toast, and resets the form without closing the modal.
- [ ] Clicking Edit on any item row opens a modal pre-filled with that item's data; saving updates the item and refreshes the table.
- [ ] Clicking Add Provider / Edit Provider on an item opens the correct modal; save persists and refreshes.
- [ ] Same for Add Subitem, Edit Subitem, Add Provider (subitem), Edit Provider (subitem).
- [ ] Clicking any delete button opens `#alert_delete_system`; confirming fires AJAX, refreshes the table, and shows a toastr success toast — no page navigation.
- [ ] Closing a modal with a dirty form (unsaved changes) shows a "You have unsaved changes" warning.
- [ ] `calcQuoteTable()` recalculates correctly after every table refresh (totals, best unit cost, profit, taxes all update).
- [ ] No regression on existing quotes: all previously saved data renders correctly.
- [ ] The general Save button still works for quote-level fields (taxes, profit, payment terms, status transitions).
- [ ] Existing separate-page routes (add_item, edit_item, etc.) still function if accessed directly.

---

## Out of Scope

- Status transition flows (Completed, Submitted, Award, Fulfillment, Invoice) — these remain redirect-based.
- Re-quote and Fulfillment domain pages — only the quote editing page is in scope.
- Services table CRUD — already modal-based, no changes needed.
- Rooms CRUD — already modal-based, no changes needed.
- Quote-level field auto-save (taxes, profit, payment terms) — not in this feature.
- Mobile / responsive layout changes.
