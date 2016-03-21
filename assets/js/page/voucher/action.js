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
    var name = $('#txt_data_name').val();
    var code = $('#txt_data_code').val();
    var description = $('#txt_data_description').val();
    var discount_type = $('#sel_data_discount_type').val();
    var transaction_type = $('#sel_data_transaction_type').val();
    var value = $('#txt_data_value').val();
    var category = $('#sel_data_category').val();
    var brand = $('#sel_data_brand').val();
    var min_price = $('#txt_data_min_price').val();
    var start_date = $('#txt_data_start_date').val();
    var end_date = $('#txt_data_end_date').val();
    var active = 0;
    if ($('#txt_data_active').prop('checked')) {
      active = 1;
    }
    //End Parameter
    
    if(state == "add"){
      add_data(name, code, description, discount_type, transaction_type, value, category, brand, min_price, start_date, end_date, active);
    }else{
      edit_data(id, name, code, description, discount_type, transaction_type, value, category, brand, min_price, start_date, end_date, active);
    }
  });
  
  $('#btn_remove_data').click(function (event) {
    event.preventDefault();
    remove_data();
  });
});