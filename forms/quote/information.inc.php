<input type="hidden" name="id_rfq" value="<?= $id_rfq; ?>">

<div class="card-body user-form">
  <div class="row">
    <div class="col-md-8 offset-md-2">

      <!-- Opportunity Name -->
      <div class="checklist-section mb-4">
        <div class="checklist-section-title">Opportunity</div>
        <div class="form-group">
          <label for="name" style="font-weight:600;">Description</label>
          <textarea class="form-control form-control-sm" id="name" name="name" rows="3"
                    placeholder="e.g. Westpine Middle School AV Refresh (upgrade and installation of audio-visual equipment across 3 classrooms)"><?= htmlspecialchars($quote->getName() ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
          <small class="form-text text-muted">
            <i class="fas fa-table mr-1" style="color:#2db4e8;"></i>Synced to the SharePoint sheet — include a short title and scope summary.
          </small>
        </div>
      </div>

      <!-- Identification -->
      <div class="checklist-section mb-4">
        <div class="checklist-section-title">Identification</div>
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="email_code">Code</label>
            <input type="text" class="form-control form-control-sm" id="email_code" name="email_code"
                   value="<?= $quote->obtener_email_code(); ?>">
            <input type="hidden" name="email_code_original" value="<?= $quote->obtener_email_code(); ?>">
          </div>
          <div class="form-group col-md-6">
            <label for="canal">Channel</label>
            <select class="form-control form-control-sm" name="canal" id="canal">
              <option <?= $quote->obtener_canal() == 'GSA-Buy'       ? 'selected' : ''; ?>>GSA-Buy</option>
              <option value="FedBid" <?= $quote->obtener_canal() == 'FedBid'    ? 'selected' : ''; ?>>Unison</option>
              <option <?= $quote->obtener_canal() == 'E-mails'       ? 'selected' : ''; ?>>E-mails</option>
              <option <?= $quote->obtener_canal() == 'Embassies'     ? 'selected' : ''; ?>>Embassies</option>
              <option value="FBO" <?= $quote->obtener_canal() == 'FBO'        ? 'selected' : ''; ?>>SAM</option>
              <option <?= $quote->obtener_canal() == 'Seaport'       ? 'selected' : ''; ?>>Seaport</option>
              <option <?= $quote->obtener_canal() == 'Ebay & Amazon' ? 'selected' : ''; ?>>Ebay &amp; Amazon</option>
              <option <?= $quote->obtener_canal() == 'Stars III'     ? 'selected' : ''; ?>>Stars III</option>
            </select>
            <input type="hidden" name="canal_original" value="<?= $quote->obtener_canal(); ?>">
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="type_of_bid">Type of Bid</label>
            <select class="form-control form-control-sm" name="type_of_bid" id="type_of_bid">
              <?php
              Conexion::abrir_conexion();
              $type_of_bids = TypeOfBidRepository::get_all(Conexion::obtener_conexion());
              Conexion::cerrar_conexion();
              foreach ($type_of_bids as $type_of_bid): ?>
                <option <?= $quote->obtener_type_of_bid() == $type_of_bid->get_type_of_bid() ? 'selected' : ''; ?>>
                  <?= $type_of_bid->get_type_of_bid(); ?>
                </option>
              <?php endforeach; ?>
            </select>
            <input type="hidden" name="type_of_bid_original" value="<?= $quote->obtener_type_of_bid(); ?>">
          </div>
          <div class="form-group col-md-6">
            <label for="priority_level">Priority Level</label>
            <select class="form-control form-control-sm" name="priority_level" id="priority_level">
              <?php
              Conexion::abrir_conexion();
              $priority_levels = PriorityLevelRepository::getAll(Conexion::obtener_conexion());
              Conexion::cerrar_conexion();
              foreach ($priority_levels as $priority_level): ?>
                <option value="<?= $priority_level->getWeight(); ?>"
                  <?= $priority_level->getWeight() == $quote->getPriority() ? 'selected' : ''; ?>>
                  <?= $priority_level->getName(); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
      </div>

      <!-- Assignment -->
      <div class="checklist-section mb-4">
        <div class="checklist-section-title">Assignment</div>
        <?php Input::print_designated_user($quote); ?>
      </div>

      <!-- Dates -->
      <div class="checklist-section mb-4">
        <div class="checklist-section-title">Dates</div>
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="issue_date">Issue Date</label>
            <input type="text" class="date form-control form-control-sm" id="issue_date" name="issue_date"
                   value="<?= $quote->obtener_issue_date(); ?>">
            <input type="hidden" name="issue_date_original" value="<?= $quote->obtener_issue_date(); ?>">
          </div>
          <div class="form-group col-md-6">
            <label for="end_date">End Date</label>
            <input type="text" class="form-control form-control-sm" id="end_date" name="end_date"
                   value="<?= $quote->obtener_end_date(); ?>">
            <input type="hidden" name="end_date_original" value="<?= $quote->obtener_end_date(); ?>">
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="internal_due_date">Internal Due Date</label>
            <input type="text" class="date form-control form-control-sm" id="internal_due_date" name="internal_due_date"
                   value="<?= $quote->getInternalDueDate() ? date('m/d/Y', strtotime($quote->getInternalDueDate())) : ''; ?>">
            <small class="form-text text-muted"><i class="fas fa-table mr-1" style="color:#2db4e8;"></i>Synced to SharePoint sheet.</small>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="completed_date">Completed Date</label>
            <input type="text" class="date form-control form-control-sm" id="completed_date" name="completed_date"
                   value="<?= $quote->obtener_fecha_completado() ? RepositorioComment::mysql_date_to_english_format($quote->obtener_fecha_completado()) : ''; ?>">
            <input type="hidden" name="completed_date_original"
                   value="<?= $quote->obtener_fecha_completado() ? RepositorioComment::mysql_date_to_english_format($quote->obtener_fecha_completado()) : ''; ?>">
          </div>
          <div class="form-group col-md-6">
            <label for="expiration_date">Expiration Date</label>
            <input type="text" class="date form-control form-control-sm" id="expiration_date" name="expiration_date"
                   value="<?= $quote->obtener_expiration_date() ? RepositorioComment::mysql_date_to_english_format($quote->obtener_expiration_date()) : ''; ?>">
            <input type="hidden" name="expiration_date_original"
                   value="<?= $quote->obtener_expiration_date() ? RepositorioComment::mysql_date_to_english_format($quote->obtener_expiration_date()) : ''; ?>">
          </div>
        </div>
      </div>

      <!-- Status -->
      <div class="checklist-section mb-4">
        <div class="checklist-section-title">Status</div>
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="comments">Comments</label>
            <select id="comments" class="form-control form-control-sm" name="comments">
              <option <?= $quote->obtener_comments() == 'No comments'                      ? 'selected' : ''; ?>>No comments</option>
              <option <?= $quote->obtener_comments() == 'Working on it'                    ? 'selected' : ''; ?>>Working on it</option>
              <option <?= $quote->obtener_comments() == 'QuickBooks'                       ? 'selected' : ''; ?>>QuickBooks</option>
              <option <?= $quote->obtener_comments() == 'No Bid'                           ? 'selected' : ''; ?>>No Bid</option>
              <option <?= $quote->obtener_comments() == 'Manufacturer in the Bid'          ? 'selected' : ''; ?>>Manufacturer in the Bid</option>
              <option <?= $quote->obtener_comments() == 'Expired due date'                 ? 'selected' : ''; ?>>Expired due date</option>
              <option <?= $quote->obtener_comments() == 'Supplier did not provide a quote' ? 'selected' : ''; ?>>Supplier did not provide a quote</option>
              <option <?= $quote->obtener_comments() == 'Others'                           ? 'selected' : ''; ?>>Others</option>
              <option <?= $quote->obtener_comments() == 'Not submitted'                    ? 'selected' : ''; ?>>Not submitted</option>
              <option <?= $quote->obtener_comments() == 'Cancelled'                        ? 'selected' : ''; ?>>Cancelled</option>
              <option <?= $quote->obtener_comments() == 'Out of our scope'                 ? 'selected' : ''; ?>>Out of our scope</option>
              <option <?= $quote->obtener_comments() == 'No Award - Pricing'               ? 'selected' : ''; ?>>No Award - Pricing</option>
              <option <?= $quote->obtener_comments() == 'No Award - Technical'             ? 'selected' : ''; ?>>No Award - Technical</option>
            </select>
            <input type="hidden" name="comments_original" value="<?= $quote->obtener_comments(); ?>">
          </div>
          <div class="form-group col-md-6">
            <label for="reference_url">Reference URL</label>
            <input type="text" class="form-control form-control-sm" id="reference_url" name="reference_url"
                   value="<?= $quote->getReferenceUrl(); ?>">
          </div>
        </div>
      </div>

      <!-- Additional Requirements -->
      <div class="checklist-section mb-4">
        <div class="checklist-section-title">Additional Requirements</div>
        <div class="br-group">
          <div class="br-group-header">
            <span class="br-group-title">Bid Requirements</span>
            <span class="br-group-optional">Optional</span>
          </div>
          <?php
            $sv_val  = $quote->getSiteVisit();
            $sv_sel  = is_null($sv_val) ? '' : (string)(int)$sv_val;
            $res_val = $quote->getResumes();
            $res_sel = is_null($res_val) ? '' : (string)(int)$res_val;
            $qa_raw  = $quote->getQaDeadline();
            $qa_disp = $qa_raw ? date('m/d/Y H:i', strtotime($qa_raw)) : '';
            $qa_val  = $quote->getQa();
            $qa_sel  = is_null($qa_val) ? '' : (string)(int)$qa_val;
          ?>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="qa">Q &amp; A</label>
              <select class="form-control form-control-sm" name="qa" id="qa">
                <option value="" <?= $qa_sel === '' ? 'selected' : ''; ?>>Not specified</option>
                <option value="1" <?= $qa_sel === '1' ? 'selected' : ''; ?>>Yes</option>
                <option value="0" <?= $qa_sel === '0' ? 'selected' : ''; ?>>No</option>
              </select>
            </div>
            <div class="form-group col-md-6">
              <label for="qa_deadline">Q &amp; A Deadline</label>
              <input type="text" class="form-control form-control-sm" id="qa_deadline" name="qa_deadline"
                     placeholder="MM/DD/YYYY HH:mm" value="<?= htmlspecialchars($qa_disp, ENT_QUOTES, 'UTF-8'); ?>">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="site_visit">Site Visit</label>
              <select class="form-control form-control-sm" name="site_visit" id="site_visit">
                <option value="" <?= $sv_sel === '' ? 'selected' : ''; ?>>Not specified</option>
                <option value="1" <?= $sv_sel === '1' ? 'selected' : ''; ?>>Yes</option>
                <option value="0" <?= $sv_sel === '0' ? 'selected' : ''; ?>>No</option>
              </select>
            </div>
            <div class="form-group col-md-6 mb-0">
              <label for="resumes">Resumes</label>
              <select class="form-control form-control-sm" name="resumes" id="resumes">
                <option value="" <?= $res_sel === '' ? 'selected' : ''; ?>>Not specified</option>
                <option value="1" <?= $res_sel === '1' ? 'selected' : ''; ?>>Yes</option>
                <option value="0" <?= $res_sel === '0' ? 'selected' : ''; ?>>No</option>
              </select>
            </div>
          </div>
        </div>
      </div>

      <!-- Shipping -->
      <div class="checklist-section mb-4">
        <div class="checklist-section-title">Shipping</div>
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="ship_via">Ship Via</label>
            <select id="ship_via" class="form-control form-control-sm" name="ship_via">
              <option <?= $quote->obtener_ship_via() == 'GROUND'   ? 'selected' : ''; ?>>GROUND</option>
              <option <?= $quote->obtener_ship_via() == 'BEST WAY' ? 'selected' : ''; ?>>BEST WAY</option>
            </select>
            <input type="hidden" name="ship_via_original" value="<?= $quote->obtener_ship_via(); ?>">
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="address">Bill To</label>
            <textarea class="form-control form-control-sm" rows="4" placeholder="Enter address..."
                      id="address" name="address"><?= $quote->obtener_address(); ?></textarea>
            <input type="hidden" name="address_original" value="<?= $quote->obtener_address(); ?>">
          </div>
          <div class="form-group col-md-6">
            <label for="ship_to">Ship To</label>
            <textarea class="form-control form-control-sm" rows="4" placeholder="Enter ship to..."
                      id="ship_to" name="ship_to"><?= $quote->obtener_ship_to(); ?></textarea>
            <input type="hidden" name="ship_to_original" value="<?= $quote->obtener_ship_to(); ?>">
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<div class="quote-action-bar">
  <a class="btn btn-secondary btn-sm" href="<?= EDITAR_COTIZACION . '/' . $quote->obtener_id(); ?>">
    <i class="fa fa-reply mr-1"></i> Back
  </a>
  <button type="submit" class="btn btn-primary btn-sm" form="information_form" name="save_information">
    <i class="fa fa-check mr-1"></i> Save
  </button>
</div>
