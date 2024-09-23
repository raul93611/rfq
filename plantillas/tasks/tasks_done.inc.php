<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-12">
          <h1 class="m-0 text-dark">Tasks</h1>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Tasks Done</h3>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table id="tasks_done_table" class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <th scope="col">ID</th>
                      <th scope="col">Title</th>
                      <th scope="col">Created By</th>
                      <th scope="col">Assigned To</th>
                    </tr>
                  </thead>
                  <tbody>
                    <!-- Dynamic task rows will be inserted here -->
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<!-- Include modals for editing tasks -->
<?php include_once 'modals/edit_task_modal.inc.php'; ?>

<!-- Tasks JS script -->
<script src="<?= RUTA_JS; ?>tasks.js"></script>