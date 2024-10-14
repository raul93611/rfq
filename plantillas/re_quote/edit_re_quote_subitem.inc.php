<div class="content-wrapper">
  <!-- Content Header -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-md-6">
          <h1>Edit Subitem</h1>
        </div>
        <div class="col-md-6">
          <!-- Placeholder for additional controls or breadcrumbs -->
        </div>
      </div>
    </div>
  </section>

  <!-- Main Content Section -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <!-- Card for Editing Subitem -->
          <div class="card card-primary">
            <!-- Card Header with Title and Icon -->
            <div class="card-header">
              <h3 class="card-title">
                <i class="fas fa-edit"></i> Update Subitem Details
              </h3>
            </div>

            <!-- Form for Editing Subitem -->
            <form role="form" method="post" action="<?= SAVE_EDIT_RE_QUOTE_SUBITEM; ?>">
              <?php include_once 'forms/re_quote/edit_re_quote_subitem_form.inc.php'; ?>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>