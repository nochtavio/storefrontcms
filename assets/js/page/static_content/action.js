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
    if(id == 6 || id == 8 || id == 9 || id == 10 || id == 11 || id == 12 || id == 13){
      content = $('#txt_data_text_field').val();
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