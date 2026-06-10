<div class="content-wrapper">

  <div class="content-header page-header-bar">
    <div>
      <h1 class="page-title">Add User</h1>
      <p class="page-subtitle">Create a new account and assign roles</p>
    </div>
    <a href="<?= USERS ?>" class="btn btn-secondary btn-sm">
      <i class="fas fa-arrow-left mr-1"></i> Back to Users
    </a>
  </div>

  <section class="content" style="padding-top:20px;">
    <div class="container-fluid">
      <div class="row justify-content-center">
        <div class="col-lg-7 col-md-10">
          <div class="card chart-card">
            <form id="add-user-form" role="form" method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>">
              <?php include_once 'forms/user/registro_usuario_vacio.inc.php'; ?>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>

</div>

<script src="<?= asset_url('js/users.js'); ?>"></script>
