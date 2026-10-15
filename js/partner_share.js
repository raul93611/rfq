/* =========================================================================
   Partner Opportunity Sync — client behaviour (pp-* namespace)
   - Solicitation # is required only while "Share with partner" is on: marker + inline error,
     and a submit guard on New Quote. (The server enforces the same rule; this is the fast path.)
   - Exposes window.PartnerShare for js/checklist_info_drawer.js:
       validate()        → false (and shows the inline error) when sharing is on and the number is blank
       showError(msg)    → show a server-side message inline (blank / duplicate)
       repaint(partner)  → repaint the header badges + Solicitation # info card from the
                           save_information response, no reload
   ========================================================================= */
(function () {
  'use strict';

  var sol = document.getElementById('solicitation_number');
  var share = document.getElementById('share_with_partner');
  var group = document.getElementById('pp-sol-group');
  var req = document.getElementById('pp-sol-req');
  var errText = document.querySelector('#pp-sol-error span');
  var touched = !!(group && group.classList.contains('has-pp-error'));
  // An error rendered by the server (duplicate number on New Quote) must survive the first paint():
  // it is not a "blank field" error, so it stays until the user edits the field or the toggle.
  if (touched) group.classList.add('has-pp-server');

  function isBlank() { return !sol || !sol.value.trim(); }

  function paint() {
    if (!sol || !share || !group) return;
    if (req) req.hidden = !share.checked;
    var bad = share.checked && isBlank() && touched;
    // A server-side message (duplicate number) stays until the user edits the field.
    if (!bad && group.classList.contains('has-pp-server')) bad = true;
    group.classList.toggle('has-pp-error', bad);
    sol.setAttribute('aria-invalid', bad ? 'true' : 'false');
  }

  function showError(message) {
    if (!group) return;
    if (errText && message) errText.textContent = message;
    group.classList.add('has-pp-server');
    paint();
    if (sol) sol.focus();
  }

  function validate() {
    touched = true;
    group && group.classList.remove('has-pp-server');
    if (errText && sol) errText.textContent = sol.getAttribute('data-blank-message') || errText.textContent;
    paint();
    var ok = !(share && share.checked && isBlank());
    if (!ok && sol) sol.focus();
    return ok;
  }

  if (sol && share) {
    share.addEventListener('change', function () { group.classList.remove('has-pp-server'); paint(); });
    sol.addEventListener('input', function () { group.classList.remove('has-pp-server'); paint(); });
    sol.addEventListener('blur', function () { touched = true; paint(); });
    // New Quote submits natively; the Information drawer saves over AJAX and calls validate() itself.
    var form = sol.form;
    if (form && form.id !== 'information_form') {
      form.addEventListener('submit', function (e) {
        if (!validate()) e.preventDefault();
      });
    }
    paint();
  }

  /* ---------- header badges + info card ---------- */

  function esc(s) {
    return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) {
      return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
    });
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

  function refreshAgo(root) {
    Array.prototype.forEach.call((root || document).querySelectorAll('[data-pp-ago]'), function (el) {
      el.textContent = ago(el.getAttribute('data-pp-ago'));
    });
  }

  // Keep in step with plantillas/quote/templates/partner_badges.inc.php.
  function badgesHtml(p) {
    var html = '';
    if (p.state === 'linked') {
      html += '<span class="pp-badge is-linked" data-testid="badge-linked"><i class="fas fa-link"></i>Linked to partner' +
        (p.syncedAt ? '<span class="pp-badge-meta">last synced <span data-pp-ago="' + esc(p.syncedAt) + '"></span></span>' : '') + '</span>';
    } else if (p.state === 'waiting') {
      html += '<span class="pp-badge is-waiting" data-testid="badge-waiting"><i class="far fa-clock"></i>Waiting for partner</span>';
    }
    if (p.needsAssignment) {
      html += '<button type="button" class="pp-badge is-assign" id="pp-assign-badge" data-testid="badge-needs-assignment" title="Open Information to assign an owner">' +
        '<i class="fas fa-exclamation-triangle"></i>Needs assignment<span class="pp-badge-chev"><i class="fas fa-chevron-right"></i></span></button>';
    }
    return html;
  }

  function bindAssignBadge() {
    var badge = document.getElementById('pp-assign-badge');
    if (!badge || badge.getAttribute('data-bound')) return;
    badge.setAttribute('data-bound', '1');
    badge.addEventListener('click', function () {
      var open = document.getElementById('qed-open-information');
      if (open) open.click();
      // The owner field also exists on the Checklist tab; the badge points at Information's.
      var owner = document.querySelector('#information_form [data-pp-owner]') || document.querySelector('[data-pp-owner]');
      if (owner) {
        owner.classList.add('is-hl');
        var select = owner.querySelector('select');
        if (select) select.addEventListener('change', function () { owner.classList.remove('is-hl'); }, { once: true });
        setTimeout(function () { owner.scrollIntoView({ block: 'center', behavior: 'smooth' }); }, 250);
      }
    });
  }

  function repaintSolCell(solicitation) {
    var cell = document.getElementById('qed-sol-cell');
    var grid = document.querySelector('.quote-info-secondary');
    if (!solicitation) { if (cell) cell.remove(); return; }
    var valueHtml = '<span class="pp-mono">' + esc(solicitation) + '</span>';
    if (cell) {
      var v = cell.querySelector('.quote-info-value');
      if (v) v.innerHTML = valueHtml;
    } else if (grid) {
      var el = document.createElement('div');
      el.className = 'quote-info-cell';
      el.id = 'qed-sol-cell';
      el.innerHTML = '<div class="quote-info-label"><i class="fas fa-hashtag"></i> Solicitation #</div>' +
        '<div class="quote-info-value quote-info-value--secondary">' + valueHtml + '</div>';
      grid.insertBefore(el, grid.firstChild);
    }
  }

  function repaint(p) {
    if (!p) return;
    var box = document.getElementById('pp-badges');
    if (box) {
      var html = badgesHtml(p);
      box.innerHTML = html;
      box.hidden = html === '';
      refreshAgo(box);
      bindAssignBadge();
    }
    repaintSolCell(p.solicitation || '');
    // Sharing state also gates the Documents drawer's per-file checkboxes.
    document.dispatchEvent(new CustomEvent('partner:sharing', { detail: { sharing: p.state !== 'off' } }));
  }

  refreshAgo(document);
  bindAssignBadge();

  window.PartnerShare = { validate: validate, showError: showError, repaint: repaint };
})();
