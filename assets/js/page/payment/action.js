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
    var description = $('#txt_data_description').code();
    var type = $('#sel_data_type').val();
    var minimum_grand_total = $('#txt_data_minimum_grand_total').val();
    var show_order = $('#txt_data_show_order').val();
    var active = 0;
    if ($('#txt_data_active').prop('checked')) {
      active = 1;
    }
    //End Parameter
    
    if(state == "add"){
      add_data(name, description, type, minimum_grand_total, show_order, active);
    }else{
      edit_data(id, name, description, type, minimum_grand_total, show_order, active);
    }
  });
  
  $('#btn_remove_data').click(function (event) {
    event.preventDefault();
    remove_data();
  });
});