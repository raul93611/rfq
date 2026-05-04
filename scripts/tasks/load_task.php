<?php
try {
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  $task     = TaskRepository::get_one($conexion, $id_task);
  $users    = RepositorioUsuario::get_enable_users($conexion);
  $comments = TaskCommentRepository::get_all($conexion, $id_task);
} catch (Exception $e) {
  die("Error fetching task data: " . $e->getMessage());
} finally {
  Conexion::cerrar_conexion();
}

$statuses = ['todo' => 'To Do', 'in_progress' => 'In Progress', 'done' => 'Done'];
?>
<input type="hidden" name="id_task" value="<?= htmlspecialchars($id_task) ?>">
<input type="hidden" name="old_status" value="<?= htmlspecialchars($task->get_status()) ?>">

<div class="modal-body">
  <!-- Status toggle -->
  <div class="form-group">
    <label class="d-block" style="font-family:'Manrope',sans-serif;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.4px;color:#39485a;margin-bottom:8px;">Status</label>
    <div class="btn-group btn-group-toggle task-status-group" data-toggle="buttons">
      <?php foreach ($statuses as $val => $label) :
        $active  = $task->get_status() === $val ? 'active' : '';
        $checked = $task->get_status() === $val ? 'checked' : '';
      ?>
        <label class="btn <?= $active ?>">
          <input type="radio" name="status" autocomplete="off" value="<?= $val ?>" <?= $checked ?>> <?= $label ?>
        </label>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Title -->
  <p class="task-detail-title"><?= htmlspecialchars($task->get_title()) ?></p>

  <!-- Assigned user -->
  <div class="form-group">
    <label>Assigned to</label>
    <?php if ($task->get_status() === 'done') : ?>
      <input type="hidden" name="assigned_user" value="<?= htmlspecialchars($task->get_assigned_user()) ?>">
      <p style="font-size:13px;margin:4px 0 0;color:#39485a;"><?= htmlspecialchars($task->get_assigned_user_name()) ?></p>
    <?php else : ?>
      <select id="assigned_user" class="form-control form-control-sm" name="assigned_user">
        <?php foreach ($users as $user) : ?>
          <option value="<?= htmlspecialchars($user->obtener_id()) ?>" <?= $user->obtener_id() == $task->get_assigned_user() ? 'selected' : '' ?>>
            <?= htmlspecialchars($user->obtener_nombre_usuario()) ?>
          </option>
        <?php endforeach; ?>
      </select>
    <?php endif; ?>
  </div>

  <!-- Description -->
  <div class="form-group">
    <label>Description</label>
    <p style="font-size:13px;color:#39485a;line-height:1.6;background:#f4f6f9;border-radius:8px;padding:10px 12px;margin:4px 0 0;">
      <?= nl2br(htmlspecialchars($task->get_message())) ?>
    </p>
  </div>

  <!-- New comment -->
  <div class="form-group">
    <label for="task_comment">Add Comment</label>
    <textarea id="task_comment" class="form-control form-control-sm" name="comment" rows="2" placeholder="Write a comment…"></textarea>
  </div>

  <!-- Comments timeline -->
  <?php if (!empty($comments)) : ?>
    <p class="task-comments-label"><i class="fas fa-comments mr-1"></i>Comments</p>
    <div class="task-timeline">
      <?php foreach ($comments as $comment) : ?>
        <div class="task-comment">
          <div class="task-comment-avatar">
            <i class="fas fa-user"></i>
          </div>
          <div class="task-comment-body">
            <div class="task-comment-header">
              <span class="task-comment-author"><?= htmlspecialchars($comment->get_id_user_name()) ?></span>
              <span class="task-comment-date"><i class="far fa-clock mr-1"></i><?= date("M d, Y", strtotime($comment->get_created_at())) ?></span>
            </div>
            <div class="task-comment-text"><?= nl2br(htmlspecialchars($comment->get_comment())) ?></div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<div class="modal-footer">
  <button type="submit" name="save" form="edit_task_form" class="btn btn-primary btn-sm">
    <i class="fas fa-save mr-1"></i>Save Changes
  </button>
</div>
