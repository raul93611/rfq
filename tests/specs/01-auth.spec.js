const { test, expect } = require('@playwright/test');

// These tests run WITHOUT saved auth to test login/logout explicitly
test.use({ storageState: { cookies: [], origins: [] } });

test.describe('Authentication', () => {
  test('login page renders correctly', async ({ page }) => {
    await page.goto('http://localhost/rfq/');
    await expect(page.locator('[name="nombre_usuario"]')).toBeVisible();
    await expect(page.locator('[name="password"]')).toBeVisible();
    await expect(page.locator('[name="iniciar_sesion"]')).toBeVisible();
  });

  test('redirects unauthenticated users to login', async ({ page }) => {
    await page.goto('http://localhost/rfq/perfil/charts');
    await expect(page).toHaveURL(/rfq\/?$/);
  });

  test('shows error on invalid credentials', async ({ page }) => {
    await page.goto('http://localhost/rfq/');
    await page.fill('[name="nombre_usuario"]', 'wrong_user');
    await page.fill('[name="password"]', 'wrong_password');
    await page.click('[name="iniciar_sesion"]');
    // Should stay on login page and show error
    await expect(page).toHaveURL(/rfq\/?$/);
    await expect(page).not.toHaveURL(/perfil/);
  });

  test('successful login redirects to dashboard', async ({ page }) => {
    await page.goto('http://localhost/rfq/');
    await page.fill('[name="nombre_usuario"]', 'pw_test_user');
    await page.fill('[name="password"]', 'PlaywrightTest123!');
    await page.click('[name="iniciar_sesion"]');
    await page.waitForURL('**/perfil/**');
    await expect(page.url()).toContain('/perfil/');
  });

  test('logout clears session', async ({ page }) => {
    await page.goto('http://localhost/rfq/');
    await page.fill('[name="nombre_usuario"]', 'pw_test_user');
    await page.fill('[name="password"]', 'PlaywrightTest123!');
    await page.click('[name="iniciar_sesion"]');
    await page.waitForURL('**/perfil/**');
    await page.goto('http://localhost/rfq/logout');
    await expect(page).toHaveURL(/rfq\/?$/);
  });
});
