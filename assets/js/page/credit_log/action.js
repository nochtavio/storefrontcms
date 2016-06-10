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
    var id_customer = $('#txt_data_id_customer').val();
    var id_reseller = $('#txt_data_id_reseller').val();
    var email = $('#txt_data_email').val();
    var type = $('#sel_data_type').val();
    var amount = $('#txt_data_amount').val();
    var status = $('#sel_data_status').val();
    //End Parameter

    if(state == "add"){
      add_data(email, type, amount);
    }else{
      edit_data(id, id_customer, id_reseller, amount, status);
    }
  });

  $('#btn_remove_data').click(function (event) {
    event.preventDefault();
    remove_data();
  });
});
