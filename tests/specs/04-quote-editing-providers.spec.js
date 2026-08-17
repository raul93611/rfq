const { test, expect } = require('@playwright/test');
const fs = require('fs');
const path = require('path');
const { openKebabFor } = require('../helpers/kebab');

function fixtures() {
  return JSON.parse(fs.readFileSync(path.join(__dirname, '../.fixtures.json'), 'utf-8'));
}

test.describe('Quote Editing — Providers', () => {
  let rfqId, itemId;

  test.beforeEach(async ({ page }) => {
    ({ rfqId, itemId } = fixtures());
    await page.goto(`http://localhost/rfq/perfil/quote/editar_cotizacion/${rfqId}`);
    await page.waitForSelector('#tabla_items');
  });

  test('add provider button opens modal', async ({ page }) => {
    const addBtn = await openKebabFor(page, '.iem-add-provider');
    await addBtn.click();
    await expect(page.locator('#add-provider-modal')).toBeVisible();
    // Modal should have item id set
    const val = await page.locator('#iem-add-provider-id-item').inputValue();
    expect(parseInt(val)).toBeGreaterThan(0);
  });

  test('add provider modal has provider and price fields', async ({ page }) => {
    const addBtn = await openKebabFor(page, '.iem-add-provider');
    await addBtn.click();
    await expect(page.locator('#add-provider-modal')).toBeVisible();
    await expect(page.locator('#add-provider-modal [name="provider"]')).toBeVisible();
    await expect(page.locator('#add-provider-modal [name="price"]')).toBeVisible();
  });

  test('add provider saves and refreshes table', async ({ page }) => {
    const { query } = require('../helpers/db');

    const addBtn = await openKebabFor(page, '.iem-add-provider');
    await addBtn.click();
    await page.waitForSelector('#add-provider-modal.show');

    await page.fill('#add-provider-modal [name="provider"]', 'PW-Provider-New');
    await page.fill('#add-provider-modal [name="price"]', '55.00');

    const toastPromise = page.waitForSelector('.toast-success', { timeout: 10000 });
    await page.click('#add-provider-modal .iem-save-btn');
    await toastPromise;

    // Verify in DB and clean up
    const rows = await query('SELECT id FROM provider WHERE id_item = ? AND provider = ?', [itemId, 'PW-Provider-New']);
    expect(rows.length).toBeGreaterThan(0);
    if (rows.length) {
      await query('DELETE FROM provider WHERE id = ?', [rows[0].id]);
    }
  });

  test('provider name with & round-trips through the real add-provider form without double-escaping', async ({ page }) => {
    // bugs/provider-name-double-html-escaping.md — guardar_add_provider.php used to
    // HTML-encode `provider` before storage, then RepositorioItem::renderProvidersList()
    // encoded it again on render, so "D&H" showed as literal "D&amp;H" on screen. Drives
    // the real HTTP form (unlike the PHP-layer test, which can't exercise
    // filter_input(INPUT_POST, ...) from a CLI request) to prove the full round trip.
    const { query } = require('../helpers/db');

    const addBtn = await openKebabFor(page, '.iem-add-provider');
    await addBtn.click();
    await page.waitForSelector('#add-provider-modal.show');

    await page.fill('#add-provider-modal [name="provider"]', 'D&H');
    await page.fill('#add-provider-modal [name="price"]', '12.34');

    const toastPromise = page.waitForSelector('.toast-success', { timeout: 10000 });
    await page.click('#add-provider-modal .iem-save-btn');
    await toastPromise;

    await expect(page.locator('.it-prov-name', { hasText: 'D&H' })).toBeVisible();
    await expect(page.locator('.it-prov-name', { hasText: 'D&amp;H' })).toHaveCount(0);

    const rows = await query('SELECT id, provider FROM provider WHERE id_item = ? AND provider LIKE ?', [itemId, 'D%H']);
    expect(rows.length).toBeGreaterThan(0);
    if (rows.length) {
      expect(rows[0].provider).toBe('D&H'); // stored raw, not "D&amp;H"
      await query('DELETE FROM provider WHERE id = ?', [rows[0].id]);
    }
  });

  test('edit provider button opens populated edit modal', async ({ page }) => {
    // The provider link is inside the item row
    await page.locator('.iem-edit-provider').first().click();
    await expect(page.locator('#edit-provider-modal')).toBeVisible();
    await page.waitForSelector('#edit-provider-modal [name="provider"]', { timeout: 5000 });
    const val = await page.locator('#edit-provider-modal [name="provider"]').inputValue();
    expect(val.length).toBeGreaterThan(0);
  });

  test('edit provider saves changes', async ({ page }) => {
    await page.locator('.iem-edit-provider').first().click();
    await page.waitForSelector('#edit-provider-modal [name="price"]', { timeout: 5000 });

    await page.fill('#edit-provider-modal [name="price"]', '99.99');

    const toastPromise = page.waitForSelector('.toast-success', { timeout: 10000 });
    await page.click('#edit-provider-modal .iem-save-btn');
    await toastPromise;

    await expect(page.locator('#edit-provider-modal')).not.toBeVisible();
  });

  test('delete provider shows confirmation modal', async ({ page }) => {
    let dialogFired = false;
    page.on('dialog', () => { dialogFired = true; });

    // Delete button is inside the edit provider modal
    await page.locator('.iem-edit-provider').first().click();
    await page.waitForSelector('#edit-provider-modal', { timeout: 5000 });
    await page.click('#edit-provider-modal .iem-delete-provider-btn');

    await expect(page.locator('#alert_delete_system')).toBeVisible();
    expect(dialogFired).toBe(false);

    // Cancel deletion
    await page.click('#alert_delete_system [data-dismiss="modal"]');
  });
});
