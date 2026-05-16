const { test: setup } = require('@playwright/test');
const path = require('path');

const authFile = path.join(__dirname, '../.auth/session.json');

setup('authenticate', async ({ page }) => {
  await page.goto('http://localhost/rfq/');
  await page.fill('[name="nombre_usuario"]', 'pw_test_user');
  await page.fill('[name="password"]', 'PlaywrightTest123!');
  await page.click('[name="iniciar_sesion"]');
  await page.waitForURL('**/perfil/**');
  await page.context().storageState({ path: authFile });
});
