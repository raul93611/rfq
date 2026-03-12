<div class="content-wrapper">
  <div class="content-header page-header-bar">
    <div>
      <h1 class="page-title">Tasks Done</h1>
      <p class="page-subtitle">Full history of completed tasks</p>
    </div>
    <a href="<?= PERFIL ?>" class="btn btn-secondary btn-sm">
      <i class="fas fa-arrow-left mr-1"></i> Back to Board
    </a>
  </div>

  <section class="content px-3 pt-3">
    <div class="card" style="border-radius:10px;border:none;box-shadow:0 1px 6px rgba(0,0,0,0.07);">
      <div class="card-body">
        <div class="table-responsive">
          <table id="tasks_done_table" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Created By</th>
                <th>Assigned To</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
  </section>
</div>

<?php include_once 'modals/edit_task_modal.inc.php'; ?>

<script src="<?= RUTA_JS; ?>tasks.js"></script>