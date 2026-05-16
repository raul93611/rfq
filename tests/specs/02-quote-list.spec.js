const { test, expect } = require('@playwright/test');
const fs = require('fs');
const path = require('path');

function fixtures() {
  return JSON.parse(fs.readFileSync(path.join(__dirname, '../.fixtures.json'), 'utf-8'));
}

test.describe('Quote List & Navigation', () => {
  test('dashboard loads with navigation', async ({ page }) => {
    await page.goto('http://localhost/rfq/perfil/charts');
    await expect(page.locator('body')).toBeVisible();
    // Should have navigation sidebar
    await expect(page.locator('.sidebar').first()).toBeVisible();
  });

  test('can navigate to quote list', async ({ page }) => {
    await page.goto('http://localhost/rfq/perfil/quote/channel/GSA-Buy');
    await expect(page.locator('body')).toBeVisible();
  });

  test('can navigate to quote editing page', async ({ page }) => {
    const { rfqId } = fixtures();
    await page.goto(`http://localhost/rfq/perfil/quote/editar_cotizacion/${rfqId}`);
    await expect(page.locator('body')).toBeVisible();
    // Should not redirect to login
    await expect(page.url()).toContain('editar_cotizacion');
  });

  test('quote editing page shows items table', async ({ page }) => {
    const { rfqId } = fixtures();
    await page.goto(`http://localhost/rfq/perfil/quote/editar_cotizacion/${rfqId}`);
    await expect(page.locator('#tabla_items')).toBeVisible();
  });

  test('quote editing page shows services section', async ({ page }) => {
    const { rfqId } = fixtures();
    await page.goto(`http://localhost/rfq/perfil/quote/editar_cotizacion/${rfqId}`);
    await expect(page.locator('#services_section')).toBeVisible();
  });

  test('quote editing page shows bottom bar with totals', async ({ page }) => {
    const { rfqId } = fixtures();
    await page.goto(`http://localhost/rfq/perfil/quote/editar_cotizacion/${rfqId}`);
    await expect(page.locator('#bar-total-price')).toBeVisible();
  });
});
