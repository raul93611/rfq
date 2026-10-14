const { test, expect } = require('@playwright/test');
const fs = require('fs');
const path = require('path');
const { query } = require('../helpers/db');

// Partner Opportunity Sync — UI behaviour: Solicitation # / Share toggle validation, per-file
// share in the Documents drawer, and the admin Partner Integration card + queues.
// PHP-level rules (matching, conflict, echo, removal) live in tests/php/partner_opportunity_sync_test.php.

function fixtures() {
  return JSON.parse(fs.readFileSync(path.join(__dirname, '../.fixtures.json'), 'utf-8'));
}

const AUTH_FILE = path.join(__dirname, '../.auth/session.json');

async function resetPartnerState(rfqId) {
  await query(
    `UPDATE rfq SET share_with_partner = 0, solicitation_number = NULL, solicitation_key = NULL, partner_record_id = NULL,
       partner_linked_at = NULL, partner_shared_updated_at = NULL, partner_payload_hash = NULL, partner_removed_at = NULL,
       partner_needs_assignment = 0 WHERE id = ?`, [rfqId]);
  await query('DELETE FROM partner_documents WHERE id_rfq = ?', [rfqId]);
  await query('DELETE FROM partner_webhook_outbox WHERE id_rfq = ?', [rfqId]);
}

// The custom checkbox hides the native input, so click its label like a user would.
const shareToggle = (page) => page.locator('label.sync-check[for="share_with_partner"]');

// Choose an owner different from the one currently selected (positions shift when the current
// owner is not in the RFQ-role list and is injected as the first option).
async function chooseOtherOwner(page, selector) {
  const other = await page.locator(selector).evaluate((sel) => {
    const o = Array.from(sel.options).find((x) => x.value !== sel.value);
    return o ? o.value : null;
  });
  await page.selectOption(selector, other);
}

test.describe('Partner sync — New Quote form', () => {
  test('Solicitation # is optional until Share with partner is on, then requires a value inline', async ({ page }) => {
    await page.goto('http://localhost/rfq/perfil/quote/nuevo');
    const sol = page.locator('#solicitation_number');
    await expect(page.locator('#share_with_partner')).not.toBeChecked();
    await expect(page.locator('#pp-sol-req')).toBeHidden();

    // Sharing off: blank is fine and shows no error.
    await sol.focus();
    await page.locator('#email_code').focus();
    await expect(page.locator('[data-testid="solicitation-error"]')).toBeHidden();

    // Sharing on: the field is now required; leaving it blank shows the inline error.
    await shareToggle(page).click();
    await expect(page.locator('#pp-sol-req')).toBeVisible();
    await sol.focus();
    await page.locator('#email_code').focus();
    await expect(page.locator('[data-testid="solicitation-error"]')).toBeVisible();
    await expect(page.locator('[data-testid="solicitation-error"]')).toContainText('Solicitation # is required to share with the partner.');

    await sol.fill('FA8307-26-Q-0021');
    await expect(page.locator('[data-testid="solicitation-error"]')).toBeHidden();
  });
});

test.describe('Partner sync — Information drawer + header badges', () => {
  let rfqId;
  test.beforeEach(async ({ page }) => {
    ({ rfqId } = fixtures());
    await resetPartnerState(rfqId);
    await page.goto(`http://localhost/rfq/perfil/quote/editar_cotizacion/${rfqId}`);
    await page.waitForSelector('#qed-open-information');
  });
  test.afterAll(async () => { await resetPartnerState(fixtures().rfqId); });

  test('sharing on with a blank Solicitation # is blocked inline and sends nothing', async ({ page }) => {
    await page.click('#qed-open-information');
    await shareToggle(page).click();
    await page.fill('#internal_due_date', '02/15/2026');
    let saved = false;
    page.on('request', (r) => { if (r.url().includes('/quote/save_information/')) saved = true; });
    await page.click('#qed-information-save-btn');
    await expect(page.locator('[data-testid="solicitation-error"]')).toBeVisible();
    expect(saved).toBe(false);
    await expect(page.locator('#qed-drawer')).toHaveClass(/is-open/);
  });

  test('filled + sharing on saves, repaints the badge and info card live, and persists across reload', async ({ page }) => {
    const sol = `PW-${Date.now()}`;
    await expect(page.locator('[data-testid="partner-badges"]')).toBeHidden();

    await page.click('#qed-open-information');
    await shareToggle(page).click();
    await page.fill('#solicitation_number', sol);
    await page.fill('#internal_due_date', '02/15/2026');
    await Promise.all([
      page.waitForResponse((res) => res.url().includes('/quote/save_information/') && res.status() === 200),
      page.click('#qed-information-save-btn'),
    ]);
    await expect(page.locator('#qed-information-save-pill')).toHaveClass(/is-shown/);

    // Live repaint, no reload: shared but not yet matched by the partner.
    await expect(page.locator('[data-testid="badge-waiting"]')).toBeVisible();
    await expect(page.locator('#qed-sol-cell')).toContainText(sol);

    const [row] = await query('SELECT share_with_partner, solicitation_key FROM rfq WHERE id = ?', [rfqId]);
    expect(row.share_with_partner).toBe(1);
    expect(row.solicitation_key).toBe(sol.toUpperCase());
    const outbox = await query("SELECT COUNT(*) AS n FROM partner_webhook_outbox WHERE id_rfq = ? AND event = 'opportunity.updated'", [rfqId]);
    expect(outbox[0].n).toBe(1);

    await page.reload();
    await expect(page.locator('[data-testid="badge-waiting"]')).toBeVisible();
    await page.click('#qed-open-information');
    await expect(page.locator('#solicitation_number')).toHaveValue(sol);
    await expect(page.locator('#share_with_partner')).toBeChecked();
  });

  test('Needs assignment: the badge opens the owner hint; saving unchanged keeps it, choosing an owner clears it', async ({ page }) => {
    const { userId } = fixtures();
    await query(`UPDATE rfq SET share_with_partner = 1, solicitation_number = 'PW-ASSIGN', solicitation_key = 'PW-ASSIGN',
      partner_record_id = 'P-ASSIGN', partner_linked_at = UTC_TIMESTAMP(), partner_shared_updated_at = UTC_TIMESTAMP(),
      partner_needs_assignment = 1, usuario_designado = ? WHERE id = ?`, [userId, rfqId]);
    await page.reload();
    const badge = page.locator('[data-testid="badge-needs-assignment"]');
    await expect(badge).toBeVisible();
    await expect(page.locator('[data-testid="badge-linked"]')).toBeVisible();

    // The badge opens the Information drawer on the flagged owner field.
    await page.click('#pp-assign-badge');
    await expect(page.locator('#qed-drawer')).toHaveClass(/is-open/);
    // The owner field exists on both the Checklist and Information tabs; the badge points at Information's.
    await expect(page.locator('#information_form [data-pp-owner]')).toHaveClass(/is-hl/);
    await expect(page.locator('#information_form [data-pp-owner] .pp-hint')).toContainText('Choose who should own this quote');

    // Saving without choosing must neither clear the flag nor hand the quote to the first user in the list.
    await page.fill('#internal_due_date', '02/15/2026');
    await Promise.all([
      page.waitForResponse((res) => res.url().includes('/quote/save_information/') && res.status() === 200),
      page.click('#qed-information-save-btn'),
    ]);
    let [row] = await query('SELECT partner_needs_assignment AS n, usuario_designado AS u FROM rfq WHERE id = ?', [rfqId]);
    expect(row.n).toBe(1);
    expect(row.u).toBe(userId);
    await expect(badge).toBeVisible();

    // Choosing a different owner resolves it, live.
    await chooseOtherOwner(page, '#information_form #usuario_designado');
    await Promise.all([
      page.waitForResponse((res) => res.url().includes('/quote/save_information/') && res.status() === 200),
      page.click('#qed-information-save-btn'),
    ]);
    [row] = await query('SELECT partner_needs_assignment AS n, usuario_designado AS u FROM rfq WHERE id = ?', [rfqId]);
    expect(row.n).toBe(0);
    expect(row.u).not.toBe(userId);
    await expect(page.locator('[data-testid="badge-needs-assignment"]')).toHaveCount(0);
    await expect(page.locator('[data-testid="badge-linked"]')).toBeVisible();
  });

  test('Needs assignment also clears when the owner is chosen on the Checklist tab', async ({ page }) => {
    const { userId } = fixtures();
    await query(`UPDATE rfq SET share_with_partner = 1, solicitation_number = 'PW-ASSIGN2', solicitation_key = 'PW-ASSIGN2',
      partner_record_id = 'P-ASSIGN2', partner_linked_at = UTC_TIMESTAMP(), partner_shared_updated_at = UTC_TIMESTAMP(),
      partner_needs_assignment = 1, usuario_designado = ? WHERE id = ?`, [userId, rfqId]);
    await page.reload();
    await expect(page.locator('[data-testid="badge-needs-assignment"]')).toBeVisible();

    await page.click('#qed-open-checklist');
    await chooseOtherOwner(page, '#cl_usuario_designado');
    await Promise.all([
      page.waitForResponse((res) => res.url().includes('/quote/save_checklist/') && res.status() === 200),
      page.click('#qed-checklist-save-btn'),
    ]);
    const [row] = await query('SELECT partner_needs_assignment AS n, usuario_designado AS u FROM rfq WHERE id = ?', [rfqId]);
    expect(row.n).toBe(0);
    expect(row.u).not.toBe(userId);
    await expect(page.locator('[data-testid="badge-needs-assignment"]')).toHaveCount(0);
  });

  test('turning sharing off removes the badge and queues a removed notice', async ({ page }) => {
    await query("UPDATE rfq SET share_with_partner = 1, solicitation_number = 'PW-OFF', solicitation_key = 'PW-OFF', partner_shared_updated_at = UTC_TIMESTAMP() WHERE id = ?", [rfqId]);
    await page.reload();
    await expect(page.locator('[data-testid="badge-waiting"]')).toBeVisible();

    await page.click('#qed-open-information');
    await shareToggle(page).click();
    await page.fill('#internal_due_date', '02/15/2026');
    await Promise.all([
      page.waitForResponse((res) => res.url().includes('/quote/save_information/') && res.status() === 200),
      page.click('#qed-information-save-btn'),
    ]);
    await expect(page.locator('[data-testid="partner-badges"]')).toBeHidden();
    const removed = await query("SELECT COUNT(*) AS n FROM partner_webhook_outbox WHERE id_rfq = ? AND event = 'opportunity.removed'", [rfqId]);
    expect(removed[0].n).toBe(1);
  });
});

test.describe('Partner sync — Documents drawer per-file share', () => {
  // Files are created through the app itself (its upload endpoint + its real checklist-PDF
  // generator), never by writing to documentos/ from the host: that folder is owned by the web
  // server user once any spec uploads to it, and writing there as the host user fails with EACCES.
  let rfqId;
  const upload = 'pw-share-me.pdf';
  const generated = 'TEST001(checklist).pdf'; // "<code>(checklist).pdf" — the fixture quote's code is TEST001

  test.beforeAll(() => { ({ rfqId } = fixtures()); });
  test.afterAll(async ({ browser }) => {
    const ctx = await browser.newContext({ storageState: AUTH_FILE });
    for (const f of [upload, generated]) {
      await ctx.request.post(`http://localhost/rfq/quote/delete_document/${rfqId}/${encodeURIComponent(f)}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
    }
    await ctx.close();
    await resetPartnerState(rfqId);
  });
  test.beforeEach(async ({ page }) => {
    await resetPartnerState(rfqId);
    const up = await page.request.post(`http://localhost/rfq/quote/load_img/${rfqId}`, {
      headers: { 'X-Requested-With': 'XMLHttpRequest' },
      multipart: { 'archivos_ejemplo[]': { name: upload, mimeType: 'application/pdf', buffer: Buffer.from('uploaded by a user') } },
    });
    expect((await up.json()).result).toBe('1');
    // The app's own generator writes "<code>(checklist).pdf" into the quote folder.
    expect((await page.request.get(`http://localhost/rfq/quote/generate_checklist_pdf/${rfqId}`)).ok()).toBe(true);
  });

  const rowFor = (page, name) => page.locator('.doc-file-row', { hasText: name });

  test('sharing off: note shown, checkboxes disabled; generated file is internal only', async ({ page }) => {
    await page.goto(`http://localhost/rfq/perfil/quote/editar_cotizacion/${rfqId}`);
    await page.click('#qed-open-documents');
    await expect(page.locator('[data-testid="sharing-off-note"]')).toBeVisible();
    await expect(rowFor(page, upload).locator('[data-testid="doc-share-checkbox"]')).toBeDisabled();
    await expect(rowFor(page, generated).locator('[data-testid="doc-internal-only"]')).toBeVisible();
    await expect(rowFor(page, generated).locator('[data-testid="doc-share-checkbox"]')).toHaveCount(0);
    await expect(page.locator('[data-testid="shared-count"]')).toBeHidden();
  });

  test('sharing on: ticking a file persists, shows the N shared count, and unticking clears it', async ({ page }) => {
    await query("UPDATE rfq SET share_with_partner = 1, solicitation_number = 'PW-DOC', solicitation_key = 'PW-DOC' WHERE id = ?", [rfqId]);
    await page.goto(`http://localhost/rfq/perfil/quote/editar_cotizacion/${rfqId}`);
    await page.click('#qed-open-documents');
    await expect(page.locator('[data-testid="sharing-off-note"]')).toBeHidden();

    const box = rowFor(page, upload).locator('[data-testid="doc-share-checkbox"]');
    await expect(box).toBeEnabled();
    await expect(box).not.toBeChecked();
    await expect(page.locator('[data-testid="shared-count"]')).toBeHidden(); // hidden at zero

    await Promise.all([
      page.waitForResponse((res) => res.url().includes('/quote/partner_share_file/') && res.status() === 200),
      rowFor(page, upload).locator('label.pp-share').click(),
    ]);
    await expect(page.locator('[data-testid="shared-count"]')).toContainText('1 shared');
    const [doc] = await query('SELECT shared FROM partner_documents WHERE id_rfq = ? AND file_name = ?', [rfqId, upload]);
    expect(doc.shared).toBe(1);
    const notices = await query("SELECT COUNT(*) AS n FROM partner_webhook_outbox WHERE id_rfq = ? AND event = 'document.shared'", [rfqId]);
    expect(notices[0].n).toBe(1);

    await page.reload();
    await page.click('#qed-open-documents');
    await expect(rowFor(page, upload).locator('[data-testid="doc-share-checkbox"]')).toBeChecked();
    await expect(page.locator('[data-testid="shared-count"]')).toContainText('1 shared');

    await Promise.all([
      page.waitForResponse((res) => res.url().includes('/quote/partner_share_file/') && res.status() === 200),
      rowFor(page, upload).locator('label.pp-share').click(),
    ]);
    await expect(page.locator('[data-testid="shared-count"]')).toBeHidden();
  });

  test('the server refuses to share a generated file even when asked directly', async ({ page }) => {
    await query("UPDATE rfq SET share_with_partner = 1, solicitation_number = 'PW-DOC', solicitation_key = 'PW-DOC' WHERE id = ?", [rfqId]);
    await page.goto(`http://localhost/rfq/perfil/quote/editar_cotizacion/${rfqId}`);
    const res = await page.request.post(`http://localhost/rfq/quote/partner_share_file/${rfqId}`, {
      headers: { 'X-Requested-With': 'XMLHttpRequest' },
      form: { file: generated, shared: '1' },
    });
    expect(res.status()).toBe(422);
    const rows = await query('SELECT shared FROM partner_documents WHERE id_rfq = ? AND file_name = ?', [rfqId, generated]);
    expect(rows.every((r) => r.shared === 0)).toBe(true);
  });
});

test.describe('Partner sync — admin Partner Integration card', () => {
  // The harness user is not an admin and the session caches the role at login, so promote the user
  // and sign in fresh for this block, then restore both.
  test.use({ storageState: { cookies: [], origins: [] } });
  let rfqId;

  test.beforeAll(async () => {
    ({ rfqId } = fixtures());
    await query("UPDATE usuarios SET cargo = '1' WHERE nombre_usuario = 'pw_test_user'");
    await query("DELETE FROM partner_review WHERE partner_record_id LIKE 'PW-REC-%'");
  });
  test.afterAll(async () => {
    await query("UPDATE usuarios SET cargo = 'QA' WHERE nombre_usuario = 'pw_test_user'");
    await query(`UPDATE partner_integration SET enabled = 0, issued_token_hash = NULL, issued_token_last4 = NULL, token_created_at = NULL,
      token_created_by = NULL, token_revoked_at = NULL, partner_base_url = NULL, partner_webhook_url = NULL, partner_token = NULL,
      signing_secret = NULL, last_pull_at = NULL WHERE id = 1`);
    await query("DELETE FROM partner_review WHERE partner_record_id LIKE 'PW-REC-%'");
    await query("DELETE FROM partner_webhook_outbox WHERE id_rfq = ? OR last_error LIKE '%PW-RETRY%'", [rfqId]);
    await resetPartnerState(rfqId);
  });

  test.beforeEach(async ({ page }) => {
    await query("UPDATE partner_integration SET enabled = 0, issued_token_hash = NULL, issued_token_last4 = NULL, token_revoked_at = NULL WHERE id = 1");
    await page.goto('http://localhost/rfq/');
    await page.fill('[name="nombre_usuario"]', 'pw_test_user');
    await page.fill('[name="password"]', 'PlaywrightTest123!');
    await page.click('[name="iniciar_sesion"]');
    await page.waitForURL('**/perfil/**');
    await page.goto('http://localhost/rfq/perfil/admin/settings');
    await expect(page.locator('[data-testid="partner-card"]')).toBeVisible();
  });

  test('integration is off by default with a banner; the switch persists immediately', async ({ page }) => {
    await expect(page.locator('#pp-banner')).toBeVisible();
    await expect(page.locator('[data-testid="partner-enabled"]')).toHaveAttribute('aria-checked', 'false');
    await Promise.all([
      page.waitForResponse((res) => res.url().includes('/admin/partner/settings') && res.status() === 200),
      page.click('[data-testid="partner-enabled"]'),
    ]);
    await expect(page.locator('#pp-banner')).toBeHidden();
    const [row] = await query('SELECT enabled FROM partner_integration WHERE id = 1');
    expect(row.enabled).toBe(1);
  });

  // The design mutes the connection + token area while the integration is off, so enable it first.
  async function enableIntegration(page) {
    await query('UPDATE partner_integration SET enabled = 1 WHERE id = 1');
    await page.reload();
    await expect(page.locator('[data-testid="partner-enabled"]')).toHaveAttribute('aria-checked', 'true');
  }

  test('while off, the connection and token area is muted and Save is disabled', async ({ page }) => {
    await expect(page.locator('[data-testid="token-panel"]')).toBeVisible();
    await expect(page.locator('[data-pp-mutable]').first()).toHaveClass(/pp-muted/);
    await expect(page.locator('[data-testid="partner-save"]')).toBeDisabled();
  });

  test('token lifecycle: issue shows it once, then only the last 4; rotate and revoke confirm first', async ({ page }) => {
    await enableIntegration(page);
    await expect(page.locator('[data-testid="token-panel"]')).toContainText('No token issued');
    await page.click('[data-testid="token-issue"]');
    const token = (await page.locator('[data-testid="token-value"]').textContent()).trim();
    expect(token).toMatch(/^elg_live_[0-9a-f]{32}$/);
    await expect(page.locator('[data-testid="token-panel"]')).toContainText("You won't see this again");

    await page.click('[data-testid="token-done"]');
    await expect(page.locator('[data-testid="token-mask"]')).toContainText(token.slice(-4));
    await expect(page.locator('[data-testid="token-panel"]')).not.toContainText(token);

    // Survives a reload as the masked form only.
    await page.reload();
    await expect(page.locator('[data-testid="token-mask"]')).toContainText(token.slice(-4));
    await expect(page.locator('[data-testid="token-panel"]')).not.toContainText(token);

    // Rotate asks first, then replaces the token.
    await page.click('[data-testid="token-rotate"]');
    await expect(page.locator('[data-testid="token-panel"]')).toContainText('Rotate this token?');
    await page.click('[data-testid="token-rotate-confirm"]');
    const rotated = (await page.locator('[data-testid="token-value"]').textContent()).trim();
    expect(rotated).not.toBe(token);
    await page.click('[data-testid="token-done"]');

    // Revoke asks first, then the partner has no token.
    await page.click('[data-testid="token-revoke"]');
    await expect(page.locator('[data-testid="token-panel"]')).toContainText('Revoke this token?');
    await page.click('[data-testid="token-revoke-confirm"]');
    await expect(page.locator('[data-testid="token-panel"]')).toContainText('Token revoked');
    const [row] = await query('SELECT issued_token_hash FROM partner_integration WHERE id = 1');
    expect(row.issued_token_hash).toBeNull();
  });

  test('stored secrets are never echoed back into the page', async ({ page }) => {
    await enableIntegration(page);
    await page.fill('#pp-base-url', 'https://api.partner.test/v2');
    await page.fill('#pp-partner-token', 'ptk_secret_value_123');
    await page.fill('#pp-signing-secret', 'whsec_secret_value_456');
    await Promise.all([
      page.waitForResponse((res) => res.url().includes('/admin/partner/settings') && res.status() === 200),
      page.click('[data-testid="partner-save"]'),
    ]);
    await page.reload();
    const html = await page.content();
    expect(html).not.toContain('ptk_secret_value_123');
    expect(html).not.toContain('whsec_secret_value_456');
    await expect(page.locator('#pp-base-url')).toHaveValue('https://api.partner.test/v2');
    await expect(page.locator('#pp-partner-token')).toHaveAttribute('placeholder', /saved/);
  });

  test('empty queues show their empty states', async ({ page }) => {
    // Never delete someone's real held records to make this pass — skip when the queue is not empty.
    const [{ n }] = await query('SELECT COUNT(*) AS n FROM partner_review WHERE resolved_at IS NULL');
    test.skip(n > 0, 'Needs review has real rows in this database');
    await expect(page.locator('[data-testid="needs-review"]')).toContainText('Nothing needs review');
    await expect(page.locator('[data-testid="failed-deliveries"]')).toContainText('No failed deliveries');
  });

  test('a possible-existing-quote row resolves with Link: the row leaves and a toast names the quote', async ({ page }) => {
    const rec = 'PW-REC-' + Date.now();
    const payload = JSON.stringify({ record_id: rec, solicitation_number: 'PW-LINK-1', name: 'Incoming PW opportunity', agency: 'PW Agency',
      issue_date: null, end_date: null, status: null, assignee_email: null, shared_updated_at: '2026-10-14T12:00:00Z', removed: false, files: null });
    await query(
      `INSERT INTO partner_review (kind, partner_record_id, solicitation_number, candidates, payload, reason, created_at)
       VALUES ('possible', ?, 'PW-LINK-1', ?, ?, 'Code equals an existing quote without a Solicitation #', UTC_TIMESTAMP())`,
      [rec, JSON.stringify([rfqId]), payload]);
    await page.reload();

    const row = page.locator('.pp-trow', { hasText: 'Incoming PW opportunity' });
    await expect(row).toBeVisible();
    await expect(row.locator('.pp-reason')).toContainText('Possible existing quote');
    await Promise.all([
      page.waitForResponse((res) => res.url().includes('/admin/partner/review') && res.status() === 200),
      row.locator('[data-pp-review="link"]').click(),
    ]);
    await expect(row).toHaveCount(0);
    await expect(page.locator('[data-testid="partner-toast"]')).toContainText(`Linked to quote #${rfqId}`);
    const [q] = await query('SELECT partner_record_id, share_with_partner FROM rfq WHERE id = ?', [rfqId]);
    expect(q.partner_record_id).toBe(rec);
    expect(q.share_with_partner).toBe(1);
  });

  test('a failed delivery can be retried; a still-failing partner keeps the row with a higher attempt count', async ({ page }) => {
    // Point the webhook at a closed local port so the retry fails fast and deterministically.
    await query(`UPDATE partner_integration SET enabled = 1, partner_webhook_url = 'http://127.0.0.1:9/hook', signing_secret = 'pw_secret' WHERE id = 1`);
    const ins = await query(
      `INSERT INTO partner_webhook_outbox (id_rfq, event, payload, attempts, next_attempt_at, failed_at, last_error, created_at)
       VALUES (?, 'opportunity.updated', '{"event":"opportunity.updated"}', 6, UTC_TIMESTAMP(), UTC_TIMESTAMP(), 'HTTP 503 PW-RETRY', UTC_TIMESTAMP())`, [rfqId]);
    await page.reload();
    // Keyed on the row id, not its error text — a retry replaces the text with the real error.
    const row = page.locator(`[data-testid="failed-row-${ins.insertId}"]`);
    await expect(row).toBeVisible();
    await expect(row).toContainText('PW-RETRY');
    await Promise.all([
      page.waitForResponse((res) => res.url().includes('/admin/partner/retry') && res.status() === 200),
      row.locator('[data-pp-retry]').click(),
    ]);
    await expect(row).toBeVisible();
    await expect(row.locator('[data-pp-attempts]')).toHaveText('7');
    await expect(row).toContainText('Connection failed');
    await expect(row.locator('[data-pp-retry]')).toBeEnabled();
  });
});
