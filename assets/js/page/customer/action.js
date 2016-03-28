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
    var customer_id = $('#txt_data_id').val();
    var customer_status = $('#sel_data_customer_status').val();
    //End Parameter
    
    if(state == "add"){
      //add_data(name, active);
    }else{
      edit_data(customer_id, customer_status);
    }
  });
  
  $('#btn_remove_data').click(function (event) {
    event.preventDefault();
    remove_data();
  });
});