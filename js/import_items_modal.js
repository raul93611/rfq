/* =========================================================================
   Import Items modal — file dropzone, Add/Replace mode radios, and the
   Fulfillment-stage warning shown only for Replace on a quote that has
   already progressed past Fulfillment (data-fulfillment on the modal root).
   Upload stays a native multipart form submit; this file only manages the
   modal's own UI state before that submit happens.
   ========================================================================= */
(function () {
  'use strict';

  function fmtSize(bytes) {
    if (bytes < 1024) return bytes + ' B';
    if (bytes < 1024 * 1024) return Math.round(bytes / 1024) + ' KB';
    return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
  }

  function fileLabel(name) {
    var ext = (name.split('.').pop() || '').toLowerCase();
    return (ext === 'csv' || ext === 'xls' || ext === 'xlsx') ? ext.toUpperCase() : 'FILE';
  }

  document.addEventListener('DOMContentLoaded', function () {
    var modal = document.getElementById('import-items-modal');
    if (!modal) return;

    var input = modal.querySelector('#uploaded_file');
    var dropzone = modal.querySelector('#ii-dropzone');
    var chip = modal.querySelector('#ii-file-chip');
    var badge = modal.querySelector('#ii-file-badge');
    var nameEl = modal.querySelector('#ii-file-name');
    var sizeEl = modal.querySelector('#ii-file-size');
    var removeBtn = modal.querySelector('#ii-file-remove');
    var footerMeta = modal.querySelector('#ii-footer-meta');
    var uploadBtn = modal.querySelector('#ii-upload-btn');
    var warningCollapse = modal.querySelector('#ii-warning-collapse');
    var radios = modal.querySelectorAll('.ii-radio-input');
    var isFulfillment = modal.getAttribute('data-fulfillment') === '1';

    function setFile(file) {
      if (!file) {
        chip.style.display = 'none';
        dropzone.style.display = '';
        footerMeta.textContent = '';
        uploadBtn.disabled = true;
        return;
      }
      badge.textContent = fileLabel(file.name);
      nameEl.textContent = file.name;
      sizeEl.textContent = fmtSize(file.size);
      chip.style.display = '';
      dropzone.style.display = 'none';
      footerMeta.textContent = file.name + ' · ' + fmtSize(file.size);
      uploadBtn.disabled = false;
    }

    function updateWarning() {
      var mode = modal.querySelector('.ii-radio-input:checked').value;
      var show = isFulfillment && mode === 'replace';
      warningCollapse.classList.toggle('is-open', show);
    }

    dropzone.addEventListener('click', function () { input.click(); });
    dropzone.addEventListener('dragover', function (e) { e.preventDefault(); dropzone.classList.add('is-drag'); });
    dropzone.addEventListener('dragleave', function () { dropzone.classList.remove('is-drag'); });
    dropzone.addEventListener('drop', function (e) {
      e.preventDefault();
      dropzone.classList.remove('is-drag');
      if (e.dataTransfer.files.length) {
        input.files = e.dataTransfer.files;
        setFile(input.files[0]);
      }
    });
    input.addEventListener('change', function () {
      setFile(input.files[0] || null);
    });
    removeBtn.addEventListener('click', function (e) {
      e.stopPropagation();
      input.value = '';
      setFile(null);
    });

    function syncRadioSelection() {
      radios.forEach(function (r) {
        r.closest('.ii-radio').classList.toggle('is-selected', r.checked);
      });
    }

    radios.forEach(function (radio) {
      radio.addEventListener('change', function () {
        syncRadioSelection();
        updateWarning();
      });
    });

    $(modal).on('hidden.bs.modal', function () {
      input.value = '';
      setFile(null);
      radios[0].checked = true;
      syncRadioSelection();
      updateWarning();
    });
  });
})();
