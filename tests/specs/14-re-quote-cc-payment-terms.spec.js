const { test, expect } = require('@playwright/test');
const { getConnection } = require('../helpers/db');

/* Regression coverage for two re-quote Net 30/CC bugs (bugs/re-quote-cc-profit-mismatch.md):
 *
 * 1. js/reQuote.js's #re_quote_form submit handler recalculated total_cost using
 *    $('input:radio[name=payment_terms]:checked') -- a selector for markup that no longer
 *    exists (payment_terms is a <select>, not radio buttons, since the 50/50 payment term
 *    feature). The selector never matched, so the CC fee was silently always computed as
 *    zero and total_cost got saved without it, however the dropdown was set -- while the
 *    on-screen live preview (calculateTotals()'s own interval, using the correct selector)
 *    showed the right number the whole time. Only the persisted/PDF value was wrong.
 *
 * 2. calcServices()'s live preview used a 1.0299 multiplier while
 *    ReQuoteServiceRepository::calc_items_with_CC() persists using 1.03 -- so the live
 *    "Total Services" preview and the saved/PDF total silently diverged by a few cents.
 */

let fixture;

test.beforeAll(async () => {
  const conn = await getConnection();
  try {
    const [userRows] = await conn.execute(
      "SELECT id FROM usuarios WHERE nombre_usuario = 'pw_test_user'"
    );
    const userId = userRows[0].id;

    const [rfqResult] = await conn.execute(
      `INSERT INTO rfq (id_usuario, usuario_designado, canal, email_code, type_of_bid, issue_date, end_date,
        status, completado, award, payment_terms, address, ship_to, ship_via, taxes, profit, additional,
        shipping_cost, shipping, fullfillment, contract_number, services_payment_term, total_price, total_cost, deleted)
       VALUES (?, ?, 'GSA-Buy', 'RQCC-E2E', 'Services', '2026-01-01', '2026-12-31',
        0, 0, 0, 'Net 30', '123 Test St', 'Test Ship', 'UPS', 0, 0, '', 0, '', 0, '', 'Net 30', 200.00, 180.00, 0)`,
      [userId, userId]
    );
    const rfqId = rfqResult.insertId;

    const [itemResult] = await conn.execute(
      `INSERT INTO item (id_rfq, id_usuario, provider_menor, brand, brand_project, part_number,
        part_number_project, description, description_project, quantity, unit_price, total_price,
        comments, website, additional)
       VALUES (?, ?, 0, 'TestBrand', 'TestBrand', 'PN-CC', 'PN-CC', 'CC Test Item',
        'CC Test Item', 2, 100.00, 200.00, '', '', '')`,
      [rfqId, userId]
    );
    const itemId = itemResult.insertId;

    await conn.execute(
      `INSERT INTO provider (id_item, provider, price) VALUES (?, 'CC Test Provider', 90.00)`,
      [itemId]
    );

    const [serviceResult] = await conn.execute(
      `INSERT INTO services (id_rfq, description, quantity, unit_price, total_price)
       VALUES (?, 'CC Test Service', 2, 78.00, 156.00)`,
      [rfqId]
    );
    const serviceId = serviceResult.insertId;

    fixture = { userId, rfqId, itemId, serviceId };
  } finally {
    await conn.end();
  }
});

test.afterAll(async () => {
  if (!fixture) return;
  const conn = await getConnection();
  try {
    const [[reQuote]] = await conn.query(
      'SELECT id FROM re_quotes WHERE id_rfq = ?', [fixture.rfqId]
    );
    if (reQuote) {
      await conn.execute('DELETE FROM re_quote_services WHERE id_re_quote = ?', [reQuote.id]);
      await conn.execute('DELETE FROM re_quote_providers WHERE id_re_quote_item IN (SELECT id FROM re_quote_items WHERE id_re_quote = ?)', [reQuote.id]);
      await conn.execute('DELETE FROM re_quote_items WHERE id_re_quote = ?', [reQuote.id]);
      await conn.execute('DELETE FROM re_quote_audit_trails WHERE id_re_quote = ?', [reQuote.id]);
      await conn.execute('DELETE FROM re_quotes WHERE id = ?', [reQuote.id]);
    }
    await conn.execute('DELETE FROM services WHERE id_rfq = ?', [fixture.rfqId]);
    await conn.execute('DELETE FROM provider WHERE id_item = ?', [fixture.itemId]);
    await conn.execute('DELETE FROM item WHERE id = ?', [fixture.itemId]);
    await conn.execute('DELETE FROM audit_trails WHERE id_rfq = ?', [fixture.rfqId]);
    await conn.execute('DELETE FROM rfq WHERE id = ?', [fixture.rfqId]);
  } finally {
    await conn.end();
  }
});

test.describe('Re-Quote Net 30/CC payment terms', () => {
  test('items: saved total_cost includes the CC fee (not silently zero)', async ({ page }) => {
    await page.goto(`http://localhost/rfq/perfil/re_quote/${fixture.rfqId}`);
    await expect(page.locator('#re_quote_form')).toBeVisible();

    await page.selectOption('select[name="payment_terms"]', 'Net 30/CC');
    await page.waitForTimeout(300); // let the 100ms polling loop settle

    // Live preview (calculateTotals()'s own interval) already computed this correctly
    // before the fix -- the bug was specifically that Save clobbered it back to zero-CC.
    const liveTotalCost = await page.locator('#total_cost').inputValue();
    expect(parseFloat(liveTotalCost)).toBeCloseTo(185.8, 1); // 180 raw + 200*0.029 CC fee

    await Promise.all([
      page.waitForNavigation(),
      page.click('button[name="save_re_quote"]'),
    ]);

    const conn = await getConnection();
    try {
      const [[reQuote]] = await conn.query(
        'SELECT total_cost, payment_terms FROM re_quotes WHERE id_rfq = ?', [fixture.rfqId]
      );
      expect(reQuote.payment_terms).toBe('Net 30/CC');
      // Bug: this was exactly 180.00 (raw, no CC fee) regardless of payment_terms.
      expect(parseFloat(reQuote.total_cost)).toBeCloseTo(185.8, 1);
    } finally {
      await conn.end();
    }
  });

  test('services: live preview matches the persisted/PDF total under CC', async ({ page }) => {
    await page.goto(`http://localhost/rfq/perfil/re_quote/${fixture.rfqId}`);
    await expect(page.locator('#services_table')).toBeVisible();

    await page.selectOption('select[name="services_payment_term"]', 'Net 30/CC');
    await page.waitForTimeout(300);

    const liveText = await page.locator('#total_service').textContent();
    const liveTotal = parseFloat(liveText.replace('$', '').trim());

    await Promise.all([
      page.waitForNavigation(),
      page.click('button[name="save_re_quote"]'),
    ]);

    const conn = await getConnection();
    try {
      const [[row]] = await conn.query(
        `SELECT SUM(rs.total_price) AS total FROM re_quote_services rs
         JOIN re_quotes rq ON rq.id = rs.id_re_quote WHERE rq.id_rfq = ?`,
        [fixture.rfqId]
      );
      const savedTotal = parseFloat(row.total);
      // Bug: live preview used a 1.0299 multiplier, the server persisted with 1.03 --
      // these silently diverged by a few cents (156 * 1.0299 = 160.66 vs 156 * 1.03 = 160.68).
      expect(liveTotal).toBeCloseTo(savedTotal, 2);
      expect(savedTotal).toBeCloseTo(160.68, 2);
    } finally {
      await conn.end();
    }
  });
});
