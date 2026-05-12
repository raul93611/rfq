const { test, expect } = require('@playwright/test');
const fs = require('fs');
const path = require('path');

function fixtures() {
  return JSON.parse(fs.readFileSync(path.join(__dirname, '../.fixtures.json'), 'utf-8'));
}

test.describe('Live Calculations', () => {
  let rfqId;

  test.beforeEach(async ({ page }) => {
    ({ rfqId } = fixtures());
    await page.goto(`http://localhost/rfq/perfil/quote/editar_cotizacion/${rfqId}`);
    await page.waitForSelector('#bar-total-price');
    // Wait for the calculation interval to fire at least once
    await page.waitForTimeout(500);
  });

  test('bottom bar total price is visible and numeric', async ({ page }) => {
    const text = await page.locator('#bar-total-price').textContent();
    // Should be a number (possibly with decimal)
    expect(parseFloat(text.replace(/[^0-9.]/g, ''))).toBeGreaterThanOrEqual(0);
  });

  test('bottom bar profit is visible', async ({ page }) => {
    await expect(page.locator('#bar-total-profit')).toBeVisible();
  });

  test('bottom bar profit percentage is visible', async ({ page }) => {
    await expect(page.locator('#bar-profit-pct')).toBeVisible();
    const text = await page.locator('#bar-profit-pct').textContent();
    expect(text).toContain('%');
  });

  test('services total is included in bottom bar total', async ({ page }) => {
    // Get the service total displayed in the table
    const serviceTotalText = await page.locator('#total_service').textContent();
    const serviceTotal = parseFloat(serviceTotalText.replace(/[^0-9.]/g, '')) || 0;

    const barTotalText = await page.locator('#bar-total-price').textContent();
    const barTotal = parseFloat(barTotalText.replace(/[^0-9.]/g, '')) || 0;

    // The bar total must be at least as large as the services total
    expect(barTotal).toBeGreaterThanOrEqual(serviceTotal);
  });

  test('Net 30/CC payment term updates service prices', async ({ page }) => {
    // Wait for initial render
    await page.waitForTimeout(300);

    const unitCellBefore = await page.locator('#services_section tbody .service_item td:nth-child(5)').first().textContent();
    const basePriceBefore = parseFloat(unitCellBefore);

    // Select Net 30/CC — click the label since it's a custom Bootstrap radio
    await page.locator('label[for="net_30ccServices"]').click();
    await page.waitForTimeout(300);

    const unitCellAfter = await page.locator('#services_section tbody .service_item td:nth-child(5)').first().textContent();
    const priceAfter = parseFloat(unitCellAfter);

    // Net 30/CC multiplies by 1.03 approximately
    expect(priceAfter).toBeGreaterThan(basePriceBefore);
  });

  test('Net 30 payment term restores base service price', async ({ page }) => {
    // First set Net 30/CC — click the label (custom Bootstrap radio)
    await page.locator('label[for="net_30ccServices"]').click();
    await page.waitForTimeout(300);

    const priceWithCC = await page.locator('#services_section tbody .service_item td:nth-child(5)').first().textContent();

    // Switch back to Net 30
    await page.locator('label[for="net_30Services"]').click();
    await page.waitForTimeout(300);

    const priceWithNet30 = await page.locator('#services_section tbody .service_item td:nth-child(5)').first().textContent();

    expect(parseFloat(priceWithNet30)).toBeLessThan(parseFloat(priceWithCC));
  });

  test('profit percentage field displays a percentage value', async ({ page }) => {
    const pctText = await page.locator('#bar-profit-pct').textContent();
    // Should contain a number followed by %
    expect(pctText.trim()).toMatch(/[\d.]+%/);
  });
});
