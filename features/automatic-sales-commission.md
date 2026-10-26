# Automatic Sales Commission

Replace the manual "No / Same / Other commission" choice at invoice time with a rule-based calculation that is frozen on the quote.

**Status:** `planned`

## Current state (context for the build)

- Commission is chosen by hand in the Invoice modal (`plantillas/quote/modals/sales_commission_modal.inc.php`, shown when the Invoice checkbox is ticked; it only renders when a re-quote exists). Stored as a string in `rfq.sales_commission` (`No commission` / `Same commission` / `Other commission`, lookup table `sales_commissions`) plus `rfq.sales_commission_comment`. Saved by `RepositorioRfq::set_sales_commission()` from `scripts/quote/guardar_editar_cotizacion.php`.
- `Same commission` = 3% x (`rfq.total_price` - re-quote `total_cost`); `Other commission` = 3% x (`rfq.total_price` - `rfq.total_fulfillment`). Equipment only. The `0.03` and the string `CASE` are duplicated in: `scripts/fulfillment/load_fulfillment_totals.php`, `plantillas/fulfillment/templates/sales_commission.inc.php`, `Report::getSalesCommissionReport` (+ drill-downs), `ExcelRepository::getSalesCommissionReport`, `YearlyProjectionRepository` (month totals + month rows).
- Invoice step is already gated by `Rfq::isEnabledToInvoice()`, which requires a re-quote, so every invoiced quote has one.
- `RepositorioRfq::remove_invoice()` only sets `invoice = 0`; re-invoicing runs the save path again.
- Type of Contract values (`type_of_contracts`): RFQ, RFP Maintenance, RFP Installation, Professional Services, Moving and Logistics. Set in the Fulfillment modal, so it exists by invoice time.

## Rules

Evaluated in this order when a quote is invoiced:

1. Type of Contract is **Moving and Logistics** or **Professional Services** -> commission **$0**, reason "not eligible".
2. Quote price is $0 -> no commission.
3. Fulfillment profit % = (`rfq.total_price` - `rfq.total_fulfillment`) / `rfq.total_price` x 100, **rounded to 2 decimals**. If **< 6.00** -> no commission, reason "under threshold".
4. Otherwise basis profit = **the smaller** of re-quote profit (`total_price` - re-quote `total_cost`) and fulfillment profit (`total_price` - `total_fulfillment`). If there is no re-quote, the basis is the fulfillment profit.
5. Commission = `ROUND(max(basis, 0) x 0.03, 2)`. A zero or negative basis gives $0.

All profits are **equipment only** (services excluded), matching today's commission math.

## User flow

1. User opens a quote that is in Fulfillment with all invoice requirements met and ticks **Invoice**.
2. The Invoice modal opens with the invoice date, a **read-only commission breakdown** and the comment box.
3. User confirms the invoice date, optionally adds a comment, and clicks Save.
4. The server **recalculates** the commission (never trusts the modal's numbers), saves the result with the invoice, and writes the Invoice audit event with the amount and basis.
5. The quote's fulfillment page shows the commission card with the saved amount and the reason.
6. Reports, Excel export, monthly projection and "Profit - Real SC" read the saved amount.
7. If the invoice is removed and the quote is invoiced again, step 4 recalculates from current numbers.

Roles: unchanged. Whoever can invoice a quote today can do it; nobody can override the amount.

## UI changes

- **Invoice modal** (Quote Edit): remove the Sales Commission dropdown. Add a read-only breakdown: Type of Contract, fulfillment profit and %, re-quote profit and %, which profit is used, commission amount, and an eligibility/reason line. Invoice date and comment stay. Modal must render whether or not a re-quote exists.
- **Fulfillment page commission card** (`sales_commission.inc.php`): for auto-calculated quotes show the amount plus the reason ("Not eligible - Moving and Logistics", "Under 6% (4.20%)", "Re-quote profit was smaller", "Fulfillment profit was smaller"). Legacy quotes keep today's rendering.
- **Fulfillment totals** (`load_fulfillment_totals.php`, "Profit - Real SC"): use the saved amount for auto quotes.
- **Sales Commission report + Excel, monthly projection**: same columns and layout; the amount comes from the saved value, with the old formula only for legacy quotes.
- **Audit trail**: the Invoice event message includes the commission amount and basis (escape dynamic values - messages render as raw HTML).

## Data model changes

New nullable columns on `rfq` via a new migration `sql/sales_commission_auto_migration.sql` (run once per environment; add to the list in DEPLOYMENT.md):

- `sales_commission_amount DECIMAL(12,2) NULL` - the frozen amount. **`NULL` = legacy quote** (old manual value + old `CASE`); non-NULL (including 0) = auto-calculated.
- `sales_commission_basis_profit DECIMAL(12,2) NULL` - the profit the 3% was applied to (the smaller of the two profits, before the $0 floor).
- `sales_commission_fulfillment_pct DECIMAL(6,2) NULL` - the 2-decimal fulfillment profit % used for the 6% test.
- `sales_commission_reason VARCHAR(30) NULL` - `not_eligible_type` | `no_price` | `under_threshold` | `requote_smaller` | `fulfillment_smaller` | `no_profit`.

`rfq.sales_commission` keeps being written with the existing strings so current report filters/sorts keep working: `Same commission` when the re-quote profit is the basis, `Other commission` when the fulfillment profit is the basis, `No commission` otherwise. No changes to `sales_commissions` or `invoices`. No config constants (config.inc.php is gitignored): the rate and threshold are two named constants in a new class `SalesCommissionCalculator` in `app/SalesCommission/` (add it to the `$subfolder` whitelist in `index.php`). One PHP implementation plus one shared SQL expression for the reports: `COALESCE(r.sales_commission_amount, <legacy CASE>)`.

## External dependencies

None.

## Acceptance criteria

- Type of Contract Moving and Logistics or Professional Services -> $0, reason "not eligible", regardless of profit.
- Fulfillment profit % 5.99 -> $0 "under threshold"; exactly 6.00 -> commission; 5.996 (displays 6.00) -> commission.
- Re-quote profit smaller than fulfillment profit -> 3% of re-quote profit; fulfillment smaller -> 3% of fulfillment profit; amount rounded to cents.
- Zero or negative basis -> $0, never negative. Quote price $0 -> $0.
- No re-quote (calculator level) -> fulfillment profit is the basis.
- Blank Type of Contract is treated as eligible.
- The amount is calculated on the server at save, frozen, and unchanged by later cost edits; remove invoice + invoice again recalculates.
- Invoice modal shows the read-only breakdown with no dropdown and no override; date and comment still save.
- Quotes invoiced before go-live (`sales_commission_amount IS NULL`) render and total exactly as before in the fulfillment page, the report, the Excel export and the monthly projection.
- Report, Excel export, monthly projection and "Profit - Real SC" agree on the same amount for a given quote.
- Audit trail Invoice event shows the amount and basis, with dynamic values escaped.
- Tests: a PHP test for `SalesCommissionCalculator` (every rule + boundaries, DB-backed with no DDL; the migration is applied beforehand) and a Playwright spec for the Invoice modal and fulfillment card.

## Out of scope

- Recalculating or changing past invoiced quotes.
- A settings screen for the 3% rate or the 6% threshold.
- Admin or manual overrides of the commission.
- The "attach commission to invoice" paperclip flow and partial-invoice behavior on the fulfillment page (including the stale `$sales_commission[1]` references there).
- Changing the Sales Commission report's columns or layout.
- Services profit, per-user commission splits or payouts, and the partner sync payload.

## Decisions

- Rate: stays 3% - user confirmed; the new rules only decide whether it applies and on which profit.
- Profit basis: equipment only - chosen over whole quote (equipment + services) to match today's commission math and the re-quote side, which has no real services cost.
- No re-quote: use the fulfillment profit alone (option B) - user's call, since fulfillment profit always exists. The Invoice step already requires a re-quote, so this is a safety fallback.
- Past quotes: keep their current commission (option A) - closed months in reports and projections must not move.
- Invoice modal: read-only breakdown plus comment, no override (option A) - the goal is to automate; exceptions were not requested.
- Excluded types (Moving and Logistics, Professional Services): get no commission at all (option B) - user's call over keeping the manual flow for them.
- Timing: freeze at invoice time (option A) - a commission that may already be paid should not move on later cost edits, and reports get simpler.
- Edge rules (user: "all good"): exactly 6.00% qualifies; the 6% test uses the displayed 2-decimal percentage; zero or negative basis gives $0; price $0 gives no commission; blank Type of Contract is eligible; cents rounding; the fulfillment card explains the outcome; the audit event records amount and basis.
- Storage (my call): reuse the existing `rfq.sales_commission` strings plus new nullable columns, with `sales_commission_amount IS NULL` as the legacy marker - needs no cutoff date and keeps report filters working.
- Go-live scope (my call): quotes already in Fulfillment at go-live that are invoiced afterward use the new rules - "from go-live onward" read as invoiced after go-live.
