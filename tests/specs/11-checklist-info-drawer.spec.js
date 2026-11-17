const { test, expect } = require('@playwright/test');
const fs = require('fs');
const path = require('path');
const { query } = require('../helpers/db');

function fixtures() {
  return JSON.parse(fs.readFileSync(path.join(__dirname, '../.fixtures.json'), 'utf-8'));
}

test.describe('Quote Checklist & Info Drawer', () => {
  let rfqId;

  test.beforeEach(async ({ page }) => {
    ({ rfqId } = fixtures());
    await page.goto(`http://localhost/rfq/perfil/quote/editar_cotizacion/${rfqId}`);
    await page.waitForSelector('#qed-open-checklist');
  });

  test('status cards render with a live checklist count and a static information label', async ({ page }) => {
    await expect(page.locator('#qed-checklist-status-value')).toContainText('of 10 items complete');
    await expect(page.locator('#qed-open-information .qed-status-value')).toContainText('Dates, status & bid requirements');
  });

  test('clicking Checklist opens the drawer on the Checklist tab', async ({ page }) => {
    await page.click('#qed-open-checklist');
    await expect(page.locator('#qed-drawer')).toHaveClass(/is-open/);
    await expect(page.locator('#qed-tab-checklist')).toHaveClass(/is-active/);
    await expect(page.locator('[data-tab-panel="checklist"]')).not.toHaveClass(/is-hidden/);
  });

  test('clicking Information opens the drawer on the Information tab', async ({ page }) => {
    await page.click('#qed-open-information');
    await expect(page.locator('#qed-drawer')).toHaveClass(/is-open/);
    await expect(page.locator('#qed-tab-information')).toHaveClass(/is-active/);
    await expect(page.locator('[data-tab-panel="information"]')).not.toHaveClass(/is-hidden/);
  });

  test('switching tabs preserves unsaved edits in the tab being left', async ({ page }) => {
    await page.click('#qed-open-checklist');
    await page.fill('#poc', 'Playwright POC Edit');
    await page.click('#qed-tab-information');
    await page.click('#qed-tab-checklist');
    await expect(page.locator('#poc')).toHaveValue('Playwright POC Edit');
  });

  test('closing with no unsaved edits closes immediately, no confirmation', async ({ page }) => {
    await page.click('#qed-open-checklist');
    await page.click('#qed-drawer-close');
    await expect(page.locator('#qed-drawer')).not.toHaveClass(/is-open/);
    await expect(page.locator('#qed-confirm-overlay')).toBeHidden();
  });

  test('closing with unsaved edits prompts a discard confirmation; cancel keeps the drawer and the edit', async ({ page }) => {
    await page.click('#qed-open-checklist');
    await page.fill('#poc', 'Unsaved edit');
    await page.click('#qed-drawer-close');
    await expect(page.locator('#qed-confirm-overlay')).toBeVisible();
    await page.click('#qed-confirm-cancel');
    await expect(page.locator('#qed-confirm-overlay')).toBeHidden();
    await expect(page.locator('#qed-drawer')).toHaveClass(/is-open/);
    await expect(page.locator('#poc')).toHaveValue('Unsaved edit');
  });

  test('confirming discard clears the edit and closes the drawer', async ({ page }) => {
    await page.click('#qed-open-checklist');
    const original = await page.locator('#poc').inputValue();
    await page.fill('#poc', 'Unsaved edit to discard');
    await page.click('#qed-drawer-close');
    await page.click('#qed-confirm-discard');
    await expect(page.locator('#qed-drawer')).not.toHaveClass(/is-open/);
    // Re-open and confirm the field reverted rather than persisting the discarded edit.
    await page.click('#qed-open-checklist');
    await expect(page.locator('#poc')).toHaveValue(original);
  });

  test('saving the checklist tab succeeds via AJAX, shows an inline success pill, and keeps the drawer open', async ({ page }) => {
    await page.click('#qed-open-checklist');
    await page.fill('#poc', 'Playwright Saved POC');
    await Promise.all([
      page.waitForResponse((res) => res.url().includes('/quote/save_checklist/') && res.status() === 200),
      page.click('#qed-checklist-save-btn'),
    ]);
    await expect(page.locator('#qed-checklist-save-pill')).toHaveClass(/is-shown/);
    await expect(page.locator('#qed-drawer')).toHaveClass(/is-open/);
    // No full-page navigation happened.
    expect(page.url()).toContain(`editar_cotizacion/${rfqId}`);
  });

  test('saving the information tab succeeds via AJAX and shows an inline success pill', async ({ page }) => {
    await page.click('#qed-open-information');
    await page.fill('#reference_url', 'https://example.com/pw-test');
    await page.fill('#internal_due_date', '02/15/2026'); // required field — see internal-due-date feature
    await Promise.all([
      page.waitForResponse((res) => res.url().includes('/quote/save_information/') && res.status() === 200),
      page.click('#qed-information-save-btn'),
    ]);
    await expect(page.locator('#qed-information-save-pill')).toHaveClass(/is-shown/);
    await expect(page.locator('#qed-drawer')).toHaveClass(/is-open/);
  });

  test('saving the information tab with Internal Due Date blank fails and shows the error banner', async ({ page }) => {
    await page.click('#qed-open-information');
    await page.fill('#internal_due_date', '');
    await Promise.all([
      page.waitForResponse((res) => res.url().includes('/quote/save_information/') && res.status() === 200),
      page.click('#qed-information-save-btn'),
    ]);
    await expect(page.locator('#qed-information-error')).toBeVisible();
    await expect(page.locator('#qed-drawer')).toHaveClass(/is-open/);
  });

  test('saving the information tab with End Date blank fails and shows the error banner', async ({ page }) => {
    // feature: end-date-pipeline-table.md -- End Date required the same way as Internal Due Date
    await page.click('#qed-open-information');
    await page.fill('#end_date', '');
    await Promise.all([
      page.waitForResponse((res) => res.url().includes('/quote/save_information/') && res.status() === 200),
      page.click('#qed-information-save-btn'),
    ]);
    await expect(page.locator('#qed-information-error')).toBeVisible();
    await expect(page.locator('#qed-drawer')).toHaveClass(/is-open/);
  });

  test('saving the information tab with a blank Description fails, shows the error and keeps the old value', async ({ page }) => {
    // bug: description-required-for-partner-sync.md. Whitespace passes the browser's `required`,
    // so this exercises the server guard; nothing may be written.
    const before = (await query('SELECT name FROM rfq WHERE id = ?', [rfqId]))[0].name;
    await page.click('#qed-open-information');
    await page.fill('#name', '   ');
    const [res] = await Promise.all([
      page.waitForResponse((r) => r.url().includes('/quote/save_information/') && r.status() === 200),
      page.click('#qed-information-save-btn'),
    ]);
    expect(await res.json()).toMatchObject({ success: false, field: 'name' });
    await expect(page.locator('#qed-information-error')).toBeVisible();
    await expect(page.locator('#qed-information-error')).toContainText('Description must be filled out.');
    await expect(page.locator('#qed-drawer')).toHaveClass(/is-open/);
    await expect(page.locator('#name')).toHaveValue('   ');
    expect((await query('SELECT name FROM rfq WHERE id = ?', [rfqId]))[0].name).toBe(before);
  });

  test('saving the information tab with End Date filled in succeeds as normal', async ({ page }) => {
    await page.click('#qed-open-information');
    await page.fill('#end_date', '12/31/2026 17:00');
    await Promise.all([
      page.waitForResponse((res) => res.url().includes('/quote/save_information/') && res.status() === 200),
      page.click('#qed-information-save-btn'),
    ]);
    await expect(page.locator('#qed-information-save-pill')).toHaveClass(/is-shown/);
    await expect(page.locator('#qed-drawer')).toHaveClass(/is-open/);
  });

  test('old /quote/checklist/{id} URL redirects to the quote page with the drawer auto-open on Checklist', async ({ page }) => {
    await page.goto(`http://localhost/rfq/perfil/quote/checklist/${rfqId}`);
    await page.waitForSelector('#qed-drawer.is-open');
    expect(page.url()).toContain(`editar_cotizacion/${rfqId}`);
    await expect(page.locator('#qed-tab-checklist')).toHaveClass(/is-active/);
  });

  test('old /quote/information/{id} URL redirects to the quote page with the drawer auto-open on Information', async ({ page }) => {
    await page.goto(`http://localhost/rfq/perfil/quote/information/${rfqId}`);
    await page.waitForSelector('#qed-drawer.is-open');
    expect(page.url()).toContain(`editar_cotizacion/${rfqId}`);
    await expect(page.locator('#qed-tab-information')).toHaveClass(/is-active/);
  });
});

test.describe('Description required — New Quote', () => {
  const code = 'PW-NODESC-' + Date.now();
  test.afterEach(async () => {
    // Safety net: if the guard ever regressed, a quote would have been created — remove it.
    for (const r of await query('SELECT id FROM rfq WHERE email_code = ?', [code])) {
      await query('DELETE FROM audit_trails WHERE id_rfq = ?', [r.id]);
      await query('DELETE FROM rfq WHERE id = ?', [r.id]);
    }
  });

  test('the Description textarea is required in the browser', async ({ page }) => {
    await page.goto('http://localhost/rfq/perfil/quote/nuevo');
    await expect(page.locator('#name')).toHaveAttribute('required', '');
  });

  test('a whitespace-only Description re-renders the form with the error and creates nothing', async ({ page }) => {
    // bug: description-required-for-partner-sync.md. Whitespace passes `required`, so this hits the server.
    await page.goto('http://localhost/rfq/perfil/quote/nuevo');
    await page.fill('#name', '   ');
    await page.fill('#email_code', code);
    if (await page.locator('#sync_to_sheet').isChecked()) await page.locator('label.sync-check[for="sync_to_sheet"]').click();
    await page.fill('#issue_date', '10/12/2026');
    await page.fill('#end_date', '10/19/2026 00:00');
    await page.fill('#internal_due_date', '10/26/2026');
    await Promise.all([
      page.waitForNavigation(),
      page.click('[name="registrar_cotizacion"]'),
    ]);

    await expect(page.getByRole('alert').filter({ hasText: 'Must be fill out.' })).toBeVisible();
    await expect(page.locator('#email_code')).toHaveValue(code);
    expect((await query('SELECT COUNT(*) AS n FROM rfq WHERE email_code = ?', [code]))[0].n).toBe(0);
  });
});
