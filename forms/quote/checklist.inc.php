<input type="hidden" name="id_rfq" value="<?= $id_rfq; ?>">

<div class="card-body user-form">

  <!-- Financial Summary (read-only) -->
  <div class="mb-4" style="background:#f8f9fa;border-radius:10px;padding:16px 20px;border:1px solid #e9ecef;">
    <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:#8896a5;margin-bottom:12px;">
      <i class="fas fa-chart-bar mr-1"></i> Financial Summary
    </div>
    <div style="display:flex;gap:0;flex-wrap:wrap;">
      <?php
      $summaryItems = [
        'Contract Amount'    => '$ ' . number_format($quote->obtener_quote_total_price(), 2),
        'RFQ Amount'         => '$ ' . number_format($quote->obtener_total_price(), 2),
        'RFP Amount'         => '$ ' . number_format($quote->getTotalQuoteServices() ?? 0, 2),
        'Estimated Profit'   => '$ ' . number_format($quote->obtener_re_quote_rfq_profit(), 2) . ' / ' . number_format($quote->obtener_re_quote_rfq_profit_percentage(), 2) . ' %',
        'Award Date'         => !empty($quote->obtener_fecha_award()) ? RepositorioComment::mysql_date_to_english_format($quote->obtener_fecha_award()) : '—',
      ];
      $count = count($summaryItems);
      $i = 0;
      foreach ($summaryItems as $label => $val):
        $i++;
        $isLast = ($i === $count);
      ?>
        <div style="padding:4px 20px 4px <?= $i === 1 ? '0' : ''; ?>;<?= !$isLast ? 'border-right:1px solid #dee2e6;margin-right:20px;' : ''; ?>">
          <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:#8896a5;margin-bottom:2px;"><?= $label; ?></div>
          <div style="font-size:16px;font-weight:700;color:var(--color-primary);font-family:'Manrope',sans-serif;"><?= $val; ?></div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <div class="row">

    <!-- Left column: Checkboxes -->
    <div class="col-md-4">

      <div class="checklist-group mb-4">
        <div class="checklist-group-title">File Document</div>
        <?php foreach (FILE_DOCUMENT as $key => $file_document): ?>
          <div class="custom-control custom-checkbox checklist-item">
            <input class="custom-control-input" name="file_document[]" type="checkbox"
                   value="<?= $key; ?>" id="<?= $key; ?>"
                   <?= in_array($key, $quote->getFileDocument()) ? 'checked' : ''; ?>>
            <label class="custom-control-label" for="<?= $key; ?>"><?= $file_document; ?></label>
          </div>
        <?php endforeach; ?>
      </div>

      <div class="checklist-group">
        <div class="checklist-group-title">Accounting</div>
        <?php foreach (ACCOUNTING_CHECKBOX as $key => $accounting): ?>
          <div class="custom-control custom-checkbox checklist-item">
            <input class="custom-control-input" name="accounting[]" type="checkbox"
                   value="<?= $key; ?>" id="<?= $key; ?>"
                   <?= in_array($key, $quote->getAccounting()) ? 'checked' : ''; ?>>
            <label class="custom-control-label" for="<?= $key; ?>"><?= $accounting; ?></label>
          </div>
        <?php endforeach; ?>
      </div>

    </div>

    <!-- Right column: Editable fields grouped by section -->
    <div class="col-md-8">

      <!-- Contract Details -->
      <div class="checklist-section mb-4">
        <div class="checklist-section-title">Contract Details</div>
        <div class="form-row">
          <div class="form-group col-md-6">
            <label>Channel</label>
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
          <div class="form-group col-md-6">
            <label for="set_side">Set Aside</label>
            <select id="set_side" class="form-control form-control-sm" name="set_side">
              <?php foreach (SET_SIDE as $key => $side): ?>
                <option value="<?= $side; ?>" <?= $quote->getSetSide() == $side ? 'selected' : ''; ?>><?= $side; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="gsa">GSA</label>
            <select id="gsa" class="form-control form-control-sm" name="gsa">
              <?php foreach (GSA as $key => $gsa): ?>
                <option value="<?= $key; ?>" <?= $quote->getGsa() == $key ? 'selected' : ''; ?>><?= $gsa; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group col-md-6 d-flex align-items-end pb-2">
            <div class="custom-control custom-checkbox">
              <input type="checkbox" name="bpa" class="custom-control-input" id="bpa"
                     <?= $quote->getBpa() ? 'checked' : ''; ?> value="1">
              <label class="custom-control-label" for="bpa">BPA</label>
            </div>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="contract_number">Contract Number</label>
            <input type="text" class="form-control form-control-sm" name="contract_number"
                   value="<?= $quote->obtener_contract_number(); ?>">
            <input type="hidden" name="contract_number_original" value="<?= $quote->obtener_contract_number(); ?>">
          </div>
          <div class="form-group col-md-6">
            <label for="email_code">Code</label>
            <input type="text" class="form-control form-control-sm" id="email_code" name="email_code"
                   value="<?= $quote->obtener_email_code(); ?>">
            <input type="hidden" name="email_code_original" value="<?= $quote->obtener_email_code(); ?>">
          </div>
        </div>
      </div>

      <!-- People -->
      <div class="checklist-section mb-4">
        <div class="checklist-section-title">People</div>
        <?php Input::print_designated_user($quote); ?>
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="client">Client</label>
            <input type="text" class="form-control form-control-sm" id="client" name="client"
                   placeholder="Client name..." value="<?= $quote->obtener_client(); ?>">
          </div>
          <div class="form-group col-md-6">
            <label for="poc">POC</label>
            <input type="text" class="form-control form-control-sm" id="poc" name="poc"
                   placeholder="Point of contact..." value="<?= $quote->getPoc(); ?>">
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="co">CO</label>
            <input type="text" class="form-control form-control-sm" id="co" name="co"
                   placeholder="Contracting Officer..." value="<?= $quote->getCo(); ?>">
          </div>
        </div>
      </div>

      <!-- Dates & Terms -->
      <div class="checklist-section mb-4">
        <div class="checklist-section-title">Dates &amp; Terms</div>
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="estimated_delivery_date">Estimated Delivery Date</label>
            <input type="text" class="date form-control form-control-sm" id="estimated_delivery_date"
                   name="estimated_delivery_date"
                   value="<?= !empty($quote->getEstimatedDeliveryDate()) ? RepositorioComment::mysql_date_to_english_format($quote->getEstimatedDeliveryDate()) : ''; ?>">
          </div>
          <div class="form-group col-md-6">
            <label for="client_payment_terms">Client Payment Terms</label>
            <select id="client_payment_terms" class="form-control form-control-sm" name="client_payment_terms">
              <?php foreach (CLIENT_PAYMENT_TERMS as $key => $client_payment_terms): ?>
                <option value="<?= $key; ?>" <?= $quote->getClientPaymentTerms() == $key ? 'selected' : ''; ?>><?= $client_payment_terms; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
      </div>

      <!-- Shipping -->
      <div class="checklist-section mb-4">
        <div class="checklist-section-title">Shipping</div>
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="shipping_address">Shipping Address</label>
            <select id="shipping_address" class="form-control form-control-sm" name="shipping_address">
              <?php foreach (SHIPPING_ADDRESS as $key => $address): ?>
                <option value="<?= $key; ?>" <?= $quote->getShippingAddress() == $key ? 'selected' : ''; ?>><?= $address; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group col-md-6">
            <label for="city">City</label>
            <input type="text" class="form-control form-control-sm" id="city" name="city"
                   placeholder="City..." value="<?= $quote->obtener_city(); ?>">
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-4">
            <label for="state">State</label>
            <select id="state" class="form-control form-control-sm" name="state">
              <?php foreach (STATES as $key => $state): ?>
                <option value="<?= $key; ?>" <?= $quote->obtener_state() == $key ? 'selected' : ''; ?>><?= $state; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group col-md-4">
            <label for="zip_code">Zip Code</label>
            <input type="text" class="form-control form-control-sm" id="zip_code" name="zip_code"
                   placeholder="Zip Code..." value="<?= $quote->obtener_zip_code(); ?>">
          </div>
        </div>
        <div class="form-group">
          <label for="ship_to">Ship To</label>
          <textarea class="form-control form-control-sm" rows="4" placeholder="Full shipping address..."
                    id="ship_to" name="ship_to"><?= $quote->obtener_ship_to(); ?></textarea>
          <input type="hidden" name="ship_to_original" value="<?= $quote->obtener_ship_to(); ?>">
        </div>
      </div>

      <!-- Notes -->
      <div class="checklist-section">
        <div class="checklist-section-title">Notes</div>
        <div class="form-group">
          <label for="special_requirements">Special Requirements / Risk / Extra Comments</label>
          <textarea class="form-control form-control-sm" rows="4"
                    placeholder="Special requirements, risks, or extra comments..."
                    id="special_requirements" name="special_requirements"><?= $quote->getSpecialRequirements(); ?></textarea>
        </div>
      </div>

    </div>
  </div>
</div>

<div class="card-footer d-flex justify-content-between align-items-center">
  <a class="btn btn-secondary btn-sm" href="<?= EDITAR_COTIZACION . '/' . $quote->obtener_id(); ?>">
    <i class="fa fa-reply mr-1"></i> Back
  </a>
  <button type="submit" class="btn btn-primary btn-sm" form="checklist_form" name="save_checklist">
    <i class="fa fa-check mr-1"></i> Save
  </button>
</div>
