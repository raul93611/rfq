<?php
$per_page  = 15;
$page      = max(1, (int)($_GET['page'] ?? 1));
$offset    = ($page - 1) * $per_page;
$id_user   = $_SESSION['user']->obtener_id();

Conexion::abrir_conexion();
$conexion = Conexion::obtener_conexion();

$total    = NotificationRepository::getTotalCount($conexion, $id_user);
$unread   = NotificationRepository::getUnreadCount($conexion, $id_user);
$notifs   = NotificationRepository::getAll($conexion, $id_user, $offset, $per_page);

Conexion::cerrar_conexion();

$total_pages = max(1, (int) ceil($total / $per_page));

function nf_relative_time($datetime_str) {
  $diff = time() - strtotime($datetime_str);
  if ($diff < 60)        return 'Just now';
  if ($diff < 3600)      return floor($diff / 60) . ' min ago';
  if ($diff < 86400)     return floor($diff / 3600) . ' hr ago';
  if ($diff < 172800)    return 'Yesterday';
  if ($diff < 604800)    return floor($diff / 86400) . ' days ago';
  return date('M j, Y', strtotime($datetime_str));
}
?>
<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="nf-page-head">
        <div class="nf-page-head-left">
          <a href="javascript:history.back()" class="nf-back-btn" aria-label="Back">
            <i class="fas fa-arrow-left"></i>
          </a>
          <div>
            <div class="nf-page-title">Notifications</div>
            <div class="nf-page-sub">All updates from quotes &amp; fulfillments you're following</div>
          </div>
        </div>
        <?php if ($total > 0): ?>
        <div class="nf-page-head-actions">
          <button type="button" class="ap-btn" id="nf_mark_all_btn">
            <i class="fas fa-check-double mr-1" style="font-size:11px;"></i>
            Mark all as read
          </button>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <div class="content">
    <div class="container-fluid">
      <div class="nl-card">

        <?php if ($total === 0): ?>
          <div class="nl-empty">
            <div class="nl-empty-illus">
              <i class="fas fa-inbox" style="font-size:44px;color:var(--nf-ink-300);"></i>
            </div>
            <div class="nl-empty-title">No notifications yet</div>
            <div class="nl-empty-sub">
              When teammates mention you with @ in a comment, or reply to your comments,
              you'll see those updates here.
            </div>
          </div>
        <?php else: ?>

          <div class="nl-card-head">
            <div>
              <span class="nl-card-head-title">All notifications</span>
              <span class="nl-card-head-sub">· <?= $total ?> total · <?= $unread ?> unread</span>
            </div>
          </div>

          <?php foreach ($notifs as $n):
            $is_read = (bool)(int)$n['is_read'];
          ?>
          <a href="<?= htmlspecialchars($n['url']) ?>" class="nf-row nl-row nf-row-link <?= $is_read ? 'nl-row-read' : '' ?>"
             data-id="<?= (int)$n['id'] ?>" data-testid="notification-row">
            <div class="nf-dot-wrap">
              <span class="nf-dot <?= $is_read ? 'nf-dot-hidden' : '' ?>"></span>
            </div>
            <div class="nf-body">
              <div class="nf-msg <?= $is_read ? 'nf-msg-read' : '' ?>">
                <?= htmlspecialchars($n['message']) ?>
              </div>
            </div>
            <div class="nf-ts"><?= nf_relative_time($n['created_at']) ?></div>
            <div class="nf-chev"><i class="fas fa-chevron-right" style="font-size:12px;color:var(--nf-ink-300);"></i></div>
          </a>
          <?php endforeach; ?>

          <div class="nl-foot">
            <div class="nl-foot-meta">
              Showing <?= $offset + 1 ?>–<?= min($offset + $per_page, $total) ?> of <?= $total ?>
            </div>
            <div class="nl-pager">
              <a href="?page=<?= $page - 1 ?>" class="nl-pager-btn <?= $page <= 1 ? 'nl-pager-btn-disabled' : '' ?>"
                 <?= $page <= 1 ? 'aria-disabled="true"' : '' ?>>‹</a>
              <?php for ($p = 1; $p <= $total_pages; $p++): ?>
                <a href="?page=<?= $p ?>" class="nl-pager-btn <?= $p === $page ? 'nl-pager-btn-active' : '' ?>"><?= $p ?></a>
              <?php endfor; ?>
              <a href="?page=<?= $page + 1 ?>" class="nl-pager-btn <?= $page >= $total_pages ? 'nl-pager-btn-disabled' : '' ?>"
                 <?= $page >= $total_pages ? 'aria-disabled="true"' : '' ?>>›</a>
            </div>
          </div>

        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<script>
(function () {
  // Click on notification row — mark as read then navigate
  document.querySelectorAll('.nf-row-link').forEach(function (row) {
    row.addEventListener('click', function (e) {
      e.preventDefault();
      const id  = this.dataset.id;
      const url = this.href;
      fetch('<?= NOTIFICATIONS_MARK_READ ?>', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'id=' + encodeURIComponent(id),
      }).finally(() => { window.location.href = url; });
    });
  });

  // Mark all as read
  const markAllBtn = document.getElementById('nf_mark_all_btn');
  if (markAllBtn) {
    markAllBtn.addEventListener('click', function () {
      this.disabled = true;
      fetch('<?= NOTIFICATIONS_MARK_READ ?>', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: '',
      }).then(() => location.reload());
    });
  }
})();
</script>
