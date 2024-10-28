<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Providers</h1>
        </div>
        <div class="col-sm-6">
          <button id="add_provider" class="float-right btn btn-primary" type="button" name="button">
            <i class="fas fa-plus"></i> Add Provider
          </button>
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
              <h3 class="card-title">Providers List</h3>
            </div>
            <div class="card-body">
              <table id="providers_table" class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th>Provider</th>
                    <th style="width: 100px;">Options</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- Data will be dynamically populated here -->
                </tbody>
              </table>
            </div>
          </div>
        </section>
      </div>
    </div>
  </section>
</div>

<?php
include_once 'modals/add_provider_modal.inc.php';
include_once 'modals/edit_provider_modal.inc.php';
?>

<script src="<?= RUTA_JS; ?>providers.js"></script>