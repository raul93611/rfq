/* =========================================================================
   Quote Checklist & Info Drawer — controller (vanilla JS)
   Ported from the Claude Design handoff. Opens a slide-over drawer over the
   quote edit page with Checklist/Information tabs; both tabs' forms stay
   mounted in the DOM the whole time the drawer is open so switching tabs
   never loses unsaved edits. Each tab saves independently via AJAX against
   its existing save_checklist.php/save_information.php endpoint.
   ========================================================================= */
(function () {
  'use strict';

  var $ = function (sel, ctx) { return (ctx || document).querySelector(sel); };
  var $$ = function (sel, ctx) { return Array.prototype.slice.call((ctx || document).querySelectorAll(sel)); };

  var scrim  = $('#qed-drawer-scrim');
  var drawer = $('#qed-drawer');
  if (!scrim || !drawer) return;

  var closeBtn = $('#qed-drawer-close');
  var tabButtons = $$('.qed-tab');
  var panels = $$('.qed-tab-panel');
  var openChecklistBtn = $('#qed-open-checklist');
  var openInformationBtn = $('#qed-open-information');
  var confirmOverlay = $('#qed-confirm-overlay');
  var confirmCancelBtn = $('#qed-confirm-cancel');
  var confirmDiscardBtn = $('#qed-confirm-discard');

  var checklistForm = $('#checklist_form');
  var informationForm = $('#information_form');
  var checklistError = $('#qed-checklist-error');
  var informationError = $('#qed-information-error');
  var checklistScroll = $('#qed-checklist-scroll');
  var informationScroll = $('#qed-information-scroll');
  var checklistSaveBtn = $('#qed-checklist-save-btn');
  var informationSaveBtn = $('#qed-information-save-btn');
  var checklistPill = $('#qed-checklist-save-pill');
  var informationPill = $('#qed-information-save-pill');

  /* ---------- form snapshot / dirty-check / restore ---------- */

  function snapshotForm(form) {
    var entries = [];
    new FormData(form).forEach(function (value, key) { entries.push([key, value]); });
    return entries;
  }

  function serializeForCompare(entries) {
    return entries.map(function (e) { return e[0] + '=' + e[1]; }).join('&');
  }

  function restoreForm(form, snapshotEntries) {
    var byName = {};
    snapshotEntries.forEach(function (pair) {
      (byName[pair[0]] = byName[pair[0]] || []).push(pair[1]);
    });
    Array.prototype.forEach.call(form.elements, function (el) {
      if (!el.name) return;
      if (el.type === 'checkbox' || el.type === 'radio') {
        var vals = byName[el.name] || [];
        el.checked = vals.indexOf(el.value) !== -1;
      } else if (el.tagName === 'SELECT' && el.multiple) {
        var vals2 = byName[el.name] || [];
        Array.prototype.forEach.call(el.options, function (opt) { opt.selected = vals2.indexOf(opt.value) !== -1; });
      } else {
        var arr = byName[el.name];
        if (arr && arr.length) el.value = arr.shift();
      }
    });
  }

  // Captured lazily on the first drawer open rather than at page load: main.js's
  // #end_date/#qa_deadline datepicker init (autoUpdateInput) rewrites those fields'
  // values asynchronously — not reliably done by 'load', let alone by the time this
  // script parses — so snapshotting on any fixed page-load-relative event risks baking
  // in a false "dirty" reading on a fresh drawer. Any real open (click or auto-open) is
  // by definition after the field has already settled; a successful save updates the
  // relevant form's snapshot on its own, and re-opening after that never re-captures,
  // so edits left unsaved across a close/reopen still trip the discard-confirmation guard.
  var savedSnapshots = { checklist: [], information: [] };
  var snapshotsCaptured = false;
  function ensureInitialSnapshots() {
    if (snapshotsCaptured) return;
    snapshotsCaptured = true;
    savedSnapshots.checklist = checklistForm ? snapshotForm(checklistForm) : [];
    savedSnapshots.information = informationForm ? snapshotForm(informationForm) : [];
  }

  function isDirty(which) {
    var form = which === 'checklist' ? checklistForm : informationForm;
    if (!form) return false;
    return serializeForCompare(snapshotForm(form)) !== serializeForCompare(savedSnapshots[which]);
  }

  function anyDirty() { return isDirty('checklist') || isDirty('information'); }

  /* ---------- drawer open/close/tabs ---------- */

  function switchTab(tab) {
    tabButtons.forEach(function (btn) { btn.classList.toggle('is-active', btn.dataset.tab === tab); });
    panels.forEach(function (panel) { panel.classList.toggle('is-hidden', panel.dataset.tabPanel !== tab); });
  }

  function openDrawer(tab) {
    ensureInitialSnapshots();
    switchTab(tab || 'checklist');
    scrim.classList.add('is-open');
    drawer.classList.add('is-open');
    drawer.setAttribute('aria-hidden', 'false');
    document.body.classList.add('qed-drawer-open');
  }

  function closeDrawerImmediate() {
    scrim.classList.remove('is-open');
    drawer.classList.remove('is-open');
    drawer.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('qed-drawer-open');
  }

  function requestClose() {
    if (drawer.classList.contains('is-open') === false) return;
    if (anyDirty()) {
      confirmOverlay.style.display = 'grid';
    } else {
      closeDrawerImmediate();
    }
  }

  if (openChecklistBtn) openChecklistBtn.addEventListener('click', function () { openDrawer('checklist'); });
  if (openInformationBtn) openInformationBtn.addEventListener('click', function () { openDrawer('information'); });
  tabButtons.forEach(function (btn) {
    btn.addEventListener('click', function () { switchTab(btn.dataset.tab); });
  });
  if (closeBtn) closeBtn.addEventListener('click', requestClose);
  scrim.addEventListener('click', requestClose);
  document.addEventListener('keydown', function (e) {
    if (e.key !== 'Escape') return;
    if (confirmOverlay.style.display && confirmOverlay.style.display !== 'none') {
      confirmOverlay.style.display = 'none';
      return;
    }
    requestClose();
  });

  if (confirmCancelBtn) confirmCancelBtn.addEventListener('click', function () { confirmOverlay.style.display = 'none'; });
  if (confirmDiscardBtn) confirmDiscardBtn.addEventListener('click', function () {
    if (checklistForm) restoreForm(checklistForm, savedSnapshots.checklist);
    if (informationForm) restoreForm(informationForm, savedSnapshots.information);
    hideError('checklist');
    hideError('information');
    confirmOverlay.style.display = 'none';
    closeDrawerImmediate();
  });

  /* ---------- inline error banners ---------- */

  function showError(which, message) {
    var banner = which === 'checklist' ? checklistError : informationError;
    var scrollEl = which === 'checklist' ? checklistScroll : informationScroll;
    if (!banner) return;
    banner.querySelector('.qed-error-text').textContent = message;
    banner.style.display = 'flex';
    if (scrollEl) scrollEl.scrollTop = 0;
  }

  function hideError(which) {
    var banner = which === 'checklist' ? checklistError : informationError;
    if (banner) banner.style.display = 'none';
  }

  $$('.qed-error-dismiss').forEach(function (btn) {
    btn.addEventListener('click', function () { hideError(btn.dataset.errorFor); });
  });

  /* ---------- live checklist completeness (status card + tab badge) ---------- */

  function updateChecklistCount(count) {
    var tabCountEl = $('#qed-tab-checklist-count');
    if (tabCountEl) tabCountEl.textContent = count + '/10';
    var statusValueEl = $('#qed-checklist-status-value');
    if (statusValueEl) statusValueEl.textContent = count + ' of 10 items complete';
    var ring = $('#qed-open-checklist .qed-status-ring');
    var ringIcon = $('#qed-open-checklist .qed-status-ring-inner i');
    if (ring) {
      ring.style.setProperty('--pct', count * 10);
      var complete = count === 10;
      ring.classList.toggle('is-complete', complete);
      if (ringIcon) ringIcon.className = complete ? 'fas fa-check' : 'fas fa-clipboard-list';
    }
  }

  /* ---------- AJAX save ---------- */

  function showSavedPill(pill) {
    if (!pill) return;
    pill.classList.add('is-shown');
    setTimeout(function () { pill.classList.remove('is-shown'); }, 2200);
  }

  function saveTab(which) {
    var form = which === 'checklist' ? checklistForm : informationForm;
    var btn = which === 'checklist' ? checklistSaveBtn : informationSaveBtn;
    var pill = which === 'checklist' ? checklistPill : informationPill;
    if (!form || !btn) return;

    hideError(which);
    btn.classList.add('is-saving');
    btn.disabled = true;
    var originalHtml = btn.innerHTML;
    btn.innerHTML = '<span class="qed-spinner"></span> Saving…';

    var fd = new FormData(form);
    fd.append(which === 'checklist' ? 'save_checklist' : 'save_information', '1');

    fetch(form.getAttribute('action'), {
      method: 'POST',
      body: fd,
      headers: { 'X-Requested-With': 'XMLHttpRequest' },
      credentials: 'same-origin'
    })
      .then(function (res) {
        return res.json()
          .catch(function () { return null; })
          .then(function (data) { return { ok: res.ok, data: data }; });
      })
      .then(function (result) {
        btn.classList.remove('is-saving');
        btn.disabled = false;
        btn.innerHTML = originalHtml;
        if (result.ok && result.data && result.data.success) {
          savedSnapshots[which] = snapshotForm(form);
          showSavedPill(pill);
          if (which === 'checklist' && typeof result.data.checklistCount === 'number') {
            updateChecklistCount(result.data.checklistCount);
          }
          if (which === 'information' && result.data.sheetSync && typeof window.ssRepaint === 'function') {
            var ss = result.data.sheetSync;
            var tone = ss.status === 'synced' ? 'synced' : (ss.status === 'failed' ? 'failed' : 'never');
            window.ssRepaint(tone, ss.syncAt);
          }
        } else {
          showError(which, (result.data && result.data.message) || 'Could not save. Please try again.');
        }
      })
      .catch(function () {
        btn.classList.remove('is-saving');
        btn.disabled = false;
        btn.innerHTML = originalHtml;
        showError(which, 'Could not save — the server did not respond. Your entries are unaffected.');
      });
  }

  if (checklistForm) checklistForm.addEventListener('submit', function (e) { e.preventDefault(); saveTab('checklist'); });
  if (informationForm) informationForm.addEventListener('submit', function (e) { e.preventDefault(); saveTab('information'); });

  /* ---------- auto-open from old bookmarked URLs (?drawer=checklist|information) ---------- */

  function autoOpenFromUrl() {
    var params = new URLSearchParams(window.location.search);
    var tab = params.get('drawer');
    if (tab !== 'checklist' && tab !== 'information') return;
    openDrawer(tab);
    params.delete('drawer');
    var qs = params.toString();
    var newUrl = window.location.pathname + (qs ? '?' + qs : '') + window.location.hash;
    window.history.replaceState({}, '', newUrl);
  }

  // Unlike a real click, this runs at script-parse time on a fresh navigation (the old
  // bookmark redirect), i.e. before the page has settled — deferred past 'load' plus a
  // beat so ensureInitialSnapshots() (called from openDrawer above) doesn't run ahead of
  // main.js's datepicker init the same way a synchronous call here would.
  if (document.readyState === 'complete') {
    setTimeout(autoOpenFromUrl, 50);
  } else {
    window.addEventListener('load', function () { setTimeout(autoOpenFromUrl, 50); });
  }
})();
