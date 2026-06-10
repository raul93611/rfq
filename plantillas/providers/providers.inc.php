<div class="content-wrapper">

  <div class="content-header page-header-bar">
    <div>
      <h1 class="page-title">Providers</h1>
      <p class="page-subtitle">Manage vendor and provider records</p>
    </div>
    <div style="display:flex;gap:8px;">
      <a href="<?= PROVIDER_EXPORT_TO_EXCEL ?>" class="btn btn-secondary btn-sm">
        <i class="fas fa-file-excel mr-1"></i>Export
      </a>
      <button id="add_provider" class="btn btn-primary btn-sm" type="button">
        <i class="fas fa-plus mr-1"></i>Add Provider
      </button>
    </div>
  </div>

  <section class="content" style="padding-top:20px;">
    <div class="container-fluid">
      <div class="card chart-card">
        <div class="card-body">
          <table id="providers_table" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>Provider</th>
                <th style="width:100px;">Options</th>
              </tr>
            </thead>
            <tbody>
              <!-- Populated dynamically -->
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>

</div>

<?php
include_once 'modals/add_provider_modal.inc.php';
include_once 'modals/edit_provider_modal.inc.php';
?>

<script src="<?= asset_url('js/providers.js'); ?>"></script>
