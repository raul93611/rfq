<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-md-6">
          <h1>Add provider subitem</h1>
        </div>
        <div class="col-md-6">

        </div>
      </div>
    </div>
  </section>
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title"><i class="fas fa-highlighter"></i> Enter the data</h3>
            </div>
            <form role="form" method="post" action="<?php echo SAVE_RE_QUOTE_SUBITEM_PROVIDER;?>">
              <?php
              include_once 'forms/add_re_quote_subitem_provider_form.inc.php';
              ?>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
