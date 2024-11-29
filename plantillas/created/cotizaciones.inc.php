<?php
$channel = urldecode($cotizacion);
$printable_channel = Input::printable_channel($channel);
?>

<div class="content-wrapper">
  <!-- Content Header -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark"><?= htmlspecialchars($printable_channel, ENT_QUOTES, 'UTF-8'); ?></h1>
        </div>
        <div class="col-sm-6 text-right">
          <!-- Optional: Add additional controls or breadcrumbs here -->
        </div>
      </div>
    </div>
  </div>

  <!-- Main Content Section -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <section class="col-12">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Quotes</h3>
              <!-- Optional: Add card tools like filters or search here -->
            </div>
            <div class="card-body table-responsive">
              <table id="tabla_quotes" data-channel="<?= htmlspecialchars($channel, ENT_QUOTES, 'UTF-8'); ?>" class="table table-bordered table-hover">
                <thead class="thead-light">
                  <tr>
                    <th>Proposal</th>
                    <th>Designated User</th>
                    <th>Type of Bid</th>
                    <th>Issue Date</th>
                    <th>End Date</th>
                    <th>Code</th>
                    <th>RFP</th>
                    <th>Options</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- Table rows populated dynamically -->
                </tbody>
              </table>
            </div>
          </div>
        </section>
      </div>
    </div>
  </section>
</div>