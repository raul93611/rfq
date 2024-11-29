<div class="content-wrapper kanban">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2 align-items-center">
        <div class="col-md-6">
          <h1 class="m-0 text-dark">Tasks</h1>
        </div>
        <div class="col-md-6 text-right">
          <!-- Add Task Button with Accessibility -->
          <button id="add_task_button" class="btn btn-primary" type="button" aria-label="Add Task">
            <i class="fas fa-plus"></i> Add Task
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Task Board Section -->
  <section id="tasks_board" class="content pb-3">
    <!-- Task board content will go here -->
  </section>
</div>

<!-- Modals for adding/editing tasks -->
<?php
include_once 'modals/add_task_modal.inc.php';
include_once 'modals/edit_task_modal.inc.php';
?>

<!-- Tasks JS script -->
<script src="<?= RUTA_JS; ?>tasks.js"></script>