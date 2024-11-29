<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Users</h1>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <section class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Registered Users</h3>
            </div>
            <div class="card-body table-responsive">
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
                  <!-- Placeholder for empty or loading state -->
                  <tr>
                    <td colspan="7" class="text-center">No users available</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </section>
      </div>
    </div>
  </section>
</div>

<!-- External JS file with version control for cache busting -->
<script src="<?= RUTA_JS; ?>users.js"></script>