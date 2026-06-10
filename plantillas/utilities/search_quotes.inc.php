<?php
// Advanced-search dropdown data + status vocabulary.
// Keys/colors mirror PipelineMetricsRepository::STATUSES so the filter always
// agrees with the Bid Pipeline Metrics chart; labels follow the design handoff.
$sq_conexion = Conexion::obtener_conexion();
$sq_users = RepositorioUsuario::getAllActiveUsers($sq_conexion);
usort($sq_users, fn($a, $b) => strcasecmp($a['nombre_usuario'], $b['nombre_usuario']));
$sq_bid_types = TypeOfBidRepository::get_all($sq_conexion);
$sq_contract_types = TypeOfContractRepository::get_all($sq_conexion);

$sq_label_overrides = [
  'submitted_ss'       => 'Submitted (Sources Sought)',
  'no_award_pricing'   => 'No Award - Pricing',
  'no_award_technical' => 'No Award - Technical',
];
$sq_statuses = array_map(function ($s) use ($sq_label_overrides) {
  $s['label'] = $sq_label_overrides[$s['key']] ?? $s['label'];
  return $s;
}, PipelineMetricsRepository::STATUSES);
?>
<div class="content-wrapper">

  <div class="content-header page-header-bar">
    <div>
      <h1 class="page-title">Search Quotes</h1>
      <p class="page-subtitle">Find quotes by proposal, code, user or keyword</p>
    </div>
  </div>

  <section class="content" style="padding-top:20px;">
    <div class="container-fluid">

      <!-- Search form -->
      <div class="row justify-content-center mb-4">
        <div class="col-lg-6 col-md-8" id="sq_search_col" data-testid="search-card">
          <div class="card chart-card mb-0">
            <div class="card-body">
              <form id="search_quotes" role="form" method="post" action="">
                <div class="sq-search-row">
                  <div class="sq-kw-wrap">
                    <span class="sq-kw-icon"><i class="fas fa-search"></i></span>
                    <input type="search" name="termino_busqueda" class="sq-kw-input" placeholder="Type at least 3 characters to search..." autofocus>
                  </div>
                  <button type="button" class="sq-adv-btn" id="sq_adv_toggle" aria-expanded="false" data-testid="advanced-toggle">
                    <i class="fas fa-sliders-h"></i>
                    Advanced
                    <span class="sq-adv-count" id="sq_adv_count" style="display:none;">0</span>
                  </button>
                </div>
                <div class="sq-search-help" id="sq_search_help">Results appear automatically after 3 characters.</div>

                <!-- Advanced filter panel -->
                <div class="sq-filters-reveal" id="sq_filters_reveal" data-testid="advanced-filter-panel">
                  <div class="sq-filters-inner">
                    <div class="sq-filters">
                      <div class="sq-filters-head">
                        <span class="sq-filters-title">Filters</span>
                        <span class="sq-filters-hint" id="sq_filters_hint">No filters set — all quotes match</span>
                        <button type="button" class="sq-clear-btn" id="sq_clear_filters" disabled data-testid="clear-filters">
                          <i class="fas fa-undo-alt"></i> Clear filters
                        </button>
                      </div>

                      <div class="sq-filters-grid">

                        <!-- Status multi-select -->
                        <div class="sq-field" id="sq_status_field">
                          <span class="sq-field-label">Status</span>
                          <div class="sq-ms-wrap">
                            <button type="button" class="sq-input sq-ms-trigger" id="sq_status_trigger" data-testid="status-filter">
                              <span class="sq-ms-summary" id="sq_status_summary"><span class="sq-ms-placeholder">Any status</span></span>
                              <span class="sq-select-caret"><i class="fas fa-chevron-down"></i></span>
                            </button>
                            <div class="sq-ms-menu" id="sq_status_menu" style="display:none;">
                              <div class="sq-ms-menu-head">
                                <span id="sq_status_menu_count">All statuses match</span>
                                <button type="button" class="sq-ms-clear" id="sq_status_clear" style="display:none;">Clear</button>
                              </div>
                              <div class="sq-ms-list">
                                <?php foreach ($sq_statuses as $s): ?>
                                  <button type="button" class="sq-ms-opt" data-status="<?= $s['key']; ?>" data-testid="status-opt-<?= $s['key']; ?>">
                                    <span class="sq-ms-check"><i class="fas fa-check"></i></span>
                                    <span class="sq-ms-dot" style="background: <?= $s['color']; ?>;"></span>
                                    <span class="sq-ms-label"><?= htmlspecialchars($s['label']); ?></span>
                                  </button>
                                <?php endforeach; ?>
                              </div>
                            </div>
                          </div>
                        </div>

                        <!-- Designated user -->
                        <label class="sq-field">
                          <span class="sq-field-label">Designated User</span>
                          <span class="sq-select-wrap">
                            <select class="sq-input sq-select is-placeholder" id="sq_f_user" data-testid="user-filter">
                              <option value="">Any user</option>
                              <?php foreach ($sq_users as $u): ?>
                                <option value="<?= (int)$u['id']; ?>"><?= htmlspecialchars($u['nombre_usuario']); ?></option>
                              <?php endforeach; ?>
                            </select>
                            <span class="sq-select-caret"><i class="fas fa-chevron-down"></i></span>
                          </span>
                        </label>

                        <!-- Type of bid -->
                        <label class="sq-field">
                          <span class="sq-field-label">Type of Bid</span>
                          <span class="sq-select-wrap">
                            <select class="sq-input sq-select is-placeholder" id="sq_f_bid_type" data-testid="bid-type-filter">
                              <option value="">Any type</option>
                              <?php foreach ($sq_bid_types as $tb): ?>
                                <option value="<?= htmlspecialchars($tb->get_type_of_bid()); ?>"><?= htmlspecialchars($tb->get_type_of_bid()); ?></option>
                              <?php endforeach; ?>
                            </select>
                            <span class="sq-select-caret"><i class="fas fa-chevron-down"></i></span>
                          </span>
                        </label>

                        <!-- Type of contract -->
                        <label class="sq-field">
                          <span class="sq-field-label">Type of Contract</span>
                          <span class="sq-select-wrap">
                            <select class="sq-input sq-select is-placeholder" id="sq_f_contract_type" data-testid="contract-type-filter">
                              <option value="">Any type</option>
                              <?php foreach ($sq_contract_types as $tc): ?>
                                <option value="<?= htmlspecialchars($tc->get_type_of_contract()); ?>"><?= htmlspecialchars($tc->get_type_of_contract()); ?></option>
                              <?php endforeach; ?>
                            </select>
                            <span class="sq-select-caret"><i class="fas fa-chevron-down"></i></span>
                          </span>
                        </label>

                        <!-- Date range + field selector -->
                        <div class="sq-field sq-field-dates">
                          <span class="sq-field-label-row">
                            <span class="sq-field-label">Date range</span>
                            <span class="sq-seg" role="radiogroup" aria-label="Date field" id="sq_date_field_seg">
                              <button type="button" class="sq-seg-btn is-on" data-date-field="created">Created</button>
                              <button type="button" class="sq-seg-btn" data-date-field="submitted">Submitted</button>
                              <button type="button" class="sq-seg-btn" data-date-field="awarded">Awarded</button>
                            </span>
                          </span>
                          <span class="sq-range">
                            <input type="date" class="sq-input" id="sq_f_date_from" aria-label="From date" data-testid="date-from">
                            <span class="sq-range-sep">to</span>
                            <input type="date" class="sq-input" id="sq_f_date_to" aria-label="To date" data-testid="date-to">
                          </span>
                        </div>

                        <!-- Total price range -->
                        <div class="sq-field">
                          <span class="sq-field-label">Total Price</span>
                          <span class="sq-range">
                            <span class="sq-money-wrap"><span class="sq-money-sign">$</span>
                              <input type="number" min="0" class="sq-input sq-money" id="sq_f_price_min" placeholder="Min" data-testid="price-min">
                            </span>
                            <span class="sq-range-sep">to</span>
                            <span class="sq-money-wrap"><span class="sq-money-sign">$</span>
                              <input type="number" min="0" class="sq-input sq-money" id="sq_f_price_max" placeholder="Max" data-testid="price-max">
                            </span>
                          </span>
                        </div>

                        <!-- Client -->
                        <label class="sq-field">
                          <span class="sq-field-label">Client</span>
                          <input type="text" class="sq-input" id="sq_f_client" placeholder="Client name contains…" data-testid="client-filter">
                        </label>

                        <!-- State -->
                        <label class="sq-field">
                          <span class="sq-field-label">State</span>
                          <input type="text" class="sq-input" id="sq_f_state" placeholder="e.g. FL" data-testid="state-filter">
                        </label>

                      </div>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

      <!-- Quotes results table -->
      <div class="card chart-card mb-4" data-testid="quotes-results-card">
        <div class="card-body">
          <p class="chart-card-label">Quotes</p>
          <table id="tabla_busqueda" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>Proposal</th>
                <th>Code</th>
                <th>Contract Number</th>
                <th>Designated User</th>
                <th>Type of Bid</th>
                <th>Comments</th>
                <th>Total Price</th>
                <th>Options</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>

      <!-- Partial invoices results table -->
      <div class="card chart-card" id="sq_invoices_card" data-testid="partial-invoices-card">
        <div class="card-body">
          <p class="chart-card-label"><i class="fas fa-file-invoice-dollar mr-1"></i> Partial Invoices</p>
          <table id="tabla_invoices" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>Invoice Name</th>
                <th>Invoice Date</th>
                <th>Parent Quote</th>
                <th>Designated User</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>

    </div>
  </section>
</div>

<script>
  window.SQ_STATUSES = <?= json_encode($sq_statuses); ?>;
</script>
<script src="<?= RUTA_JS; ?>searchQuotes.js"></script>
