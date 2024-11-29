<div class="content-wrapper kanban">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-md-6">
          <h1 class="m-0 text-dark">Tasks</h1>
        </div>
        <div class="col-md-6 text-right">
          <button id="add_task_button" class="btn btn-primary" aria-label="Add New Task">
            <i class="fas fa-plus"></i> Add Task
          </button>
        </div>
      </div>
    </div>
  </div>

  <section id="my_tasks_board" class="content pb-3">
    <!-- Task board content will be dynamically inserted here -->
  </section>
</div>

<?php
// Include modals for task management
include_once 'modals/add_task_modal.inc.php';
include_once 'modals/edit_task_modal.inc.php';
?>

<!-- JavaScript for handling tasks -->
<script src="<?= RUTA_JS; ?>tasks.js"></script>