const { test, expect } = require('@playwright/test');

const DASH = 'http://localhost/rfq/perfil/reports/pipeline_metrics';

// Step the year back to a data-rich historical year so all five charts populate.
async function gotoDataRichYear(page) {
  await page.goto(DASH);
  await page.waitForSelector('#pm-chart-status .apexcharts-canvas', { timeout: 15000 });
  for (let i = 0; i < 3; i++) {            // current year -> 3 years back
    await page.click('#pm-year-prev');
    await page.waitForTimeout(700);
  }
  await page.waitForTimeout(1200);
}

test.describe('Bid Pipeline Metrics dashboard', () => {
  test('dashboard renders KPI cards and the status donut', async ({ page }) => {
    await page.goto(DASH);
    await expect(page.locator('.pm-title')).toHaveText('Bid Pipeline Metrics');
    await expect(page.locator('#pm-kpi-total')).toBeVisible();
    await expect(page.locator('#pm-kpi-winrate')).toBeVisible();
    await page.waitForSelector('#pm-chart-status .apexcharts-canvas', { timeout: 15000 });
    await expect(page.locator('#pm-subtitle')).toContainText('bids in pipeline');
  });

  test('appears in the reports sidebar', async ({ page }) => {
    await page.goto(DASH);
    await expect(page.locator('.nav-link', { hasText: 'Bid Pipeline Metrics' })).toBeVisible();
  });

  test('all five charts render on a data-rich period', async ({ page }) => {
    await gotoDataRichYear(page);
    for (const key of ['status', 'winloss', 'awards', 'submitted', 'pricing']) {
      await expect(page.locator(`#pm-chart-${key} .apexcharts-canvas`)).toBeVisible();
    }
  });

  test('clicking a category bar opens the drill-down drawer with quotes', async ({ page }) => {
    await gotoDataRichYear(page);
    const bar = page.locator('#pm-chart-awards .apexcharts-bar-area').first();
    await bar.click({ force: true });
    await expect(page.locator('#pm-drawer')).toHaveClass(/is-open/);
    await expect(page.locator('#pm-drawer-meta')).toContainText('quote');
    // each quote row links to its edit page
    const href = await page.locator('#pm-drawer-list .pm-quote').first().getAttribute('href');
    expect(href).toContain('editar_cotizacion');
  });

  test('changing the period re-renders without a full reload', async ({ page }) => {
    await page.goto(DASH);
    await page.waitForSelector('#pm-chart-status .apexcharts-canvas', { timeout: 15000 });
    const before = await page.textContent('#pm-subtitle');
    await page.click('#pm-year-prev');
    await page.waitForTimeout(1200);
    const after = await page.textContent('#pm-subtitle');
    expect(after).not.toBe(before);          // subtitle reflects the new period
    expect(page.url()).toContain('pipeline_metrics'); // no navigation away
  });

  test('count / percent toggle stays interactive', async ({ page }) => {
    await gotoDataRichYear(page);
    await page.click('#pm-show .pm-seg-btn[data-show="percent"]');
    await expect(page.locator('#pm-show .pm-seg-btn[data-show="percent"]')).toHaveClass(/is-active/);
  });

  test('empty period shows a clean no-data state', async ({ page }) => {
    await page.goto(DASH);
    await page.waitForSelector('#pm-chart-status', { timeout: 15000 });
    // step far into the future where there is no data
    for (let i = 0; i < 1; i++) { await page.click('#pm-year-next'); await page.waitForTimeout(700); }
    await page.waitForTimeout(1000);
    await expect(page.locator('[data-card="status"] [data-empty]')).toBeVisible();
  });
});
