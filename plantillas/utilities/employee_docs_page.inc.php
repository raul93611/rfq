<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Hi,
            <?php
            echo $_SESSION['nombre_usuario'];
            ?>
          </h1>
        </div>
        <div class="col-sm-6">
        </div>
      </div>
    </div>
  </div>
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-6">
          <div class="card-documents card card-primary">
            <div class="card-header">
              <h3 class="card-title"><i class="fas fa-file"></i> Administration</h3>
            </div>
            <div class="card-body">
              <?php
              $directory = $_SERVER['DOCUMENT_ROOT'] . '/rfq/employee_docs/administration/';
              if (is_dir($directory)) {
                  $manager = opendir($directory);
                  echo '<div class="list-group">';
                  $folder = @scandir($directory);
                  if(count($folder) <= 2){
                    echo '<h3 class="text-center text-danger"><i class="fa fa-times"></i> No files!</h3>';
                  }
                  while (($file = readdir($manager)) !== false) {
                      $complete_directory = $directory . "/" . $file;
                      if ($file != "." && $file != "..") {
                          $file_url = str_replace(' ', '%20', $file);
                          echo '<li class="list-group-item">' . $file . '<a download href="' . EMPLOYEE_DOCS . 'administration/' . $file_url . '" class="close float-right"><i class="fas fa-file-download"></i></a></li>';
                      }
                  }
                  closedir($manager);
                  echo "</div>";
              }
              ?>
            </div>
          </div>
          <div class="card-documents card card-primary">
            <div class="card-header">
              <h3 class="card-title"><i class="fas fa-file"></i> Branding</h3>
            </div>
            <div class="card-body">
              <?php
              $directory = $_SERVER['DOCUMENT_ROOT'] . '/rfq/employee_docs/brand/';
              if (is_dir($directory)) {
                  $manager = opendir($directory);
                  echo '<div class="list-group">';
                  $folder = @scandir($directory);
                  if(count($folder) <= 2){
                    echo '<h3 class="text-center text-danger"><i class="fa fa-times"></i> No files!</h3>';
                  }
                  while (($file = readdir($manager)) !== false) {
                      $complete_directory = $directory . "/" . $file;
                      if ($file != "." && $file != "..") {
                          $file_url = str_replace(' ', '%20', $file);
                          echo '<li class="list-group-item">' . $file . '<a download href="' . EMPLOYEE_DOCS . 'brand/' . $file_url . '" class="close float-right"><i class="fas fa-file-download"></i></a></li>';
                      }
                  }
                  closedir($manager);
                  echo "</div>";
              }
              ?>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card-documents card card-primary">
            <div class="card-header">
              <h3 class="card-title"><i class="fas fa-file"></i> Accounting</h3>
            </div>
            <div class="card-body">
              <?php
              $directory = $_SERVER['DOCUMENT_ROOT'] . '/rfq/employee_docs/accounting/';
              if (is_dir($directory)) {
                  $manager = opendir($directory);
                  echo '<div class="list-group">';
                  $folder = @scandir($directory);
                  if(count($folder) <= 2){
                    echo '<h3 class="text-center text-danger"><i class="fa fa-times"></i> No files!</h3>';
                  }
                  while (($file = readdir($manager)) !== false) {
                      $complete_directory = $directory . "/" . $file;
                      if ($file != "." && $file != "..") {
                          $file_url = str_replace(' ', '%20', $file);
                          echo '<li class="list-group-item">' . $file . '<a download href="' . EMPLOYEE_DOCS . 'accounting/' . $file_url . '" class="close float-right"><i class="fas fa-file-download"></i></a></li>';
                      }
                  }
                  closedir($manager);
                  echo "</div>";
              }
              ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
