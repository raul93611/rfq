<div class="modal fade" id="add_task_modal" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="addTaskModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addTaskModalLabel">Add Task</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="add_task_form" method="post" action="">
          <div class="form-group">
            <label for="title">Title:</label>
            <input type="text" class="form-control form-control-sm" name="title" id="title" aria-label="Task Title" required>
            <small class="form-text text-muted">Enter a short and descriptive title for the task.</small>
          </div>
          <div class="form-group">
            <label for="assigned_user">Assigned user:</label>
            <select id="assigned_user" class="form-control form-control-sm" name="assigned_user" aria-label="Assigned User" required>
              <?php
              // Fetch enabled users
              Conexion::abrir_conexion();
              $users = RepositorioUsuario::get_enable_users(Conexion::obtener_conexion());
              Conexion::cerrar_conexion();
              foreach ($users as $user) {
                echo "<option value='{$user->obtener_id()}'>{$user->obtener_nombre_usuario()}</option>";
              }
              ?>
            </select>
            <small class="form-text text-muted">Select the user to whom the task will be assigned.</small>
          </div>
          <div class="form-group">
            <label for="message">Message:</label>
            <textarea name="message" id="message" class="form-control form-control-sm" rows="5" aria-label="Task Message" required></textarea>
            <small class="form-text text-muted">Provide additional details or instructions for the task.</small>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="submit" form="add_task_form" class="btn btn-success">Save</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>