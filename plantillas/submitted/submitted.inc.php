<?php
$channel = urldecode($cotizacion);
$printable_channel = Input::printable_channel($channel);
?>
<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Submitted Quotes</h1>
        </div>
      </div>
    </div>
  </div>
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <section class="col-12">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title"><?= htmlspecialchars($printable_channel); ?></h3>
            </div>
            <div class="card-body">
              <table id="submitted_table" data-channel="<?= htmlspecialchars($channel); ?>" class="table table-bordered table-responsive-md">
                <thead>
                  <tr>
                    <th scope="col">Proposal</th>
                    <th scope="col">Designated User</th>
                    <th scope="col">Type of Bid</th>
                    <th scope="col">Submitted Date</th>
                    <th scope="col">Code</th>
                    <th scope="col">RFP</th>
                    <th scope="col">Options</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- Dynamic content will be injected here -->
                </tbody>
              </table>
            </div>
          </div>
        </section>
      </div>
    </div>
  </section>
</div>