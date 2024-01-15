$(document).ready(function () {
  const monthTable = $('#month-table');
  const monthDataTable = $('#month-table').DataTable({
    "processing": true,
    "serverSide": true,
    "searching": false,
    "pageLength": 10,
    "order": [[1, "asc"]],
    "ajax": {
      "url": '/rfq/projection/month',
      "type": "POST",
      "data": {
        "id": monthTable.data('id'),
      }
    },
    "columns": [
      {
        "data": "id_quote",
        "visible": false
      },
      { "data": "invoice_date" },
      { "data": "id" },
      { "data": "type_of_contract" },
      { "data": "total_price" },
      {
        "data": "total_cost",
        "visible": false
      },
      {
        "data": "profit",
        "visible": false
      },
      {
        "data": "profit_percentage",
        "visible": false
      },
      { "data": "sales_commission" },
      { "data": "total_profit" },
      { "data": "total_profit_percentage" }
    ]
  });

  //totals
  const totalsContainer = $('#totals-container');
  totalsContainer.load('/rfq/projection/get_month_totals', { id: totalsContainer.data('id') });
});