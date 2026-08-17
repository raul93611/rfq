const { test, expect } = require('@playwright/test');
const fs = require('fs');
const path = require('path');

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
