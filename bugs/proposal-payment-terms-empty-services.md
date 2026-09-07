# Proposal PDF uses services payment terms even when there are no service items

Status: planned

When a quote is flagged as a services-type bid but has zero service line items, the generated Proposal PDF still displays the services-specific payment terms instead of falling back to the item-level payment terms.

## Steps to Reproduce

1. Create/open a quote with a services bid type.
2. Don't add any service items to the quote.
3. Generate the Proposal PDF.

## Expected Behavior

The Payment Terms field falls back to the item-level `payment_terms`, since there's no service pricing to justify using the services-specific term.

## Actual Behavior

The Payment Terms field always shows `services_payment_term` (falling back to `payment_terms` only if that column is literally `NULL` in the DB) whenever the quote is services-type, regardless of whether any service items actually exist.

## Severity

Minor — cosmetic/wrong text on a client-facing document, not blocking.

## Has this always been broken?

Yes — present since the payment-terms-per-quote-type logic was originally built. This scenario (services-type quote with zero service items) was never anticipated.

## Investigation

Root cause confirmed in [scripts/utilities/proposal.php:11-19](scripts/utilities/proposal.php#L11-L19):

```php
if ($cotizacion->isServices()) {
    $services = ServiceRepository::get_services($conexion, $id_rfq);
    $total_service = ServiceRepository::get_total($conexion, $id_rfq);
    $payment_terms = $cotizacion->obtener_services_payment_term() ?? $cotizacion->obtener_payment_terms();
} else {
    $total_service = 0;
    $payment_terms = $cotizacion->obtener_payment_terms();
}
```

The payment-terms choice is gated solely on the `isServices()` bid-type flag, not on whether the quote actually has service line items. `$services` is already fetched on line 13 (available, unused for this decision) — confirmed via `ServiceRepository::get_services()` ([app/Service/ServiceRepository.inc.php:23-42](app/Service/ServiceRepository.inc.php#L23-L42)), which returns an empty array `[]` (never `null`/`false`) when a quote has no service items, so an `empty($services)` check is safe.

The value is rendered as a raw, unmodified string in [herramientas/pdfTemplates/proposal.inc.php:242-243](herramientas/pdfTemplates/proposal.inc.php#L242-L243) — no reformatting happens downstream, so the fix belongs entirely in the value-selection logic in `proposal.php`.

## Fix Plan

1. Add a small, testable helper — e.g. `ProposalRepository::resolve_payment_terms(Rfq $cotizacion, array $services): string` in [app/Utilities/ProposalRepository.inc.php](app/Utilities/ProposalRepository.inc.php) (alongside the existing `is_split_term`/`split_5050` methods) — encapsulating:
   - Services quote **and** `$services` non-empty → `services_payment_term ?? payment_terms`.
   - Otherwise (non-services quote, or services quote with zero items) → `payment_terms`.
2. Update `scripts/utilities/proposal.php:15` to call `ProposalRepository::resolve_payment_terms($cotizacion, $services)` instead of the inline ternary, removing the now-redundant `isServices()` branch just for `$payment_terms` (the `$services`/`$total_service` fetch still only needs to happen when `isServices()` is true).
3. Add a PHP test in `tests/php/` covering three cases: services quote with items (behavior unchanged — uses `services_payment_term`), services quote with zero items (now falls back to `payment_terms`), non-services quote (behavior unchanged — uses `payment_terms`).

## Open Questions

None blocking — the fix is self-contained, only affects what's computed/displayed at PDF-generation time, and doesn't touch stored data. Confirmed with the user: automatic fallback logic (not a popup prompt before generating the PDF).
