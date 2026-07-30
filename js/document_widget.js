/* =========================================================================
   Documents widget — dropzone + file list, shared between:
   - "immediate" mode (quote edit page, inside the qed-drawer Documents tab):
     uploads each file to /rfq/quote/load_img/{idRfq} via XHR with a live
     progress bar, deletes via /rfq/quote/delete_document/{idRfq}/{file} with
     an inline confirm row, and reports its live count via onCountChange.
   - "deferred" mode (create-quote page): no id_rfq exists yet, so dropped
     files are staged client-side (no upload) and mirrored onto a hidden
     <input type=file name="documentos[]"> via DataTransfer, so the page's
     existing native form submit is unaffected.
   Replaces the kartik-v bootstrap-fileinput plugin everywhere.
   ========================================================================= */
(function () {
  'use strict';

  function fmtSize(bytes) {
    if (bytes < 1024) return bytes + ' B';
    if (bytes < 1024 * 1024) return Math.round(bytes / 1024) + ' KB';
    return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
  }

  function fileMeta(name) {
    var ext = (name.split('.').pop() || '').toLowerCase();
    if (ext === 'pdf') return { cls: 'pdf', label: 'PDF' };
    if (ext === 'doc' || ext === 'docx') return { cls: 'word', label: 'DOC' };
    if (ext === 'xls' || ext === 'xlsx' || ext === 'csv') return { cls: 'excel', label: 'XLS' };
    if (['png', 'jpg', 'jpeg', 'gif', 'webp'].indexOf(ext) !== -1) return { cls: 'image', label: 'IMG' };
    return { cls: 'generic', label: 'FILE' };
  }

  var uid = 0;
  function nextId() { return 'df' + (++uid); }

  function icon(name) {
    var i = document.createElement('i');
    i.className = 'fas ' + name;
    return i;
  }

  function init(root, opts) {
    opts = opts || {};
    if (!root) return null;
    var mode = opts.mode === 'deferred' ? 'deferred' : 'immediate';
    var idRfq = opts.idRfq;
    var dropzone = root.querySelector('.doc-dropzone');
    var input = root.querySelector('.doc-dropzone input[type=file]');
    var listEl = root.querySelector('.doc-filelist');
    var emptyEl = root.querySelector('.doc-empty');
    if (!dropzone || !input || !listEl) return null;

    // { id, name, size, blob, status: 'uploading'|'done'|'error'|'staged', progress, error }
    var files = [];
    var confirmingId = null;

    function notifyCount() {
      if (typeof opts.onCountChange === 'function') opts.onCountChange(files.length);
    }

    function syncHiddenInput() {
      if (mode !== 'deferred' || typeof DataTransfer === 'undefined') return;
      var dt = new DataTransfer();
      files.forEach(function (f) { if (f.blob) dt.items.add(f.blob); });
      input.files = dt.files;
    }

    function render() {
      listEl.innerHTML = '';
      if (emptyEl) emptyEl.style.display = files.length ? 'none' : '';
      files.forEach(function (f) { listEl.appendChild(renderRow(f)); });
    }

    function renderRow(f) {
      var meta = fileMeta(f.name);
      var row = document.createElement('div');
      row.className = 'doc-file-row' + (f.status === 'error' ? ' is-error' : '');
      row.setAttribute('data-testid', 'doc-file-row');

      var badge = document.createElement('span');
      badge.className = 'doc-file-badge ' + meta.cls;
      badge.textContent = meta.label;
      row.appendChild(badge);

      var main = document.createElement('div');
      main.className = 'doc-file-main';
      var nameEl = document.createElement('span');
      nameEl.className = 'doc-file-name';
      nameEl.textContent = f.name;
      main.appendChild(nameEl);

      if (f.status === 'uploading') {
        var track = document.createElement('div');
        track.className = 'doc-file-progress-track';
        var bar = document.createElement('div');
        bar.className = 'doc-file-progress-bar';
        bar.style.width = (f.progress || 0) + '%';
        track.appendChild(bar);
        main.appendChild(track);
        var upEl = document.createElement('span');
        upEl.className = 'doc-file-meta';
        upEl.textContent = 'Uploading… ' + (f.progress || 0) + '%';
        main.appendChild(upEl);
      } else if (f.status === 'error') {
        var errEl = document.createElement('span');
        errEl.className = 'doc-file-meta is-error-text';
        errEl.textContent = 'Upload failed — ' + (f.error || 'connection error');
        main.appendChild(errEl);
      } else if (f.size != null) {
        var sizeEl = document.createElement('span');
        sizeEl.className = 'doc-file-meta';
        sizeEl.textContent = fmtSize(f.size);
        main.appendChild(sizeEl);
      }
      row.appendChild(main);

      var actions = document.createElement('div');
      actions.className = 'doc-file-actions';
      if (f.status === 'done' || f.status === 'staged') {
        if (f.status === 'done') {
          var dlBtn = document.createElement('a');
          dlBtn.className = 'doc-file-btn';
          dlBtn.setAttribute('data-testid', 'doc-file-download');
          dlBtn.setAttribute('aria-label', 'Download');
          dlBtn.setAttribute('href', '/rfq/documentos/' + idRfq + '/' + encodeURIComponent(f.name));
          dlBtn.setAttribute('download', f.name);
          dlBtn.appendChild(icon('fa-download'));
          actions.appendChild(dlBtn);
        }
        var delBtn = document.createElement('button');
        delBtn.type = 'button';
        delBtn.className = 'doc-file-btn is-danger';
        delBtn.setAttribute('data-testid', 'doc-file-delete');
        delBtn.setAttribute('aria-label', f.status === 'staged' ? 'Remove' : 'Delete');
        delBtn.appendChild(icon(f.status === 'staged' ? 'fa-times' : 'fa-trash'));
        delBtn.addEventListener('click', function () {
          if (f.status === 'staged') { removeFile(f.id); } else { confirmingId = f.id; render(); }
        });
        actions.appendChild(delBtn);
      } else if (f.status === 'error') {
        var retryBtn = document.createElement('button');
        retryBtn.type = 'button';
        retryBtn.className = 'doc-file-btn doc-file-retry';
        retryBtn.setAttribute('aria-label', 'Retry');
        retryBtn.appendChild(icon('fa-redo'));
        retryBtn.addEventListener('click', function () { upload(f); });
        actions.appendChild(retryBtn);

        var rmBtn = document.createElement('button');
        rmBtn.type = 'button';
        rmBtn.className = 'doc-file-btn is-danger';
        rmBtn.setAttribute('aria-label', 'Remove');
        rmBtn.appendChild(icon('fa-times'));
        rmBtn.addEventListener('click', function () { removeFile(f.id); });
        actions.appendChild(rmBtn);
      }
      row.appendChild(actions);

      if (confirmingId === f.id) {
        var confirm = document.createElement('div');
        confirm.className = 'doc-file-confirm';
        confirm.setAttribute('data-testid', 'doc-file-confirm');
        var text = document.createElement('span');
        text.className = 'doc-file-confirm-text';
        text.textContent = 'Delete this file?';
        confirm.appendChild(text);
        var confirmActions = document.createElement('div');
        confirmActions.className = 'doc-file-confirm-actions';
        var cancelBtn = document.createElement('button');
        cancelBtn.type = 'button';
        cancelBtn.className = 'doc-file-confirm-cancel';
        cancelBtn.textContent = 'Cancel';
        cancelBtn.addEventListener('click', function () { confirmingId = null; render(); });
        var deleteBtn = document.createElement('button');
        deleteBtn.type = 'button';
        deleteBtn.className = 'doc-file-confirm-delete';
        deleteBtn.textContent = 'Delete';
        deleteBtn.addEventListener('click', function () { confirmDelete(f); });
        confirmActions.appendChild(cancelBtn);
        confirmActions.appendChild(deleteBtn);
        confirm.appendChild(confirmActions);
        row.appendChild(confirm);
      }

      return row;
    }

    function removeFile(id) {
      files = files.filter(function (f) { return f.id !== id; });
      if (confirmingId === id) confirmingId = null;
      syncHiddenInput();
      render();
      notifyCount();
    }

    function confirmDelete(f) {
      fetch('/rfq/quote/delete_document/' + idRfq + '/' + encodeURIComponent(f.name), {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        credentials: 'same-origin'
      })
        .then(function (res) { return res.json().catch(function () { return null; }); })
        .then(function (data) {
          confirmingId = null;
          if (data && data.result === '1') removeFile(f.id); else render();
        })
        .catch(function () { confirmingId = null; render(); });
    }

    function upload(f) {
      f.status = 'uploading';
      f.progress = 0;
      f.error = null;
      render();

      var fd = new FormData();
      fd.append('archivos_ejemplo[]', f.blob, f.name);
      var xhr = new XMLHttpRequest();
      xhr.open('POST', '/rfq/quote/load_img/' + idRfq);
      xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
      xhr.upload.addEventListener('progress', function (e) {
        if (!e.lengthComputable) return;
        f.progress = Math.round((e.loaded / e.total) * 100);
        render();
      });
      xhr.addEventListener('load', function () {
        var data = null;
        try { data = JSON.parse(xhr.responseText); } catch (e) {}
        if (xhr.status >= 200 && xhr.status < 300 && data && data.result === '1') {
          f.status = 'done';
        } else {
          f.status = 'error';
          f.error = (data && data.error) || 'connection error';
        }
        render();
        notifyCount();
      });
      xhr.addEventListener('error', function () {
        f.status = 'error';
        f.error = 'connection error';
        render();
        notifyCount();
      });
      xhr.send(fd);
    }

    function addFiles(fileList) {
      Array.prototype.slice.call(fileList).forEach(function (blob) {
        var f = {
          id: nextId(), name: blob.name, size: blob.size, blob: blob,
          status: mode === 'deferred' ? 'staged' : 'uploading', progress: 0
        };
        files.unshift(f);
        if (mode !== 'deferred') upload(f);
      });
      syncHiddenInput();
      render();
      notifyCount();
    }

    dropzone.addEventListener('click', function () { input.click(); });
    dropzone.addEventListener('dragover', function (e) { e.preventDefault(); dropzone.classList.add('is-drag'); });
    dropzone.addEventListener('dragleave', function () { dropzone.classList.remove('is-drag'); });
    dropzone.addEventListener('drop', function (e) {
      e.preventDefault();
      dropzone.classList.remove('is-drag');
      if (e.dataTransfer.files.length) addFiles(e.dataTransfer.files);
    });
    input.addEventListener('change', function (e) {
      if (e.target.files.length) addFiles(e.target.files);
      // Deferred mode's input.files IS the staged set (kept in sync by syncHiddenInput) —
      // clearing it here would immediately wipe what was just staged.
      if (mode !== 'deferred') e.target.value = '';
    });

    if (mode !== 'deferred' && idRfq) {
      fetch('/rfq/quote/get_quote_files/' + idRfq, { credentials: 'same-origin' })
        .then(function (res) { return res.json(); })
        .then(function (data) {
          (data.files || []).forEach(function (name) {
            files.push({ id: nextId(), name: name, size: null, status: 'done' });
          });
          render();
          notifyCount();
        })
        .catch(function () { render(); });
    } else {
      render();
    }
  }

  window.DocumentWidget = { init: init };
})();
