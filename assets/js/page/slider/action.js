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
    var show_order = $('#txt_data_show_order').val();
    var link = $('#txt_data_link').val();
    var target = $('#sel_data_target').val();
    var title = $('#txt_data_title').val();
    var description = $('#txt_data_description').val();
    var active = 0;
    if ($('#txt_data_active').prop('checked')) {
      active = 1;
    }
    //End Parameter
    
    if(state == "add"){
      add_data(show_order, link, target, title, description, active);
    }else{
      edit_data(id, show_order, link, target, title, description, active);
    }
  });
  
  $('#btn_remove_data').click(function (event) {
    event.preventDefault();
    remove_data();
  });
});