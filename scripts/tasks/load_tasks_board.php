<?php
try {
  Conexion::abrir_conexion();
  $conexion = Conexion::obtener_conexion();

  $tasks = [
    'todo'        => TaskRepository::get_todo($conexion),
    'in_progress' => TaskRepository::get_in_progress($conexion),
    'done'        => TaskRepository::get_done($conexion),
  ];
} catch (Exception $e) {
  die("Error fetching tasks: " . $e->getMessage());
} finally {
  Conexion::cerrar_conexion();
}

function render_task_cards(array $tasks): void
{
  if (empty($tasks)) {
    echo '<div class="kanban-empty"><i class="far fa-check-circle"></i>No tasks here</div>';
    return;
  }
  foreach ($tasks as $task) {
    $id    = htmlspecialchars($task->get_id());
    $title = htmlspecialchars($task->get_title());
    $by    = htmlspecialchars($task->get_id_user_name());
    $to    = htmlspecialchars($task->get_assigned_user_name());
    echo <<<HTML
    <div class="task-card">
      <a class="task-card-title edit_task_button" data-id="{$id}" href="#">{$title}</a>
      <div class="task-card-meta">
        <span><i class="fas fa-pencil-alt"></i> {$by}</span>
        <span><i class="fas fa-user"></i> {$to}</span>
      </div>
    </div>
    HTML;
  }
}

$columns = [
  'todo'        => ['label' => 'To Do',       'css' => 'col-todo'],
  'in_progress' => ['label' => 'In Progress',  'css' => 'col-progress'],
  'done'        => ['label' => 'Done',         'css' => 'col-done'],
];
?>
<div class="kanban-board">
  <?php foreach ($columns as $key => $col) : ?>
    <?php $count = count($tasks[$key]); ?>
    <div class="kanban-col <?= $col['css'] ?>">
      <div class="kanban-col-header">
        <span class="kanban-col-title"><?= $col['label'] ?></span>
        <span class="kanban-col-count"><?= $count ?></span>
      </div>
      <div class="kanban-col-body">
        <?php render_task_cards($tasks[$key]); ?>
      </div>
      <?php if ($key === 'done') : ?>
        <div class="kanban-col-view-all">
          <a href="<?= TASKS_DONE ?>"><i class="fas fa-list mr-1"></i>View all done</a>
        </div>
      <?php endif; ?>
    </div>
  <?php endforeach; ?>
</div>
