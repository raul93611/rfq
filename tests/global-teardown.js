const { getConnection } = require('./helpers/db');
const fs = require('fs');
const path = require('path');

module.exports = async function globalTeardown() {
  const fixturesPath = path.join(__dirname, '.fixtures.json');
  if (!fs.existsSync(fixturesPath)) return;

  const fixtures = JSON.parse(fs.readFileSync(fixturesPath, 'utf-8'));
  const { rfqId, userId } = fixtures;

  const conn = await getConnection();
  try {
    if (rfqId) {
      // Delete all data tied to the test quote
      const [items] = await conn.execute('SELECT id FROM item WHERE id_rfq = ?', [rfqId]);
      for (const item of items) {
        const [subitems] = await conn.execute('SELECT id FROM subitems WHERE id_item = ?', [item.id]);
        for (const sub of subitems) {
          await conn.execute('DELETE FROM provider_subitems WHERE id_subitem = ?', [sub.id]);
        }
        await conn.execute('DELETE FROM subitems WHERE id_item = ?', [item.id]);
        await conn.execute('DELETE FROM provider WHERE id_item = ?', [item.id]);
      }
      await conn.execute('DELETE FROM item WHERE id_rfq = ?', [rfqId]);
      await conn.execute('DELETE FROM services WHERE id_rfq = ?', [rfqId]);
      // Delete audit trails before rfq (FK constraint)
      await conn.execute('DELETE FROM audit_trails WHERE id_rfq = ?', [rfqId]);
      await conn.execute('DELETE FROM rfq WHERE id = ?', [rfqId]);
    }
    // Delete user last — item table has FK to usuarios
    if (userId) {
      // Null out any remaining rfq references first
      await conn.execute('UPDATE rfq SET id_usuario = 1, usuario_designado = 1 WHERE id_usuario = ? OR usuario_designado = ?', [userId, userId]);
      await conn.execute('UPDATE item SET id_usuario = 1 WHERE id_usuario = ?', [userId]);
      await conn.execute('DELETE FROM usuarios WHERE id = ?', [userId]);
    }
    fs.unlinkSync(fixturesPath);
    console.log('Global teardown complete.');
  } finally {
    await conn.end();
  }
};
