$(document).ready(function () {
  const idRfq = $('[name="id_rfq"]').val();

  /**
 * Enables the continue button in the deletion modal.
 * @param {jQuery} button - The button triggering the delete action.
 */
  function enableContinueButton(button) {
    const alertDeleteSystem = $('#alert_delete_system');
    const continueButton = $('#continue_button');

    alertDeleteSystem.modal();
    const linkToDelete = button.attr('href');
    continueButton.attr('href', linkToDelete);
  }

  /**
   * Binds the delete button alert functionality to elements matching the selector.
   * @param {string} selector - The jQuery selector for the delete buttons.
   */
  function bindDeleteButtonAlert(selector) {
    $(selector).click(function () {
      enableContinueButton($(this));
      return false; // Prevent default navigation
    });
  }
  /*********************************** ALERT BUTTONS FOR DELETION ***********************************/
  bindDeleteButtonAlert('.delete_service_button');
  bindDeleteButtonAlert('.delete_item_button');
  bindDeleteButtonAlert('.delete_subitem_button');
  bindDeleteButtonAlert('.delete_provider_item_button');
  bindDeleteButtonAlert('.delete_provider_subitem_button');
  bindDeleteButtonAlert('.delete_document_button');
  bindDeleteButtonAlert('#copy_quote');

  // Bind delete button alert functionality for quotes tables
  $('#tabla_quotes, #no_bid_table, #not_submitted_table, #cancelled_table')
    .on('click', '.delete_quote_button', function () {
      enableContinueButton($(this));
      return false; // Prevent default navigation
    });
  /************************************** FONT COLOR FOR TEXTAREAS ***********/
  $('.summernote_textarea').summernote({
    callbacks: {
      onPaste: function (e) {
        let clipboardData = (e.originalEvent || e).clipboardData || window.clipboardData;
        let bufferText = clipboardData.getData('text/html') || clipboardData.getData('text/plain');
        e.preventDefault();
        // Remove all HTML tags from the pasted content
        let plainText = bufferText.replace(/<\/?[^>]+(>|$)/g, '');
        setTimeout(() => {
          $(this).summernote('insertText', plainText);
        }, 10);
      }
    },
    toolbar: [
      ['style', ['bold', 'italic', 'underline', 'clear']],
      ['color', ['color']],  // Add font color options
      ['insert', ['link']],  // Add link insertion
      ['para', ['ul', 'ol', 'paragraph']], // Add bullet and number lists
      ['view', ['fullscreen', 'codeview']] // Optional view options
    ],
    placeholder: 'Start typing here...', // Adds a placeholder for empty text areas
    height: 200 // Set a default height for the editor
  });
  /*********************** FILE INPUT INITIALIZATION ***********************/
  $('#archivos_crear').fileinput({
    theme: 'fa',
    initialPreviewAsData: true,
    showUpload: false,
    overwriteInitial: false,
    fileActionSettings: {
      showZoom: false,
      showUpload: false,
      showRemove: false
    }
  });
  /*********************** FILE PREVIEW AND DELETION ***********************/
  if ($('#archivos_ejemplo').length !== 0) {
    $.ajax({
      url: `/rfq/quote/get_quote_files/${idRfq}`,
      dataType: 'json',
      contentType: "application/json; charset=utf-8",
      method: "GET",
      success: function (data) {
        const filesIcon = data.files.map(() => '<h1><i class="p-3 fas fa-file"></i></h1>');
        const filesConfig = data.files.map(file => ({
          previewAsData: false,
          caption: file,
          url: `/rfq/quote/delete_document/${idRfq}/${file}`,
          downloadUrl: `/rfq/documentos/${idRfq}/${file}`,
          key: `/rfq/quote/delete_document/${idRfq}/${file}`
        }));

        $('#archivos_ejemplo').fileinput({
          theme: 'explorer-fa',
          mainClass: 'input-group-sm',
          uploadUrl: `/rfq/quote/load_img/${idRfq}`,
          overwriteInitial: false,
          initialPreviewAsData: true,
          initialPreview: filesIcon,
          initialPreviewConfig: filesConfig,
          showRemove: false,
          showCancel: false,
          fileActionSettings: {
            showZoom: false
          }
        });

        // Handle pre-delete events
        $('#archivos_ejemplo').on('filepredelete', function (event, key) {
          alert_delete_system.modal(); // Open delete confirmation modal
          continue_button.attr('href', key);

          continue_button.one('click', function (e) {
            e.preventDefault();
            $.ajax({
              url: key,
              type: 'POST',
              success: function () {
                location.reload(); // Reload the page on successful deletion
              },
              error: function (jqXHR, textStatus, errorThrown) {
                console.error("Deletion failed:", textStatus, errorThrown);
              }
            });
          });

          return true;
        });
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.error("Failed to load files:", textStatus, errorThrown);
      }
    });
  }

  /*********************** DOWNLOAD ALL FILES ***********************/
  $('#download-all').on('click', function (e) {
    e.preventDefault();

    $.ajax({
      url: '/rfq/quote/download_all',
      type: 'POST',
      data: { idRfq }, // Use the existing `idRfq` variable
      success: function (response) {
        if (response.error) {
          console.error("Error:", response.error);
        } else if (response.downloadUrl) {
          window.location.href = response.downloadUrl; // Redirect to download
        } else {
          console.error("Unexpected response from the server.");
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.error("AJAX request failed:", textStatus, errorThrown);
      }
    });
  });
  /********************* AUDIT TRAILS *********************************************/
  $(document).ready(function () {
    const HIGHLIGHT_DURATION = 5000; // Duration to keep the element highlighted

    // Highlight an element when an audit trail link is clicked
    $('.audit_trail').on('click', function (e) {
      e.preventDefault(); // Prevent default anchor behavior
      $('#audit_trails_modal').modal('hide'); // Close the modal

      const targetElement = $($(this).attr('href')); // Get the target element by its href
      targetElement.addClass('highlight');

      setTimeout(() => {
        targetElement.removeClass('highlight'); // Remove the highlight after the specified duration
      }, HIGHLIGHT_DURATION);
    });

    // Show the audit trails modal when the button is clicked
    $('#audit_trails_button').on('click', function () {
      $('#audit_trails_modal').modal('show');
    });
  });
  /************************************** SHOW COMMENTS BUTTON ************************/
  $('#mostrar_comentarios').on('click', function () {
    $('#todos_commentarios_quote').modal('show');
  });
  /************************************* ADD NEW COMMENT ***********************************/
  if ($('#nuevo_comment').length) {
    $('#add_comment').on('click', function () {
      $('#nuevo_comment').modal('show');
    });
  }
  /************************************ TOGGLE SIDEBAR BUTTON *****************************/
  $('#sidebar_collapse').on('click', function () {
    $('#footer_item').toggleClass('footer_item1');
  });
  /************************************** DATEPICKER FOR DATE FIELDS *********************/
  function initializeDatePicker(selector, options = {}) {
    const defaultOptions = {
      singleDatePicker: true,
      autoUpdateInput: false,
      autoApply: true,
    };

    $(selector).daterangepicker({ ...defaultOptions, ...options });

    $(selector).on('apply.daterangepicker', function (ev, picker) {
      $(this).val(picker.startDate.format(options.format || 'MM/DD/YYYY'));
    });

    $(selector).on('cancel.daterangepicker', function () {
      $(this).val('');
    });
  }

  // Initialize standard date pickers
  initializeDatePicker('.date');

  // Initialize date picker with time picker for specific field
  initializeDatePicker('#end_date', {
    timePicker: true,
    timePicker24Hour: true,
    locale: {
      format: 'MM/DD/YYYY HH:mm'
    },
  });
  /************************************ DATATABLES JQUERY CONFIGURATION **************************/
  function initializeDataTable(selector, options) {
    const defaultOptions = {
      processing: true,
      serverSide: true,
      pageLength: 50,
      order: [[4, 'desc']],
      ajax: {
        url: '/rfq/quote/created_table', // Default URL for created table, can be overridden
        type: 'POST',
        data: {
          channel: $(selector).data('channel'),
        },
      },
      rowCallback: function (row, data) {
        if (data.comments === 'Working on it') {
          $(row).addClass('waiting_for');
        }
      },
      columns: [
        {
          data: 'id',
          render: function (data, type) {
            return type === 'display'
              ? `<a href="/rfq/perfil/quote/editar_cotizacion/${data}">${data}</a>`
              : data;
          },
        },
        { data: 'nombre_usuario' },
        { data: 'type_of_bid' },
        { data: 'issue_date' },
        { data: 'end_date' },
        { data: 'email_code' },
        {
          data: 'rfp',
          orderable: false,
          render: function (data, type) {
            return type === 'display'
              ? `<i class="fas fa-${data ? 'check text-success' : 'times text-danger'}"></i>`
              : data;
          },
        },
        {
          data: 'options',
          orderable: false,
          render: function (data, type, row) {
            return type === 'display'
              ? `<a href="/rfq/quote/delete_quote/${row.id}" class="delete_quote_button btn btn-sm btn-danger">
                  <i class="fas fa-trash"></i>
                </a>`
              : data;
          },
        },
      ],
    };

    // Merge default options with the passed options
    $(selector).DataTable({ ...defaultOptions, ...options });
  }

  // Initialize DataTable for #tabla_quotes
  initializeDataTable('#tabla_quotes', {
    ajax: {
      url: '/rfq/quote/created_table',
      type: 'POST',
      data: {
        channel: $('#tabla_quotes').data('channel'),
      }
    },
    order: [[4, 'desc']], // Default order for created table
  });

  // Initialize DataTable for #completed_table with custom settings
  initializeDataTable('#completed_table', {
    ajax: {
      url: '/rfq/quote/completed_table', // Custom URL for completed table
      type: 'POST',
      data: {
        channel: $('#completed_table').data('channel'),
      }
    },
    order: [[3, 'desc']], // Custom order for completed table
    columns: [
      {
        data: 'id',
        render: function (data, type) {
          return type === 'display'
            ? `<a href="/rfq/perfil/quote/editar_cotizacion/${data}">${data}</a>`
            : data;
        },
      },
      { data: 'nombre_usuario' },
      { data: 'type_of_bid' },
      { data: 'fecha_completado' },
      { data: 'email_code' },
      {
        data: 'rfp',
        orderable: false,
        render: function (data, type) {
          return type === 'display'
            ? `<i class="text-success fas fa-check"></i>`
            : `<i class="text-danger fas fa-times"></i>`;
        },
      },
      {
        data: 'options',
        orderable: false,
        render: function (data, type, row) {
          return type === 'display'
            ? `
              <a class="btn btn-sm calculate" href="/rfq/quote/proposal/${row.id}" target="_blank">
                <i class="fa fa-copy"></i>
              </a>
              <a class="btn btn-primary btn-sm" href="/rfq/quote/proposal_gsa/${row.id}" target="_blank">
                <i class="fa fa-copy"></i>
              </a>
            `
            : data;
        },
      },
    ],
  });

  // Initialize DataTable for #submitted_table with custom settings
  initializeDataTable('#submitted_table', {
    ajax: {
      url: '/rfq/quote/submitted_table', // Custom URL for submitted table
      type: 'POST',
      data: {
        channel: $('#submitted_table').data('channel'),
      }
    },
    order: [[3, 'desc']], // Custom order for submitted table
    columns: [
      {
        data: 'id',
        render: function (data, type) {
          return type === 'display'
            ? `<a href="/rfq/perfil/quote/editar_cotizacion/${data}">${data}</a>`
            : data;
        },
      },
      { data: 'nombre_usuario' },
      { data: 'type_of_bid' },
      { data: 'fecha_submitted' },
      { data: 'email_code' },
      {
        data: 'rfp',
        orderable: false,
        render: function (data, type) {
          return type === 'display'
            ? `<i class="text-success fas fa-check"></i>`
            : `<i class="text-danger fas fa-times"></i>`;
        },
      },
      {
        data: 'options',
        orderable: false,
        render: function (data, type, row) {
          return type === 'display'
            ? `
              <a class="btn btn-sm calculate" href="/rfq/quote/proposal/${row.id}" target="_blank">
                <i class="fa fa-copy"></i>
              </a>
              <a class="btn btn-primary btn-sm" href="/rfq/quote/proposal_gsa/${row.id}" target="_blank">
                <i class="fa fa-copy"></i>
              </a>
            `
            : data;
        },
      },
    ],
  });

  // Initialize DataTable for #award_table with custom settings
  initializeDataTable('#award_table', {
    ajax: {
      url: '/rfq/quote/award_table', // Custom URL for award table
      type: 'POST',
      data: {
        channel: $('#award_table').data('channel'),
      }
    },
    order: [[3, 'desc']], // Custom order for award table
    columns: [
      {
        data: 'id',
        render: function (data, type) {
          return type === 'display'
            ? `<a href="/rfq/perfil/quote/editar_cotizacion/${data}">${data}</a>`
            : data;
        },
      },
      { data: 'nombre_usuario' },
      { data: 'type_of_bid' },
      { data: 'fecha_award' },
      { data: 'email_code' },
      {
        data: 'rfp',
        orderable: false,
        render: function (data, type) {
          return type === 'display'
            ? `<i class="text-success fas fa-check"></i>`
            : `<i class="text-danger fas fa-times"></i>`;
        },
      },
      {
        data: 'options',
        orderable: false,
        render: function (data, type, row) {
          return type === 'display'
            ? `
              <a class="btn btn-sm calculate" href="/rfq/quote/proposal/${row.id}" target="_blank">
                <i class="fa fa-copy"></i>
              </a>
              <a class="btn btn-primary btn-sm" href="/rfq/quote/proposal_gsa/${row.id}" target="_blank">
                <i class="fa fa-copy"></i>
              </a>
            `
            : data;
        },
      },
    ],
  });

  // Initialize DataTable for #no_bid_table with custom settings
  initializeDataTable('#no_bid_table', {
    ajax: {
      url: '/rfq/quote/no_bid_table', // Custom URL for no_bid table
      type: 'POST'
    },
    order: [[4, 'desc']], // Custom order for no_bid table
    columns: [
      {
        data: 'id',
        render: function (data, type) {
          return type === 'display'
            ? `<a href="/rfq/perfil/quote/editar_cotizacion/${data}">${data}</a>`
            : data;
        },
      },
      { data: 'nombre_usuario' },
      { data: 'email_code' },
      { data: 'type_of_bid' },
      { data: 'comments' },
      {
        data: 'options',
        orderable: false,
        render: function (data, type, row) {
          return type === 'display'
            ? `<a href="/rfq/quote/delete_quote/${row.id}" class="delete_quote_button text-danger">
                <i class="fa fa-times"></i> Delete
              </a>`
            : data;
        },
      },
    ],
  });

  // Initialize DataTable for #not_submitted_table with custom settings
  initializeDataTable('#not_submitted_table', {
    ajax: {
      url: '/rfq/quote/not_submitted_table', // Custom URL for not_submitted table
      type: 'POST'
    },
    order: [[4, 'desc']], // Custom order for not_submitted table
    columns: [
      {
        data: 'id',
        render: function (data, type) {
          return type === 'display'
            ? `<a href="/rfq/perfil/quote/editar_cotizacion/${data}">${data}</a>`
            : data;
        },
      },
      { data: 'nombre_usuario' },
      { data: 'email_code' },
      { data: 'type_of_bid' },
      {
        data: 'options',
        orderable: false,
        render: function (data, type, row) {
          return type === 'display'
            ? `<a href="/rfq/quote/delete_quote/${row.id}" class="delete_quote_button text-danger">
                <i class="fa fa-times"></i> Delete
              </a>`
            : data;
        },
      },
    ],
  });

  // Initialize DataTable for #cancelled_table with custom settings
  initializeDataTable('#cancelled_table', {
    ajax: {
      url: '/rfq/quote/cancelled_table', // Custom URL for cancelled table
      type: 'POST'
    },
    order: [[4, 'desc']], // Custom order for cancelled table
    columns: [
      {
        data: 'id',
        render: function (data, type) {
          return type === 'display'
            ? `<a href="/rfq/perfil/quote/editar_cotizacion/${data}">${data}</a>`
            : data;
        },
      },
      { data: 'nombre_usuario' },
      { data: 'email_code' },
      { data: 'type_of_bid' },
      {
        data: 'options',
        orderable: false,
        render: function (data, type, row) {
          return type === 'display'
            ? `<a href="/rfq/quote/delete_quote/${row.id}" class="delete_quote_button text-danger">
                <i class="fa fa-times"></i> Delete
              </a>`
            : data;
        },
      },
    ],
  });

  // Initialize DataTable for #deleted_table with custom settings
  initializeDataTable('#deleted_table', {
    ajax: {
      url: '/rfq/quote/deleted_table', // Custom URL for deleted table
      type: 'POST'
    },
    order: [[4, 'desc']], // Custom order for deleted table
    columns: [
      {
        data: 'id',
        render: function (data, type) {
          return type === 'display'
            ? `<a href="/rfq/perfil/quote/editar_cotizacion/${data}">${data}</a>`
            : data;
        },
      },
      { data: 'nombre_usuario' },
      { data: 'email_code' },
      { data: 'type_of_bid' },
      {
        data: 'options',
        orderable: false,
        render: function (data, type, row) {
          return type === 'display'
            ? `<a href="/rfq/quote/restore_quote/${row.id}" class="btn btn-sm btn-success">
                <i class="fas fa-sync"></i>
              </a>`
            : data;
        },
      },
    ],
  });

  // Initialize DataTable for #fulfillment_quotes_table with custom settings
  initializeDataTable('#fulfillment_quotes_table', {
    ajax: {
      url: '/rfq/fulfillment/fulfillment_quotes_table', // Custom URL for fulfillment quotes table
      type: 'POST'
    },
    order: [[3, 'desc']], // Custom order for fulfillment quotes table
    columns: [
      {
        data: 'id',
        render: function (data, type) {
          return type === 'display'
            ? `<a href="/rfq/perfil/quote/editar_cotizacion/${data}">${data}</a>`
            : data;
        },
      },
      { data: 'email_code' },
      { data: 'canal' },
      { data: 'fulfillment_date' },
      { data: 'fecha_award' },
      { data: 'type_of_contract' },
    ],
  });

  // Initialize DataTable for #invoice_quotes_table with custom settings
  initializeDataTable('#invoice_quotes_table', {
    ajax: {
      url: '/rfq/invoice/invoice_quotes_table', // Custom URL for invoice quotes table
      type: 'POST'
    },
    order: [[3, 'desc']], // Custom order for invoice quotes table
    columns: [
      {
        data: 'id',
        render: function (data, type) {
          return type === 'display'
            ? `<a href="/rfq/perfil/quote/editar_cotizacion/${data}">${data}</a>`
            : data;
        },
      },
      { data: 'email_code' },
      { data: 'canal' },
      { data: 'invoice_date' },
      { data: 'type_of_contract' },
    ],
  });

  // Initialize DataTable for #submitted_invoice_quotes_table
  initializeDataTable('#submitted_invoice_quotes_table', {
    ajax: {
      url: '/rfq/submitted_invoice/submitted_invoice_quotes_table', // Custom URL
      type: 'POST'
    },
    order: [[3, 'desc']], // Custom order
    columns: [
      {
        data: 'id',
        render: function (data, type) {
          return type === 'display'
            ? `<a href="/rfq/perfil/quote/editar_cotizacion/${data}">${data}</a>`
            : data;
        },
      },
      { data: 'email_code' },
      { data: 'canal' },
      { data: 'submitted_invoice_date' },
      { data: 'type_of_contract' },
    ],
  });
});