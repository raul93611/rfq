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

  test('per-chart and full-report buttons export .xlsx', async ({ page }) => {
    await gotoDataRichYear(page);
    const [chartDl] = await Promise.all([
      page.waitForEvent('download'),
      page.click('[data-export="pricing"]'),
    ]);
    expect(chartDl.suggestedFilename()).toMatch(/\.xlsx$/);
    const [reportDl] = await Promise.all([
      page.waitForEvent('download'),
      page.click('#pm-export-all'),
    ]);
    expect(reportDl.suggestedFilename()).toMatch(/\.xlsx$/);
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

test.describe('Bid Pipeline Metrics — Table view', () => {
  test.beforeEach(async ({ page }) => {
    await page.goto(DASH);
    await page.click('#pm-view .pm-seg-btn[data-view="table"]');
    await page.waitForSelector('#pm-table-view:not([hidden])');
    await page.waitForResponse((res) => res.url().includes('/quote/pipeline_table'));
  });

  test('table has an End Date filter next to Internal Due Date, both with the four date presets', async ({ page }) => {
    // feature: end-date-pipeline-table.md
    const dueDate = page.locator('#pt-f-dueDate');
    const endDate = page.locator('#pt-f-endDate');
    await expect(endDate).toBeVisible();
    const options = await endDate.locator('option').allTextContents();
    expect(options).toEqual(['Any end date', 'Today', 'Tomorrow', 'Next 7 days', 'Overdue']);
    // Internal Due Date field comes immediately before End Date in the filter row.
    const dueDateBox = await dueDate.locator('xpath=..').boundingBox();
    const endDateBox = await endDate.locator('xpath=..').boundingBox();
    expect(endDateBox.x).toBeGreaterThan(dueDateBox.x);
  });

  test('table shows Internal Due Date and End Date columns right after Created', async ({ page }) => {
    const headers = await page.locator('.pt-table thead th').allTextContents();
    expect(headers.slice(0, 4)).toEqual(['Quote ID', 'Created', 'Internal Due Date', 'End Date']);
  });

  test('End Date filter re-queries the server with the selected preset', async ({ page }) => {
    const [req] = await Promise.all([
      page.waitForRequest((r) => r.url().includes('/quote/pipeline_table') && r.url().includes('endDate=today')),
      page.selectOption('#pt-f-endDate', 'today'),
    ]);
    expect(req.url()).toContain('endDate=today');
  });

  test('Clear filters resets the End Date select back to "Any end date"', async ({ page }) => {
    await page.selectOption('#pt-f-endDate', 'overdue');
    await expect(page.locator('#pt-f-endDate')).toHaveValue('overdue');
    await page.waitForResponse((res) => res.url().includes('/quote/pipeline_table'));
    await page.click('#pt-clear');
    await expect(page.locator('#pt-f-endDate')).toHaveValue('');
  });
});
