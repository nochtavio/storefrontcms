$(document).ready(function () {
  $('#btn_filter').click(function (event) {
    event.preventDefault();
    get_data(page);
  });

  $('#btn_add_data').click(function (event) {
    event.preventDefault();
    set_state("add");
  });

  $('#btn_submit_data').click(function (event) {
    event.preventDefault();

    //Parameter
    var id = $('#txt_data_id').val();
    var username = $('#txt_data_username').val();
    var password = $('#txt_data_password').val();
    var conf_password = $('#txt_data_conf_password').val();
    var active = 2;
    if ($('#txt_data_active').prop('checked')) {
      active = 1;
    }
    //End Parameter
    
    if(state == "add"){
      add_data(username, password, conf_password, active);
    }else{
      edit_data(id, password, conf_password, active);
    }
  });
});