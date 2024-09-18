<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Hi, <?= $_SESSION['user']->obtener_nombre_usuario(); ?></h1>
        </div>
        <div class="col-sm-6"></div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-6">
          <?php
          function renderCard($title, $directoryName) {
            $directory = $_SERVER['DOCUMENT_ROOT'] . "/rfq/employee_docs/$directoryName/";

            echo '<div class="card-documents card card-primary">
                    <div class="card-header">
                      <h3 class="card-title"><i class="fas fa-file"></i> ' . $title . '</h3>
                    </div>
                    <div class="card-body">';

            if (is_dir($directory)) {
              $files = array_diff(scandir($directory), ['.', '..']);
              if (count($files) === 0) {
                echo '<h3 class="text-center text-danger"><i class="fa fa-times"></i> No files!</h3>';
              } else {
                echo '<div class="list-group">';
                foreach ($files as $file) {
                  echo '<li class="list-group-item">' . $file .
                    '<a download href="' . EMPLOYEE_DOCS . "$directoryName/$file" . '" class="close float-right"><i class="fas fa-file-download"></i></a></li>';
                }
                echo '</div>';
              }
            } else {
              echo '<h3 class="text-center text-danger"><i class="fa fa-times"></i> Directory not found!</h3>';
            }

            echo '</div></div>';
          }

          // Render the cards for different sections
          renderCard('Administration', 'administration');
          renderCard('Branding', 'brand');
          renderCard('Letter of Authorization', 'letter_of_authorization');
          ?>
        </div>

        <div class="col-md-6">
          <?php
          // Render the cards for the other sections
          renderCard('Accounting', 'accounting');
          renderCard('Tax Exemption', 'tax_exemption');
          ?>
        </div>
      </div>
    </div>
  </section>
</div>