const { test, expect } = require('@playwright/test');

const PAGE = 'http://localhost/rfq/perfil/search_quotes';

async function openAdvanced(page) {
  await page.goto(PAGE);
  await page.click('[data-testid="advanced-toggle"]');
  await expect(page.locator('[data-testid="advanced-filter-panel"]')).toHaveClass(/is-open/);
}

/** Waits for the quotes DataTable to finish a server-side load. */
async function waitForResults(page) {
  await expect(page.locator('#tabla_busqueda_processing')).toBeHidden({ timeout: 15000 });
  await expect(page.locator('#tabla_busqueda tbody tr').first()).toBeVisible({ timeout: 15000 });
}

test.describe('Advanced Quote Search', () => {
  test('basic mode behaves as before: keyword search fills both tables', async ({ page }) => {
    await page.goto(PAGE);
    await expect(page.locator('[data-testid="advanced-filter-panel"]')).not.toHaveClass(/is-open/);
    await expect(page.locator('[data-testid="partial-invoices-card"]')).toBeVisible();

    await page.fill('[name="termino_busqueda"]', 'quote');
    await waitForResults(page);
    // No Status column in basic mode
    await expect(page.locator('#tabla_busqueda thead th.sq-status-th')).toHaveCount(0);
    await expect(page.locator('#tabla_invoices tbody tr').first()).toBeVisible();
  });

  test('advanced toggle opens panel, hides invoices, loads all quotes with Status pills', async ({ page }) => {
    await openAdvanced(page);
    await expect(page.locator('[data-testid="partial-invoices-card"]')).toBeHidden();
    await waitForResults(page);

    await expect(page.locator('#tabla_busqueda thead th.sq-status-th')).toHaveCount(1);
    await expect(page.locator('#tabla_busqueda tbody .sq-pill').first()).toBeVisible();
    // browsing with no filters and no keyword is valid
    const info = await page.locator('#tabla_busqueda_info').innerText();
    expect(info).toMatch(/of [\d,]+ entries/);
  });

  test('status filter narrows results to the selected bucket', async ({ page }) => {
    await openAdvanced(page);
    await waitForResults(page);

    await page.click('[data-testid="status-filter"]');
    await page.click('[data-testid="status-opt-award"]');
    await page.click('h1.page-title'); // close the dropdown
    await waitForResults(page);

    const pills = page.locator('#tabla_busqueda tbody .sq-pill');
    const n = await pills.count();
    expect(n).toBeGreaterThan(0);
    for (let i = 0; i < n; i++) {
      await expect(pills.nth(i)).toHaveText('Award');
    }
    await expect(page.locator('#sq_adv_count')).toHaveText('1');
  });

  test('inverted price range shows the empty state, not an error', async ({ page }) => {
    await openAdvanced(page);
    await waitForResults(page);

    await page.fill('[data-testid="price-min"]', '999999');
    await page.fill('[data-testid="price-max"]', '1');
    await expect(page.locator('#tabla_busqueda .sq-empty-title')).toHaveText('No matching records', { timeout: 15000 });
  });

  test('clear filters resets the panel and reloads', async ({ page }) => {
    await openAdvanced(page);
    await waitForResults(page);

    await page.fill('[data-testid="state-filter"]', 'FL');
    await expect(page.locator('#sq_adv_count')).toHaveText('1');
    await page.click('[data-testid="clear-filters"]');
    await expect(page.locator('#sq_adv_count')).toBeHidden();
    await expect(page.locator('[data-testid="state-filter"]')).toHaveValue('');
  });

  test('toggling back to basic restores the page and resets filters', async ({ page }) => {
    await openAdvanced(page);
    await waitForResults(page);
    await page.fill('[data-testid="client-filter"]', 'county');

    await page.click('[data-testid="advanced-toggle"]');
    await expect(page.locator('[data-testid="advanced-filter-panel"]')).not.toHaveClass(/is-open/);
    await expect(page.locator('[data-testid="partial-invoices-card"]')).toBeVisible();
    await expect(page.locator('#tabla_busqueda thead th.sq-status-th')).toHaveCount(0);

    // reopening shows filters were reset
    await page.click('[data-testid="advanced-toggle"]');
    await expect(page.locator('[data-testid="client-filter"]')).toHaveValue('');
  });
});
