$(document).ready(function () {
  const addEventModal = $('#add-event-modal');
  const editEventModal = $('#edit-event-modal');
  const addEventForm = $('#add-event-form');
  const editEventForm = $('#edit-event-form');
  const addSharedEventButton = $('#add-shared-event-button');
  const addSharedEventModal = $('#add-shared-event-modal');
  const addSharedEventForm = $('#add-shared-event-form');
  let timeline;

  // Colorpicker options configuration
  const colorpickerOptions = {
    extensions: [
      {
        name: 'swatches',
        options: {
          colors: {
            '#000000': '#000000',
            '#888888': '#888888',
            '#ffffff': '#ffffff',
            '#ff0000': '#ff0000',
            '#777777': '#777777',
            '#337ab7': '#337ab7',
            '#5cb85c': '#5cb85c',
            '#5bc0de': '#5bc0de',
            '#f0ad4e': '#f0ad4e',
            '#d9534f': '#d9534f',
            '#007bff': '#007bff',
            '#6610f2': '#6610f2',
            '#fd7e14': '#fd7e14',
            '#dc3545': '#dc3545',
            '#e83e8c': '#e83e8c',
            '#6f42c1': '#6f42c1'
          },
          namesAsValues: true
        }
      }
    ]
  };

  // Fetch and initialize timeline events
  function fetchTimelineEvents() {
    const personnelId = $(this).data('id');

    $.ajax({
      url: '/rfq/fulfillment/personnel/get_personnel_events',
      type: 'POST',
      data: { id: personnelId },
      success: setTimeline,
      error: (xhr, status, error) => {
        console.error('Failed to fetch timeline events:', error);
      }
    });
  }

  fetchTimelineEvents();

  // Configure and set up the timeline
  function setTimeline(response) {
    const { personnel, events } = response.data;

    const timelineOptions = {
      stack: true,
      maxHeight: 500,
      horizontalScroll: false,
      verticalScroll: true,
      zoomKey: "ctrlKey",
      start: Date.now() - 3 * 24 * 60 * 60 * 1000, // 3 days before now
      end: Date.now() + 21 * 24 * 60 * 60 * 1000, // 21 days after now
      orientation: {
        axis: "both",
        item: "top"
      }
    };

    // Create dataset for groups and items
    const groups = new vis.DataSet(personnel);
    const items = new vis.DataSet(events);

    // Initialize the timeline
    const container = document.getElementById('roadmap');
    timeline = new vis.Timeline(container, null, timelineOptions);
    timeline.setGroups(groups);
    timeline.setItems(items);

    // Bind event handlers
    timeline.on('doubleClick', loadAddModal);
    timeline.on('select', loadEditModal);
  }

  // Utility to initialize color picker
  function initColorPicker(element) {
    element.colorpicker(colorpickerOptions);
    element.on('colorpickerChange', function (event) {
      $(this).parent().find('.fa-square').css('color', event.color.toString());
    });
  }

  // Utility to initialize date range picker
  function initDateRangePicker(startElement, endElement) {
    startElement.daterangepicker({
      singleDatePicker: true,
      autoApply: true
    });
    endElement.daterangepicker({
      singleDatePicker: true,
      autoApply: true
    });
  }

  // Load Add Event Modal
  function loadAddModal(e) {
    if (e.what === 'background') {
      addEventForm[0].reset();
      addEventModal.modal();

      addEventModal.find('#start').val(moment(e.time).format('MM/DD/YYYY'));
      addEventModal.find('#end').val(moment(e.time).add(1, 'days').format('MM/DD/YYYY'));
      addEventModal.find('input[name="id_personnel"]').val(e.group);

      const colorPickerElement = addEventModal.find('#color');
      initColorPicker(colorPickerElement);
      initDateRangePicker(addEventModal.find('#start'), addEventModal.find('#end'));
    }
  }

  // Load Edit Event Modal
  function loadEditModal(e) {
    if (!e.items.length) return;

    const eventId = e.items[0];
    editEventForm.load(`/rfq/fulfillment/personnel_calendar/load`, { id: eventId }, () => {
      editEventModal.modal();

      const colorPickerElement = editEventForm.find('#color');
      initColorPicker(colorPickerElement);
      initDateRangePicker(editEventForm.find('#start'), editEventForm.find('#end'));

      editEventForm.on('click', '.delete-event-button', deleteEvent);
    });
  }

  // Delete Event
  function deleteEvent(e) {
    const eventId = $(e.target).data('id');
    $.ajax({
      url: '/rfq/fulfillment/personnel_calendar/delete',
      type: 'POST',
      data: { id: eventId },
      success: () => {
        editEventModal.modal('hide');
        reloadDatasets();
      },
      error: (xhr, status, error) => {
        console.error('Failed to delete event:', error);
      }
    });
  }

  // Reload Dataset
  function reloadDatasets() {
    const personnelId = $(this).data('id');
    $.ajax({
      url: '/rfq/fulfillment/personnel/get_personnel_events',
      type: 'POST',
      data: { id: personnelId },
      success: (res) => {
        const groups = new vis.DataSet(res.data.personnel);
        const items = new vis.DataSet(res.data.events);
        timeline.setGroups(groups);
        timeline.setItems(items);
      },
      error: (xhr, status, error) => {
        console.error('Failed to reload datasets:', error);
      }
    });
  }

  // Form Validation and Submission Helper
  function setupFormValidation(formElement, submitUrl, onSuccess) {
    formElement.validate({
      rules: {
        name: { required: true },
        start: { required: true },
        end: { required: true }
      },
      submitHandler: (form) => {
        $.ajax({
          url: submitUrl,
          type: 'POST',
          data: $(form).serialize(),
          success: onSuccess,
          error: (xhr, status, error) => {
            console.error('Form submission failed:', error);
          }
        });
      }
    });
  }

  // Initialize Add Event Form
  setupFormValidation(addEventForm, '/rfq/fulfillment/personnel_calendar/save', () => {
    addEventModal.modal('hide');
    reloadDatasets();
  });

  // Initialize Edit Event Form
  setupFormValidation(editEventForm, '/rfq/fulfillment/personnel_calendar/update', () => {
    editEventModal.modal('hide');
    reloadDatasets();
  });

  // Add Shared Event Button Handler
  addSharedEventButton.click(() => {
    addSharedEventForm[0].reset();
    addSharedEventModal.modal('show');

    addSharedEventModal.find('#start').val(moment().format('MM/DD/YYYY'));
    addSharedEventModal.find('#end').val(moment().add(1, 'days').format('MM/DD/YYYY'));

    const colorPickerElement = addSharedEventModal.find('#color');
    initColorPicker(colorPickerElement);
    initDateRangePicker(addSharedEventModal.find('#start'), addSharedEventModal.find('#end'));
  });

  // Initialize Add Shared Event Form
  setupFormValidation(addSharedEventForm, '/rfq/fulfillment/personnel_calendar/save_shared_event', () => {
    addSharedEventModal.modal('hide');
    reloadDatasets();
  });
});