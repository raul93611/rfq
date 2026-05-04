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
              echo '<li class="list-group-item d-flex justify-content-between align-items-center">' . $file .
                '<div class="btn-group" role="group">' .
                '<a download href="' . EMPLOYEE_DOCS . "$directoryName/$file" . '" class="close btn btn-link"><i class="fas fa-file-download"></i></a>';

              // Check if the user is an admin before showing the delete button
              if (isset($_SESSION['user']) && $_SESSION['user']->is_admin()) {
                echo '<button data-path="' . "$directoryName/$file" . '" class="delete-employee-doc-button close btn btn-link text-danger"><i class="fa fa-trash"></i></button>';
              }

              echo '</div>' .
                '</li>';
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
      renderCard('Company Compliance Documents', 'company_compliance_documents');
      ?>
    </div>

    <div class="col-md-6">
      <?php
      // Render the cards for the other sections
      renderCard('Accounting', 'accounting');
      renderCard('Tax Exemption', 'tax_exemption');
      renderCard('Policies', 'policies');
      ?>
    </div>
  </div>
</div>