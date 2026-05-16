const { test, expect } = require('@playwright/test');
const fs = require('fs');
const path = require('path');

function fixtures() {
  return JSON.parse(fs.readFileSync(path.join(__dirname, '../.fixtures.json'), 'utf-8'));
}

test.describe('Delete Confirmation Modal', () => {
  let rfqId;

  test.beforeEach(async ({ page }) => {
    ({ rfqId } = fixtures());
    await page.goto(`http://localhost/rfq/perfil/quote/editar_cotizacion/${rfqId}`);
    await page.waitForSelector('#tabla_items');
  });

  test('alert_delete_system modal exists in DOM', async ({ page }) => {
    await expect(page.locator('#alert_delete_system')).toHaveCount(1);
  });

  test('cancel button on confirmation modal does not delete', async ({ page }) => {
    const { query } = require('../helpers/db');
    const { rfqId: id } = fixtures();

    const countBefore = await query('SELECT COUNT(*) as c FROM services WHERE id_rfq = ?', [id]);
    const before = countBefore[0].c;

    // Trigger service delete
    await page.locator('.svc-delete-btn').first().click();
    await expect(page.locator('#alert_delete_system')).toBeVisible();

    // Click cancel
    await page.locator('#alert_delete_system [data-dismiss="modal"]').first().click();
    await expect(page.locator('#alert_delete_system')).not.toBeVisible();

    // Count should be unchanged
    const countAfter = await query('SELECT COUNT(*) as c FROM services WHERE id_rfq = ?', [id]);
    expect(countAfter[0].c).toBe(before);
  });

  test('confirm button on confirmation modal executes delete', async ({ page }) => {
    const { query } = require('../helpers/db');
    const { rfqId: id } = fixtures();

    // Add a temp service to delete
    await query(
      'INSERT INTO services (id_rfq, description, quantity, unit_price, total_price) VALUES (?, ?, 1, 1.00, 1.00)',
      [id, 'PW-Temp-Delete-Me']
    );
    await page.reload();
    await page.waitForSelector('#services_section .service_item');

    // Find the temp service and delete it via the first delete button (since it's last, we need to click the right one)
    // Trigger the last svc-delete-btn since we just added it
    const rows = await query('SELECT id FROM services WHERE id_rfq = ? AND description = ?', [id, 'PW-Temp-Delete-Me']);
    expect(rows.length).toBe(1);
    const tempId = rows[0].id;

    // Click delete on that specific service row
    await page.locator(`#service${tempId} .svc-delete-btn`).click();
    await expect(page.locator('#alert_delete_system')).toBeVisible();

    // Confirm
    const toastPromise = page.waitForSelector('.toast-success', { timeout: 10000 });
    await page.locator('#continue_button').click();
    await toastPromise;

    // Verify deleted from DB
    const afterRows = await query('SELECT id FROM services WHERE id = ?', [tempId]);
    expect(afterRows.length).toBe(0);
  });

  test('no browser confirm dialogs are used anywhere on quote page', async ({ page }) => {
    const dialogs = [];
    page.on('dialog', (dialog) => {
      dialogs.push(dialog.message());
      dialog.dismiss();
    });

    // Trigger item delete
    if (await page.locator('.iem-delete-item').count() > 0) {
      await page.locator('.iem-delete-item').first().click();
      await expect(page.locator('#alert_delete_system')).toBeVisible();
      await page.locator('#alert_delete_system [data-dismiss="modal"]').first().click();
    }

    // Trigger subitem delete
    if (await page.locator('.iem-delete-subitem').count() > 0) {
      await page.locator('.iem-delete-subitem').first().click();
      await expect(page.locator('#alert_delete_system')).toBeVisible();
      await page.locator('#alert_delete_system [data-dismiss="modal"]').first().click();
    }

    // Trigger service delete
    if (await page.locator('.svc-delete-btn').count() > 0) {
      await page.locator('.svc-delete-btn').first().click();
      await expect(page.locator('#alert_delete_system')).toBeVisible();
      await page.locator('#alert_delete_system [data-dismiss="modal"]').first().click();
    }

    expect(dialogs).toHaveLength(0);
  });
});
