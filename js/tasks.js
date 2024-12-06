$(document).ready(function () {
  const tasksBoard = $('#tasks_board');
  const myTasksBoard = $('#my_tasks_board');
  const addTaskModal = $('#add_task_modal');
  const addTaskForm = $('#add_task_form');
  const editTaskModal = $('#edit_task_modal');
  const editTaskForm = $('#edit_task_form');
  const doneTasksTable = $('#tasks_done_table');

  // Load task boards
  function loadTaskBoards() {
    tasksBoard.load('/rfq/task/load_tasks_board/');
    myTasksBoard.load('/rfq/task/load_my_tasks_board/');
  }

  // Initialize tasks done table
  const tasksDoneTable = doneTasksTable.DataTable({
    processing: true,
    serverSide: true,
    order: [[1, "desc"]],
    ajax: {
      url: '/rfq/task/load_tasks_done_table',
      type: "POST"
    },
    columns: [
      { data: "id", visible: false },
      {
        data: "title",
        render: function (data, type, row) {
          return type === 'display'
            ? `<a class="edit_task_button" data-id="${row.id}" href="#">${data}</a>`
            : data;
        }
      },
      { data: "created_by" },
      { data: "assigned_to" }
    ]
  });

  // Open "Add Task" modal
  $('#add_task_button').on('click', function () {
    addTaskModal.modal('show');
  });

  // Handle "Add Task" form submission
  addTaskForm.on('submit', function (e) {
    e.preventDefault();
    $.post('/rfq/task/save_task', $(this).serialize(), function () {
      addTaskForm[0].reset();
      addTaskModal.modal('hide');
      loadTaskBoards();
    });
  });

  // Open "Edit Task" modal
  function openEditTaskModal(taskId) {
    const loadUrl = `/rfq/task/load_task/${taskId}`;
    editTaskModal.find('form').load(loadUrl, function () {
      editTaskModal.modal('show');
    });
  }

  // Handle clicks on "Edit Task" buttons
  $('#tasks_board, #my_tasks_board, #tasks_done_table').on('click', '.edit_task_button', function (e) {
    e.preventDefault();
    const taskId = $(this).data('id');
    if (taskId) {
      openEditTaskModal(taskId);
    } else {
      console.error('Task ID is missing');
    }
  });

  // Handle "Edit Task" form submission
  editTaskForm.on('submit', function (e) {
    e.preventDefault();
    $.post('/rfq/task/update_task', $(this).serialize(), function () {
      editTaskForm[0].reset();
      editTaskModal.modal('hide');
      tasksDoneTable.ajax.reload();
      loadTaskBoards();
    });
  });

  // Initial loading of task boards
  loadTaskBoards();
});