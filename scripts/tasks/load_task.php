<?php
Conexion::abrir_conexion();
$task = TaskRepository::get_one(Conexion::obtener_conexion(), $id_task);
$users = RepositorioUsuario::get_enable_users(Conexion::obtener_conexion());
$comments = TaskCommentRepository::get_all(Conexion::obtener_conexion(), $id_task);
Conexion::cerrar_conexion();
?>
<input type="hidden" name="id_task" value="<?php echo $id_task;?>">
<div class="modal-body">
  <div class="row">
    <div class="col-md-12">
      <div class="form-group">
        <input type="hidden" name="old_status" value="<?php echo $task-> get_status(); ?>">
        <div class="btn-group btn-block btn-group-toggle" data-toggle="buttons">
          <label class="btn bg-olive <?php echo $task-> get_status() == 'todo' ? 'active' : ''; ?>">
            <input type="radio" name="status" id="option_b1" autocomplete="off" value="todo" <?php echo $task-> get_status() == 'todo' ? 'checked' : ''; ?>> To Do
          </label>
          <label class="btn bg-olive <?php echo $task-> get_status() == 'in_progress' ? 'active' : ''; ?>">
            <input type="radio" name="status" id="option_b2" autocomplete="off" value="in_progress" <?php echo $task-> get_status() == 'in_progress' ? 'checked' : ''; ?>> In Progress
          </label>
          <label class="btn bg-olive <?php echo $task-> get_status() == 'done' ? 'active' : ''; ?>">
            <input type="radio" name="status" id="option_b3" autocomplete="off" value="done" <?php echo $task-> get_status() == 'done' ? 'checked' : ''; ?>> Done
          </label>
        </div>
      </div>
      <h4>Title: <?php echo $task-> get_title(); ?></h4>
      <div class="form-group">
        <label for="assigned_user">Assigned user:</label>
        <?php
        if($task-> get_status() == 'done'){
          ?>
          <input type="hidden" name="assigned_user" value="<?php echo $task-> get_assigned_user(); ?>">
          <p><?php echo $task-> get_assigned_user_name(); ?></p>
          <?php
        }else{
          ?>
          <select id="assigned_user" class="form-control form-control-sm" name="assigned_user">
            <?php
            foreach ($users as $user) {
              ?>
              <option value="<?php echo $user-> obtener_id(); ?>" <?php echo $user-> obtener_id() == $task-> get_assigned_user() ? 'selected' : ''; ?>><?php echo $user-> obtener_nombre_usuario(); ?></option>
              <?php
            }
            ?>
          </select>
          <?php
        }
        ?>
      </div>
      <div class="form-group">
        <label>Message:</label>
        <p><?php echo $task-> get_message(); ?></p>
      </div>
      <div class="form-group">
        <label for="task_comment">Comment:</label>
        <textarea id="task_comment" class="form-control form-control-sm" name="comment" rows="3" cols="80"></textarea>
      </div>
      <div class="timeline">
        <div>
          <i class="fa fa-bookmark"></i>
          <div class="timeline-item">
            <h3 class="timeline-header">Comments</h3>
          </div>
        </div>
        <?php
        if(count($comments)){
          foreach ($comments as $comment) {
            $created_at = RepositorioComment::mysql_datetime_to_english_format($comment-> get_created_at());
            ?>
            <div>
              <i class="fa fa-user"></i>
              <div class="timeline-item">
                <span class="time"><i class="far fa-clock"></i> <?php echo $created_at; ?></span>
                <h3 class="timeline-header">
                  <span class="text-primary"><?php echo $comment-> get_id_user_name(); ?></span></h3>
                  <div class="timeline-body">
                    <?php echo $comment-> get_comment(); ?>
                  </div>
                </div>
            </div>
            <?php
          }
        }
        ?>
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
