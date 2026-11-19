const { test, expect } = require('@playwright/test');
const fs = require('fs');
const path = require('path');
const { query } = require('../helpers/db');

// Retire the Tracking Sheet (features/retire-tracking-sheet.md): Letter of Intent / Submission Method
// on New Quote + the Information drawer, the 18-column Pipeline Table view, and no sheet-sync UI left.
// PHP-level rules (whitelist, audit rows, table filters) live in tests/php/retire_tracking_sheet_test.php.

function fixtures() {
  return JSON.parse(fs.readFileSync(path.join(__dirname, '../.fixtures.json'), 'utf-8'));
}

const DASH = 'http://localhost/rfq/perfil/reports/pipeline_metrics';
const code = 'PW-RTS-' + Date.now();

test.describe.serial('Retire tracking sheet — bid fields end to end', () => {
  let id;

  test.afterAll(async () => {
    for (const r of await query('SELECT id FROM rfq WHERE email_code = ?', [code])) {
      await query('DELETE FROM audit_trails WHERE id_rfq = ?', [r.id]);
      await query('DELETE FROM rfq WHERE id = ?', [r.id]);
    }
  });

  test('New Quote with both fields set: the values show in the Information drawer', async ({ page }) => {
    await page.goto('http://localhost/rfq/perfil/quote/nuevo');
    await expect(page.locator('#letter_of_intent option')).toHaveText(['—', 'YES', 'NO', 'TBD']);
    await expect(page.locator('#submission_method option')).toHaveText(['—', 'PLATFORM', 'EMAIL', 'MAIL']);

    await page.fill('#name', 'Playwright retire-sheet quote');
    await page.fill('#email_code', code);
    await page.fill('#issue_date', '10/12/2026');
    await page.fill('#end_date', '10/19/2026 00:00');
    await page.fill('#internal_due_date', '10/26/2026');
    await page.selectOption('#letter_of_intent', 'TBD');
    await page.selectOption('#submission_method', 'EMAIL');
    await Promise.all([
      page.waitForNavigation(),
      page.click('[name="registrar_cotizacion"]'),
    ]);

    const rows = await query('SELECT id, letter_of_intent, submission_method FROM rfq WHERE email_code = ?', [code]);
    expect(rows).toHaveLength(1);
    expect(rows[0]).toMatchObject({ letter_of_intent: 'TBD', submission_method: 'EMAIL' });
    id = rows[0].id;

    await page.goto(`http://localhost/rfq/perfil/quote/editar_cotizacion/${id}`);
    await page.click('#qed-open-information');
    await expect(page.locator('#letter_of_intent')).toHaveValue('TBD');
    await expect(page.locator('#submission_method')).toHaveValue('EMAIL');
  });

  test('changing a value in the drawer writes an audit row', async ({ page }) => {
    await page.goto(`http://localhost/rfq/perfil/quote/editar_cotizacion/${id}`);
    await page.click('#qed-open-information');
    await page.selectOption('#submission_method', 'PLATFORM');
    await Promise.all([
      page.waitForResponse((res) => res.url().includes('/quote/save_information/') && res.status() === 200),
      page.click('#qed-information-save-btn'),
    ]);
    await expect(page.locator('#qed-information-save-pill')).toHaveClass(/is-shown/);

    expect((await query('SELECT submission_method FROM rfq WHERE id = ?', [id]))[0].submission_method).toBe('PLATFORM');
    const audit = await query("SELECT audit_trail FROM audit_trails WHERE id_rfq = ? AND audit_trail LIKE '%Submission Method%'", [id]);
    expect(audit).toHaveLength(1);
    expect(audit[0].audit_trail).toContain('EMAIL > PLATFORM');
    // Letter of Intent was untouched, so no row for it.
    expect(await query("SELECT id FROM audit_trails WHERE id_rfq = ? AND audit_trail LIKE '%Letter of Intent%'", [id])).toHaveLength(0);
  });

  test('the values show in the table view, and the Submission Method filter works', async ({ page }) => {
    await page.goto(DASH);
    await page.click('#pm-view .pm-seg-btn[data-view="table"]');
    await page.waitForSelector('#pm-table-view:not([hidden])');
    await page.waitForResponse((res) => res.url().includes('/quote/pipeline_table'));

    await Promise.all([
      page.waitForResponse((res) => res.url().includes('/quote/pipeline_table') && res.url().includes('quoteId=' + id)),
      page.fill('#pt-f-quoteId', String(id)),
    ]);
    const row = page.locator(`#pt-tbody tr[data-row="${id}"]`);
    await expect(row).toBeVisible();
    await expect(row.locator('.pt-desc')).toHaveText('Playwright retire-sheet quote');
    await expect(row.locator('.pt-tag', { hasText: 'TBD' })).toBeVisible();
    await expect(row.locator('.pt-tag', { hasText: 'PLATFORM' })).toBeVisible();

    const [req] = await Promise.all([
      page.waitForResponse((res) => res.url().includes('/quote/pipeline_table') && res.url().includes('submissionMethod=PLATFORM')),
      page.selectOption('#pt-f-submissionMethod', 'PLATFORM'),
    ]);
    expect(req.url()).toContain('quoteId=' + id);
    await expect(row).toBeVisible();
    await expect(page.locator('#pt-filters-count')).toHaveText('2 filters applied');

    await Promise.all([
      page.waitForResponse((res) => res.url().includes('/quote/pipeline_table') && res.url().includes('submissionMethod=MAIL')),
      page.selectOption('#pt-f-submissionMethod', 'MAIL'),
    ]);
    await expect(row).toHaveCount(0);
    await expect(page.locator('.pt-empty-title')).toHaveText('No data for this period');

    await page.click('#pt-clear');
    await expect(page.locator('#pt-f-submissionMethod')).toHaveValue('');
    await expect(page.locator('#pt-f-quoteId')).toHaveValue('');
  });
});

test.describe('Retire tracking sheet — table layout', () => {
  test('the table scrolls sideways with Quote ID pinned', async ({ page }) => {
    await page.setViewportSize({ width: 1280, height: 900 });
    await page.goto(DASH);
    await page.click('#pm-view .pm-seg-btn[data-view="table"]');
    await page.waitForSelector('#pm-table-view:not([hidden])');
    await page.waitForResponse((res) => res.url().includes('/quote/pipeline_table'));

    const wrap = page.locator('.pt-table-wrap');
    const { scrollWidth, clientWidth } = await wrap.evaluate((el) => ({ scrollWidth: el.scrollWidth, clientWidth: el.clientWidth }));
    expect(scrollWidth).toBeGreaterThan(clientWidth);

    const idHeader = page.locator('.pt-table thead th').first();
    const before = await idHeader.boundingBox();
    await wrap.evaluate((el) => { el.scrollLeft = 600; el.dispatchEvent(new Event('scroll')); });
    await expect(wrap).toHaveClass(/is-scrolled/);
    const after = await idHeader.boundingBox();
    expect(Math.round(after.x)).toBe(Math.round(before.x));
  });
});

test.describe('Retire tracking sheet — sheet sync UI is gone', () => {
  test('New Quote has no "Sync to sheet" checkbox', async ({ page }) => {
    await page.goto('http://localhost/rfq/perfil/quote/nuevo');
    await expect(page.locator('#name')).toBeVisible();
    await expect(page.locator('#sync_to_sheet')).toHaveCount(0);
    await expect(page.getByText('Sync to pipeline')).toHaveCount(0);
  });

  test('the quote page has no sync badge, Sync or Break Sync button', async ({ page }) => {
    const { rfqId } = fixtures();
    await page.goto(`http://localhost/rfq/perfil/quote/editar_cotizacion/${rfqId}`);
    await page.waitForSelector('#qed-open-information');
    await expect(page.locator('#ss-block')).toHaveCount(0);
    await expect(page.locator('#ss-sync-btn')).toHaveCount(0);
    await expect(page.locator('#ss-break-btn')).toHaveCount(0);
    await expect(page.locator('#ss-break-modal')).toHaveCount(0);
  });
});
