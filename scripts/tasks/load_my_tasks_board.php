<?php
try {
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  $tasks = [
    'todo' => TaskRepository::get_my_todo($conexion),
    'in_progress' => TaskRepository::get_my_in_progress($conexion),
    'done' => TaskRepository::get_my_done($conexion)
  ];
} catch (Exception $e) {
  // Handle exceptions (e.g., log the error, display a message to the user)
  die("Error fetching tasks: " . $e->getMessage());
} finally {
  Conexion::cerrar_conexion();
}

function renderTaskCard($tasks, $status) {
  $statusMap = [
    'todo' => ['title' => 'To Do', 'class' => 'primary'],
    'in_progress' => ['title' => 'In Progress', 'class' => 'info'],
    'done' => ['title' => 'Done', 'class' => 'success']
  ];
?>
  <div class="card card-row card-<?= $statusMap[$status]['class']; ?>">
    <div class="card-header">
      <h3 class="card-title"><?= $statusMap[$status]['title']; ?></h3>
    </div>
    <div class="card-body">
      <?php foreach ($tasks as $task) : ?>
        <div class="card card-primary card-outline">
          <div class="card-header">
            <h5 class="card-title">
              <a class="edit_task_button" data="<?= $task->get_id(); ?>" href="#"><?= $task->get_title(); ?></a>
            </h5>
          </div>
          <div class="card-body">
            <span class="text-info"><i class="fas fa-user"></i> Created by:</span> <?= $task->get_id_user_name(); ?><br>
            <span class="text-info"><i class="fas fa-user"></i> Assigned to:</span> <?= $task->get_assigned_user_name(); ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
<?php
}

?>

<div class="container-fluid h-100">
  <?php
  renderTaskCard($tasks['todo'], 'todo');
  renderTaskCard($tasks['in_progress'], 'in_progress');
  renderTaskCard($tasks['done'], 'done');
  ?>
</div>