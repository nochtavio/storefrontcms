$(document).ready(function () {
  page = 1;
  last_page = 0;
  total_data = 0;
  state = "";

  start_date = '';
  end_date = '';
  start_date_text = '';
  end_date_text = '';

  $('#txt_order_date').daterangepicker({
    autoUpdateInput: false,
    locale: {
        cancelLabel: 'Clear'
    },
    "dateLimit": {
      "months": 6
    }
  });

  $('#txt_order_date').on('apply.daterangepicker', function (ev, picker) {
    start_date = picker.startDate.format('YYYY-MM-DD');
    end_date = picker.endDate.format('YYYY-MM-DD');
    
    start_date_text = picker.startDate.format('Do MMMM YYYY');
    end_date_text = picker.endDate.format('Do MMMM YYYY');
    
    $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
  });
  
  $('#txt_order_date').on('cancel.daterangepicker', function(ev, picker) {
    start_date = '';
    end_date = '';
    
    start_date_text = '';
    end_date_text = '';
    
    $(this).val('');
    
    get_data(1);
  });

  get_data(page);
});