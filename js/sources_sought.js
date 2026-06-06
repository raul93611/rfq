/* =========================================================================
   Sources Sought submit modal
   When the user checks the "Submitted" box on a quote, ask whether it is a
   regular submission or a Sources Sought response before letting the box stay
   checked. The choice rides along in the hidden #sources_sought_input field
   that submits with the quote-edit form.
   ========================================================================= */
(function () {
  'use strict';
  document.addEventListener('DOMContentLoaded', function () {
    var checkbox = document.getElementById('status');          // the "Submitted" checkbox
    var hidden = document.getElementById('sources_sought_input');
    var modalEl = document.getElementById('sources-sought-modal');
    if (!checkbox || !hidden || !modalEl || !window.jQuery) return;

    var $modal = window.jQuery(modalEl);
    var confirmed = false;

    checkbox.addEventListener('click', function (e) {
      if (checkbox.checked) {
        // Hold the submission until the user answers the question.
        e.preventDefault();
        checkbox.checked = false;
        confirmed = false;
        $modal.modal('show');
      }
    });

    function choose(isSourcesSought) {
      hidden.value = isSourcesSought ? '1' : '0';
      confirmed = true;
      checkbox.checked = true;
      $modal.modal('hide');
    }

    var regularBtn = document.getElementById('ss-regular-btn');
    var sourcesBtn = document.getElementById('ss-sources-btn');
    if (regularBtn) regularBtn.addEventListener('click', function () { choose(false); });
    if (sourcesBtn) sourcesBtn.addEventListener('click', function () { choose(true); });

    // Dismissed without choosing → revert the checkbox.
    $modal.on('hidden.bs.modal', function () {
      if (!confirmed) {
        checkbox.checked = false;
        hidden.value = '0';
      }
    });
  });
})();
