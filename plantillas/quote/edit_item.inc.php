<?php
// Ensure session is active before allowing access
if (!ControlSesion::sesion_iniciada()) {
  Redireccion::redirigir1(SERVIDOR);
}
?>
<div class="content-wrapper">
  <div class="content-header page-header-bar">
    <div>
      <h1 class="page-title">Edit Item</h1>
      <p class="page-subtitle" id="edit-item-subtitle">Loading quote info...</p>
    </div>
  </div>

  <section class="content" style="padding-top:20px;">
    <div class="container-fluid">
      <div class="row justify-content-center">
        <div class="col-lg-10 col-md-12">
          <div class="chart-card">
            <form role="form" method="post" action="<?= htmlspecialchars(GUARDAR_EDIT_ITEM . $id_item); ?>">
              <?php include_once 'forms/quote/edicion_item_vacio.inc.php'; ?>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>