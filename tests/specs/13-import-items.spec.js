const { test, expect } = require('@playwright/test');
const fs = require('fs');
const path = require('path');
const { query } = require('../helpers/db');

function fixtures() {
  return JSON.parse(fs.readFileSync(path.join(__dirname, '../.fixtures.json'), 'utf-8'));
}

async function openImportModal(page) {
  await page.click('.quote-action-bar__right .btn-group .dropdown-toggle:text("Actions")');
  await page.click('#import-items-modal-button');
  await page.waitForSelector('#import-items-modal.show');
}

test.describe('Import Items modal', () => {
  let rfqId;

  test.beforeEach(async ({ page }) => {
    ({ rfqId } = fixtures());
    await page.goto(`http://localhost/rfq/perfil/quote/editar_cotizacion/${rfqId}`);
    await page.waitForSelector('#tabla_items');
  });

  test('opens from Actions with Append selected by default and Upload disabled', async ({ page }) => {
    await openImportModal(page);
    await expect(page.locator('#ii-upload-btn')).toBeDisabled();
    await expect(page.locator('#ii-radio-append')).toHaveClass(/is-selected/);
    await expect(page.locator('#ii-radio-replace')).not.toHaveClass(/is-selected/);
    await expect(page.locator('#ii-warning-collapse')).not.toHaveClass(/is-open/);
  });

  test('Download Template link streams a 20-column xlsx, no sample row', async ({ page, request }) => {
    await openImportModal(page);
    const href = await page.locator('[data-testid="ii-download-template"]').getAttribute('href');
    const response = await request.get(new URL(href, 'http://localhost/rfq/').toString());
    expect(response.ok()).toBeTruthy();
    expect(response.headers()['content-disposition']).toContain('Item_Import_Template.xlsx');
  });

  test('choosing a file shows the file chip and enables Upload', async ({ page }) => {
    await openImportModal(page);
    await page.setInputFiles('#uploaded_file', {
      name: 'items.csv',
      mimeType: 'text/csv',
      buffer: Buffer.from('Brand,Part Number\nAcme,PN-1\n'),
    });
    await expect(page.locator('#ii-file-chip')).toBeVisible();
    await expect(page.locator('#ii-file-name')).toHaveText('items.csv');
    await expect(page.locator('#ii-upload-btn')).toBeEnabled();
  });

  test('removing the chosen file hides the chip and disables Upload again', async ({ page }) => {
    await openImportModal(page);
    await page.setInputFiles('#uploaded_file', {
      name: 'items.csv',
      mimeType: 'text/csv',
      buffer: Buffer.from('Brand,Part Number\nAcme,PN-1\n'),
    });
    await expect(page.locator('#ii-upload-btn')).toBeEnabled();

    await page.click('[data-testid="ii-file-remove"]');
    await expect(page.locator('#ii-file-chip')).toBeHidden();
    await expect(page.locator('#ii-upload-btn')).toBeDisabled();
  });

  test('Replace mode shows no warning on a pre-Fulfillment quote', async ({ page }) => {
    await openImportModal(page);
    await page.click('#ii-radio-replace');
    await expect(page.locator('#ii-radio-replace')).toHaveClass(/is-selected/);
    await expect(page.locator('#ii-warning-collapse')).not.toHaveClass(/is-open/);
  });

  test('Replace mode shows the Fulfillment warning once the quote is past Fulfillment', async ({ page }) => {
    await query('UPDATE rfq SET fullfillment = 1 WHERE id = ?', [rfqId]);
    try {
      await page.reload();
      await page.waitForSelector('#tabla_items');
      await openImportModal(page);

      await expect(page.locator('#ii-warning-collapse')).not.toHaveClass(/is-open/); // Append: never warns
      await page.click('#ii-radio-replace');
      await expect(page.locator('#ii-warning-collapse')).toHaveClass(/is-open/);
      await expect(page.locator('[data-testid="ii-fulfillment-warning"]')).toBeVisible();

      // Switching back to Append hides it again.
      await page.click('#ii-radio-append');
      await expect(page.locator('#ii-warning-collapse')).not.toHaveClass(/is-open/);
    } finally {
      await query('UPDATE rfq SET fullfillment = 0 WHERE id = ?', [rfqId]);
    }
  });

  test('closing and reopening the modal resets file selection and mode', async ({ page }) => {
    await openImportModal(page);
    await page.setInputFiles('#uploaded_file', {
      name: 'items.csv',
      mimeType: 'text/csv',
      buffer: Buffer.from('Brand,Part Number\nAcme,PN-1\n'),
    });
    await page.click('#ii-radio-replace');

    await page.click('#import-items-modal .iem-cancel-btn');
    await expect(page.locator('#import-items-modal')).not.toBeVisible();

    await openImportModal(page);
    await expect(page.locator('#ii-file-chip')).toBeHidden();
    await expect(page.locator('#ii-upload-btn')).toBeDisabled();
    await expect(page.locator('#ii-radio-append')).toHaveClass(/is-selected/);
  });
});
