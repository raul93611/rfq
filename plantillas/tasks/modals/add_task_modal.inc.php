<div class="modal fade" id="add_task_modal" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="addTaskModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addTaskModalLabel"><i class="fas fa-plus-circle mr-2"></i>Add Task</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="add_task_form" method="post" action="">
          <div class="form-group">
            <label for="title">Title</label>
            <input type="text" class="form-control form-control-sm" name="title" id="title" placeholder="Enter a short, descriptive title" required>
          </div>
          <div class="form-group">
            <label for="assigned_user">Assigned to</label>
            <select id="assigned_user" class="form-control form-control-sm" name="assigned_user" required>
              <?php
              Conexion::abrir_conexion();
              $users = RepositorioUsuario::get_enable_users(Conexion::obtener_conexion());
              Conexion::cerrar_conexion();
              foreach ($users as $user) {
                echo "<option value='{$user->obtener_id()}'>{$user->obtener_nombre_usuario()}</option>";
              }
              ?>
            </select>
          </div>
          <div class="form-group">
            <label for="message">Description</label>
            <textarea name="message" id="message" class="form-control form-control-sm" rows="4" placeholder="Provide details or instructions for this task" required></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
        <button type="submit" form="add_task_form" class="btn btn-primary btn-sm"><i class="fas fa-save mr-1"></i>Save Task</button>
      </div>
    </div>
  </div>
</div>
