const { test, expect } = require('@playwright/test');
const fs = require('fs');
const path = require('path');
const { openKebabFor } = require('../helpers/kebab');

function fixtures() {
  return JSON.parse(fs.readFileSync(path.join(__dirname, '../.fixtures.json'), 'utf-8'));
}

test.describe('Services', () => {
  let rfqId, serviceId;

  test.beforeEach(async ({ page }) => {
    ({ rfqId, serviceId } = fixtures());
    await page.goto(`http://localhost/rfq/perfil/quote/editar_cotizacion/${rfqId}`);
    await page.waitForSelector('#services_section');
  });

  test('services section renders with test service', async ({ page }) => {
    await expect(page.locator('#services_section')).toBeVisible();
    await expect(page.locator('#services_section .service_item').first()).toBeVisible();
  });

  test('services table scrolls horizontally on a narrow viewport instead of squeezing the description column', async ({ page }) => {
    // bugs/items-table-narrow-description-columns.md — same root cause as the Items table:
    // .it-table.is-services had no min-width, so the description column got squeezed instead
    // of the existing overflow-x:auto wrapper engaging.
    await page.setViewportSize({ width: 700, height: 900 });
    const table = page.locator('#services_table table.it-table.is-services');
    await expect(table).toBeVisible();

    const minWidth = await table.evaluate((el) => parseFloat(getComputedStyle(el).minWidth) || 0);
    expect(minWidth).toBeGreaterThanOrEqual(750);

    const overflow = await page.locator('#services_table').evaluate((el) => el.scrollWidth - el.clientWidth);
    expect(overflow).toBeGreaterThan(0);
  });

  test('add service button opens modal', async ({ page }) => {
    await page.click('#add_service');
    await expect(page.locator('#add_service_modal')).toBeVisible();
  });

  test('add service modal has required fields', async ({ page }) => {
    await page.click('#add_service');
    await expect(page.locator('#add_service_modal')).toBeVisible();
    await expect(page.locator('#add_service_modal [name="description"]')).toBeVisible();
    await expect(page.locator('#add_service_modal [name="quantity"]')).toBeVisible();
    await expect(page.locator('#add_service_modal [name="unit_price"]')).toBeVisible();
  });

  test('add service saves and refreshes section', async ({ page }) => {
    const { query } = require('../helpers/db');
    const { rfqId: id } = fixtures();

    await page.click('#add_service');
    await page.waitForSelector('#add_service_modal.show');

    await page.fill('#add_service_modal [name="description"]', 'PW Test Service Added');
    await page.fill('#add_service_modal [name="quantity"]', '2');
    await page.fill('#add_service_modal [name="unit_price"]', '15.00');

    const toastPromise = page.waitForSelector('.toast-success', { timeout: 10000 });
    await page.click('#add_service_modal .svc-add-save-btn');
    await toastPromise;

    // Verify in DB and clean up
    const rows = await query('SELECT id FROM services WHERE id_rfq = ? AND description = ?', [id, 'PW Test Service Added']);
    expect(rows.length).toBeGreaterThan(0);
    if (rows.length) {
      await query('DELETE FROM services WHERE id = ?', [rows[0].id]);
    }
  });

  test('add service modal resets after save', async ({ page }) => {
    await page.click('#add_service');
    await page.waitForSelector('#add_service_modal.show');

    await page.fill('#add_service_modal [name="description"]', 'Temp service');
    await page.fill('#add_service_modal [name="quantity"]', '1');
    await page.fill('#add_service_modal [name="unit_price"]', '5.00');

    const toastPromise = page.waitForSelector('.toast-success', { timeout: 10000 });
    await page.click('#add_service_modal .svc-add-save-btn');
    await toastPromise;

    // Form should reset after save
    const desc = await page.locator('#add_service_modal [name="description"]').inputValue();
    expect(desc).toBe('');

    // Clean up
    const { query } = require('../helpers/db');
    const { rfqId: id } = fixtures();
    await query('DELETE FROM services WHERE id_rfq = ? AND description = ?', [id, 'Temp service']);
  });

  test('edit service button opens populated edit modal', async ({ page }) => {
    const editBtn = await openKebabFor(page, '.edit_service');
    await editBtn.click();
    await expect(page.locator('#edit_service_modal')).toBeVisible();
    await page.waitForSelector('#edit_service_form [name="description"]', { timeout: 5000 });
    const desc = await page.locator('#edit_service_form [name="description"]').inputValue();
    expect(desc.length).toBeGreaterThan(0);
  });

  test('edit service saves changes and refreshes section', async ({ page }) => {
    const editBtn = await openKebabFor(page, '.edit_service');
    await editBtn.click();
    await page.waitForSelector('#edit_service_form [name="quantity"]', { timeout: 5000 });

    await page.fill('#edit_service_form [name="quantity"]', '5');

    const toastPromise = page.waitForSelector('.toast-success', { timeout: 10000 });
    await page.click('#edit_service_modal .svc-edit-save-btn');
    await toastPromise;

    await expect(page.locator('#edit_service_modal')).not.toBeVisible();
  });

  test('delete service shows confirmation modal not browser confirm', async ({ page }) => {
    let dialogFired = false;
    page.on('dialog', () => { dialogFired = true; });

    const deleteBtn = await openKebabFor(page, '.svc-delete-btn');
    await deleteBtn.click();
    await expect(page.locator('#alert_delete_system')).toBeVisible();
    expect(dialogFired).toBe(false);

    // Cancel
    await page.click('#alert_delete_system [data-dismiss="modal"]');
  });

  test('duplicate service creates new service row', async ({ page }) => {
    const { query } = require('../helpers/db');
    const { rfqId: id } = fixtures();

    const countBefore = await query('SELECT COUNT(*) as c FROM services WHERE id_rfq = ?', [id]);
    const before = countBefore[0].c;

    const toastPromise = page.waitForSelector('.toast-success', { timeout: 10000 });
    const dupBtn = await openKebabFor(page, '.svc-duplicate-btn');
    await dupBtn.click();
    await toastPromise;

    const countAfter = await query('SELECT COUNT(*) as c FROM services WHERE id_rfq = ?', [id]);
    expect(countAfter[0].c).toBeGreaterThan(before);

    // Clean up duplicate
    const newest = await query('SELECT id FROM services WHERE id_rfq = ? ORDER BY id DESC LIMIT 1', [id]);
    if (newest.length) {
      await query('DELETE FROM services WHERE id = ?', [newest[0].id]);
    }
  });

  test('service payment term select offers Net 30 and Net 30/CC', async ({ page }) => {
    // Payment Terms is a <select id="services_payment_term">, not a radio group.
    const select = page.locator('#services_payment_term');
    await expect(select).toBeVisible();
    await expect(select.locator('option[value="Net 30"]')).toHaveCount(1);
    await expect(select.locator('option[value="Net 30/CC"]')).toHaveCount(1);
  });
});
