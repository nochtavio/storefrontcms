$(document).ready(function () {
  $('#btn_filter').click(function (event) {
    event.preventDefault();
    get_data(page);
  });
  
  $('#btn_approval').click(function (event) {
    event.preventDefault();
    approval();
  });
});