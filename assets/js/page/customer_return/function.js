$(document).ready(function () {
  get_data = function (page) {
    //Filter
    var purchase_code = $('#txt_purchase_code').val();
    var customer_email = $('#txt_customer_email').val();
    var status = $('#sel_status').val();
    var order = $('#sel_order').val();
    //End Filter

    $.ajax({
      url: base_url + 'customer_return/get_data',
      type: 'POST',
      data: {
        page: page,
        purchase_code: purchase_code,
        customer_email: customer_email,
        status: status,
        order: order
      },
      dataType: 'json',
      success: function (result) {
        $('#div_hidden').empty();
        $('#table_content').empty();
        $('#table_content').append("\
          <tr>\
            <th>No</th>\
            <th>Purchase Code</th>\
            <th>Customer Email</th>\
            <th>Item Detail</th>\
            <th>Status</th>\
            <th>Date</th>\
            <th>Action</th>\
          </tr>\
        ");

        if (result['result'] === 'r1') {
          //Set Paging
          var no = 1;
          var size = result['size'];
          var total_page = result['totalpage'];
          var class_page = ".page";
          if (page > 1) {
            no = parseInt(1) + (parseInt(size) * (parseInt(page) - parseInt(1)));
          }

          writePaging(total_page, page, class_page);
          last_page = total_page;
          //End Set Paging

          for (var x = 0; x < result['total']; x++) {
            //Item Detail
            var item_detail = "";
            item_detail = "\
              <strong>Product Name: </strong> " + result['products_name'][x] + " <br/>\
              <strong>SKU: </strong> " + result['SKU'][x] + " <br/>\
              <strong>Quantity: </strong> " + result['qty'][x] + " <br/>\
            ";
            //End Item Detail
            
            //Status
            var status = "<span class='label label-danger'>New</span>";
            if (result['status'][x] == 1) {
              status = "<span class='label label-warning'>Processed</span>";
            }else if(result['status'][x] == 2){
              status = "<span class='label label-success'>Finished</span>";
            }
            //End Status

            //Date
            var date = "Created by <strong>" + result['creby'][x] + "</strong> <br/> on <strong>" + result['cretime'][x] + "</strong>";
            if (result['modby'][x] != null) {
              date += "<br/><br/> Modified by <strong>" + result['modby'][x] + "</strong> <br/> on <strong>" + result['modtime'][x] + "</strong>";
            }
            //End Date
            
            //Action
            var action = "";
            if(result['allowed_edit']){
              action += "<a href='#' id='btn_edit" + result['id'][x] + "' class='fa fa-pencil-square-o'></a> &nbsp;";
            }
            if(result['allowed_delete']){
              action += "<a href='#' id='btn_remove" + result['id'][x] + "' class='fa fa-times'></a> &nbsp;";
            }
            //End Action

            $('#table_content').append("\
              <tr>\
                <td>" + (parseInt(no) + parseInt(x)) + "</td>\
                <td>" + result['purchase_code'][x] + "</td>\
                <td>" + result['customer_email'][x] + "</td>\
                <td>" + item_detail + "</td>\
                <td>" + status + "</td>\
                <td>" + date + "</td>\
                <td>" + action + "</td>\
              </tr>");

            //Set Object ID
            $('#div_hidden').append("\
              <input type='hidden' id='object" + x + "' value='" + result['id'][x] + "' />\
            ");
            total_data++;
            //End Set Object ID
          }
          
          set_edit();
          set_remove();
        } else {
          $('#table_content').append("\
          <tr>\
            <td colspan='7'><strong style='color:red;'>" + result['message'] + "</strong></td>\
          </tr>");
        }
      }
    });
  };

  set_edit = function () {
    var id = [];
    for (var x = 0; x < total_data; x++) {
      id[x] = $('#object' + x).val();
    }

    $.each(id, function (x, val) {
      $(document).off('click', '#btn_edit' + val);
      $(document).on('click', '#btn_edit' + val, function (event) {
        event.preventDefault();
        set_state("edit");
        $.ajax({
          url: base_url + 'customer_return/get_specific_data',
          type: 'POST',
          data:{
            id: val
          },
          dataType: 'json',
          success: function (result) {
            if (result['result'] === 'r1') {
              $("#txt_data_id").val(val);
              $("#txt_data_order_item_id").val(result['order_item_id']);
              $("#txt_data_customer_id").val(result['customer_id']);
              $("#txt_data_purchase_code").val(result['purchase_code']);
              $("#txt_data_customer_email").val(result['customer_email']);
              $("#txt_data_qty").val(result['qty']);
              $("#txt_data_reason").val(result['reason']);
              $("#sel_data_status").val(result['status']);
              get_sku($("#txt_data_purchase_code").val(), result['SKU']);
              $('#modal_data').modal('show');
            }
            else {
              alert("Error in connection");
              $('#modal_data').modal('hide');
            }
          }
        });
      });
    });
  };
  
  set_remove = function () {
    var id = [];
    for (var x = 0; x < total_data; x++) {
      id[x] = $('#object' + x).val();
    }

    $.each(id, function (x, val) {
      $(document).off('click', '#btn_remove' + val);
      $(document).on('click', '#btn_remove' + val, function (event) {
        event.preventDefault();
        $('#remove_message').html("Are you sure you want to remove this customer return?");
        $('#txt_remove_id').val(val);
        $('#modal_remove').modal("show");
      });
    });
  };

  set_state = function (x) {
    state = x;
    if (x == "add") {
      $('#modal_data_title').html("Add Customer Return");
      
      $('.form_data').val('');
      $('#txt_data_purchase_code').val('');
      $('.hidden-div').hide();
      $('.hidden-div-2').hide();

      $('#error_container').hide();
      $('#error_container_message').empty();
    } else {
      $('#modal_data_title').html("Edit Customer Return");

      $('.form_data').val('');
      $('.hidden-div').show();
      $('.hidden-div-2').show();

      $('#error_container').hide();
      $('#error_container_message').empty();
    }
  };

  add_data = function (purchase_code, customer_id, order_item_id, sku, qty, reason, status) {
    $.ajax({
      url: base_url + 'customer_return/add_data',
      type: 'POST',
      data: {
        purchase_code: purchase_code,
        customer_id: customer_id,
        order_item_id: order_item_id,
        SKU: sku,
        qty: qty,
        reason: reason,
        status: status
      },
      dataType: 'json',
      beforeSend: function () {
        $('#error_container').hide();
        $('#error_container_message').empty();
      },
      success: function (result) {
        if (result['result'] === 'r1') {
          $('#modal_data').modal('hide');
          get_data(page);
        } else {
          $('#error_container').show();
          $('#error_container_message').append(result['result_message']);
        }
      }
    });
  };

  edit_data = function (id, purchase_code, customer_id, order_item_id, sku, qty, reason, status) {
    $.ajax({
      url: base_url + 'customer_return/edit_data',
      type: 'POST',
      data: {
        id: id,
        purchase_code: purchase_code,
        customer_id: customer_id,
        order_item_id: order_item_id,
        SKU: sku,
        qty: qty,
        reason: reason,
        status: status
      },
      dataType: 'json',
      beforeSend: function () {
        $('#error_container').hide();
        $('#error_container_message').empty();
      },
      success: function (result) {
        if (result['result'] === 'r1') {
          $('#modal_data').modal('hide');
          get_data(page);
        } else {
          $('#error_container').show();
          $('#error_container_message').append(result['result_message']);
        }
      }
    });
  };
  
  remove_data = function () {
    //param
    var id = $('#txt_remove_id').val();
    //end param
    
    $.ajax({
      url: base_url + 'customer_return/remove_data',
      type: 'POST',
      data: {
        id: id
      },
      dataType: 'json',
      success: function (result) {
        if (result['result'] === 'r1') {
          get_data(page);
        } else {
          alert(result['result_message']);
          get_data(page);
        }
        $('#modal_remove').modal("hide");
      }
    });
  };
  
  get_sku = function(purchase_code, sku){
    $.ajax({
      url: base_url + 'customer_return/get_SKU',
      type: 'POST',
      data: {
        purchase_code: purchase_code
      },
      dataType: 'json',
      success: function (result) {
        $('.hidden-div-2').hide();
        $('#sel_data_sku').empty();
        $('#sel_data_sku').append("\
          <option value='0'>Pick SKU</option>\
        ");
        if (result['result'] === 'r1') {
          $('.hidden-div-2').show();
          for (var x = 0; x < result['total']; x++) {
            $('#sel_data_sku').append("\
              <option value='" + result['SKU'][x] + "'><strong>" + result['SKU'][x] + "</strong> - " + result['products_name'][x] + "</option>\
            ");
          }
          if(sku != ""){
            $("#sel_data_sku").val(sku);
          }
          $("#txt_data_customer_id").val(result['customer_id']);
          $("#txt_data_customer_email").val(result['customer_email']);
        } else {
          alert(result['result_message']);
          $('.form_data').val('');
        }
        $('#modal_remove').modal("hide");
      }
    });
  };
  
  get_order_item = function(sku){
    $.ajax({
      url: base_url + 'customer_return/get_order_item',
      type: 'POST',
      data: {
        SKU: sku
      },
      dataType: 'json',
      success: function (result) {
        $('.hidden-div').show();
        if (result['result'] === 'r1') {
          $("#txt_data_order_item_id").val(result['id']);
          $("#txt_data_qty").val(result['quantity']);
        } else {
          alert(result['result_message']);
          $('.form_data').val('');
        }
        $('#modal_remove').modal("hide");
      }
    });
  };
});