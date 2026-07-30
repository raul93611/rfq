const { test, expect } = require('@playwright/test');
const fs = require('fs');
const path = require('path');

function fixtures() {
  return JSON.parse(fs.readFileSync(path.join(__dirname, '../.fixtures.json'), 'utf-8'));
}

// In-memory file — setInputFiles uses `name` verbatim as the uploaded filename,
// independent of any file on disk, so the exact filename asserted against later
// (e.g. the download link's href) is guaranteed rather than derived from a local path.
function uploadFile(name) {
  return { name, mimeType: 'application/pdf', buffer: Buffer.from('Playwright test upload contents for ' + name) };
}

test.describe('Documents Drawer Tab', () => {
  let rfqId;

  test.beforeEach(async ({ page }) => {
    ({ rfqId } = fixtures());
    await page.goto(`http://localhost/rfq/perfil/quote/editar_cotizacion/${rfqId}`);
    await page.waitForSelector('#qed-open-documents');
  });

  test('Documents status card renders with a live file count and no inline block on the page itself', async ({ page }) => {
    await expect(page.locator('#qed-documents-status-value')).toContainText('attached');
    // The old inline Documents block (bare <input id="archivos_ejemplo">) is gone.
    await expect(page.locator('#archivos_ejemplo')).toHaveCount(0);
  });

  test('clicking Documents opens the drawer on the Documents tab', async ({ page }) => {
    await page.click('#qed-open-documents');
    await expect(page.locator('#qed-drawer')).toHaveClass(/is-open/);
    await expect(page.locator('#qed-tab-documents')).toHaveClass(/is-active/);
    await expect(page.locator('[data-tab-panel="documents"]')).not.toHaveClass(/is-hidden/);
  });

  test('switching to Checklist/Information and back leaves the Documents tab panel mounted', async ({ page }) => {
    await page.click('#qed-open-documents');
    await page.click('#qed-tab-checklist');
    await page.click('#qed-tab-documents');
    await expect(page.locator('[data-tab-panel="documents"]')).not.toHaveClass(/is-hidden/);
    await expect(page.locator('#qed-documents-widget')).toBeVisible();
  });

  test('dropping a file uploads it via XHR, shows it as done, and bumps the live count', async ({ page }) => {
    await page.click('#qed-open-documents');
    const before = await page.locator('#qed-tab-documents-count').textContent();

    await page.setInputFiles('#qed-documents-widget .doc-dropzone input[type=file]', uploadFile('upload-test.pdf'));

    const row = page.locator('.doc-file-row', { hasText: 'upload-test.pdf' });
    await expect(row).toBeVisible();
    await expect(row.locator('.doc-file-meta.is-error-text')).toHaveCount(0);
    await expect(page.locator('[data-testid="doc-file-delete"]').first()).toBeVisible({ timeout: 10000 });

    const downloadLink = row.locator('[data-testid="doc-file-download"]');
    await expect(downloadLink).toBeVisible();
    await expect(downloadLink).toHaveAttribute('href', /\/rfq\/documentos\/\d+\/upload-test\.pdf$/);

    await expect(page.locator('#qed-tab-documents-count')).not.toHaveText(before);
    await expect(page.locator('#qed-documents-status-value')).toContainText('attached');
  });

  test('deleting a file shows an inline confirm; confirming removes it via AJAX with no reload', async ({ page }) => {
    await page.click('#qed-open-documents');
    await page.setInputFiles('#qed-documents-widget .doc-dropzone input[type=file]', uploadFile('to-delete.pdf'));

    const row = page.locator('.doc-file-row', { hasText: 'to-delete.pdf' });
    await row.locator('[data-testid="doc-file-delete"]').click();
    await expect(row.locator('[data-testid="doc-file-confirm"]')).toBeVisible();

    await Promise.all([
      page.waitForResponse((res) => res.url().includes('/quote/delete_document/') && res.status() === 200),
      row.locator('.doc-file-confirm-delete').click(),
    ]);
    await expect(page.locator('.doc-file-row', { hasText: 'to-delete.pdf' })).toHaveCount(0);
    expect(page.url()).toContain(`editar_cotizacion/${rfqId}`);
  });

  test('Download All zips the attached files and returns a working download URL', async ({ page, request }) => {
    await page.click('#qed-open-documents');
    await page.setInputFiles('#qed-documents-widget .doc-dropzone input[type=file]', uploadFile('zip-me.pdf'));
    await page.waitForSelector('[data-testid="doc-file-delete"]');

    const [response] = await Promise.all([
      page.waitForResponse((res) => res.url().includes('/quote/download_all')),
      page.click('#qed-download-all-btn'),
    ]);
    expect(response.status()).toBe(200);
    const body = await response.json();
    expect(body.downloadUrl).toMatch(new RegExp(`^/rfq/tmp/zips/files_${rfqId}\\.zip$`));

    const zipRes = await request.get('http://localhost' + body.downloadUrl);
    expect(zipRes.ok()).toBe(true);
  });
});

test.describe('Documents widget on the New Quote page (deferred mode)', () => {
  test('files are staged client-side with no upload request until the form is submitted', async ({ page }) => {
    await page.goto('http://localhost/rfq/perfil/quote/nuevo');
    await page.waitForSelector('#doc-widget-create');

    let uploadRequestSeen = false;
    page.on('request', (req) => { if (req.url().includes('/quote/load_img/')) uploadRequestSeen = true; });

    await page.setInputFiles('#doc-widget-create .doc-dropzone input[type=file]', uploadFile('staged-test.pdf'));

    await expect(page.locator('#doc-widget-create .doc-file-row', { hasText: 'staged-test.pdf' })).toBeVisible();
    expect(uploadRequestSeen).toBe(false);
  });
});
