<?php
$channel = urldecode($cotizacion);
$printable_channel = Input::printable_channel($channel);
?>

<div class="content-wrapper">

  <div class="content-header page-header-bar">
    <div>
      <h1 class="page-title"><?= htmlspecialchars($printable_channel, ENT_QUOTES, 'UTF-8'); ?></h1>
      <p class="page-subtitle">All quotes in this channel</p>
    </div>
    <a href="<?= NUEVA_COTIZACION; ?>" class="btn btn-primary btn-sm">
      <i class="fas fa-plus mr-1"></i>New Quote
    </a>
  </div>

  <section class="content" style="padding-top:20px;">
    <div class="container-fluid">
      <div class="card chart-card">
        <div class="card-body">
          <table id="tabla_quotes" data-channel="<?= htmlspecialchars($channel, ENT_QUOTES, 'UTF-8'); ?>" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>Proposal</th>
                <th>Designated User</th>
                <th>Type of Bid</th>
                <th>Issue Date</th>
                <th>End Date</th>
                <th>Code</th>
                <th>Priority</th>
                <th>RFP</th>
                <th>Sheet</th>
                <th>Options</th>
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
