$(document).ready(function () {
  id_products = $('#txt_id_products').val();
  id_color = $('#txt_id_color').val();
  
  get_data = function (page) {
    //Filter
    var sku = $('#txt_sku').val();
    var active = $('#sel_active').val();
    var order = $('#sel_order').val();
    //End Filter

    $.ajax({
      url: base_url + 'products_variant_detail/get_data',
      type: 'POST',
      data: {
        page: page,
        id_products: id_products,
        id_color:id_color,
        sku:sku,
        active: active,
        order: order
      },
      dataType: 'json',
      success: function (result) {
        $('#div_hidden').empty();
        $('#table_content').empty();
        $('#table_content').append("\
          <tr>\
            <th>No</th>\
            <th>SKU</th>\
            <th>Color</th>\
            <th>Size</th>\
            <th>Quantity</th>\
            <th>Show Order</th>\
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
            //Status
            var status = "";
            if(result['allowed_edit']){
              status = "<a href='#' id='btn_active" + result['id'][x] + "' class='label label-success'>Active</a>";
              if (result['active'][x] != 1) {
                status = "<a href='#' id='btn_active" + result['id'][x] + "' class='label label-danger'>Not Active</a>";
              }
            }else{
              status = "<span class='label label-success'>Active</span>";
              if (result['active'][x] != 1) {
                status = "<span class='label label-danger'>Not Active</span>";
              }
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
                <td>" + result['sku'][x] + "</td>\
                <td>" + result['color_name'][x] + "</td>\
                <td>" + result['variant_size'][x] + "</td>\
                <td>" + result['quantity'][x] + "</td>\
                <td>" + result['show_order'][x] + "</td>\
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
          
          set_active();
          set_edit();
          set_remove();
        } else {
          $('#table_content').append("\
          <tr>\
            <td colspan='8'><strong style='color:red;'>" + result['message'] + "</strong></td>\
          </tr>");
        }
      }
    });
  };
  
  set_active = function () {
    var id = [];
    for (var x = 0; x < total_data; x++) {
      id[x] = $('#object' + x).val();
    }

    $.each(id, function (x, val) {
      $(document).off('click', '#btn_active' + val);
      $(document).on('click', '#btn_active' + val, function (event) {
        event.preventDefault();
        $.ajax({
          url: base_url + 'products_variant_detail/set_active',
          type: 'POST',
          data:{
            id: val
          },
          dataType: 'json',
          success: function (result) {
            if (result['result'] === 'r1') {
              get_data(page);
            }
            else {
              alert("Error in connection");
              get_data(page);
            }
          }
        });
      });
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
          url: base_url + 'products_variant_detail/get_specific_data',
          type: 'POST',
          data:{
            id: val
          },
          dataType: 'json',
          success: function (result) {
            if (result['result'] === 'r1') {
              $("#txt_data_id").val(val);
              $("#txt_data_sku").val(result['sku']);
              $("#txt_data_size").val(result['size']);
              $("#txt_data_quantity").val(result['quantity']);
              $("#txt_data_max_quantity_order").val(result['max_quantity_order']);
              $("#txt_data_show_order").val(result['show_order']);
              if (result['active'] == "1") {
                $('#txt_data_active').prop('checked', true);
              } else {
                $('#txt_data_active').prop('checked', false);
              }
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
        $('#remove_message').html("Are you sure you want to remove this variant?");
        $('#txt_remove_id').val(val);
        $('#modal_remove').modal("show");
      });
    });
  };

  set_state = function (x) {
    state = x;
    if (x == "add") {
      $('#modal_data_title').html("Add Products Variant");
      
      $('.form_data').val('');
      $('#txt_data_id_color').val(id_color);
      $('#txt_data_id_color').prop("disabled", true);
      $('#txt_data_sku').prop("readonly", false);
      $('#txt_data_quantity').prop("readonly", false);

      $('#error_container').hide();
      $('#error_container_message').empty();
    } else {
      $('#modal_data_title').html("Edit Products Variant");

      $('.form_data').val('');
      $('#txt_data_id_color').val(id_color);
      $('#txt_data_id_color').prop("disabled", true);
      $('#txt_data_sku').prop("readonly", true);
      $('#txt_data_quantity').prop("readonly", true);

      $('#error_container').hide();
      $('#error_container_message').empty();
    }
  };

  add_data = function (sku, size, quantity, max_quantity_order, show_order, active) {
    $.ajax({
      url: base_url + 'products_variant_detail/add_data',
      type: 'POST',
      data: {
        id_products: id_products,
        id_color: id_color,				
        sku : sku,
        size: size,
        quantity: quantity,
        max_quantity_order: max_quantity_order,
        show_order: show_order,
        active: active
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

  edit_data = function (id, size, quantity, max_quantity_order, show_order, active) {
    $.ajax({
      url: base_url + 'products_variant_detail/edit_data',
      type: 'POST',
      data: {
        id: id,
        id_products: id_products,
        id_color: id_color,
        size: size,
        quantity: quantity,
        max_quantity_order: max_quantity_order,
        show_order: show_order,
        active: active
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
      url: base_url + 'products_variant_detail/remove_data',
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
});