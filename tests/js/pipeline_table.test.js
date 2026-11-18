/* Unit tests for the Pipeline Table view helpers (features/retire-tracking-sheet.md).
   Run with: node --test tests/js/
   Exercises the pure PipelineTableView helpers exported from js/pipeline_table.js:
   the 18-column tracking row, the empty state, filter counting and the query string. */

const test = require('node:test');
const assert = require('node:assert/strict');
const V = require('../../js/pipeline_table.js');

const FULL = {
  id: 1234, created: '03/15/2026', description: 'Janitorial <services> & "more"', channel: 'SAM',
  emailCode: 'W912-26', status: 'submitted', internalDueDate: '03/20/2026', endDate: '03/25/2026 14:00',
  submitted: '03/22/2026', bidType: 'IT', typeOfContract: 'Uncategorized', qa: 'YES', qaDeadline: '03/18/2026 10:30',
  resumes: 'NO', letterOfIntent: 'TBD', siteVisit: 'YES', submissionMethod: 'PLATFORM', user: 'RSantos',
};
const UNSET = Object.assign({}, FULL, { description: null, qa: null, qaDeadline: null, resumes: null, letterOfIntent: null, siteVisit: null, submissionMethod: null });

/** The text content of each <td>, in order (tags stripped, entities decoded). */
function cells(html) {
  return [...html.matchAll(/<td[^>]*>([\s\S]*?)<\/td>/g)].map(m =>
    m[1].replace(/<[^>]+>/g, '').replace(/&lt;/g, '<').replace(/&gt;/g, '>').replace(/&quot;/g, '"').replace(/&amp;/g, '&'));
}

test('table view shows all 18 columns in the specified order', () => {
  assert.equal(V.COLUMN_COUNT, 18);
  assert.deepEqual(cells(V.rowHtml(FULL, '<span>Submitted</span>')), [
    '1234', '03/15/2026', 'Janitorial <services> & "more"', 'SAM', 'W912-26', 'Submitted',
    '03/20/2026', '03/25/2026 14:00', '03/22/2026', 'IT', 'Uncategorized',
    'YES', '03/18/2026 10:30', 'NO', 'TBD', 'YES', 'PLATFORM', 'RSantos',
  ]);
});

test('Quote ID cell is pinned (sticky class)', () => {
  assert.match(V.rowHtml(FULL, ''), /^<tr[^>]*><td class="pt-td-id pt-sticky">/);
});

test('Description is escaped, clamped and carries the full text in title', () => {
  const html = V.rowHtml(FULL, '');
  assert.ok(html.includes('<div class="pt-desc" title="Janitorial &lt;services&gt; &amp; &quot;more&quot;">'));
  assert.ok(!html.includes('<services>'), 'raw markup never reaches the DOM');
});

test('unset values render as —', () => {
  const c = cells(V.rowHtml(UNSET, ''));
  assert.equal(c[2], '—');                                  // Description
  assert.deepEqual(c.slice(11, 17), ['—', '—', '—', '—', '—', '—']); // Q&A … Submission Method
  assert.equal((V.rowHtml(UNSET, '').match(/pt-null/g) || []).length, 7);
});

test('yes/no and option values render as neutral tags', () => {
  const html = V.rowHtml(FULL, '');
  for (const v of ['YES', 'NO', 'TBD', 'PLATFORM']) assert.ok(html.includes('<span class="pt-tag">' + v + '</span>'), v);
});

test('empty state spans every column', () => {
  assert.ok(V.emptyRowHtml(false, 0).includes('colspan="18"'));
  assert.ok(V.emptyRowHtml(true, 640).includes('style="width:640px"'));
  assert.match(V.emptyRowHtml(true, 0), /Try removing a filter/);
  assert.match(V.emptyRowHtml(false, 0), /Try a different year/);
});

test('Description, Letter of Intent and Submission Method count as filters and clear to empty', () => {
  const f = V.EMPTY_FILTERS();
  assert.equal(V.countFilters(f), 0);
  f.description = '  janitorial ';
  f.letterOfIntent = 'YES';
  f.submissionMethod = 'MAIL';
  f.channel = 'FBO';
  assert.equal(V.countFilters(f), 4);
  assert.equal(V.countFilters(V.EMPTY_FILTERS()), 0, 'Clear resets them');
  f.description = '   ';
  assert.equal(V.countFilters(f), 3, 'blank description is not a filter');
});

test('new filters are sent in the query and combine with the existing ones', () => {
  const f = Object.assign(V.EMPTY_FILTERS(), { description: ' Acme & Sons ', letterOfIntent: 'TBD', submissionMethod: 'EMAIL', channel: 'FBO' });
  const q = V.buildQuery({ mode: 'year', year: 2026 }, f, 2);
  assert.ok(q.startsWith('mode=year&year=2026&page=2'));
  assert.ok(q.includes('description=Acme%20%26%20Sons'));
  assert.ok(q.includes('letterOfIntent=TBD'));
  assert.ok(q.includes('submissionMethod=EMAIL'));
  assert.ok(q.includes('channel=FBO'));
  assert.ok(!V.buildQuery({ mode: 'year', year: 2026 }, V.EMPTY_FILTERS(), 0).includes('description='));
});
