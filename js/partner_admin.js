/* =========================================================================
   Partner Integration — Admin Settings behaviour (pp-* namespace)
   Talks to admin/partner/{settings|token|retry|review} (POST, JSON, admin only).
   The page is server-rendered; this wires the switch, secrets, token lifecycle,
   Retry on failed deliveries and Link / Create new on held records.
   ========================================================================= */
(function () {
  'use strict';

  var cfg = window.PP_ADMIN;
  var card = document.getElementById('pp-card');
  if (!cfg || !card) return;

  function $(sel, root) { return (root || document).querySelector(sel); }
  function $$(sel, root) { return Array.prototype.slice.call((root || document).querySelectorAll(sel)); }
  function esc(s) {
    return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) {
      return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
    });
  }

  function post(op, data) {
    var fd = new FormData();
    Object.keys(data || {}).forEach(function (k) { fd.append(k, data[k]); });
    return fetch(cfg.action + '/' + op, {
      method: 'POST', body: fd, credentials: 'same-origin', headers: { 'X-Requested-With': 'XMLHttpRequest' }
    }).then(function (res) {
      return res.json().catch(function () { return null; }).then(function (d) { return { ok: res.ok && d && d.success, d: d || {} }; });
    }).catch(function () { return { ok: false, d: { message: 'The server did not respond.' } }; });
  }

  function ago(iso) {
    var t = Date.parse(iso);
    if (isNaN(t)) return '';
    var s = Math.max(0, Math.round((Date.now() - t) / 1000));
    if (s < 60) return 'just now';
    if (s < 3600) return Math.round(s / 60) + ' min ago';
    if (s < 86400) return Math.round(s / 3600) + ' h ago';
    return Math.round(s / 86400) + ' d ago';
  }
  $$('[data-pp-ago]').forEach(function (el) { el.textContent = ago(el.getAttribute('data-pp-ago')); });

  /* ---------- toasts ---------- */
  var toasts = document.createElement('div');
  toasts.className = 'pp-toasts';
  document.body.appendChild(toasts);
  function toast(kind, title, sub) {
    var el = document.createElement('div');
    el.className = 'pp-toast' + (kind === 'error' ? ' is-error' : '');
    el.setAttribute('role', 'status');
    el.setAttribute('data-testid', 'partner-toast');
    el.innerHTML = '<span class="pp-toast-ic"><i class="fas ' + (kind === 'error' ? 'fa-exclamation-triangle' : (kind === 'created' ? 'fa-plus' : 'fa-link')) + '"></i></span>' +
      '<div><div class="pp-toast-title">' + esc(title) + '</div>' + (sub ? '<div class="pp-toast-sub">' + esc(sub) + '</div>' : '') + '</div>' +
      '<button type="button" class="pp-toast-close" aria-label="Dismiss"><i class="fas fa-times"></i></button>';
    el.querySelector('button').addEventListener('click', function () { el.remove(); });
    toasts.appendChild(el);
    setTimeout(function () { el.remove(); }, 6500);
  }

  /* ---------- enabled switch ---------- */
  var sw = $('#pp-enabled');
  function applyEnabled(on) {
    cfg.enabled = on;
    sw.setAttribute('aria-checked', on ? 'true' : 'false');
    $('#pp-enabled-label').textContent = on ? 'Enabled' : 'Disabled';
    $('#pp-banner').hidden = on;
    card.setAttribute('data-enabled', on ? '1' : '0');
    $$('[data-pp-mutable]', card).forEach(function (el) { el.classList.toggle('pp-muted', !on); });
    $$('.ac-status-dot', card).forEach(function (d) {
      d.classList.toggle('ac-status-dot-connected', on);
      d.classList.toggle('ac-status-dot-disconnected', !on);
    });
    $$('[data-pp-retry]').forEach(function (b) { b.disabled = !on; });
    var save = $('#pp-save');
    if (save) save.disabled = !on;
  }
  function toggleEnabled(on) {
    var prev = cfg.enabled;
    applyEnabled(on);
    // Persisted at once — the integration being off is itself a state worth saving.
    post('settings', { enabled: on ? '1' : '0' }).then(function (r) {
      if (!r.ok) { applyEnabled(prev); toast('error', 'Could not change the integration', r.d.message || 'Please try again.'); }
    });
  }
  sw.addEventListener('click', function () { toggleEnabled(!cfg.enabled); });
  $('#pp-banner-enable').addEventListener('click', function () { toggleEnabled(true); });

  /* ---------- connection settings ---------- */
  $$('[data-pp-eye]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var input = document.getElementById(btn.getAttribute('data-pp-eye'));
      var show = input.type === 'password';
      input.type = show ? 'text' : 'password';
      btn.setAttribute('aria-label', show ? 'Hide' : 'Show');
      btn.innerHTML = '<i class="fas ' + (show ? 'fa-eye-slash' : 'fa-eye') + '"></i>';
    });
  });

  var saveBtn = $('#pp-save');
  var savedPill = $('#pp-saved');
  var formError = $('#pp-settings-error');
  saveBtn.addEventListener('click', function () {
    formError.hidden = true;
    saveBtn.disabled = true;
    post('settings', {
      enabled: cfg.enabled ? '1' : '0',
      partner_base_url: $('#pp-base-url').value,
      partner_webhook_url: $('#pp-webhook-url').value,
      partner_token: $('#pp-partner-token').value,
      signing_secret: $('#pp-signing-secret').value
    }).then(function (r) {
      saveBtn.disabled = false;
      if (!r.ok) { formError.textContent = r.d.message || 'Could not save the settings.'; formError.hidden = false; return; }
      ['pp-partner-token', 'pp-signing-secret'].forEach(function (id) {
        var input = document.getElementById(id);
        if (input.value) input.placeholder = '•••••••• saved — leave blank to keep';
        input.value = '';
        input.type = 'password';
      });
      savedPill.classList.add('is-shown');
      setTimeout(function () { savedPill.classList.remove('is-shown'); }, 2000);
    });
  });

  /* ---------- token lifecycle ---------- */
  var panel = $('#pp-token-panel');
  var st = panel.getAttribute('data-has-token') === '1'
    ? { mode: 'masked', last4: panel.getAttribute('data-last4'), issued: panel.getAttribute('data-issued'), issuer: panel.getAttribute('data-issuer') }
    : { mode: 'none', revoked: panel.getAttribute('data-revoked') === '1' };
  var confirming = null;
  var copied = false;

  function mask(last4) { return 'elg_live_' + '••••••••••••••••••••' + last4; }

  function renderToken() {
    var html;
    if (st.mode === 'none') {
      html = '<span class="pp-token-ic"><i class="fas fa-key"></i></span>' +
        '<div class="pp-token-main"><div class="pp-token-title">' + (st.revoked ? 'Token revoked' : 'No token issued') + '</div>' +
        '<div class="pp-token-sub">' + (st.revoked ? 'The partner can no longer call Elogic. Issue a new token to restore access.'
          : 'Issue a token the partner uses to send opportunities and documents to Elogic.') + '</div></div>' +
        '<button type="button" class="pp-btn pp-btn-dark" data-pp-token="issue" data-testid="token-issue"><i class="fas fa-key"></i> Issue token</button>';
    } else if (st.mode === 'reveal') {
      html = '<div class="pp-reveal-head"><i class="fas fa-check-circle"></i>' + (st.rotated ? 'New token issued — the previous token no longer works' : 'Token issued') + '</div>' +
        '<div class="pp-reveal-box"><code class="pp-reveal-code" data-testid="token-value">' + esc(st.token) + '</code>' +
        '<button type="button" class="pp-btn pp-btn-sm' + (copied ? ' is-copied' : '') + '" data-pp-token="copy"><i class="fas ' + (copied ? 'fa-check' : 'fa-copy') + '"></i> ' + (copied ? 'Copied' : 'Copy') + '</button></div>' +
        '<div class="pp-reveal-foot"><div class="pp-reveal-warn"><i class="fas fa-exclamation-triangle"></i>' +
        '<span><strong>You won\'t see this again.</strong> Copy it now and send it to the partner — afterwards only the last 4 characters are shown.</span></div>' +
        '<button type="button" class="pp-btn pp-btn-primary" data-pp-token="done" data-testid="token-done">I\'ve copied it</button></div>';
    } else {
      var rot = confirming === 'rotate', rev = confirming === 'revoke';
      panel.className = 'pp-token' + (rot ? ' is-rotate' : '') + (rev ? ' is-revoke' : '');
      html = '<span class="pp-token-ic"><i class="fas ' + (confirming ? 'fa-exclamation-triangle' : 'fa-key') + '"></i></span>';
      if (!confirming) {
        html += '<div class="pp-token-main"><div class="pp-token-title pp-token-mask" data-testid="token-mask">' + esc(mask(st.last4)) + '</div>' +
          '<div class="pp-token-sub">Issued ' + esc(st.issued || '') + ' by ' + esc(st.issuer || 'an admin') + ' · <span class="pp-active">Active</span></div></div>' +
          '<div class="pp-token-actions"><button type="button" class="pp-btn" data-pp-token="ask-rotate" data-testid="token-rotate"><i class="fas fa-sync-alt"></i> Rotate</button>' +
          '<button type="button" class="pp-btn pp-btn-danger" data-pp-token="ask-revoke" data-testid="token-revoke">Revoke</button></div>';
      } else {
        html += '<div class="pp-token-main"><div class="pp-token-title">' + (rot ? 'Rotate this token?' : 'Revoke this token?') + '</div>' +
          '<div class="pp-token-sub">' + (rot ? 'A new token is issued and the current one stops working immediately. The partner must switch to the new token.'
            : 'The partner loses access immediately and nothing more is received from them until you issue a new token.') + '</div></div>' +
          '<div class="pp-token-actions"><button type="button" class="pp-btn" data-pp-token="cancel">Cancel</button>' +
          (rot ? '<button type="button" class="pp-btn pp-btn-dark" data-pp-token="rotate" data-testid="token-rotate-confirm">Rotate token</button>'
               : '<button type="button" class="pp-btn pp-btn-danger-solid" data-pp-token="revoke" data-testid="token-revoke-confirm">Revoke token</button>') + '</div>';
      }
    }
    if (st.mode !== 'masked') panel.className = 'pp-token' + (st.mode === 'reveal' ? ' is-reveal' : '');
    panel.innerHTML = html;
  }

  panel.addEventListener('click', function (e) {
    var b = e.target.closest('[data-pp-token]');
    if (!b) return;
    var act = b.getAttribute('data-pp-token');
    if (act === 'issue' || act === 'rotate') {
      b.disabled = true;
      post('token', { op: act }).then(function (r) {
        if (!r.ok) { toast('error', 'Could not issue the token', r.d.message || 'Please try again.'); confirming = null; renderToken(); return; }
        st = { mode: 'reveal', token: r.d.token, rotated: !!r.d.rotated };
        confirming = null; copied = false; renderToken();
      });
    } else if (act === 'revoke') {
      b.disabled = true;
      post('token', { op: 'revoke' }).then(function (r) {
        if (!r.ok) { toast('error', 'Could not revoke the token', r.d.message || 'Please try again.'); confirming = null; renderToken(); return; }
        st = { mode: 'none', revoked: true }; confirming = null; renderToken();
      });
    } else if (act === 'ask-rotate') { confirming = 'rotate'; renderToken(); }
    else if (act === 'ask-revoke') { confirming = 'revoke'; renderToken(); }
    else if (act === 'cancel') { confirming = null; renderToken(); }
    else if (act === 'copy') {
      try { if (navigator.clipboard) navigator.clipboard.writeText(st.token); } catch (err) { /* user can still select the code */ }
      copied = true; renderToken();
    } else if (act === 'done') {
      st = { mode: 'masked', last4: st.token.slice(-4), issued: 'just now', issuer: 'you' };
      st.token = null; renderToken();
    }
  });
  renderToken();

  /* ---------- Failed deliveries: Retry ---------- */
  function setCount(id, n, emptyId, tableId) {
    var c = document.getElementById(id);
    c.textContent = n;
    c.classList.toggle('is-zero', n === 0);
    document.getElementById(emptyId).hidden = n !== 0;
    document.getElementById(tableId).hidden = n === 0;
  }
  function rowCount(tableId) { return $$('.pp-trow', document.getElementById(tableId)).length; }

  document.addEventListener('click', function (e) {
    var btn = e.target.closest('[data-pp-retry]');
    if (!btn || btn.disabled) return;
    var row = btn.closest('.pp-trow');
    var original = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="pp-spin"></span> Retrying…';
    post('retry', { id: btn.getAttribute('data-pp-retry') }).then(function (r) {
      if (r.ok && r.d.outcome === 'delivered') {
        btn.parentNode.innerHTML = '<span class="pp-delivered"><i class="fas fa-check"></i> Delivered</span>';
        setTimeout(function () {
          row.remove();
          setCount('pp-failed-count', rowCount('pp-failed-table'), 'pp-failed-empty', 'pp-failed-table');
        }, 800);
        return;
      }
      btn.disabled = !cfg.enabled;
      btn.innerHTML = original;
      if (r.ok) {
        if (r.d.attempts != null) $('[data-pp-attempts]', row).textContent = r.d.attempts;
        if (r.d.error) $('[data-pp-error]', row).textContent = r.d.error;
        var when = $('[data-pp-when]', row);
        if (when) { when.removeAttribute('data-pp-ago'); when.textContent = 'just now'; }
      } else {
        toast('error', 'Retry failed', r.d.message || 'Please try again.');
      }
    });
  });

  /* ---------- Needs review: Link / Create new ---------- */
  document.addEventListener('click', function (e) {
    var btn = e.target.closest('[data-pp-review]');
    if (!btn || btn.disabled) return;
    var row = btn.closest('.pp-trow');
    var incoming = row.getAttribute('data-incoming') || '';
    var op = btn.getAttribute('data-pp-review');
    $$('button', row).forEach(function (b) { b.disabled = true; });
    var data = { id: row.getAttribute('data-id'), op: op };
    var quoteBtn = $('[data-quote]', row);
    if (op === 'link' && quoteBtn) data.quote_id = quoteBtn.getAttribute('data-quote');
    post('review', data).then(function (r) {
      if (!r.ok) {
        $$('button', row).forEach(function (b) { b.disabled = false; });
        toast('error', 'Could not resolve this record', r.d.message || 'Please try again.');
        return;
      }
      row.remove();
      setCount('pp-review-count', rowCount('pp-review-table'), 'pp-review-empty', 'pp-review-table');
      if (op === 'link') {
        toast('linked', 'Linked to quote #' + r.d.id_rfq, '“' + incoming + '” is now linked to ' + r.d.quote_name + (r.d.quote_code ? ' (' + r.d.quote_code + ')' : '') + '.');
      } else {
        toast('created', 'Created quote #' + r.d.id_rfq, 'New quote created from “' + incoming + '”.');
      }
    });
  });
})();
