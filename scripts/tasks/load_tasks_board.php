<?php
try {
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  $tasks = [
    'todo' => TaskRepository::get_todo($conexion),
    'in_progress' => TaskRepository::get_in_progress($conexion),
    'done' => TaskRepository::get_done($conexion),
  ];
} catch (Exception $e) {
  // Handle exceptions (e.g., log the error, display a message to the user)
  die("Error fetching tasks: " . $e->getMessage());
} finally {
  Conexion::cerrar_conexion();
}

function render_tasks($tasks, $status) {
  foreach ($tasks as $task) {
    echo '<div class="card card-primary card-outline">';
    echo '<div class="card-header">';
    echo '<h5 class="card-title"><a class="edit_task_button" data="' . htmlspecialchars($task->get_id()) . '" href="#">' . htmlspecialchars($task->get_title()) . '</a></h5>';
    echo '</div>';
    echo '<div class="card-body">';
    echo '<span class="text-info"><i class="fas fa-user"></i> Created by:</span> ' . htmlspecialchars($task->get_id_user_name()) . '<br>';
    echo '<span class="text-info"><i class="fas fa-user"></i> Assigned to:</span> ' . htmlspecialchars($task->get_assigned_user_name());
    echo '</div>';
    echo '</div>';
  }
}

?>
<div class="container-fluid h-100">
  <?php
  $statuses = [
    'todo' => ['title' => 'To Do', 'class' => 'primary'],
    'in_progress' => ['title' => 'In Progress', 'class' => 'info'],
    'done' => ['title' => 'Done', 'class' => 'success']
  ];

  foreach ($statuses as $status => $info) {
    echo '<div class="card card-row card-' . $info['class'] . '">';
    echo '<div class="card-header bg-' . $info['class'] . '">';
    echo '<h3 class="card-title">' . $info['title'] . '</h3>';
    echo '</div>';
    echo '<div class="card-body">';
    render_tasks($tasks[$status], $status);
    if ($status === 'done') {
      echo '<div class="text-center">';
      echo '<a href="' . TASKS_DONE . '" class="btn btn-link">View all</a>';
      echo '</div>';
    }
    echo '</div>';
    echo '</div>';
  }
  ?>
</div>