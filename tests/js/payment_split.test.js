/* Unit tests for the 50/50 payment-split helper (feature: commercial-moving-payment-term).
   Run with: node --test tests/js/
   Exercises split5050() from js/payment_split.js — the pure helper that halves the
   grand total for the "50% Upfront / 50% on Completion" term. The two halves must
   always sum back to the exact total; odd cents go to the upfront half. */

const test = require('node:test');
const assert = require('node:assert/strict');
const { split5050, PAYMENT_TERM_SPLIT } = require('../../js/payment_split.js');

test('clean total splits into two equal halves', () => {
  const { upfront, completion } = split5050(25000);
  assert.equal(upfront, 12500);
  assert.equal(completion, 12500);
});

test('odd-cent total reconciles to the exact total (extra cent to upfront)', () => {
  const { upfront, completion } = split5050(8749.55);
  assert.equal(upfront, 4374.78);
  assert.equal(completion, 4374.77);
  assert.equal(Math.round((upfront + completion) * 100), Math.round(8749.55 * 100));
});

test('zero total splits into $0.00 / $0.00', () => {
  const { upfront, completion } = split5050(0);
  assert.equal(upfront, 0);
  assert.equal(completion, 0);
});

test('non-numeric / undefined total is treated as zero', () => {
  const { upfront, completion } = split5050(undefined);
  assert.equal(upfront, 0);
  assert.equal(completion, 0);
});

test('halves always sum to the exact total across a range of odd values', () => {
  for (const total of [0.01, 1.01, 99.99, 12345.67, 1000000.05, 33.33]) {
    const { upfront, completion } = split5050(total);
    assert.equal(
      Math.round((upfront + completion) * 100),
      Math.round(total * 100),
      `halves must reconcile for ${total}`
    );
  }
});

test('exposes the canonical split-term string', () => {
  assert.equal(PAYMENT_TERM_SPLIT, '50% Upfront / 50% on Completion');
});
