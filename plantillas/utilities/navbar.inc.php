<?php
Conexion::abrir_conexion();
$conexion = Conexion::obtener_conexion();
$unread_count = NotificationRepository::getUnreadCount($conexion, $_SESSION['user']->obtener_id());
Conexion::cerrar_conexion();
?>
<nav class="main-header navbar navbar-expand navbar-dark">
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" id="sidebar_collapse" data-widget="pushmenu" href="#" aria-label="Toggle Sidebar">
        <i class="fa fa-bars"></i>
      </a>
    </li>
  </ul>
  <ul class="navbar-nav ml-auto">
    <!-- Bell icon -->
    <li class="nav-item nf-bell-wrap" id="nf_bell_li">
      <button class="nf-bell-btn" id="nf_bell_btn" aria-label="Notifications" type="button">
        <i class="fas fa-bell" style="font-size:16px;"></i>
        <span class="nf-badge" id="nf_badge" style="<?= $unread_count === 0 ? 'display:none;' : '' ?>">
          <?= $unread_count > 99 ? '99+' : $unread_count ?>
        </span>
      </button>

      <!-- Dropdown -->
      <div class="nf-dropdown" id="nf_dropdown" style="display:none;" role="dialog" aria-label="Notifications">
        <div class="nf-dropdown-head">
          <span class="nf-dropdown-title">Notifications</span>
          <span class="nf-dropdown-count" id="nf_dropdown_count" style="<?= $unread_count === 0 ? 'display:none;' : '' ?>">
            <?= $unread_count ?> unread
          </span>
        </div>
        <div class="nf-dropdown-list" id="nf_dropdown_list">
          <div class="nf-dropdown-loading">
            <div class="nf-spinner"></div>
          </div>
        </div>
        <div class="nf-dropdown-foot">
          <button type="button" class="ap-btn-link" id="nf_mark_all_btn">
            <i class="fas fa-check-double" style="font-size:11px;"></i>
            Mark all as read
          </button>
          <a href="<?= MY_NOTIFICATIONS ?>" class="ap-btn-link">
            See all <i class="fas fa-arrow-right" style="font-size:11px;"></i>
          </a>
        </div>
      </div>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="<?= LOGOUT ?>" aria-label="Log out">
        Log out <i class="fas fa-power-off"></i>
      </a>
    </li>
  </ul>
</nav>

<script>
(function () {
  let dropdownOpen = false;
  const bellBtn    = document.getElementById('nf_bell_btn');
  const dropdown   = document.getElementById('nf_dropdown');
  const badge      = document.getElementById('nf_badge');
  const countEl    = document.getElementById('nf_dropdown_count');
  const listEl     = document.getElementById('nf_dropdown_list');
  const markAllBtn = document.getElementById('nf_mark_all_btn');

  function setCount(n) {
    if (n > 0) {
      badge.textContent = n > 99 ? '99+' : n;
      badge.style.display = '';
      countEl.textContent = n + ' unread';
      countEl.style.display = '';
      markAllBtn.disabled = false;
    } else {
      badge.style.display = 'none';
      countEl.style.display = 'none';
      markAllBtn.disabled = true;
    }
  }

  function relativeTime(dt) {
    const diff = Math.floor((Date.now() - new Date(dt).getTime()) / 1000);
    if (diff < 60)    return 'Just now';
    if (diff < 3600)  return Math.floor(diff / 60) + ' min ago';
    if (diff < 86400) return Math.floor(diff / 3600) + ' hr ago';
    if (diff < 172800) return 'Yesterday';
    return Math.floor(diff / 86400) + ' days ago';
  }

  function renderItems(items) {
    if (!items || items.length === 0) {
      return '<div class="nf-dropdown-empty">' +
        '<div class="nf-dropdown-empty-icon"><i class="fas fa-check" style="font-size:20px;"></i></div>' +
        '<div class="nf-dropdown-empty-title">You\'re all caught up</div>' +
        '<div class="nf-dropdown-empty-sub">No new notifications</div>' +
        '</div>';
    }
    return items.map(function (n) {
      return '<a href="' + n.url + '" class="nf-row nf-row-link" data-id="' + n.id + '">' +
        '<div class="nf-dot-wrap"><span class="nf-dot' + (n.is_read ? ' nf-dot-hidden' : '') + '"></span></div>' +
        '<div class="nf-body"><div class="nf-msg' + (n.is_read ? ' nf-msg-read' : '') + '">' + escapeHtml(n.message) + '</div>' +
        '<div class="nf-ts">' + relativeTime(n.created_at) + '</div></div>' +
        '<div class="nf-chev"><i class="fas fa-chevron-right" style="font-size:12px;"></i></div>' +
        '</a>';
    }).join('');
  }

  function escapeHtml(s) {
    return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
  }

  function loadDropdown() {
    listEl.innerHTML = '<div class="nf-dropdown-loading"><div class="nf-spinner"></div></div>';
    fetch('<?= NOTIFICATIONS_LIST ?>')
      .then(function (r) { return r.json(); })
      .then(function (data) {
        setCount(data.count || 0);
        listEl.innerHTML = renderItems(data.items || []);
        // attach click handlers
        listEl.querySelectorAll('.nf-row-link').forEach(function (row) {
          row.addEventListener('click', function (e) {
            e.preventDefault();
            const id  = this.dataset.id;
            const url = this.href;
            fetch('<?= NOTIFICATIONS_MARK_READ ?>', {
              method: 'POST',
              headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
              body: 'id=' + encodeURIComponent(id),
            }).finally(function () { window.location.href = url; });
          });
        });
      })
      .catch(function () {
        listEl.innerHTML = '<div class="nf-dropdown-empty"><div class="nf-dropdown-empty-sub">Failed to load.</div></div>';
      });
  }

  function openDropdown() {
    dropdownOpen = true;
    dropdown.style.display = '';
    bellBtn.classList.add('is-open');
    loadDropdown();
  }

  function closeDropdown() {
    dropdownOpen = false;
    dropdown.style.display = 'none';
    bellBtn.classList.remove('is-open');
  }

  bellBtn.addEventListener('click', function (e) {
    e.stopPropagation();
    dropdownOpen ? closeDropdown() : openDropdown();
  });

  document.addEventListener('click', function (e) {
    if (dropdownOpen && !dropdown.contains(e.target) && e.target !== bellBtn) {
      closeDropdown();
    }
  });

  markAllBtn.addEventListener('click', function () {
    fetch('<?= NOTIFICATIONS_MARK_READ ?>', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: '',
    }).then(function () {
      setCount(0);
      loadDropdown();
    });
  });

  // SSE for real-time badge updates
  if (typeof EventSource !== 'undefined') {
    const sse = new EventSource('<?= NOTIFICATIONS_STREAM ?>');
    sse.onmessage = function (e) {
      try {
        const data = JSON.parse(e.data);
        setCount(data.count || 0);
        if (dropdownOpen) {
          listEl.innerHTML = renderItems(data.items || []);
        }
      } catch (_) {}
    };
    sse.onerror = function () { sse.close(); };
  }
})();
</script>
