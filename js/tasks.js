$(document).ready(function () {
  if(window.location.href.indexOf('#edit_task_modal') != -1 && window.location.href.indexOf('#id') != -1) {
    $('#edit_task_modal form').load('/rfq/task/load_task/' + window.location.href.substring(window.location.href.indexOf('#id')+4), function(){
      $('#edit_task_modal').modal();
    });
  }

  $('#tasks_board').load('/rfq/task/load_tasks_board/');
  $('#my_tasks_board').load('/rfq/task/load_my_tasks_board/');
  let tasks_done_table = $('#tasks_done_table').DataTable({
    ajax: '/rfq/task/load_tasks_done_table/'
  });

  $('#add_task_button').click(function (){
    $('#add_task_modal').modal();
  });

  $('#add_task_form').submit(function(){
    $.post('/rfq/task/save_task', $(this).serialize(), function(res){
      $('#add_task_form')[0].reset();
      $('#add_task_modal').modal('hide');
      $('#tasks_board').load('/rfq/task/load_tasks_board/');
      $('#my_tasks_board').load('/rfq/task/load_my_tasks_board/');
    });

    return false;
  });

  $('#tasks_board, #my_tasks_board, #tasks_done_table').on('click', '.edit_task_button', function(){
    $('#edit_task_modal form').load('/rfq/task/load_task/' + $(this).attr('data'), function(){
      $('#edit_task_modal').modal();
    });
    return false;
  });

  $('#edit_task_form').submit(function(){
    $.post('/rfq/task/update_task', $(this).serialize(), function(res){
      $('#edit_task_form')[0].reset();
      $('#edit_task_modal').modal('hide');
      tasks_done_table.ajax.reload(null, false);
      $('#tasks_board').load('/rfq/task/load_tasks_board/');
      $('#my_tasks_board').load('/rfq/task/load_my_tasks_board/');
    });
    return false;
  });
});
