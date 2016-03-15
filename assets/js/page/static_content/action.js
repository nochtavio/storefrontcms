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
    var content = $('#txt_data_content').code();
    //End Parameter
    
    if(state == "add"){
      
    }else{
      edit_data(id, content);
    }
  });
  
  $('#btn_remove_data').click(function (event) {
    event.preventDefault();
    remove_data();
  });
});