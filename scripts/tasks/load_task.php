<?php
try {
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  $task = TaskRepository::get_one($conexion, $id_task);
  $users = RepositorioUsuario::get_enable_users($conexion);
  $comments = TaskCommentRepository::get_all($conexion, $id_task);
} catch (Exception $e) {
  // Handle exceptions (e.g., log the error, display a message to the user)
  die("Error fetching task data: " . $e->getMessage());
} finally {
  Conexion::cerrar_conexion();
}
?>
<input type="hidden" name="id_task" value="<?= htmlspecialchars($id_task); ?>">
<div class="modal-body">
  <div class="row">
    <div class="col-md-12">
      <div class="form-group">
        <input type="hidden" name="old_status" value="<?= htmlspecialchars($task->get_status()); ?>">
        <div class="btn-group btn-block btn-group-toggle" data-toggle="buttons">
          <?php
          $statuses = ['todo' => 'To Do', 'in_progress' => 'In Progress', 'done' => 'Done'];
          foreach ($statuses as $status => $label) {
            $active = $task->get_status() === $status ? 'active' : '';
            $checked = $task->get_status() === $status ? 'checked' : '';
            echo "<label class='btn bg-olive $active'>
                    <input type='radio' name='status' autocomplete='off' value='$status' $checked> $label
                  </label>";
          }
          ?>
        </div>
        <small class="form-text text-muted">Select the current status of the task.</small>
      </div>
      <h4>Title: <?= htmlspecialchars($task->get_title()); ?></h4>
      <div class="form-group">
        <label for="assigned_user">Assigned user:</label>
        <?php if ($task->get_status() === 'done') : ?>
          <input type="hidden" name="assigned_user" value="<?= htmlspecialchars($task->get_assigned_user()); ?>">
          <p><?= htmlspecialchars($task->get_assigned_user_name()); ?></p>
        <?php else : ?>
          <select id="assigned_user" class="form-control form-control-sm" name="assigned_user">
            <?php foreach ($users as $user) : ?>
              <option value="<?= htmlspecialchars($user->obtener_id()); ?>" <?= $user->obtener_id() == $task->get_assigned_user() ? 'selected' : ''; ?>>
                <?= htmlspecialchars($user->obtener_nombre_usuario()); ?>
              </option>
            <?php endforeach; ?>
          </select>
          <small class="form-text text-muted">Assign the task to a specific user.</small>
        <?php endif; ?>
      </div>
      <div class="form-group">
        <label>Message:</label>
        <p><?= nl2br(htmlspecialchars($task->get_message())); ?></p>
        <small class="form-text text-muted">This is the message or description associated with the task.</small>
      </div>
      <div class="form-group">
        <label for="task_comment">Comment:</label>
        <textarea id="task_comment" class="form-control form-control-sm" name="comment" rows="3" cols="80"></textarea>
        <small class="form-text text-muted">Add your comment regarding this task.</small>
      </div>
      <div class="timeline">
        <div>
          <i class="fa fa-bookmark"></i>
          <div class="timeline-item">
            <h3 class="timeline-header">Comments</h3>
          </div>
        </div>
        <?php if (count($comments)) : ?>
          <?php foreach ($comments as $comment) : ?>
            <?php $created_at = date("m/d/Y", strtotime($comment->get_created_at())); ?>
            <div>
              <i class="fa fa-user"></i>
              <div class="timeline-item">
                <span class="time"><i class="far fa-clock"></i> <?= htmlspecialchars($created_at); ?></span>
                <h3 class="timeline-header">
                  <span class="text-primary"><?= htmlspecialchars($comment->get_id_user_name()); ?></span>
                </h3>
                <div class="timeline-body">
                  <?= nl2br(htmlspecialchars($comment->get_comment())); ?>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
        <div>
          <i class="fa fa-infinity"></i>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal-footer">
  <button type="submit" name="save" form="edit_task_form" class="btn btn-success">Save</button>
</div>
