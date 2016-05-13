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
    var content = $('#txt_data_content').summernote('code');
    if(id == 6){
      content = $('#txt_data_email').val();
    }
    if(id == 8){
      content = $('#txt_data_minimum_reseller_wallet').val();
    }
    //End Parameter
    
    if(state == "add"){
      
    }else{
      edit_data(id, name, content);
    }
  });
  
  $('#btn_remove_data').click(function (event) {
    event.preventDefault();
    remove_data();
  });
});