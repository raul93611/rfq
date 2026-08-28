const { test, expect } = require('@playwright/test');
const fs = require('fs');
const path = require('path');
const { openKebabFor } = require('../helpers/kebab');

function fixtures() {
  return JSON.parse(fs.readFileSync(path.join(__dirname, '../.fixtures.json'), 'utf-8'));
}

test.describe('Quote Editing — Items', () => {
  let rfqId;

  test.beforeEach(async ({ page }) => {
    ({ rfqId } = fixtures());
    await page.goto(`http://localhost/rfq/perfil/quote/editar_cotizacion/${rfqId}`);
    await page.waitForSelector('#tabla_items');
  });

  test('items table renders with test item', async ({ page }) => {
    await expect(page.locator('#tabla_items tbody tr').first()).toBeVisible();
    const editBtn = await openKebabFor(page, '.iem-edit-item');
    await expect(editBtn).toBeVisible();
  });

  test('Downloads dropdown lives in the bottom action bar, not above the Items table', async ({ page }) => {
    // See bugs/downloads-button-relocate-to-navbar.md — Downloads used to render inside
    // .quote-section-header via RepositorioItem::escribir_items(), styled as a leftward
    // dropleft dropdown. It now lives in .quote-action-bar__right as the rightmost button,
    // matching Rooms/Actions (dropup, right-aligned).
    const actionBarDownloads = page.locator('.quote-action-bar__right .dropdown-toggle', { hasText: 'Downloads' });
    await expect(actionBarDownloads).toBeVisible();
    await expect(page.locator('.quote-section-header .dropdown-toggle', { hasText: 'Downloads' })).toHaveCount(0);

    const group = page.locator('.quote-action-bar__right .btn-group:has(.dropdown-toggle:text("Downloads"))');
    await expect(group).toHaveClass(/dropup/);

    await actionBarDownloads.click();
    const menu = group.locator('.dropdown-menu');
    await expect(menu).toHaveClass(/dropdown-menu-right/);
    await expect(menu.locator('.dropdown-item', { hasText: 'PDF - Items table' })).toBeVisible();
  });

  test('items table scrolls horizontally on a narrow viewport instead of squeezing description columns', async ({ page }) => {
    // bugs/items-table-narrow-description-columns.md — .it-table had no min-width, so on a
    // narrow (~13-14") viewport the auto description columns got squeezed toward zero instead
    // of the existing overflow-x:auto wrapper ever engaging.
    await page.setViewportSize({ width: 1300, height: 900 });
    const table = page.locator('#tabla_items');
    await expect(table).toBeVisible();

    const minWidth = await table.evaluate((el) => parseFloat(getComputedStyle(el).minWidth) || 0);
    expect(minWidth).toBeGreaterThanOrEqual(1360);

    const overflow = await page.locator('#table_items_container').evaluate((el) => el.scrollWidth - el.clientWidth);
    expect(overflow).toBeGreaterThan(0);

    const descWidth = await page.locator('#tabla_items .it-td-spec').first().evaluate((el) => el.getBoundingClientRect().width);
    expect(descWidth).toBeGreaterThanOrEqual(200);
  });

  test('add item button opens modal', async ({ page }) => {
    await page.click('[data-target="#add-item-modal"]');
    await expect(page.locator('#add-item-modal')).toBeVisible();
  });

  test('add item modal has required fields', async ({ page }) => {
    await page.click('[data-target="#add-item-modal"]');
    await expect(page.locator('#add-item-modal')).toBeVisible();
    await expect(page.locator('#add-item-modal [name="brand_project"]')).toBeVisible();
    await expect(page.locator('#add-item-modal [name="part_number_project"]')).toBeVisible();
    await expect(page.locator('#add-item-modal [name="quantity"]')).toBeVisible();
    await expect(page.locator('#add-item-modal [name="description_project"]')).toBeVisible();
  });

  test('add item saves and refreshes table', async ({ page }) => {
    const { query } = require('../helpers/db');
    const { rfqId: id } = fixtures();

    await page.click('[data-target="#add-item-modal"]');
    await page.waitForSelector('#add-item-modal.show');

    await page.fill('#add-item-modal [name="brand"]', 'PW-Brand-New');
    await page.fill('#add-item-modal [name="brand_project"]', 'PW-Brand-New');
    await page.fill('#add-item-modal [name="part_number"]', 'PW-PN-001');
    await page.fill('#add-item-modal [name="part_number_project"]', 'PW-PN-001');
    await page.fill('#add-item-modal [name="description"]', 'Playwright Added Item');
    await page.fill('#add-item-modal [name="description_project"]', 'Playwright Added Item');
    await page.fill('#add-item-modal [name="quantity"]', '1');

    const toastPromise = page.waitForSelector('.toast-success', { timeout: 10000 });
    await page.click('#add-item-modal .iem-save-btn');
    await toastPromise;

    await expect(page.locator('#add-item-modal')).not.toBeVisible();

    // Verify in DB and clean up
    const rows = await query('SELECT id FROM item WHERE id_rfq = ? AND brand_project = ?', [id, 'PW-Brand-New']);
    expect(rows.length).toBeGreaterThan(0);
    if (rows.length) {
      await query('DELETE FROM item WHERE id = ?', [rows[0].id]);
    }
  });

  test('edit item button opens populated edit modal', async ({ page }) => {
    const $editBtn = await openKebabFor(page, '.iem-edit-item');
    await $editBtn.click();
    await expect(page.locator('#edit-item-modal')).toBeVisible();
    // Form should load content
    await page.waitForSelector('#iem-edit-item-body [name="brand"], #iem-edit-item-body [name="brand_project"]', { timeout: 5000 });
  });

  test('edit item saves changes and updates table', async ({ page }) => {
    const editBtn = await openKebabFor(page, '.iem-edit-item');
    await editBtn.click();
    await page.waitForSelector('#iem-edit-item-body [name="brand_project"]', { timeout: 5000 });

    await page.fill('#iem-edit-item-body [name="brand_project"]', 'UpdatedBrand');

    const toastPromise = page.waitForSelector('.toast-success', { timeout: 10000 });
    await page.click('#edit-item-modal .iem-save-btn');
    await toastPromise;

    await expect(page.locator('#edit-item-modal')).not.toBeVisible();
  });

  test('delete item shows confirmation modal not browser confirm', async ({ page }) => {
    // Check we don't use browser confirm — we use #alert_delete_system modal
    let dialogFired = false;
    page.on('dialog', () => { dialogFired = true; });

    const deleteBtn = await openKebabFor(page, '.iem-delete-item');
    await deleteBtn.click();
    // Should open #alert_delete_system
    await expect(page.locator('#alert_delete_system')).toBeVisible();
    expect(dialogFired).toBe(false);

    // Dismiss without confirming
    await page.click('#alert_delete_system [data-dismiss="modal"]');
    await expect(page.locator('#alert_delete_system')).not.toBeVisible();
  });

  test('adding the first item to an empty quote renders it immediately, no reload needed', async ({ page }) => {
    // Regression: on an empty quote, <table id="tabla_items">/<tbody id="items"> doesn't
    // exist in the DOM at all (RepositorioItem::escribir_items() only renders it inside
    // `if (count($items))`). refreshItemsTable() used to target '#items' directly, so
    // $('#items').html(...) silently no-opped on the very first item — it only appeared
    // after a full page reload. Fixed by replacing the whole '#items-section-wrapper'.
    const { query } = require('../helpers/db');
    const { userId } = fixtures();

    const insertResult = await query(
      `INSERT INTO rfq (id_usuario, usuario_designado, canal, email_code, type_of_bid, issue_date, end_date,
        status, completado, award, payment_terms, address, ship_to, ship_via, taxes, profit, additional,
        shipping_cost, shipping, fullfillment, contract_number, deleted)
       VALUES (?, ?, 'GSA-Buy', ?, 'Services', '2026-01-01', '2026-12-31', 0, 0, 0, 'Net 30', '', '', '', 0, 0, '', 0, '', 0, '', 0)`,
      [userId, userId, 'EMPTY-' + Date.now()]
    );
    const emptyRfqId = insertResult.insertId;

    try {
      await page.goto(`http://localhost/rfq/perfil/quote/editar_cotizacion/${emptyRfqId}`);
      await page.waitForSelector('.items-section-wrapper .section-empty-state');
      await expect(page.locator('#tabla_items')).toHaveCount(0);

      await page.click('[data-target="#add-item-modal"]');
      await page.waitForSelector('#add-item-modal.show');
      await page.fill('#add-item-modal [name="brand"]', 'PW-Empty-Brand');
      await page.fill('#add-item-modal [name="brand_project"]', 'PW-Empty-Brand');
      await page.fill('#add-item-modal [name="part_number"]', 'PW-Empty-PN');
      await page.fill('#add-item-modal [name="part_number_project"]', 'PW-Empty-PN');
      await page.fill('#add-item-modal [name="description"]', 'Playwright Empty Quote Item');
      await page.fill('#add-item-modal [name="description_project"]', 'Playwright Empty Quote Item');
      await page.fill('#add-item-modal [name="quantity"]', '1');

      const toastPromise = page.waitForSelector('.toast-success', { timeout: 10000 });
      await page.click('#add-item-modal .iem-save-btn');
      await toastPromise;

      // No page.reload() anywhere above — this must come from the AJAX refresh alone.
      await expect(page.locator('#tabla_items')).toBeVisible({ timeout: 10000 });
      await expect(page.locator('#tabla_items tbody tr', { hasText: 'PW-Empty-Brand' })).toBeVisible();
      await expect(page.locator('.items-section-wrapper .section-empty-state')).toHaveCount(0);
    } finally {
      await query('DELETE FROM provider WHERE id_item IN (SELECT id FROM item WHERE id_rfq = ?)', [emptyRfqId]);
      await query('DELETE FROM item WHERE id_rfq = ?', [emptyRfqId]);
      await query('DELETE FROM audit_trails WHERE id_rfq = ?', [emptyRfqId]);
      await query('DELETE FROM rfq WHERE id = ?', [emptyRfqId]);
    }
  });

  test('scroll position is restored after adding a provider refreshes the items table', async ({ page }) => {
    // bugs/items-table-scroll-reset-on-refresh.md — refreshItemsTable() did a full HTML swap
    // of #items-section-wrapper with no scroll-position handling. A real Chrome run of the
    // literal repro (scroll down, add a provider, save) doesn't visibly move window.scrollY
    // here — jQuery's .html() sets innerHTML in one shot with no intermediate empty state to
    // clamp against — so this asserts the actual contract from the bug's fix plan instead:
    // refreshItemsTable() must explicitly restore the pre-refresh scroll position afterward.
    // A window.scrollTo spy catches that regardless of whether a given swap happens to move
    // the visible scrollbar.
    const { query } = require('../helpers/db');
    const { rfqId: id, userId } = fixtures();

    const values = [];
    const params = [];
    for (let i = 0; i < 25; i++) {
      values.push('(?, ?, 0, ?, ?, ?, ?, ?, ?, 1, 10.00, 10.00, \'\', \'\', \'\')');
      params.push(id, userId, 'ScrollBrand', 'ScrollBrand', 'SCROLL-PN-' + i, 'SCROLL-PN-' + i,
        'Scroll Test Item ' + i, 'Scroll Test Item ' + i);
    }
    await query(
      `INSERT INTO item (id_rfq, id_usuario, provider_menor, brand, brand_project, part_number,
        part_number_project, description, description_project, quantity, unit_price, total_price,
        comments, website, additional)
       VALUES ${values.join(',')}`,
      params
    );

    try {
      await page.goto(`http://localhost/rfq/perfil/quote/editar_cotizacion/${id}`);
      await page.waitForSelector('#tabla_items');

      await page.evaluate(() => window.scrollTo(0, document.documentElement.scrollHeight));
      expect(await page.evaluate(() => window.scrollY)).toBeGreaterThan(0);

      await page.evaluate(() => {
        window.__scrollToCalls = [];
        const original = window.scrollTo.bind(window);
        window.scrollTo = function (...args) {
          window.__scrollToCalls.push(args);
          return original(...args);
        };
      });

      // Read window.scrollY at the exact moment refreshItemsTable()'s AJAX request fires —
      // this is what the fix's `var scrollY = window.scrollY` line captures. Reading it
      // earlier (e.g. right before the Save click) is unreliable: clicking/focus changes on
      // the way there can themselves nudge the scroll position before the request ever fires.
      let scrollAtRequestTime = null;
      await page.route('**/quote/get_items_table/*', async (route) => {
        scrollAtRequestTime = await page.evaluate(() => window.scrollY);
        await route.continue();
      });

      // Scope to the kebab-menu variant — renderProvidersList() also emits an inline
      // "No providers" .it-prov-add.iem-add-provider button for provider-less items,
      // which isn't behind a kebab wrap and would break openKebabFor's ancestor lookup.
      const addBtnCount = await page.locator('.it-menu-item.iem-add-provider').count();
      const addBtn = await openKebabFor(page, '.it-menu-item.iem-add-provider', addBtnCount - 1);
      await addBtn.click();
      await page.waitForSelector('#add-provider-modal.show');

      await page.fill('#add-provider-modal [name="provider"]', 'PW-ScrollProvider');
      await page.fill('#add-provider-modal [name="price"]', '5.00');

      const toastPromise = page.waitForSelector('.toast-success', { timeout: 10000 });
      await page.click('#add-provider-modal .iem-save-btn');
      await toastPromise;

      await expect(page.locator('#add-provider-modal')).not.toBeVisible();
      await page.waitForTimeout(300);

      expect(scrollAtRequestTime).toBeGreaterThan(0);
      const calls = await page.evaluate(() => window.__scrollToCalls);
      const restoredOriginalScroll = calls.some(([, y]) => Math.abs(y - scrollAtRequestTime) <= 5);
      expect(restoredOriginalScroll).toBe(true);
    } finally {
      await query('DELETE FROM provider WHERE id_item IN (SELECT id FROM item WHERE id_rfq = ? AND brand = ?)', [id, 'ScrollBrand']);
      await query('DELETE FROM item WHERE id_rfq = ? AND brand = ?', [id, 'ScrollBrand']);
    }
  });
});
