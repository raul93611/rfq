<div class="content-wrapper kanban">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-md-6">
          <h1 class="m-0 text-dark">Tasks</h1>
        </div>
        <div class="col-md-6">
          <button id="add_task_button" class="btn btn-primary float-right" type="button" name="button"><i class="fas fa-plus"></i></button>
        </div>
      </div>
    </div>
  </div>
  <section id="my_tasks_board" class="content pb-3">

  </section>
</div>
<?php
include_once 'modals/add_task_modal.inc.php';
include_once 'modals/edit_task_modal.inc.php';
?>
<script src="<?php echo RUTA_JS; ?>tasks.js"></script>
