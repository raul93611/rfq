$(document).ready(function () {
  const addEventModal = $('#add-event-modal');
  const editEventModal = $('#edit-event-modal');
  const addEventForm = $('#add-event-form');
  const editEventForm = $('#edit-event-form');
  const addSharedEventButton = $('#add-shared-event-button');
  const addSharedEventModal = $('#add-shared-event-modal');
  const addSharedEventForm = $('#add-shared-event-form');
  let timeline;

  $.ajax({
    url: '/rfq/fulfillment/personnel/get_personnel_events',
    data: {
      id: $(this).data('id'),
    },
    type: 'POST',
    success: setTimeline
  });

  function setTimeline(res) {
    const options = {
      stack: true,
      maxHeight: 500,
      horizontalScroll: false,
      verticalScroll: true,
      zoomKey: "ctrlKey",
      start: Date.now() - 1000 * 60 * 60 * 24 * 3,
      end: Date.now() + 1000 * 60 * 60 * 24 * 21,
      orientation: {
        axis: "both",
        item: "top"
      },
    };
    const groups = new vis.DataSet(res.personnel);
    const items = new vis.DataSet(res.events);

    const container = document.getElementById('roadmap');
    timeline = new vis.Timeline(container, null, options);
    timeline.setGroups(groups);
    timeline.setItems(items);
    timeline.on('doubleClick', loadAddModal);
    timeline.on('select', loadEditModal);
  }

  function loadAddModal(e) {
    if (e.what == 'background') {
      addEventForm[0].reset();
      addEventModal.modal();
      addEventModal.find('#start').val(moment(e.time).format('MM/DD/YYYY'));
      addEventModal.find('#end').val(moment(e.time).add(1, 'days').format('MM/DD/YYYY'));
      addEventModal.find('input[name="id_personnel"]').val(e.group);
      addEventModal.find('#color').colorpicker();
      addEventModal.find('#color').on('colorpickerChange', function (event) {
        $(this).parent().find('.fa-square').css('color', event.color.toString());
      })
      $('#start, #end').daterangepicker({
        singleDatePicker: true,
        autoApply: true
      });
    }
  }

  function loadEditModal(e) {
    if (e.items.length == 0) return;
    editEventForm.load(`/rfq/fulfillment/personnel_calendar/load`, { id: e.items[0] }, () => {
      editEventModal.modal();
      editEventForm.find('#color').colorpicker();
      editEventForm.find('#color').on('colorpickerChange', function (event) {
        $(this).parent().find('.fa-square').css('color', event.color.toString());
      })
      $('#start, #end').daterangepicker({
        singleDatePicker: true,
        autoApply: true
      });
    });
    editEventForm.on('click', '.delete-event-button', deleteEvent);
  }

  function deleteEvent(e) {
    $.ajax({
      url: '/rfq/fulfillment/personnel_calendar/delete',
      data: {
        id: $(e.target).data('id'),
      },
      type: 'POST',
      success: function (res) {
        editEventModal.modal('hide');
        reloadDatasets();
      }
    });
  }

  function reloadDatasets() {
    $.ajax({
      url: '/rfq/fulfillment/personnel/get_personnel_events',
      data: {
        id: $(this).data('id'),
      },
      type: 'POST',
      success: function (res) {
        const groups = new vis.DataSet(res.personnel);
        const items = new vis.DataSet(res.events);
        timeline.setGroups(groups);
        timeline.setItems(items);
      }
    });
  }

  addEventForm.validate({
    rules: {
      name: { required: true },
      start: { required: true },
      end: { required: true }
    },
    submitHandler: function (form) {
      $.ajax({
        url: '/rfq/fulfillment/personnel_calendar/save',
        type: 'POST',
        data: $(form).serialize(),
        success: function (response) {
          addEventModal.modal('hide');
          reloadDatasets();
        },
        error: function (xhr, status, error) {
          console.error(error);
        }
      });
    }
  });

  editEventForm.validate({
    rules: {
      name: { required: true },
      start: { required: true },
      end: { required: true }
    },
    submitHandler: function (form) {
      $.ajax({
        url: '/rfq/fulfillment/personnel_calendar/update',
        type: 'POST',
        data: $(form).serialize(),
        success: function (response) {
          editEventModal.modal('hide');
          reloadDatasets();
        },
        error: function (xhr, status, error) {
          console.error(error);
        }
      });
    }
  });

  addSharedEventButton.click(function () {
    addSharedEventForm[0].reset();
    addSharedEventModal.modal('show');
    addSharedEventModal.find('#start').val(moment().format('MM/DD/YYYY'));
    addSharedEventModal.find('#end').val(moment().add(1, 'days').format('MM/DD/YYYY'));
    addSharedEventModal.find('#color').colorpicker();
    addSharedEventModal.find('#color').on('colorpickerChange', function (event) {
      $(this).parent().find('.fa-square').css('color', event.color.toString());
    })
    addSharedEventModal.find('#start, #end').daterangepicker({
      singleDatePicker: true,
      autoApply: true
    });
  });

  addSharedEventForm.validate({
    rules: {
      name: { required: true },
      start: { required: true },
      end: { required: true }
    },
    submitHandler: function (form) {
      $.ajax({
        url: '/rfq/fulfillment/personnel_calendar/save_shared_event',
        type: 'POST',
        data: $(form).serialize(),
        success: function (response) {
          addSharedEventModal.modal('hide');
          reloadDatasets();
        },
        error: function (xhr, status, error) {
          console.error(error);
        }
      });
    }
  });
});