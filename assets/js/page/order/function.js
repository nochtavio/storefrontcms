$(document).ready(function () {
  get_data = function (page) {
    //Filter
    var purchase_code = $('#txt_purchase_code').val();
    var customer_email = $('#txt_customer_email').val();
    var status = $('#sel_status').val();
    var order = $('#sel_order').val();
    //End Filter

    $.ajax({
      url: base_url + 'order/get_data',
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
            <th>Order Detail</th>\
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
            //Order Detail
            var order_detail = "\
              <strong>Order Item: </strong> <a href='#' id='btn_detail" + result['purchase_code'][x] + "'>Detail</a> <br/>\
              <strong>Payment: </strong> " + result['payment_name'][x] + "\
            ";
            
            if(result['confirm_transfer_by'][x] != ""){
              order_detail += "<br/> <br/> \
                <strong>Payment Information: </strong> <br/>\
                <strong>Transferred By: </strong> " + result['confirm_transfer_by'][x] + " <br/>\
                <strong>Bank: </strong> " + result['confirm_transfer_bank'][x] + " <br/>\
                <strong>Amount: </strong> <span style='color:orange;font-weight:bold;'>" + result['confirm_transfer_amount'][x] + "</span>\
                \
              ";
            }else{
              order_detail += "<br/> <br/> \
                <strong>Payment Information: </strong> <br/>\
                <em>(Not confirmed)</em>\
              ";
            }
            //End Order Detail
            
            //Status
            var status = "<strong style='color:red'>Unpaid</strong>";
            if(result['status'][x] == 1){
              status = "<strong style='color:green'>Paid</strong>";
            }
            //End Status
            
            //Date
            var date = "Purchased on <strong> <br/> " + result['purchase_date'][x] + "</strong>";
            if (result['updated_by'][x] != "") {
              date += "<br/> <br/> Modified by <strong>" + result['updated_by'][x] + "</strong>";
            }
            //End Date
            
            //Action
            var action = "";
            if(result['allowed_edit']){
              action += "<a href='#' id='btn_edit" + result['purchase_code'][x] + "' class='fa fa-pencil-square-o'></a> &nbsp;";
            }
            //End Action

            $('#table_content').append("\
              <tr>\
                <td>" + (parseInt(no) + parseInt(x)) + "</td>\
                <td>" + result['purchase_code'][x] + "</td>\
                <td>" + result['customer_email'][x] + "</td>\
                <td>" + order_detail + "</td>\
                <td>" + status + "</td>\
                <td>" + date + "</td>\
                <td>" + action + "</td>\
              </tr>");

            //Set Object ID
            $('#div_hidden').append("\
              <input type='hidden' id='object" + x + "' value='" + result['purchase_code'][x] + "' />\
            ");
            total_data++;
            //End Set Object ID
          }
          
          set_edit();
          set_detail();
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
    var purchase_code = [];
    for (var x = 0; x < total_data; x++) {
      purchase_code[x] = $('#object' + x).val();
    }

    $.each(purchase_code, function (x, val) {
      $(document).off('click', '#btn_edit' + val);
      $(document).on('click', '#btn_edit' + val, function (event) {
        event.preventDefault();
        set_state("edit");
        $.ajax({
          url: base_url + 'order/get_specific_data',
          type: 'POST',
          data:{
            purchase_code: val
          },
          dataType: 'json',
          success: function (result) {
            if (result['result'] === 'r1') {
              $("#txt_data_id").val(val);
              $("#txt_data_purchase_code").val(result['purchase_code']);
              $("#sel_data_status").val(result['status']);
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
  
  set_detail = function () {
    var purchase_code = [];
    for (var x = 0; x < total_data; x++) {
      purchase_code[x] = $('#object' + x).val();
    }

    $.each(purchase_code, function (x, val) {
      $(document).off('click', '#btn_detail' + val);
      $(document).on('click', '#btn_detail' + val, function (event) {
        event.preventDefault();
        $('#table_content_detail').empty();
        $('#table_content_detail').append("\
          <tr>\
            <th>Product Name</th>\
            <th>SKU</th>\
            <th>Each Price</th>\
            <th>Quantity</th>\
            <th>Total Price</th>\
          </tr>\
        ");
        $.ajax({
          url: base_url + 'order/get_order_item',
          type: 'POST',
          data:{
            purchase_code: val
          },
          dataType: 'json',
          success: function (result) {
            if (result['result'] === 'r1') {
              $("#txt_detail_purchase_code").html(val);
              for (var x = 0; x < result['total']; x++) {
                $('#table_content_detail').append("\
                <tr>\
                  <td>" + result['product_name'][x] + "</td>\
                  <td>" + result['SKU'][x] + "</td>\
                  <td>" + result['each_price'][x] + "</td>\
                  <td>" + result['quantity'][x] + "</td>\
                  <td>" + result['total_price'][x] + "</td>\
                </tr>");
              }
              $("#txt_detail_subtotal").html(result['subtotal']);
              $("#txt_detail_paycode").html(result['paycode']);
              $("#txt_detail_shipping_cost").html(result['shipping_cost']);
              $("#txt_detail_discount").html(result['discount']);
              $("#txt_detail_credit_use").html(result['credit_use']);
              $("#txt_detail_grand_total").html(result['grandtotal']);
              $('#modal_detail_order').modal('show');
            }
            else {
              alert("Error in connection");
              $('#modal_detail_order').modal('hide');
            }
          }
        });
      });
    });
  };

  set_state = function (x) {
    state = x;
    if (x == "add") {
      $('#modal_data_title').html("Add Order");
      
      $('.form_data').val('');

      $('#error_container').hide();
      $('#error_container_message').empty();
    } else {
      $('#modal_data_title').html("Edit Order");

      $('.form_data').val('');

      $('#error_container').hide();
      $('#error_container_message').empty();
    }
  };

  edit_data = function (purchase_code, status) {
    $.ajax({
      url: base_url + 'order/edit_data',
      type: 'POST',
      data: {
        purchase_code: purchase_code,
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
});