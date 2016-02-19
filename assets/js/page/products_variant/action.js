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
    var id_color = $('#txt_data_id_color').val();
    var size = $('#txt_data_size').val();
    var quantity = $('#txt_data_quantity').val();
    var active = 0;
    if ($('#txt_data_active').prop('checked')) {
      active = 1;
    }
    //End Parameter
    
    if(state == "add"){
      add_data(id_color, size, quantity, active);
    }else{
      edit_data(id, id_color, size, quantity, active);
    }
  });
  
  $('#btn_remove_data').click(function (event) {
    event.preventDefault();
    remove_data();
  });
});