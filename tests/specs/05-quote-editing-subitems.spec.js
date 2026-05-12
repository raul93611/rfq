const { test, expect } = require('@playwright/test');
const fs = require('fs');
const path = require('path');

function fixtures() {
  return JSON.parse(fs.readFileSync(path.join(__dirname, '../.fixtures.json'), 'utf-8'));
}

test.describe('Quote Editing — Subitems', () => {
  let rfqId, itemId, subitemId;

  test.beforeEach(async ({ page }) => {
    ({ rfqId, itemId, subitemId } = fixtures());
    await page.goto(`http://localhost/rfq/perfil/quote/editar_cotizacion/${rfqId}`);
    await page.waitForSelector('#tabla_items');
  });

  test('add subitem button opens modal with correct item id', async ({ page }) => {
    await page.locator('.iem-add-subitem').first().click();
    await expect(page.locator('#add-subitem-modal')).toBeVisible();
    const val = await page.locator('#iem-add-subitem-id-item').inputValue();
    expect(parseInt(val)).toBeGreaterThan(0);
  });

  test('add subitem modal has required fields', async ({ page }) => {
    await page.locator('.iem-add-subitem').first().click();
    await expect(page.locator('#add-subitem-modal')).toBeVisible();
    await expect(page.locator('#add-subitem-modal [name="brand_project"]')).toBeVisible();
    await expect(page.locator('#add-subitem-modal [name="quantity"]')).toBeVisible();
    await expect(page.locator('#add-subitem-modal [name="description_project"]')).toBeVisible();
  });

  test('add subitem saves and refreshes table', async ({ page }) => {
    const { query } = require('../helpers/db');

    await page.locator('.iem-add-subitem').first().click();
    await page.waitForSelector('#add-subitem-modal.show');

    await page.fill('#add-subitem-modal [name="brand"]', 'PW-Sub-Brand');
    await page.fill('#add-subitem-modal [name="brand_project"]', 'PW-Sub-Brand');
    await page.fill('#add-subitem-modal [name="part_number"]', 'SPN-PW-001');
    await page.fill('#add-subitem-modal [name="part_number_project"]', 'SPN-PW-001');
    await page.fill('#add-subitem-modal [name="description"]', 'Playwright Subitem');
    await page.fill('#add-subitem-modal [name="description_project"]', 'Playwright Subitem');
    await page.fill('#add-subitem-modal [name="quantity"]', '1');

    const toastPromise = page.waitForSelector('.toast-success', { timeout: 10000 });
    await page.click('#add-subitem-modal .iem-save-btn');
    await toastPromise;

    // Verify in DB and clean up
    const rows = await query('SELECT id FROM subitems WHERE id_item = ? AND brand_project = ?', [itemId, 'PW-Sub-Brand']);
    expect(rows.length).toBeGreaterThan(0);
    if (rows.length) {
      await query('DELETE FROM subitems WHERE id = ?', [rows[0].id]);
    }
  });

  test('edit subitem button opens populated modal', async ({ page }) => {
    await page.locator('.iem-edit-subitem').first().click();
    await expect(page.locator('#edit-subitem-modal')).toBeVisible();
    await page.waitForSelector('#iem-edit-subitem-body [name="brand_project"]', { timeout: 5000 });
  });

  test('edit subitem saves changes', async ({ page }) => {
    await page.locator('.iem-edit-subitem').first().click();
    await page.waitForSelector('#iem-edit-subitem-body [name="quantity"]', { timeout: 5000 });

    await page.fill('#iem-edit-subitem-body [name="quantity"]', '2');

    const toastPromise = page.waitForSelector('.toast-success', { timeout: 10000 });
    await page.click('#edit-subitem-modal .iem-save-btn');
    await toastPromise;

    await expect(page.locator('#edit-subitem-modal')).not.toBeVisible();
  });

  test('delete subitem shows confirmation modal not browser confirm', async ({ page }) => {
    let dialogFired = false;
    page.on('dialog', () => { dialogFired = true; });

    await page.locator('.iem-delete-subitem').first().click();
    await expect(page.locator('#alert_delete_system')).toBeVisible();
    expect(dialogFired).toBe(false);

    // Cancel
    await page.click('#alert_delete_system [data-dismiss="modal"]');
  });

  test('add provider subitem button opens modal', async ({ page }) => {
    await page.locator('.iem-add-provider-subitem').first().click();
    await expect(page.locator('#add-provider-subitem-modal')).toBeVisible();
    const val = await page.locator('#iem-add-psi-id-subitem').inputValue();
    expect(parseInt(val)).toBeGreaterThan(0);
  });

  test('add provider subitem saves and refreshes table', async ({ page }) => {
    const { query } = require('../helpers/db');

    await page.locator('.iem-add-provider-subitem').first().click();
    await page.waitForSelector('#add-provider-subitem-modal.show');

    await page.fill('#add-provider-subitem-modal [name="provider"]', 'PW-SubProvider');
    await page.fill('#add-provider-subitem-modal [name="price"]', '30.00');

    const toastPromise = page.waitForSelector('.toast-success', { timeout: 10000 });
    await page.click('#add-provider-subitem-modal .iem-save-btn');
    await toastPromise;

    // Verify in DB and clean up
    const rows = await query('SELECT id FROM provider_subitems WHERE id_subitem = ? AND provider = ?', [subitemId, 'PW-SubProvider']);
    expect(rows.length).toBeGreaterThan(0);
    if (rows.length) {
      await query('DELETE FROM provider_subitems WHERE id = ?', [rows[0].id]);
    }
  });

  test('edit provider subitem opens modal', async ({ page }) => {
    await page.locator('.iem-edit-provider-subitem').first().click();
    await expect(page.locator('#edit-provider-subitem-modal')).toBeVisible();
    await page.waitForSelector('#iem-edit-provider-subitem-body [name="price"]', { timeout: 5000 });
    const priceVal = await page.locator('#iem-edit-provider-subitem-body [name="price"]').inputValue();
    expect(parseFloat(priceVal)).toBeGreaterThanOrEqual(0);
  });

  test('edit provider subitem saves changes', async ({ page }) => {
    await page.locator('.iem-edit-provider-subitem').first().click();
    await page.waitForSelector('#iem-edit-provider-subitem-body [name="price"]', { timeout: 5000 });

    await page.fill('#iem-edit-provider-subitem-body [name="price"]', '48.50');

    const toastPromise = page.waitForSelector('.toast-success', { timeout: 10000 });
    await page.click('#edit-provider-subitem-modal .iem-save-btn');
    await toastPromise;
    await expect(page.locator('#edit-provider-subitem-modal')).not.toBeVisible();
  });
});
