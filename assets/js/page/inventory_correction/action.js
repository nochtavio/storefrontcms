$(document).ready(function () {
  $('#btn_apply_sku').click(function (event) {
    event.preventDefault();

    //Parameter
    var sku = $('#txt_data_sku').val();
    //End Parameter
    
    generate_sku(sku);
  });
  
  $('#btn_submit_data').click(function(event){
    event.preventDefault();
    
    //Parameter
    var SKU = $('#txt_data_sku').val();
    var product_id = $('#txt_data_id_products').val();
    var quantity = $('#txt_data_current_quantity').val();
    var quantity_warehouse = $('#txt_data_current_quantity_warehouse').val();
    var updated_quantity = $('#txt_data_quantity').val();
    var history_type = $('#sel_data_type').val();
    //End Parameter
    
    add_data(SKU, product_id, quantity, quantity_warehouse, updated_quantity, history_type);
  })
});