$(document).ready(function () {
  id_products = $('#txt_id_products').val();
  
  get_data = function (page) {
    //Filter
    var order = $('#sel_order').val();
    //End Filter

    $.ajax({
      url: base_url + 'products_variant/get_data',
      type: 'POST',
      data: {
        page: page,
        id_products: id_products,
        order: order
      },
      dataType: 'json',
      success: function (result) {
        $('#div_hidden').empty();
        $('#table_content').empty();
        $('#table_content').append("\
          <tr>\
            <th>No</th>\
            <th>Color</th>\
            <th>Total Size</th>\
            <th>Total Quantity</th>\
            <th>Total Images</th>\
            <th>Status</th>\
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
              status = "<a href='#' id='btn_set_active" + result['id_color'][x] + "' class='label label-success'>Set Active</a> &nbsp; <a href='#' id='btn_set_non_active" + result['id_color'][x] + "' class='label label-danger'>Set Not Active</a>";
            }else{
              status = "<span class='label label-success'>Set Active</span> &nbsp; <span class='label label-danger'>Set Not Active</span>";
            }
            //End Status
            
            $('#table_content').append("\
              <tr>\
                <td>" + (parseInt(no) + parseInt(x)) + "</td>\
                <td>" + result['color_name'][x] + "</td>\
                <td>" + result['total_size'][x] + "</td>\
                <td>" + result['total_quantity'][x] + "</td>\
                <td>" + result['total_images'][x] + "</td>\
                <td>" + status + "</td>\
                <td>\
                  <a href='"+base_url+"products_variant_detail/?id_products=" + id_products + "&id_color=" + result['id_color'][x] + "' class='fa fa-folder-open'></a> &nbsp;\
                  <a href='"+base_url+"products_image/?id_products=" + id_products + "&id_color=" + result['id_color'][x] + "' class='fa fa-picture-o'></a> &nbsp;\
                </td>\
              </tr>");

            //Set Object ID
            $('#div_hidden').append("\
              <input type='hidden' id='object" + x + "' value='" + result['id_color'][x] + "' />\
            ");
            total_data++;
            //End Set Object ID
          }
          
          set_active();
          set_non_active();
        } else {
          $('#table_content').append("\
          <tr>\
            <td colspan='7'><strong style='color:red;'>" + result['message'] + "</strong></td>\
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
      $(document).off('click', '#btn_set_active' + val);
      $(document).on('click', '#btn_set_active' + val, function (event) {
        event.preventDefault();
        $.ajax({
          url: base_url + 'products_variant/set_active',
          type: 'POST',
          data:{
            id: val,
            id_products: id_products,
            active: 1
          },
          dataType: 'json',
          success: function (result) {
            alert(result['result_message']);
          }
        });
      });
    });
  };
  
  set_non_active = function () {
    var id = [];
    for (var x = 0; x < total_data; x++) {
      id[x] = $('#object' + x).val();
    }

    $.each(id, function (x, val) {
      $(document).off('click', '#btn_set_non_active' + val);
      $(document).on('click', '#btn_set_non_active' + val, function (event) {
        event.preventDefault();
        $.ajax({
          url: base_url + 'products_variant/set_active',
          type: 'POST',
          data:{
            id: val,
            id_products: id_products,
            active: 0
          },
          dataType: 'json',
          success: function (result) {
            alert(result['result_message']);
          }
        });
      });
    });
  };
  
  set_state = function (x) {
    state = x;
    if (x == "add") {
      $('#modal_data_title').html("Add Products Variant");
      
      $('.form_data').val('');
      $('#txt_data_id_color').val(0);
      $('#txt_data_id_color').prop("disabled", false);

      $('#error_container').hide();
      $('#error_container_message').empty();
    }
  };

  add_data = function (id_color, size, quantity, show_order, active) {
    $.ajax({
      url: base_url + 'products_variant/add_data',
      type: 'POST',
      data: {
        id_products: id_products,
        id_color: id_color,
        size: size,
        quantity: quantity,
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
});