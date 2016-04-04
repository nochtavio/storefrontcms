$(document).ready(function () {
  generate_sku = function(sku){
    $.ajax({
      url: base_url + 'inventory_correction/get_sku_detail',
      type: 'POST',
      data: {
        sku: sku
      },
      dataType: 'json',
      beforeSend: function () {
        $('#div_alert').hide();
        $('#txt_data_id_products').val('');
        $('#txt_data_product_name').val('');
        $('#txt_data_color').val('');
        $('#txt_data_size').val('');
        $('#txt_data_current_quantity').val('');
        $('#txt_data_current_quantity_warehouse').val('');
      },
      success: function (result) {
        if (result['result'] === 'r1') {
          $('.form-hidden').show();
          $('#txt_data_id_products').val(result['id_products']);
          $('#txt_data_product_name').val(result['products_name']);
          $('#txt_data_color').val(result['color_name']);
          $('#txt_data_size').val(result['size']);
          $('#txt_data_current_quantity').val(result['quantity']);
          $('#txt_data_current_quantity_warehouse').val(result['quantity_warehouse']);
        } else {
          $('.form-hidden').hide();
          $('#div_alert').show();
          $('#span_error').html('<strong>SKU</strong> is not exist !');
        }
      }
    });
  };
  
  add_data = function (SKU, product_id, quantity, quantity_warehouse, updated_quantity, history_type) {
    $.ajax({
      url: base_url + 'inventory_correction/add_data',
      type: 'POST',
      data: {
        SKU: SKU,
        product_id: product_id,
        quantity: quantity,
        quantity_warehouse: quantity_warehouse,
        updated_quantity: updated_quantity,
        history_type: history_type
      },
      dataType: 'json',
      beforeSend: function () {
        $('#div_alert').hide();
        $('#div_success').hide();
      },
      success: function (result) {
        if (result['result'] === 'r1') {
          $('#div_success').show();
          $('#span_success').html('Inventory Correction has successfully inserted !');
          $('.form-control').val('');
          $('#sel_data_type').val(3);
          $('.form-hidden').hide();
        } else {
          $('#div_alert').show();
          $('#span_error').html(result['result_message']);
        }
      }
    });
  };
});