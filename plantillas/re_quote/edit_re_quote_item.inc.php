<div class="content-wrapper">
  <!-- Content Header -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-md-6">
          <h1>Edit Item</h1>
        </div>
        <div class="col-md-6">
          <!-- Placeholder for additional actions or breadcrumbs -->
        </div>
      </div>
    </div>
  </section>

  <!-- Main Content Section -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card card-primary">
            <!-- Card Header -->
            <div class="card-header">
              <h3 class="card-title">
                <i class="fas fa-edit"></i> Edit the Data
              </h3>
            </div>

            <!-- Form Section -->
            <form role="form" method="post" action="<?= SAVE_EDIT_RE_QUOTE_ITEM; ?>">
              <?php include_once 'forms/re_quote/edit_re_quote_item_form.inc.php'; ?>
            </form>

          </div>
        </div>
      </div>
    </div>
  </section>
</div>