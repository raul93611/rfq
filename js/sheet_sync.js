(function () {
  'use strict';

  const btn    = document.getElementById('ss-sync-btn');
  const block  = document.getElementById('ss-block');
  if (!btn || !block) return;

  const idRfq = block.dataset.id;

  const TONES = {
    synced:  { label: 'Synced',       btnText: 'Re-sync',      btnClass: 'ss-btn-success', blockClass: 'ss-block-synced' },
    failed:  { label: 'Sync failed',  btnText: 'Retry Sync',   btnClass: 'ss-btn-danger',  blockClass: 'ss-block-failed' },
    never:   { label: 'Never synced', btnText: 'Sync to Sheet', btnClass: 'ss-btn-neutral', blockClass: 'ss-block-never'  },
    syncing: { label: 'Syncing…',     btnText: 'Syncing…',     btnClass: 'ss-btn-syncing', blockClass: 'ss-block-syncing' },
  };

  const ICON_SVG = {
    synced:  '<polyline points="20 6 9 17 4 12"/>',
    failed:  '<line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>',
    never:   '<line x1="5" y1="12" x2="19" y2="12"/>',
    syncing: '<path d="M3 12a9 9 0 1 0 3-6.7"/><polyline points="3 4 3 9 8 9"/>',
  };

  function setTone(tone, syncAtText) {
    const t = TONES[tone] || TONES.never;

    // Block class
    block.className = block.className.replace(/ss-block-\S+/g, '').trim();
    block.classList.add('ss-block', t.blockClass);

    // Block icon
    const iconWrap = block.querySelector('.ss-block-icon');
    if (iconWrap) {
      iconWrap.className = `ss-block-icon ss-block-icon-${tone}`;
      iconWrap.innerHTML = `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">${ICON_SVG[tone] || ICON_SVG.never}</svg>`;
    }

    // Status label
    const statusEl = block.querySelector('.ss-block-status');
    if (statusEl) {
      statusEl.className = `ss-block-status ss-block-status-${tone}`;
      statusEl.textContent = t.label;
    }

    // Meta line (last synced / last attempted)
    const metaEl = block.querySelector('.ss-block-meta');
    if (syncAtText) {
      if (metaEl) {
        metaEl.textContent = `· ${tone === 'synced' ? 'Last synced' : 'Last attempted'} ${syncAtText}`;
        metaEl.style.display = '';
      }
    }

    // Button
    btn.className = `ss-btn ${t.btnClass}`;
    btn.disabled  = tone === 'syncing';
    const btnLabel = document.getElementById('ss-btn-label');
    if (btnLabel) btnLabel.textContent = t.btnText;

    const btnIcon = document.getElementById('ss-btn-icon');
    if (btnIcon) {
      if (tone === 'syncing') {
        btnIcon.innerHTML = '<circle cx="12" cy="12" r="9" stroke-dasharray="28 56" class="ss-spin-circle"/>';
        btnIcon.style.animation = 'ss-spin 0.8s linear infinite';
      } else {
        btnIcon.style.animation = '';
        if (tone === 'synced') {
          btnIcon.innerHTML = '<polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/>';
        } else if (tone === 'failed') {
          btnIcon.innerHTML = '<path d="M3 12a9 9 0 1 0 3-6.7"/><polyline points="3 4 3 9 8 9"/>';
        } else {
          btnIcon.innerHTML = '<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/>';
        }
      }
    }
  }

  btn.addEventListener('click', function () {
    setTone('syncing');

    fetch('/rfq/quote/sync_to_sheet/', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: new URLSearchParams({ id_rfq: idRfq }),
    })
      .then(function (res) { return res.json(); })
      .then(function (data) {
        if (data.success) {
          setTone('synced', data.sync_at);
        } else {
          setTone('failed', null);
          console.error('Sheet sync failed:', data.message);
        }
      })
      .catch(function (err) {
        setTone('failed', null);
        console.error('Sheet sync error:', err);
      });
  });
}());
