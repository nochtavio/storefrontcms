$(document).ready(function () {
  get_data = function (page) {
    //Filter
    var name = $('#txt_name').val();
    var active = $('#sel_active').val();
    var order = $('#sel_order').val();
    //End Filter

    $.ajax({
      url: base_url + 'products/get_data',
      type: 'POST',
      data: {
        page: page,
        name: name,
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
            <th>Name</th>\
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
            var status = "<span class='label label-success'>Active</span>";
            if (result['active'][x] != 1) {
              status = "<span class='label label-danger'>Not Active</span>";
            }
            //End Status

            //Date
            var date = "Created by <strong>" + result['creby'][x] + "</strong> <br/> on <strong>" + result['cretime'][x] + "</strong>";
            if (result['modby'][x] != null) {
              date += "<br/><br/> Modified by <strong>" + result['modby'][x] + "</strong> <br/> on <strong>" + result['modtime'][x] + "</strong>";
            }
            //End Date

            $('#table_content').append("\
              <tr>\
                <td>" + (parseInt(no) + parseInt(x)) + "</td>\
                <td>" + result['name'][x] + "</td>\
                <td>" + status + "</td>\
                <td>" + date + "</td>\
                <td>\
                  <a href='#' id='btn_edit" + result['id'][x] + "' class='fa fa-pencil-square-o'></a> &nbsp;\
                  <a href='#' id='btn_remove" + result['id'][x] + "' class='fa fa-times'></a> &nbsp;\
                </td>\
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
            <td colspan='5'><strong style='color:red;'>" + result['message'] + "</strong></td>\
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
      $(document).on('click', '#btn_edit' + val, function () {
        set_state("edit");
        $.ajax({
          url: base_url + 'products/get_specific_data',
          type: 'POST',
          data:{
            id: val
          },
          dataType: 'json',
          success: function (result) {
            if (result['result'] === 'r1') {
              $("#txt_data_id").val(val);
              $("#txt_data_name").val(result['name']);
              $("#txt_data_price").val(result['price']);
              $("#txt_data_sale_price").val(result['sale_price']);
              $("#txt_data_reseller_price").val(result['reseller_price']);
              $("#txt_data_weight").val(result['weight']);
              $("#txt_data_description").code(result['description']);
              $("#txt_data_short_description").code(result['short_description']);
              $("#txt_data_info").val(result['info']);
              $("#txt_data_size_guideline").val(result['size_guideline']);
              $('input:checkbox[name=cb_category]').each(function(){
                var temp_element = $(this);
                var temp_value = this.value;
                $.each(result['category'], function( i, val) {
                  if(temp_value === val){
                    temp_element.prop('checked', true);
                  }
                });
              });
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
      $(document).on('click', '#btn_remove' + val, function () {
        $('#remove_message').html("Are you sure you want to remove this products?");
        $('#txt_remove_id').val(val);
        $('#modal_remove').modal("show");
      });
    });
  };

  set_state = function (x) {
    state = x;
    if (x == "add") {
      $('#modal_data_title').html("Add Product");
      
      $('.form_data').val('');
      $('#txt_data_description').code('');
      $('#txt_data_short_description').code('');
      $('input:checkbox[name=cb_category]').prop('checked', false);

      $('#error_container').hide();
      $('#error_container_message').empty();
    } else {
      $('#modal_data_title').html("Edit Product");

      $('.form_data').val('');
      $('input:checkbox[name=cb_category]').prop('checked', false);

      $('#error_container').hide();
      $('#error_container_message').empty();
    }
  };

  add_data = function (name, price, sale_price, reseller_price, weight, attribute, description, short_description, info, size_guideline, category, active) {
    $.ajax({
      url: base_url + 'products/add_data',
      type: 'POST',
      data: {
        name: name,
        price: price,
        sale_price: sale_price,
        reseller_price: reseller_price,
        weight: weight,
        attribute: attribute,
        description: description,
        short_description: short_description,
        info: info,
        size_guideline: size_guideline,
        category: category,
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

  edit_data = function (id, name, price, sale_price, reseller_price, weight, attribute, description, short_description, info, size_guideline, category, active) {
    $.ajax({
      url: base_url + 'products/edit_data',
      type: 'POST',
      data: {
        id: id,
        name: name,
        price: price,
        sale_price: sale_price,
        reseller_price: reseller_price,
        weight: weight,
        attribute: attribute,
        description: description,
        short_description: short_description,
        info: info,
        size_guideline: size_guideline,
        category: category,
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
      url: base_url + 'products/remove_data',
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