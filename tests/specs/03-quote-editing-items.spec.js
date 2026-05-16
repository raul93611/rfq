const { test, expect } = require('@playwright/test');
const fs = require('fs');
const path = require('path');

function fixtures() {
  return JSON.parse(fs.readFileSync(path.join(__dirname, '../.fixtures.json'), 'utf-8'));
}

test.describe('Quote Editing — Items', () => {
  let rfqId;

  test.beforeEach(async ({ page }) => {
    ({ rfqId } = fixtures());
    await page.goto(`http://localhost/rfq/perfil/quote/editar_cotizacion/${rfqId}`);
    await page.waitForSelector('#tabla_items');
  });

  test('items table renders with test item', async ({ page }) => {
    await expect(page.locator('#tabla_items tbody tr').first()).toBeVisible();
    await expect(page.locator('.iem-edit-item').first()).toBeVisible();
  });

  test('add item button opens modal', async ({ page }) => {
    await page.click('[data-target="#add-item-modal"]');
    await expect(page.locator('#add-item-modal')).toBeVisible();
  });

  test('add item modal has required fields', async ({ page }) => {
    await page.click('[data-target="#add-item-modal"]');
    await expect(page.locator('#add-item-modal')).toBeVisible();
    await expect(page.locator('#add-item-modal [name="brand_project"]')).toBeVisible();
    await expect(page.locator('#add-item-modal [name="part_number_project"]')).toBeVisible();
    await expect(page.locator('#add-item-modal [name="quantity"]')).toBeVisible();
    await expect(page.locator('#add-item-modal [name="description_project"]')).toBeVisible();
  });

  test('add item saves and refreshes table', async ({ page }) => {
    const { query } = require('../helpers/db');
    const { rfqId: id } = fixtures();

    await page.click('[data-target="#add-item-modal"]');
    await page.waitForSelector('#add-item-modal.show');

    await page.fill('#add-item-modal [name="brand"]', 'PW-Brand-New');
    await page.fill('#add-item-modal [name="brand_project"]', 'PW-Brand-New');
    await page.fill('#add-item-modal [name="part_number"]', 'PW-PN-001');
    await page.fill('#add-item-modal [name="part_number_project"]', 'PW-PN-001');
    await page.fill('#add-item-modal [name="description"]', 'Playwright Added Item');
    await page.fill('#add-item-modal [name="description_project"]', 'Playwright Added Item');
    await page.fill('#add-item-modal [name="quantity"]', '1');

    const toastPromise = page.waitForSelector('.toast-success', { timeout: 10000 });
    await page.click('#add-item-modal .iem-save-btn');
    await toastPromise;

    // Verify in DB and clean up
    const rows = await query('SELECT id FROM item WHERE id_rfq = ? AND brand_project = ?', [id, 'PW-Brand-New']);
    expect(rows.length).toBeGreaterThan(0);
    if (rows.length) {
      await query('DELETE FROM item WHERE id = ?', [rows[0].id]);
    }
  });

  test('edit item button opens populated edit modal', async ({ page }) => {
    const $editBtn = page.locator('.iem-edit-item').first();
    await $editBtn.click();
    await expect(page.locator('#edit-item-modal')).toBeVisible();
    // Form should load content
    await page.waitForSelector('#iem-edit-item-body [name="brand"], #iem-edit-item-body [name="brand_project"]', { timeout: 5000 });
  });

  test('edit item saves changes and updates table', async ({ page }) => {
    await page.locator('.iem-edit-item').first().click();
    await page.waitForSelector('#iem-edit-item-body [name="brand_project"]', { timeout: 5000 });

    await page.fill('#iem-edit-item-body [name="brand_project"]', 'UpdatedBrand');

    const toastPromise = page.waitForSelector('.toast-success', { timeout: 10000 });
    await page.click('#edit-item-modal .iem-save-btn');
    await toastPromise;

    await expect(page.locator('#edit-item-modal')).not.toBeVisible();
  });

  test('delete item shows confirmation modal not browser confirm', async ({ page }) => {
    // Check we don't use browser confirm — we use #alert_delete_system modal
    let dialogFired = false;
    page.on('dialog', () => { dialogFired = true; });

    await page.locator('.iem-delete-item').first().click();
    // Should open #alert_delete_system
    await expect(page.locator('#alert_delete_system')).toBeVisible();
    expect(dialogFired).toBe(false);

    // Dismiss without confirming
    await page.click('#alert_delete_system [data-dismiss="modal"]');
    await expect(page.locator('#alert_delete_system')).not.toBeVisible();
  });
});
