/* @mention autocomplete — attaches to any textarea with id="comment_rfq" */
(function () {
  'use strict';

  var USERS_URL = window.NOTIFICATIONS_USERS_FOR_MENTION_URL || '';
  var allUsers  = [];
  var popup     = null;
  var textarea  = null;
  var mentionStart = -1;
  var activeIndex  = 0;

  function fetchUsers(cb) {
    if (!USERS_URL) return;
    fetch(USERS_URL)
      .then(function (r) { return r.json(); })
      .then(function (data) { allUsers = data || []; if (cb) cb(); })
      .catch(function () {});
  }

  function createPopup() {
    var el = document.createElement('div');
    el.className = 'cm-mention-pop';
    el.style.display = 'none';
    el.style.position = 'absolute';
    el.style.zIndex   = '9999';
    document.body.appendChild(el);
    return el;
  }

  function positionPopup() {
    if (!textarea || !popup) return;
    var rect = textarea.getBoundingClientRect();
    popup.style.left = (rect.left + window.scrollX) + 'px';
    popup.style.top  = (rect.bottom + window.scrollY + 4) + 'px';
    popup.style.width = Math.min(260, rect.width) + 'px';
  }

  function getQuery() {
    if (!textarea || mentionStart < 0) return '';
    return textarea.value.slice(mentionStart + 1, textarea.selectionStart);
  }

  function filterUsers(query) {
    var q = query.toLowerCase();
    return allUsers.filter(function (u) {
      return !q || u.username.toLowerCase().indexOf(q) === 0 || u.name.toLowerCase().indexOf(q) !== -1;
    }).slice(0, 6);
  }

  function renderPopup(users) {
    if (!popup) return;
    if (users.length === 0) { hidePopup(); return; }
    activeIndex = 0;
    var html = '<div class="cm-mention-head">'
      + '<svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4"/><path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-4 8"/></svg>'
      + ' Mention a teammate</div>';
    users.forEach(function (u, i) {
      html += '<div class="cm-mention-item' + (i === 0 ? ' cm-mention-item-active' : '') + '" data-username="' + escHtml(u.username) + '">'
        + '<div class="cm-avatar-mini cm-avatar-' + ((i % 4) + 1) + '">' + escHtml(u.username.slice(0, 2).toUpperCase()) + '</div>'
        + '<div class="cm-mention-item-meta">'
        + '<div class="cm-mention-item-name">' + escHtml(u.username) + '</div>'
        + '<div class="cm-mention-item-handle">@' + escHtml(u.username.toLowerCase()) + '</div>'
        + '</div></div>';
    });
    popup.innerHTML = html;
    popup.style.display = '';
    positionPopup();

    popup.querySelectorAll('.cm-mention-item').forEach(function (item) {
      item.addEventListener('mousedown', function (e) {
        e.preventDefault();
        insertMention(item.dataset.username);
      });
    });
  }

  function setActive(index) {
    var items = popup ? popup.querySelectorAll('.cm-mention-item') : [];
    items.forEach(function (it, i) {
      it.classList.toggle('cm-mention-item-active', i === index);
    });
    activeIndex = index;
  }

  function hidePopup() {
    if (popup) popup.style.display = 'none';
    mentionStart = -1;
  }

  function insertMention(username) {
    if (!textarea || mentionStart < 0) return;
    var before = textarea.value.slice(0, mentionStart);
    var after  = textarea.value.slice(textarea.selectionStart);
    textarea.value = before + '@' + username + ' ' + after;
    var pos = mentionStart + username.length + 2;
    textarea.setSelectionRange(pos, pos);
    textarea.focus();
    hidePopup();
  }

  function escHtml(s) {
    return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
  }

  function onKeyDown(e) {
    if (popup && popup.style.display !== 'none') {
      var items = popup.querySelectorAll('.cm-mention-item');
      if (e.key === 'ArrowDown') {
        e.preventDefault();
        setActive(Math.min(activeIndex + 1, items.length - 1));
        return;
      }
      if (e.key === 'ArrowUp') {
        e.preventDefault();
        setActive(Math.max(activeIndex - 1, 0));
        return;
      }
      if (e.key === 'Enter' || e.key === 'Tab') {
        if (items.length > 0) {
          e.preventDefault();
          insertMention(items[activeIndex].dataset.username);
        }
        return;
      }
      if (e.key === 'Escape') {
        hidePopup();
        return;
      }
    }
  }

  function onInput() {
    var val    = textarea.value;
    var cursor = textarea.selectionStart;

    // Find last @ before cursor
    var searchBack = val.slice(0, cursor);
    var atPos      = searchBack.lastIndexOf('@');

    if (atPos === -1) { hidePopup(); return; }

    // Ensure no space between @ and cursor
    var fragment = searchBack.slice(atPos + 1);
    if (/\s/.test(fragment)) { hidePopup(); return; }

    mentionStart = atPos;
    var users = filterUsers(fragment);
    renderPopup(users);
  }

  function attach(ta) {
    textarea = ta;
    if (!popup) popup = createPopup();

    ta.addEventListener('input',   onInput);
    ta.addEventListener('keydown', onKeyDown);
    ta.addEventListener('blur', function () {
      setTimeout(hidePopup, 150);
    });

    fetchUsers();
  }

  function init() {
    var ta = document.getElementById('comment_rfq');
    if (ta) {
      attach(ta);
    }
  }

  // Run on DOMContentLoaded or immediately if DOM is ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

  // Also re-attach when a Bootstrap modal is shown (for modal-based comment forms)
  document.addEventListener('shown.bs.modal', function (e) {
    var ta = e.target ? e.target.querySelector('#comment_rfq') : null;
    if (ta && ta !== textarea) attach(ta);
  });

  // Expose for use in inline scripts (pass the URL)
  window.MentionAutocomplete = { attach: attach };
})();
