$(document).ready(function () {
  const monthTable = $('#month-table');
  const monthDataTable = $('#month-table').DataTable({
    "processing": true,
    "serverSide": true,
    "searching": false,
    "pageLength": 10,
    "order": [[0, "asc"]],
    "ajax": {
      "url": '/rfq/projection/month',
      "type": "POST",
      "data": {
        "id": monthTable.data('id'),
      }
    },
    "columns": [
      { "data": "invoice_date" },
      { "data": "id" },
      { "data": "type_of_contract" },
      { "data": "total_price" },
      {
        "data": "total_cost",
        "visible": false
      },
      { "data": "profit" },
      { "data": "profit_percentage" }
    ]
  });
});