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
    var id_brand = $('#sel_data_brand').val();
    var name = $('#txt_data_name').val();
    var price = $('#txt_data_price').val();
    var sale_price = $('#txt_data_sale_price').val();
    var modal_price = $('#txt_data_modal_price').val();
    var potongan_gold = $('#txt_data_potongan_gold').val();
    var potongan_silver = $('#txt_data_potongan_silver').val();
    var potongan_bronze = $('#txt_data_potongan_bronze').val();
    var weight = $('#txt_data_weight').val();
    var attribute = $('#txt_data_attribute').val();
    var description = $('#txt_data_description').summernote('code');
    var short_description = $('#txt_data_short_description').summernote('code');
    var info = $('#txt_data_info').val();
    var size_guideline = $('#txt_data_size_guideline').val();
    var category = $('#sel_data_category').val();
    var category_child = $('#sel_data_category_child').val();
    var category_child_ = $('#sel_data_category_child_').val();
    var active = 0;
    if ($('#txt_data_active').prop('checked')) {
      active = 1;
    }
    //End Parameter
    
    if(state == "add"){
      add_data(id_brand, name, price, sale_price, modal_price, potongan_gold, potongan_silver, potongan_bronze, weight, attribute, description, short_description, info, size_guideline, category, category_child, category_child_, active);
    }else{
      edit_data(id, id_brand, name, price, sale_price, modal_price, potongan_gold, potongan_silver, potongan_bronze, weight, attribute, description, short_description, info, size_guideline, category, category_child, category_child_, active);
    }
  });
  
  $('#btn_remove_data').click(function (event) {
    event.preventDefault();
    remove_data();
  });
});