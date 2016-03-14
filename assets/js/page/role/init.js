$(document).ready(function () {
  page = 1;
  last_page = 0;
  total_data = 0;
  state = "";
  
  $('#sel_data_menu').multiselect({
    enableFiltering: true,
    buttonClass: 'btn btn-default',
    maxHeight: 400
  });
  
  get_data(page);
});