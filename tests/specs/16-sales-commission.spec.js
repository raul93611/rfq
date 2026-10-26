const { test, expect } = require('@playwright/test');
const { getConnection, query } = require('../helpers/db');

// Automatic Sales Commission — Invoice modal (read-only breakdown, no dropdown, no override) and the
// Fulfillment commission strip. The rules themselves (every branch + boundaries, report/Excel/
// projection agreement) are covered in tests/php/sales_commission_calculator_test.php.

const created = [];

/** A fulfilled quote that satisfies every Invoice requirement, with a re-quote. */
async function invoiceReadyQuote({ type = 'AV - Installation', price = 100000, fulfillment = 92000, requote = 90000, invoiced = false, legacy = null, withItem = false } = {}) {
  const [{ id: userId }] = await query("SELECT id FROM usuarios WHERE nombre_usuario = 'pw_test_user'");
  const conn = await getConnection();
  try {
    const [r] = await conn.execute(
      `INSERT INTO rfq (id_usuario, usuario_designado, canal, email_code, type_of_bid, issue_date, end_date,
        status, completado, award, payment_terms, address, ship_to, ship_via, taxes, profit, additional,
        shipping_cost, shipping, fullfillment, contract_number, services_payment_term, total_price, total_cost,
        total_fulfillment, fulfillment_profit, type_of_contract, city, zip_code, client, set_side, poc, co,
        estimated_delivery_date, file_document, sync_to_sheet, invoice, invoice_date, fulfillment_pending, name, deleted)
       VALUES (?, ?, 'GSA-Buy', ?, 'RFQ', '2026-01-01', '2026-12-31', 1, 1, 1, 'Net 30', '123 Test St', 'Test Ship',
        'UPS', 0, 0, '', 0, '', 1, 'SC-E2E', 'Net 30', ?, ?, ?, ?, ?, 'Miami', '33101', 'Test Agency', 'None',
        'Pat POC', 'Casey CO', '2026-12-01', 'W9', 0, ?, ?, 0, 'Commission e2e', 0)`,
      [userId, userId, `SC-${Date.now()}-${created.length}`, price, requote, fulfillment, price - fulfillment, type,
        invoiced ? 1 : 0, invoiced ? '2026-10-26' : null]
    );
    await conn.execute(
      `INSERT INTO re_quotes (id_rfq, total_cost, total_price, payment_terms, taxes, profit, shipping_cost, shipping)
       VALUES (?, ?, ?, 'Net 30', 0, 0, 0, '')`,
      [r.insertId, requote, price]
    );
    if (withItem) {
      // Saving the edit form re-posts total_price from the items table (cheapest provider × profit %,
      // and rfq.profit is 0 here), so a quote that is actually saved needs a real item + provider
      // that add up to its price.
      const [item] = await conn.execute(
        `INSERT INTO item (id_rfq, id_usuario, provider_menor, brand, brand_project, part_number, part_number_project,
          description, description_project, quantity, unit_price, total_price, comments, website, additional)
         VALUES (?, ?, 0, 'Brand', 'Brand', 'PN-SC', 'PN-SC', 'Commission item', 'Commission item', 1, ?, ?, '', '', '')`,
        [r.insertId, userId, price, price]
      );
      await conn.execute('INSERT INTO provider (id_item, provider, price) VALUES (?, ?, ?)', [item.insertId, 'SC Provider', price]);
    }
    if (legacy) {
      await conn.execute('UPDATE rfq SET sales_commission = ?, sales_commission_comment = ? WHERE id = ?', [legacy.string, legacy.comment, r.insertId]);
    }
    created.push(r.insertId);
    return r.insertId;
  } finally {
    await conn.end();
  }
}

test.afterAll(async () => {
  const conn = await getConnection();
  try {
    for (const id of created) {
      await conn.execute('DELETE FROM audit_trails WHERE id_rfq = ?', [id]);
      await conn.execute('DELETE FROM provider WHERE id_item IN (SELECT id FROM item WHERE id_rfq = ?)', [id]);
      await conn.execute('DELETE FROM item WHERE id_rfq = ?', [id]);
      await conn.execute('DELETE FROM re_quotes WHERE id_rfq = ?', [id]);
      await conn.execute('DELETE FROM rfq WHERE id = ?', [id]);
    }
  } finally {
    await conn.end();
  }
});

async function openInvoiceModal(page, id) {
  await page.goto(`http://localhost/rfq/perfil/quote/editar_cotizacion/${id}`);
  // The Invoice checkbox is a Bootstrap custom-control: the input is visually hidden, click its label.
  await page.locator('label[for="invoice"]').click();
  await expect(page.locator('#sales_commission_modal')).toBeVisible();
}

test.describe('Invoice modal — read-only commission breakdown', () => {
  test('shows the breakdown with the smaller profit marked Used, and no dropdown', async ({ page }) => {
    const id = await invoiceReadyQuote({ fulfillment: 92000, requote: 90000 }); // fulfillment 8,000 < re-quote 10,000
    await openInvoiceModal(page, id);

    const card = page.locator('[data-testid="ic-card"]');
    await expect(card).toBeVisible();
    await expect(page.locator('[data-testid="ic-amount"]')).toHaveText('$240.00');
    await expect(page.locator('[data-testid="ic-row-fulfillment"] [data-testid="ic-used"]')).toBeVisible();
    await expect(page.locator('[data-testid="ic-row-requote"] [data-testid="ic-used"]')).toHaveCount(0);
    await expect(page.locator('[data-testid="ic-type"]')).toHaveText('AV - Installation');
    await expect(page.locator('[data-testid="ic-status"]')).toHaveText('Eligible');
    await expect(page.locator('#sales_commission')).toHaveCount(0); // dropdown is gone
    await expect(page.locator('[data-testid="ic-save"]')).toBeEnabled();
  });

  test('Save is disabled while the breakdown loads', async ({ page }) => {
    const id = await invoiceReadyQuote();
    await page.route('**/quote/commission_preview/**', async (route) => {
      await new Promise((r) => setTimeout(r, 600));
      await route.continue();
    });
    await page.goto(`http://localhost/rfq/perfil/quote/editar_cotizacion/${id}`);
    await page.locator('label[for="invoice"]').click();
    await expect(page.locator('[data-testid="ic-loading"]')).toBeVisible();
    await expect(page.locator('[data-testid="ic-save"]')).toBeDisabled();
    await expect(page.locator('[data-testid="ic-card"]')).toBeVisible();
    await expect(page.locator('[data-testid="ic-save"]')).toBeEnabled();
  });

  test('re-quote profit smaller → marks the re-quote row Used', async ({ page }) => {
    const id = await invoiceReadyQuote({ fulfillment: 90000, requote: 92000 }); // re-quote 8,000 < fulfillment 10,000
    await openInvoiceModal(page, id);
    await expect(page.locator('[data-testid="ic-amount"]')).toHaveText('$240.00');
    await expect(page.locator('[data-testid="ic-row-requote"] [data-testid="ic-used"]')).toBeVisible();
  });

  test('under 6% → $0.00 with the percentage in the status line', async ({ page }) => {
    const id = await invoiceReadyQuote({ fulfillment: 95800, requote: 95000 }); // 4.20%
    await openInvoiceModal(page, id);
    await expect(page.locator('[data-testid="ic-amount"]')).toHaveText('$0.00');
    await expect(page.locator('[data-testid="ic-status"]')).toHaveText('Under 6% (4.20%)');
    await expect(page.locator('[data-testid="ic-used"]')).toHaveCount(0);
  });

  test('Moving and Logistics → not eligible whatever the profit', async ({ page }) => {
    const id = await invoiceReadyQuote({ type: 'Moving and Logistics' });
    await openInvoiceModal(page, id);
    await expect(page.locator('[data-testid="ic-amount"]')).toHaveText('$0.00');
    await expect(page.locator('[data-testid="ic-status"]')).toHaveText('Not eligible — Moving and Logistics');
  });

  test('Cancel closes the modal and unticks Invoice', async ({ page }) => {
    const id = await invoiceReadyQuote();
    await openInvoiceModal(page, id);
    await page.click('#sales_commission_cancel');
    await expect(page.locator('#sales_commission_modal')).toBeHidden();
    await expect(page.locator('#invoice')).not.toBeChecked();
  });

  test('a failed preview shows a retry link and keeps Save disabled', async ({ page }) => {
    const id = await invoiceReadyQuote();
    let fail = true;
    await page.route('**/quote/commission_preview/**', (route) => (fail ? route.fulfill({ status: 500, body: 'x' }) : route.continue()));
    await page.goto(`http://localhost/rfq/perfil/quote/editar_cotizacion/${id}`);
    await page.locator('label[for="invoice"]').click();
    await expect(page.locator('[data-testid="ic-error"]')).toBeVisible();
    await expect(page.locator('[data-testid="ic-save"]')).toBeDisabled();
    fail = false;
    await page.click('#ic-retry');
    await expect(page.locator('[data-testid="ic-card"]')).toBeVisible();
    await expect(page.locator('[data-testid="ic-save"]')).toBeEnabled();
  });
});

test.describe('Invoicing — server calculates and freezes the commission', () => {
  test('Save stores the server-calculated amount (ignoring tampered fields), then the fulfillment strip shows it', async ({ page }) => {
    const id = await invoiceReadyQuote({ fulfillment: 92000, requote: 90000, withItem: true });
    await openInvoiceModal(page, id);
    await expect(page.locator('[data-testid="ic-card"]')).toBeVisible();

    // A client can no longer pick or post a commission: the server ignores anything it sends.
    await page.evaluate(() => {
      const form = document.getElementById('form_edited_quote');
      for (const [name, value] of [['sales_commission', 'Same commission'], ['sales_commission_amount', '99999']]) {
        const input = document.createElement('input');
        input.type = 'hidden'; input.name = name; input.value = value;
        form.appendChild(input);
      }
    });
    await page.fill('#sales_commission_comment', 'Paid with <b>November</b> payroll & bonus');
    await page.click('[data-testid="ic-save"]');
    await page.waitForURL(/invoice_quotes/);

    const [row] = await query('SELECT invoice, sales_commission, sales_commission_amount, sales_commission_basis_profit, sales_commission_fulfillment_pct, sales_commission_reason, sales_commission_comment FROM rfq WHERE id = ?', [id]);
    expect(row.invoice).toBe(1);
    expect(row.sales_commission).toBe('Other commission');
    expect(row.sales_commission_amount).toBe('240.00');
    expect(row.sales_commission_basis_profit).toBe('8000.00');
    expect(row.sales_commission_fulfillment_pct).toBe('8.00');
    expect(row.sales_commission_reason).toBe('fulfillment_smaller');
    expect(row.sales_commission_comment).toBe('Paid with <b>November</b> payroll & bonus');

    const [audit] = await query("SELECT audit_trail FROM audit_trails WHERE id_rfq = ? AND audit_trail LIKE '%Invoice%' ORDER BY id DESC LIMIT 1", [id]);
    expect(audit.audit_trail).toContain('<b>$240.00</b>');
    expect(audit.audit_trail).toContain('fulfillment profit $8,000.00');

    // Later cost edits don't move the frozen amount.
    await query('UPDATE rfq SET total_fulfillment = 50000 WHERE id = ?', [id]);
    await page.goto(`http://localhost/rfq/perfil/fulfillment/${id}`);
    const strip = page.locator('[data-testid="cs-strip"]');
    await expect(strip).toBeVisible();
    await expect(page.locator('[data-testid="cs-amount"]')).toHaveText('$240.00');
    await expect(page.locator('[data-testid="cs-reason"]')).toContainText('Fulfillment profit was smaller');
    await expect(page.locator('[data-testid="cs-type"]')).toHaveText('AV - Installation');
    // Comment is escaped, not interpreted.
    await expect(page.locator('[data-testid="cs-comment"]')).toHaveText('Paid with <b>November</b> payroll & bonus');
  });
});

test.describe('Fulfillment commission strip', () => {
  test('not-eligible quote shows $0.00 and the reason', async ({ page }) => {
    const id = await invoiceReadyQuote({ type: 'Professional Services', invoiced: true });
    await query("UPDATE rfq SET sales_commission = 'No commission', sales_commission_amount = 0, sales_commission_reason = 'not_eligible_type' WHERE id = ?", [id]);
    await page.goto(`http://localhost/rfq/perfil/fulfillment/${id}`);
    await expect(page.locator('[data-testid="cs-amount"]')).toHaveText('$0.00');
    await expect(page.locator('[data-testid="cs-reason"]')).toContainText('Not eligible — Professional Services');
    await expect(page.locator('[data-testid="cs-comment"]')).toHaveText('—');
  });

  test('under-threshold quote shows the saved percentage', async ({ page }) => {
    const id = await invoiceReadyQuote({ invoiced: true });
    await query("UPDATE rfq SET sales_commission = 'No commission', sales_commission_amount = 0, sales_commission_fulfillment_pct = 4.20, sales_commission_reason = 'under_threshold' WHERE id = ?", [id]);
    await page.goto(`http://localhost/rfq/perfil/fulfillment/${id}`);
    await expect(page.locator('[data-testid="cs-reason"]')).toContainText('Under 6% (4.20%)');
  });

  test('legacy quote (no saved amount) keeps today\'s card', async ({ page }) => {
    const id = await invoiceReadyQuote({ invoiced: true, legacy: { string: 'Other commission', comment: 'Old comment' } });
    await page.goto(`http://localhost/rfq/perfil/fulfillment/${id}`);
    await expect(page.getByText('Real Sales Commission')).toBeVisible();
    await expect(page.locator('[data-testid="cs-strip"]')).toHaveCount(0);
    await expect(page.getByText('Other commission')).toBeVisible();
  });
});
