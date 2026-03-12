<div class="content-wrapper">
  <div class="content-header page-header-bar">
    <div>
      <h1 class="page-title">My Tasks</h1>
      <p class="page-subtitle">Tasks assigned to you</p>
    </div>
    <button id="add_task_button" class="btn btn-primary btn-sm" type="button">
      <i class="fas fa-plus mr-1"></i> Add Task
    </button>
  </div>

  <section id="my_tasks_board" class="content">
    <!-- Task board loaded via AJAX -->
  </section>
</div>

<?php
include_once 'modals/add_task_modal.inc.php';
include_once 'modals/edit_task_modal.inc.php';
?>

<script src="<?= RUTA_JS; ?>tasks.js"></script>