$(document).ready(function () {
  const monthTable = $('#month-table');
  const monthDataTable = $('#month-table').DataTable({
    "processing": true,
    "serverSide": true,
    "searching": false,
    "pageLength": 50,
    "order": [[1, "asc"]],
    "ajax": {
      "url": '/rfq/projection/month',
      "type": "POST",
      "data": {
        "id": monthTable.data('id'),
      }
    },
    "columns": [
      { "data": "id" },
      { "data": "invoice_date" },
      { "data": "contract_number" },
      { "data": "total_cost" },
      { "data": "total_price" },
      { "data": "profit" },
      { "data": "type_of_contract" }
    ]
  });
});