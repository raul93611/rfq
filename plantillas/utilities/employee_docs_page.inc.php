<div class="content-wrapper">
  <div class="content-header page-header-bar">
    <div>
      <h1 class="page-title">Employee Documents</h1>
      <p class="page-subtitle">Company resources and shared documents</p>
    </div>
    <?php if (isset($_SESSION['user']) && $_SESSION['user']->is_admin()): ?>
    <button class="btn btn-primary btn-sm" id="add-employee-doc-button">
      <i class="fa fa-plus mr-1"></i> Add Document
    </button>
    <?php endif; ?>
  </div>

  <section class="content" id="employee-docs-container" style="padding-top:20px;">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-6">
          <?php
          function renderCard($title, $directoryName, $icon = 'fa-folder') {
            $directory = $_SERVER['DOCUMENT_ROOT'] . "/rfq/employee_docs/$directoryName/";

            echo '<div class="chart-card mb-4">
                    <div class="chart-card-header" style="display:flex;align-items:center;gap:8px;">
                      <i class="fas ' . $icon . '" style="font-size:14px;opacity:0.8;"></i>
                      <span>' . $title . '</span>
                    </div>
                    <div class="card-body p-0">';

            if (is_dir($directory)) {
              $files = array_diff(scandir($directory), ['.', '..']);
              if (count($files) === 0) {
                echo '<div class="text-center py-4 text-muted">
                        <i class="fas fa-inbox fa-2x mb-2 d-block" style="opacity:0.3;"></i>
                        <small>No documents uploaded yet</small>
                      </div>';
              } else {
                echo '<ul class="list-group list-group-flush">';
                foreach ($files as $file) {
                  $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                  $fileIcon = in_array($ext, ['pdf']) ? 'fa-file-pdf text-danger' :
                              (in_array($ext, ['doc','docx']) ? 'fa-file-word text-primary' :
                              (in_array($ext, ['xls','xlsx']) ? 'fa-file-excel text-success' :
                              (in_array($ext, ['jpg','jpeg','png','gif']) ? 'fa-file-image text-warning' : 'fa-file text-secondary')));

                  echo '<li class="list-group-item d-flex justify-content-between align-items-center py-2 px-3" style="border-left:none;border-right:none;">
                          <span class="d-flex align-items-center gap-2" style="gap:8px;overflow:hidden;">
                            <i class="fas ' . $fileIcon . '" style="font-size:13px;flex-shrink:0;"></i>
                            <span class="text-truncate" style="font-size:13px;max-width:260px;" title="' . htmlspecialchars($file) . '">' . htmlspecialchars($file) . '</span>
                          </span>
                          <div class="d-flex" style="gap:4px;flex-shrink:0;">';

                  echo '<a download href="' . EMPLOYEE_DOCS . "$directoryName/$file" . '" class="btn btn-sm btn-outline-primary" style="padding:2px 8px;" title="Download"><i class="fas fa-download" style="font-size:11px;"></i></a>';

                  if (isset($_SESSION['user']) && $_SESSION['user']->is_admin()) {
                    echo '<button data-path="' . htmlspecialchars("$directoryName/$file") . '" class="delete-employee-doc-button btn btn-sm btn-outline-danger" style="padding:2px 8px;" title="Delete"><i class="fa fa-trash" style="font-size:11px;"></i></button>';
                  }

                  echo '</div></li>';
                }
                echo '</ul>';
              }
            } else {
              echo '<div class="text-center py-4 text-muted">
                      <i class="fas fa-exclamation-triangle fa-2x mb-2 d-block" style="opacity:0.4;"></i>
                      <small>Directory not found</small>
                    </div>';
            }

            echo '</div></div>';
          }

          renderCard('Administration', 'administration', 'fa-building');
          renderCard('Branding', 'brand', 'fa-paint-brush');
          renderCard('Letter of Authorization', 'letter_of_authorization', 'fa-file-alt');
          renderCard('Company Compliance Documents', 'company_compliance_documents', 'fa-shield-alt');
          ?>
        </div>

        <div class="col-md-6">
          <?php
          renderCard('Accounting', 'accounting', 'fa-calculator');
          renderCard('Tax Exemption', 'tax_exemption', 'fa-percentage');
          renderCard('Policies', 'policies', 'fa-book');
          ?>
        </div>
      </div>
    </div>
  </section>
</div>

<?php include_once 'modals/add_employee_doc_modal.inc.php'; ?>

<script src="<?= RUTA_JS; ?>employee_docs.js"></script>
