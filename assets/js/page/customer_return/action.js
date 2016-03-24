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
    var purchase_code = $('#txt_data_purchase_code').val();
    var customer_id = $('#txt_data_customer_id').val();
    var order_item_id = $('#txt_data_order_item_id').val();
    var sku = $('#sel_data_sku').val();
    var qty = $('#txt_data_qty').val();
    var reason = $('#txt_data_reason').val();
    var status = $('#sel_data_status').val();
    //End Parameter

    if (state == "add") {
      add_data(purchase_code, customer_id, order_item_id, sku, qty, reason, status);
    } else {
      edit_data(id, purchase_code, customer_id, order_item_id, sku, qty, reason, status);
    }
  });

  $('#btn_remove_data').click(function (event) {
    event.preventDefault();
    remove_data();
  });

  $('#btn_purchase_code').click(function (event) {
    event.preventDefault();
    
    var purchase_code = $('#txt_data_purchase_code').val();
    get_sku(purchase_code, "");
  });
  
  $('#sel_data_sku').change(function (event) {
    event.preventDefault();
    
    var sku = $('#sel_data_sku').val();
    get_order_item(sku);
  });
});