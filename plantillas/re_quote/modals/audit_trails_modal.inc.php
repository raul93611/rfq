<div class="modal fade" id="audit_trails_modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog at-modal-dialog" role="document">
    <div class="modal-content at-modal-content">

      <!-- Header -->
      <div class="at-header">
        <div class="at-header-left">
          <div class="at-header-icon">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
          </div>
          <div>
            <div class="at-title">Audit Trail</div>
            <div class="at-subtitle">Proposal #—</div>
          </div>
        </div>
        <button class="at-close" data-dismiss="modal" aria-label="Close">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
      </div>

      <!-- Filters -->
      <div class="at-filters">
        <div class="at-tabs-primary">
          <button class="at-tab is-active" data-tab="all">All <span class="at-tab-count">0</span></button>
          <button class="at-tab" data-tab="status">Status <span class="at-tab-count">0</span></button>
          <button class="at-tab" data-tab="edits">Edits <span class="at-tab-count">0</span></button>
          <button class="at-tab" data-tab="items">Items <span class="at-tab-count">0</span></button>
          <button class="at-tab" data-tab="invoices">Invoices <span class="at-tab-count">0</span></button>
          <button class="at-tab" data-tab="sync">Sync <span class="at-tab-count">0</span></button>
          <div class="at-search">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input class="at-search-input" type="text" placeholder="Search audit trail" />
          </div>
        </div>
        <div class="at-tabs-scope">
          <button class="at-scope" data-scope="quote">Quote</button>
          <button class="at-scope" data-scope="requote">Re-Quote</button>
          <button class="at-scope" data-scope="fulfillment">Fulfillment</button>
          <button class="at-scope is-active" data-scope="full">Full History</button>
        </div>
      </div>

      <!-- Body -->
      <div class="at-body">
        <div class="at-timeline-wrap">
          <div class="at-loading">
            <div class="at-loading-spinner"></div>
            <span>Loading audit trail&hellip;</span>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <div class="at-footer">
        <div class="at-footer-count"></div>
        <div class="at-footer-actions">
          <button class="at-btn at-btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>

    </div>
  </div>
</div>
