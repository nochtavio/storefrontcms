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
    var price = $('#txt_data_price').val();
    var sale_price = $('#txt_data_sale_price').val();
    var reseller_price = $('#txt_data_reseller_price').val();
    var weight = $('#txt_data_weight').val();
    var attribute = $('#txt_data_attribute').val();
    var description = $('#txt_data_description').code();
    var short_description = $('#txt_data_short_description').code();
    var info = $('#txt_data_info').val();
    var size_guideline = $('#txt_data_size_guideline').val();
    var category = [];
    $('input:checkbox[name=cb_category]:checked').each(function(){
      category.push(this.value);
    });
    var active = 0;
    if ($('#txt_data_active').prop('checked')) {
      active = 1;
    }
    //End Parameter
    
    if(state == "add"){
      add_data(name, price, sale_price, reseller_price, weight, attribute, description, short_description, info, size_guideline, category, active);
    }else{
      edit_data(id, name, price, sale_price, reseller_price, weight, attribute, description, short_description, info, size_guideline, category, active);
    }
  });
  
  $('#btn_remove_data').click(function (event) {
    event.preventDefault();
    remove_data();
  });
});