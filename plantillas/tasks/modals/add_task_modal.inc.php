<div class="modal fade" id="add_task_modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="add_task_form" method="post" action="">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" class="form-control form-control-sm" name="title" id="title" value="" required>
              </div>
              <div class="form-group">
                <label for="assigned_user">Assigned user:</label>
                <select id="assigned_user" class="form-control form-control-sm" name="assigned_user">
                  <?php
                  Conexion::abrir_conexion();
                  $users = RepositorioUsuario::get_enable_users(Conexion::obtener_conexion());
                  Conexion::cerrar_conexion();
                  foreach ($users as $user) {
                    ?>
                    <option value="<?php echo $user-> obtener_id(); ?>"><?php echo $user-> obtener_nombre_usuario(); ?></option>
                    <?php
                  }
                  ?>
                </select>
              </div>
              <div class="form-group">
                <label for="message">Message:</label>
                <textarea name="message" id="message" class="form-control form-control-sm" rows="8" cols="80" required></textarea>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="submit" name="save" form="add_task_form" class="btn btn-success">Save</button>
      </div>
    </div>
  </div>
</div>
