$(document).ready(function () {
  page = 1;
  last_page = 0;
  total_data = 0;
  state = "";
  
  $('#sel_data_category').multiselect({
    enableFiltering: true,
    buttonClass: 'btn btn-default',
    maxHeight: 400
  });
  
  $('#sel_data_brand').multiselect({
    enableFiltering: true,
    buttonClass: 'btn btn-default',
    maxHeight: 400
  });
  
  $('#txt_data_start_date').datepicker({
    todayHighlight: true,
    zIndexOffset: '9999',
    format: 'yyyy-m-d'
  });
  
  $('#txt_data_end_date').datepicker({
    todayHighlight: true,
    zIndexOffset: '9999',
    format: 'yyyy-m-d'
  });
  
  get_data(page);
});