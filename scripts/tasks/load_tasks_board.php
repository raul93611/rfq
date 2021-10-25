<?php
Conexion::abrir_conexion();
$tasks_todo = TaskRepository::get_todo(Conexion::obtener_conexion());
$tasks_in_progress = TaskRepository::get_in_progress(Conexion::obtener_conexion());
$tasks_done = TaskRepository::get_done(Conexion::obtener_conexion());
conexion::cerrar_conexion();
?>
<div class="container-fluid h-100">
  <div class="card card-row card-primary">
    <div class="card-header">
      <h3 class="card-title">
        To Do
      </h3>
    </div>
    <div class="card-body">
      <?php
      foreach ($tasks_todo as $key => $task_todo) {
        ?>
        <div class="card card-primary card-outline">
          <div class="card-header">
            <h5 class="card-title"><a class="edit_task_button" data="<?php echo $task_todo-> get_id(); ?>" href="#"><?php echo $task_todo-> get_title(); ?></a></h5>
          </div>
          <div class="card-body">
            <span class="text-info"><i class="fas fa-user"></i> Created by:</span> <?php echo $task_todo-> get_id_user_name(); ?><br>
            <span class="text-info"><i class="fas fa-user"></i> Assigned to:</span> <?php echo $task_todo-> get_assigned_user_name(); ?>
          </div>
        </div>
        <?php
      }
      ?>
    </div>
  </div>
  <div class="card card-row card-default">
    <div class="card-header bg-info">
      <h3 class="card-title">
        In Progress
      </h3>
    </div>
    <div class="card-body">
      <?php
      foreach ($tasks_in_progress as $key => $task_in_progress) {
        ?>
        <div class="card card-primary card-outline">
          <div class="card-header">
            <h5 class="card-title"><a class="edit_task_button" data="<?php echo $task_in_progress-> get_id(); ?>" href="#"><?php echo $task_in_progress-> get_title(); ?></a></h5>
          </div>
          <div class="card-body">
            <span class="text-info"><i class="fas fa-user"></i> Created by:</span> <?php echo $task_in_progress-> get_id_user_name(); ?><br>
            <span class="text-info"><i class="fas fa-user"></i> Assigned to:</span> <?php echo $task_in_progress-> get_assigned_user_name(); ?>
          </div>
        </div>
        <?php
      }
      ?>
    </div>
  </div>
  <div class="card card-row card-success">
    <div class="card-header">
      <h3 class="card-title">
        Done
      </h3>
    </div>
    <div class="card-body">
      <?php
      foreach ($tasks_done as $key => $task_done) {
        ?>
        <div class="card card-primary card-outline">
          <div class="card-header">
            <h5 class="card-title"><a class="edit_task_button" data="<?php echo $task_done-> get_id(); ?>" href="#"><?php echo $task_done-> get_title(); ?></a></h5>
          </div>
          <div class="card-body">
            <span class="text-info"><i class="fas fa-user"></i> Created by:</span> <?php echo $task_done-> get_id_user_name(); ?><br>
            <span class="text-info"><i class="fas fa-user"></i> Assigned to:</span> <?php echo $task_done-> get_assigned_user_name(); ?>
          </div>
        </div>
        <?php
      }
      ?>
      <div class="text-center">
        <a href="<?php echo TASKS_DONE; ?>" class="btn btn-link">View all</a>
      </div>
    </div>
  </div>
</div>
