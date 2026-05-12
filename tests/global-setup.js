const { getConnection } = require('./helpers/db');
const fs = require('fs');
const path = require('path');

module.exports = async function globalSetup() {
  const conn = await getConnection();
  try {
    // Password hash generated via PHP: password_hash('PlaywrightTest123!', PASSWORD_DEFAULT)
    const pwHash = '$2y$12$hCPDcg.RrUl1jQderC0ZjuWGdIwBEgDWgpg1ReAXH80/Rh.K/jHl.';
    await conn.execute(
      `INSERT INTO usuarios (nombre_usuario, password, nombres, apellidos, cargo, email, status, hash_recover_email)
       VALUES (?, ?, 'Playwright', 'Test', 'QA', 'pw_test@test.local', 1, '')
       ON DUPLICATE KEY UPDATE password = VALUES(password), status = 1`,
      ['pw_test_user', pwHash]
    );
    const [userRows] = await conn.execute(
      'SELECT id FROM usuarios WHERE nombre_usuario = ?', ['pw_test_user']
    );
    const userId = userRows[0].id;

    // Create test quote
    const [rfqResult] = await conn.execute(
      `INSERT INTO rfq (id_usuario, usuario_designado, canal, email_code, type_of_bid, issue_date, end_date,
        status, completado, award, payment_terms, address, ship_to, ship_via, taxes, profit, additional,
        shipping_cost, shipping, fullfillment, contract_number, deleted)
       VALUES (?, ?, 'GSA-Buy', 'TEST001', 'Services', '2026-01-01', '2026-12-31',
        0, 0, 0, 'Net 30', '123 Test St', 'Test Ship', 'UPS', 0, 10, '', 0, '', 0, '', 0)`,
      [userId, userId]
    );
    const rfqId = rfqResult.insertId;

    // Create test item
    const [itemResult] = await conn.execute(
      `INSERT INTO item (id_rfq, id_usuario, provider_menor, brand, brand_project, part_number,
        part_number_project, description, description_project, quantity, unit_price, total_price,
        comments, website, additional)
       VALUES (?, ?, 0, 'TestBrand', 'TestBrand', 'PN-001', 'PN-001', 'Test Item Description',
        'Test Item Description', 2, 100.00, 200.00, '', 'http://example.com', '')`,
      [rfqId, userId]
    );
    const itemId = itemResult.insertId;

    // Create test subitem
    const [subitemResult] = await conn.execute(
      `INSERT INTO subitems (id_item, provider_menor, brand, brand_project, part_number,
        part_number_project, description, description_project, quantity, unit_price, total_price,
        comments, website, additional)
       VALUES (?, 0, 'SubBrand', 'SubBrand', 'SPN-001', 'SPN-001', 'Test Subitem Description',
        'Test Subitem Description', 1, 50.00, 50.00, '', 'http://example.com', '')`,
      [itemId]
    );
    const subitemId = subitemResult.insertId;

    // Create test provider for item
    const [providerResult] = await conn.execute(
      `INSERT INTO provider (id_item, provider, price) VALUES (?, 'TestProvider Inc', 90.00)`,
      [itemId]
    );
    const providerId = providerResult.insertId;

    // Create test provider for subitem
    const [providerSubitemResult] = await conn.execute(
      `INSERT INTO provider_subitems (id_subitem, provider, price) VALUES (?, 'SubProvider Inc', 45.00)`,
      [subitemId]
    );
    const providerSubitemId = providerSubitemResult.insertId;

    // Create test service
    const [serviceResult] = await conn.execute(
      `INSERT INTO services (id_rfq, description, quantity, unit_price, total_price)
       VALUES (?, 'Test Service Description', 3, 25.00, 75.00)`,
      [rfqId]
    );
    const serviceId = serviceResult.insertId;

    const fixtures = { userId, rfqId, itemId, subitemId, providerId, providerSubitemId, serviceId };
    fs.writeFileSync(
      path.join(__dirname, '.fixtures.json'),
      JSON.stringify(fixtures, null, 2)
    );
    console.log('Global setup complete. Fixtures:', fixtures);
  } finally {
    await conn.end();
  }
};
