<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-12">
          <h1 class="m-0 text-dark">Tasks</h1>
        </div>
      </div>
    </div>
  </div>
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <section class="col-12">
          <div class="card">
            <div class="card-header align-middle">
              <h3 class="card-title">Tasks Done</h3>
            </div>
            <div class="card-body">
              <table id="tasks_done_table" class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th>TITLE</th>
                    <th>CREATED BY</th>
                    <th>ASSIGNED TO</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </section>
      </div>
    </div>
  </section>
</div>
<?php
include_once 'modals/edit_task_modal.inc.php';
?>
<script src="<?php echo RUTA_JS; ?>tasks.js"></script>
