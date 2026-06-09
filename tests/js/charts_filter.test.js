/* Unit tests for the Charts dashboard per-user filter (bug: charts-hide-zero-users).
   Run with: node --test tests/js/
   Exercises activeUserSeries() exported from js/main_charts.js — the helper that
   zips a user's current/last-month series and drops users with no activity in
   either month. Filtering is independent per chart (Completed vs Awards). */

const test = require('node:test');
const assert = require('node:assert/strict');
const { activeUserSeries } = require('../../js/main_charts.js');

// Builds the two parallel arrays the backend ships (index-aligned by user).
function series(rows) {
  const past = rows.map(r => ({ user_name: r.name, total_quotes: r.past }));
  const current = rows.map(r => ({ user_name: r.name, total_quotes: r.current }));
  return [past, current];
}

test('drops a user whose current and last month are both zero', () => {
  const [past, current] = series([
    { name: 'LSarabia', current: 0, past: 0 },
    { name: 'RSantos', current: 3, past: 1 },
  ]);
  const result = activeUserSeries(past, current);
  assert.deepEqual(result.map(u => u.name), ['RSantos']);
});

test('keeps a user with activity in only the current month', () => {
  const [past, current] = series([{ name: 'Mboulahsen', current: 2, past: 0 }]);
  assert.deepEqual(activeUserSeries(past, current).map(u => u.name), ['Mboulahsen']);
});

test('keeps a user with activity in only the last month', () => {
  const [past, current] = series([{ name: 'AJkaoua', current: 0, past: 5 }]);
  assert.deepEqual(activeUserSeries(past, current).map(u => u.name), ['AJkaoua']);
});

test('treats string "0" from JSON as zero (coercion gotcha)', () => {
  const [past, current] = series([
    { name: 'RMoumou', current: '0', past: '0' },
    { name: 'WBuendia', current: '0', past: '4' },
  ]);
  const result = activeUserSeries(past, current);
  assert.deepEqual(result.map(u => u.name), ['WBuendia']);
});

test('preserves user order and carries the numeric values through', () => {
  const [past, current] = series([
    { name: 'gwilliams', current: 0, past: 0 },
    { name: 'SHafaou', current: 7, past: 2 },
    { name: 'CRamos', current: 0, past: 0 },
    { name: 'AForero', current: 1, past: 9 },
  ]);
  const result = activeUserSeries(past, current);
  assert.deepEqual(result.map(u => u.name), ['SHafaou', 'AForero']);
  assert.deepEqual(result.map(u => u.current), [7, 1]);
  assert.deepEqual(result.map(u => u.past), [2, 9]);
});

test('returns an empty array when no user has activity', () => {
  const [past, current] = series([
    { name: 'CMendez', current: 0, past: 0 },
    { name: 'LSarabia', current: 0, past: 0 },
  ]);
  assert.deepEqual(activeUserSeries(past, current), []);
});

test('is independent per chart — same users filter differently for completed vs awards', () => {
  // CRamos has completed activity but no awards; the two charts must differ.
  const [completedPast, completedCurrent] = series([
    { name: 'CRamos', current: 4, past: 0 },
    { name: 'AForero', current: 0, past: 0 },
  ]);
  const [awardPast, awardCurrent] = series([
    { name: 'CRamos', current: 0, past: 0 },
    { name: 'AForero', current: 2, past: 0 },
  ]);
  assert.deepEqual(activeUserSeries(completedPast, completedCurrent).map(u => u.name), ['CRamos']);
  assert.deepEqual(activeUserSeries(awardPast, awardCurrent).map(u => u.name), ['AForero']);
});
