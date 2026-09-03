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

  // feature: pipeline-type-of-contract.md
  test('Quotes by Type of Contract donut renders right after Status distribution', async ({ page }) => {
    await gotoDataRichYear(page);
    const cardKeys = await page.locator('.pm-grid > [data-card]').evaluateAll(
      (els) => els.map((el) => el.dataset.card)
    );
    const statusIdx = cardKeys.indexOf('status');
    expect(cardKeys[statusIdx + 1]).toBe('contractType');
    await expect(page.locator('[data-card="contractType"] .pm-card-title')).toHaveText('Quotes by Type of Contract');
    await expect(page.locator('#pm-chart-contractType .apexcharts-canvas')).toBeVisible();
  });

  test('clicking a Type of Contract slice opens the drill-down drawer', async ({ page }) => {
    await gotoDataRichYear(page);
    await page.waitForSelector('#pm-chart-contractType .apexcharts-canvas', { timeout: 15000 });
    // click the smallest slice, not the first (DESC-sorted by count): a slice covering
    // most of the ring has a bounding box centered on the empty donut hole, so a
    // force-click there misses the visible arc entirely.
    const slice = page.locator('#pm-chart-contractType .apexcharts-pie-area').last();
    await slice.click({ force: true });
    await expect(page.locator('#pm-drawer')).toHaveClass(/is-open/);
    await expect(page.locator('#pm-drawer-meta')).toContainText('quote');
  });
});

test.describe('Bid Pipeline Metrics — Table view', () => {
  test.beforeEach(async ({ page }) => {
    await page.goto(DASH);
    await page.click('#pm-view .pm-seg-btn[data-view="table"]');
    await page.waitForSelector('#pm-table-view:not([hidden])');
    await page.waitForResponse((res) => res.url().includes('/quote/pipeline_table'));
  });

  test('table has an End Date range filter next to Internal Due Date', async ({ page }) => {
    // feature: pipeline-table-end-date-range-filter.md
    const dueDate = page.locator('#pt-f-dueDate');
    const endDateFrom = page.locator('#pt-f-endDateFrom');
    const endDateTo = page.locator('#pt-f-endDateTo');
    await expect(endDateFrom).toBeVisible();
    await expect(endDateTo).toBeVisible();
    const labels = await page.locator('.pt-filters-grid .pt-field-label').allTextContents();
    const dueDateIdx = labels.indexOf('Internal Due Date');
    const endDateIdx = labels.indexOf('End Date');
    expect(endDateIdx).toBe(dueDateIdx + 1);
    // Internal Due Date field comes immediately before End Date in the filter row.
    const dueDateBox = await dueDate.locator('xpath=..').boundingBox();
    const endDateBox = await endDateFrom.locator('xpath=..').boundingBox();
    expect(endDateBox.x).toBeGreaterThan(dueDateBox.x);
  });

  test('table shows Internal Due Date and End Date columns right after Created', async ({ page }) => {
    const headers = await page.locator('.pt-table thead th').allTextContents();
    expect(headers.slice(0, 4)).toEqual(['Quote ID', 'Created', 'Internal Due Date', 'End Date']);
  });

  test('End Date filter re-queries the server with the selected range', async ({ page }) => {
    const [req] = await Promise.all([
      page.waitForRequest((r) => r.url().includes('/quote/pipeline_table') && r.url().includes('endDateFrom=2020-01-01')),
      page.fill('#pt-f-endDateFrom', '2020-01-01'),
    ]);
    expect(req.url()).toContain('endDateFrom=2020-01-01');
  });

  test('End Date filter counts as one active filter and Clear filters resets both inputs', async ({ page }) => {
    await page.fill('#pt-f-endDateFrom', '2020-01-01');
    await page.waitForResponse((res) => res.url().includes('/quote/pipeline_table'));
    await expect(page.locator('#pt-filters-count')).toHaveText('1 filter applied');
    await page.click('#pt-clear');
    await expect(page.locator('#pt-f-endDateFrom')).toHaveValue('');
    await expect(page.locator('#pt-f-endDateTo')).toHaveValue('');
  });

  // feature: pipeline-table-submitted-date-filter.md
  test('table has a Submitted Date range filter right after Designated User and before Internal Due Date', async ({ page }) => {
    await expect(page.locator('#pt-f-submittedFrom')).toBeVisible();
    await expect(page.locator('#pt-f-submittedTo')).toBeVisible();
    const labels = await page.locator('.pt-filters-grid .pt-field-label').allTextContents();
    const userIdx = labels.indexOf('Designated User');
    const submittedIdx = labels.indexOf('Submitted Date');
    const dueDateIdx = labels.indexOf('Internal Due Date');
    expect(submittedIdx).toBe(userIdx + 1);
    expect(dueDateIdx).toBe(submittedIdx + 1);
  });

  test('table shows a Submitted column right after End Date', async ({ page }) => {
    const headers = await page.locator('.pt-table thead th').allTextContents();
    expect(headers.slice(0, 5)).toEqual(['Quote ID', 'Created', 'Internal Due Date', 'End Date', 'Submitted']);
  });

  test('Submitted Date filter re-queries the server with the selected range', async ({ page }) => {
    const [req] = await Promise.all([
      page.waitForRequest((r) => r.url().includes('/quote/pipeline_table') && r.url().includes('submittedFrom=2020-01-01')),
      page.fill('#pt-f-submittedFrom', '2020-01-01'),
    ]);
    expect(req.url()).toContain('submittedFrom=2020-01-01');
  });

  test('Submitted Date filter counts as one active filter and Clear filters resets both inputs', async ({ page }) => {
    await page.fill('#pt-f-submittedFrom', '2020-01-01');
    await page.waitForResponse((res) => res.url().includes('/quote/pipeline_table'));
    await expect(page.locator('#pt-filters-count')).toHaveText('1 filter applied');
    await page.click('#pt-clear');
    await expect(page.locator('#pt-f-submittedFrom')).toHaveValue('');
    await expect(page.locator('#pt-f-submittedTo')).toHaveValue('');
  });

  // feature: pipeline-type-of-contract.md
  test('Type of Contract filter appears right after Type of Bid and Type of Contract column right after Type of Bid column', async ({ page }) => {
    const labels = await page.locator('.pt-filters-grid .pt-field-label').allTextContents();
    const bidTypeIdx = labels.indexOf('Type of Bid');
    expect(labels[bidTypeIdx + 1]).toBe('Type of Contract');
    await expect(page.locator('#pt-f-contractType')).toBeVisible();

    const headers = await page.locator('.pt-table thead th').allTextContents();
    const headerBidTypeIdx = headers.indexOf('Type of Bid');
    expect(headers[headerBidTypeIdx + 1]).toBe('Type of Contract');
  });

  test('Type of Contract filter re-queries the server and counts as one active filter', async ({ page }) => {
    const value = await page.locator('#pt-f-contractType option').nth(1).getAttribute('value');
    const [req] = await Promise.all([
      page.waitForRequest((r) => r.url().includes('/quote/pipeline_table') && r.url().includes('contractType=')),
      page.selectOption('#pt-f-contractType', value),
    ]);
    expect(req.url()).toContain('contractType=' + encodeURIComponent(value));
    await expect(page.locator('#pt-filters-count')).toHaveText('1 filter applied');
    await page.click('#pt-clear');
    await expect(page.locator('#pt-f-contractType')).toHaveValue('');
  });
});
