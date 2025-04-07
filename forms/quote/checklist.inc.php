<input type="hidden" name="id_rfq" value="<?= $id_rfq; ?>">
<div class="card-body">
  <div class="row">
    <div class="col-md-4">
      <h5>File Document</h5>
      <?php
      foreach (FILE_DOCUMENT as $key => $file_document) {
      ?>
        <div class="form-check">
          <input class="form-check-input" name="file_document[]" type="checkbox" value="<?= $key; ?>" id="<?= $key; ?>" <?= in_array($key, $quote->getFileDocument()) ? 'checked' : ''; ?>>
          <label class="form-check-label" for="<?= $key; ?>">
            <?= $file_document; ?>
          </label>
        </div>
      <?php
      }
      ?>
      <br>
      <h5>Accounting</h5>
      <?php
      foreach (ACCOUNTING_CHECKBOX as $key => $accounting) {
      ?>
        <div class="form-check">
          <input class="form-check-input" name="accounting[]" type="checkbox" value="<?= $key; ?>" id="<?= $key; ?>" <?= in_array($key, $quote->getAccounting()) ? 'checked' : ''; ?>>
          <label class="form-check-label" for="<?= $key; ?>">
            <?= $accounting; ?>
          </label>
        </div>
      <?php
      }
      ?>
    </div>
    <div class="col-md-8">
      <div class="form-group">
        <label for="set_side">Set Aside:</label>
        <select id="set_side" class="form-control form-control-sm" name="set_side">
          <?php
          foreach (SET_SIDE as $key => $side) {
          ?>
            <option value="<?= $side; ?>" <?= $quote->getSetSide() == $side ? 'selected' : ''; ?>><?= $side; ?></option>
          <?php
          }
          ?>
        </select>
        <small class="form-text text-muted">Select the set aside category.</small>
      </div>
      <div class="form-group">
        <label for="canal">Channel:</label>
        <select class="form-control form-control-sm" name="canal" id="canal">
          <option <?= $quote->obtener_canal() == 'GSA-Buy' ? 'selected' : ''; ?>>GSA-Buy</option>
          <option value="FedBid" <?= $quote->obtener_canal() == 'FedBid' ? 'selected' : ''; ?>>Unison</option>
          <option <?= $quote->obtener_canal() == 'E-mails' ? 'selected' : ''; ?>>E-mails</option>
          <option <?= $quote->obtener_canal() == 'Embassies' ? 'selected' : ''; ?>>Embassies</option>
          <option value="FBO" <?= $quote->obtener_canal() == 'FBO' ? 'selected' : ''; ?>>SAM</option>
          <option <?= $quote->obtener_canal() == 'Seaport' ? 'selected' : ''; ?>>Seaport</option>
          <option <?= $quote->obtener_canal() == 'Ebay & Amazon' ? 'selected' : ''; ?>>Ebay & Amazon</option>
          <option <?= $quote->obtener_canal() == 'Stars III' ? 'selected' : ''; ?>>Stars III</option>
        </select>
        <input type="hidden" name="canal_original" value="<?= $quote->obtener_canal(); ?>">
        <small class="form-text text-muted">Choose the source channel for the quote.</small>
      </div>
      <div class="form-group">
        <label for="gsa">GSA:</label>
        <select id="gsa" class="form-control form-control-sm" name="gsa">
          <?php
          foreach (GSA as $key => $gsa) {
          ?>
            <option value="<?= $key; ?>" <?= $quote->getGsa() == $key ? 'selected' : ''; ?>><?= $gsa; ?></option>
          <?php
          }
          ?>
        </select>
        <small class="form-text text-muted">Select the applicable GSA contract.</small>
      </div>
      <div class="form-group">
        <div class="custom-control custom-checkbox">
          <input type="checkbox" name="bpa" class="custom-control-input" id="bpa" <?= $quote->getBpa() ? 'checked' : '' ?> value="1">
          <label class="custom-control-label" for="bpa">BPA</label>
        </div>
        <small class="form-text text-muted">Check if BPA applies.</small>
      </div>
      <div class="form-group">
        <label for="contract_number">Contract Number:</label>
        <input type="text" class="form-control form-control-sm" name="contract_number" value="<?= $quote->obtener_contract_number(); ?>">
        <input type="hidden" name="contract_number_original" value="<?= $quote->obtener_contract_number(); ?>">
        <small class="form-text text-muted">Enter the contract number for this quote.</small>
      </div>
      <?php
      Input::print_designated_user($quote);
      ?>
      <br>
      <div class="form-group">
        <label for="email_code">Code:</label>
        <input type="text" class="form-control form-control-sm" id="email_code" name="email_code" value="<?= $quote->obtener_email_code(); ?>">
        <input type="hidden" name="email_code_original" value="<?= $quote->obtener_email_code(); ?>">
        <small class="form-text text-muted">Enter the code provided via email.</small>
      </div>
      <div class="form-group">
        <label>Award Date:</label>
        <input type="text" class="form-control form-control-sm" value="<?= !empty($quote->obtener_fecha_award()) ? RepositorioComment::mysql_date_to_english_format($quote->obtener_fecha_award()) : ''; ?>" disabled>
        <small class="form-text text-muted">This is the award date.</small>
      </div>
      <div class="form-group">
        <label for="poc">POC:</label>
        <input type="text" class="form-control form-control-sm" id="poc" name="poc" placeholder="POC ..." value="<?= $quote->getPoc(); ?>">
        <small class="form-text text-muted">Enter the point of contact for this quote.</small>
      </div>
      <div class="form-group">
        <label for="co">CO:</label>
        <input type="text" class="form-control form-control-sm" id="co" name="co" placeholder="CO ..." value="<?= $quote->getCo(); ?>">
        <small class="form-text text-muted">Enter the Contracting Officer (CO) responsible for this quote.</small>
      </div>

      <div class="form-group">
        <label for="client">Client:</label>
        <input type="text" class="form-control form-control-sm" id="client" name="client" placeholder="Client name ..." value="<?= $quote->obtener_client(); ?>">
        <small class="form-text text-muted">Provide the client’s name for this project.</small>
      </div>

      <div class="form-group">
        <label>Contract Amount:</label>
        <input type="text" class="form-control form-control-sm" value="<?= number_format($quote->obtener_quote_total_price(), 2); ?>" disabled>
        <small class="form-text text-muted">This is the total contract amount, auto-calculated.</small>
      </div>

      <div class="form-group">
        <label>RFQ Amount:</label>
        <input type="text" class="form-control form-control-sm" value="<?= number_format($quote->obtener_total_price(), 2); ?>" disabled>
        <small class="form-text text-muted">This is the Request for Quotation (RFQ) total amount.</small>
      </div>

      <div class="form-group">
        <label>RFP Amount:</label>
        <input type="text" class="form-control form-control-sm" value="<?= number_format($quote->getTotalQuoteServices() ?? 0, 2); ?>" disabled>
        <small class="form-text text-muted">This is the Request for Proposal (RFP) total amount.</small>
      </div>

      <div class="form-group">
        <label for="estimated_delivery_date">Estimated Delivery Date:</label>
        <input type="text" class="date form-control form-control-sm" id="estimated_delivery_date" name="estimated_delivery_date" value="<?= !empty($quote->getEstimatedDeliveryDate()) ? RepositorioComment::mysql_date_to_english_format($quote->getEstimatedDeliveryDate()) : ''; ?>">
        <small class="form-text text-muted">Specify the expected delivery date for the project.</small>
      </div>

      <div class="form-group">
        <label for="client_payment_terms">Client Payment Terms:</label>
        <select id="client_payment_terms" class="form-control form-control-sm" name="client_payment_terms">
          <?php foreach (CLIENT_PAYMENT_TERMS as $key => $client_payment_terms) { ?>
            <option value="<?= $key; ?>" <?= $quote->getClientPaymentTerms() == $key ? 'selected' : ''; ?>><?= $client_payment_terms; ?></option>
          <?php } ?>
        </select>
        <small class="form-text text-muted">Select the payment terms agreed upon with the client.</small>
      </div>

      <div class="form-group">
        <label>Estimated Profit (RFQ):</label>
        <input type="text" class="form-control form-control-sm" value="<?= '$ ' . number_format($quote->obtener_re_quote_rfq_profit(), 2) . ' / ' . number_format($quote->obtener_re_quote_rfq_profit_percentage(), 2) . ' %'; ?>" disabled>
        <small class="form-text text-muted">This shows the estimated profit percentage and amount for the RFQ.</small>
      </div>

      <div class="form-group">
        <label for="shipping_address">Shipping Address:</label>
        <select id="shipping_address" class="form-control form-control-sm" name="shipping_address">
          <?php foreach (SHIPPING_ADDRESS as $key => $address) { ?>
            <option value="<?= $key; ?>" <?= $quote->getShippingAddress() == $key ? 'selected' : ''; ?>><?= $address; ?></option>
          <?php } ?>
        </select>
        <small class="form-text text-muted">Choose the shipping address for this order.</small>
      </div>

      <div class="form-group">
        <label for="city">City:</label>
        <input type="text" class="form-control form-control-sm" id="city" name="city" placeholder="City ..." value="<?= $quote->obtener_city(); ?>">
        <small class="form-text text-muted">Provide the city for shipping.</small>
      </div>

      <div class="form-group">
        <label for="zip_code">Zip Code:</label>
        <input type="text" class="form-control form-control-sm" id="zip_code" name="zip_code" placeholder="Zip Code ..." value="<?= $quote->obtener_zip_code(); ?>">
        <small class="form-text text-muted">Enter the shipping location's zip code.</small>
      </div>

      <div class="form-group">
        <label for="state">State:</label>
        <select id="state" class="form-control form-control-sm" name="state">
          <?php foreach (STATES as $key => $state) { ?>
            <option value="<?= $key; ?>" <?= $quote->obtener_state() == $key ? 'selected' : ''; ?>><?= $state; ?></option>
          <?php } ?>
        </select>
        <small class="form-text text-muted">Select the state for shipping.</small>
      </div>

      <div class="form-group">
        <label for="ship_to">Ship to:</label>
        <textarea class="form-control form-control-sm" rows="5" placeholder="Enter ship to ..." id="ship_to" name="ship_to"><?= $quote->obtener_ship_to(); ?></textarea>
        <input type="hidden" name="ship_to_original" value="<?= $quote->obtener_ship_to(); ?>">
        <small class="form-text text-muted">Enter the full address for shipping the goods.</small>
      </div>

      <div class="form-group">
        <label for="special_requirements">Special Requirements/Risk/Extra Comments:</label>
        <textarea class="form-control form-control-sm" rows="5" placeholder="Special Requirements/Risk/Extra Comments ..." id="special_requirements" name="special_requirements"><?= $quote->getSpecialRequirements(); ?></textarea>
        <small class="form-text text-muted">Add any special instructions, risks, or extra comments for this quote.</small>
      </div>
    </div>
  </div>
</div>
<div class="card-footer footer_item">
  <a class="btn btn-secondary" id="go_back" href="<?= EDITAR_COTIZACION . '/' . $quote->obtener_id(); ?>"><i class="fa fa-reply"></i></a>
  <button type="submit" class="btn btn-success" form="checklist_form" name="save_checklist"><i class="fa fa-check"></i> Save</button>
</div>