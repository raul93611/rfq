/* Payment Terms — "50% Upfront / 50% on Completion"
   Shared helper for the quote + re-quote pages. Pure logic (no DOM) so it can be
   unit-tested under Node (tests/js/payment_split.test.js) and reused in the
   browser via the window globals. */

// Canonical string stored in rfq.payment_terms / rfq.services_payment_term.
var PAYMENT_TERM_SPLIT = '50% Upfront / 50% on Completion';

/* Half the total, always summing back to the exact total. Odd cents go to the
   upfront half so the pair reconciles cleanly (8749.55 -> 4374.78 + 4374.77). */
function split5050(total) {
  var cents = Math.round((Number(total) || 0) * 100);
  var up = Math.ceil(cents / 2);
  return { upfront: up / 100, completion: (cents - up) / 100 };
}

if (typeof window !== 'undefined') {
  window.PAYMENT_TERM_SPLIT = PAYMENT_TERM_SPLIT;
  window.split5050 = split5050;
}

if (typeof module !== 'undefined' && module.exports) {
  module.exports = { PAYMENT_TERM_SPLIT, split5050 };
}
