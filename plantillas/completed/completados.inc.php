<?php
$channel = urldecode($cotizacion);
$printable_channel = Input::printable_channel($channel);
?>
<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Completed Quotes</h1>
        </div>
        <div class="col-sm-6">
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
              <h3 class="card-title"><?= $printable_channel; ?></h3>
            </div>
            <div class="card-body">
              <table id="completed_table" data-channel="<?= $channel; ?>" class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th>PROPOSAL</th>
                    <th>DESIGNATED USER</th>
                    <th>TYPE OF BID</th>
                    <th>COMPLETED DATE</th>
                    <th>CODE</th>
                    <th>RFP</th>
                    <th>OPTIONS</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>
        </section>
      </div>
    </div>
  </section>
</div>