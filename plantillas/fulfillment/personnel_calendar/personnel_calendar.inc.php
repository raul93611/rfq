<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Personnel Calendar</h1>
        </div>
        <div class="col-sm-6">
          <button id="add-shared-event-button" class="float-right btn btn-primary">
            <i class="far fa-calendar-plus"></i> Add Shared Event
          </button>
        </div>
      </div>
    </div>
  </section>

  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div id="roadmap"></div>
        </div>
      </div>
    </div>
  </section>
</div>

<?php
// Include modal files for adding and editing events
include_once 'modals/add_modal.inc.php';
include_once 'modals/edit_modal.inc.php';
include_once 'modals/add_shared_event_modal.inc.php';
?>

<script src="<?= RUTA_JS; ?>roadmap.js"></script>