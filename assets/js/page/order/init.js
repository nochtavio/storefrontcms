$(document).ready(function () {
  page = 1;
  last_page = 0;
  total_data = 0;
  state = "";

  start_date = '';
  end_date = '';

  $('#txt_order_date').daterangepicker();

  $('#txt_order_date').daterangepicker({
    "autoApply": true,
    "dateLimit": {
      "months": 3
    }
  });

  $('#txt_order_date').on('apply.daterangepicker', function (ev, picker) {
    start_date = picker.startDate.format('YYYY-MM-DD');
    end_date = picker.endDate.format('YYYY-MM-DD');
  });

  get_data(page);
});