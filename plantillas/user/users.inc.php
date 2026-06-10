<div class="content-wrapper">

  <div class="content-header page-header-bar">
    <div>
      <h1 class="page-title">Users</h1>
      <p class="page-subtitle">Manage registered users and their roles</p>
    </div>
    <a href="<?= REGISTRO ?>" class="btn btn-primary btn-sm">
      <i class="fas fa-plus mr-1"></i> Add User
    </a>
  </div>

  <section class="content" style="padding-top:20px;">
    <div class="container-fluid">
      <div class="card chart-card">
        <div class="card-body">
          <table id="tabla_usuarios" class="table table-bordered table-hover" aria-label="Registered Users Table">
            <thead>
              <tr>
                <th scope="col">ID</th>
                <th scope="col">Roles</th>
                <th scope="col">First Name</th>
                <th scope="col">Last Name</th>
                <th scope="col">Username</th>
                <th scope="col">Status</th>
                <th scope="col">Options</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td colspan="7" class="text-center">No users available</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>

</div>

<script src="<?= asset_url('js/users.js'); ?>"></script>
