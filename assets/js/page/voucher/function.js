$(document).ready(function () {
  get_data = function (page) {
    //Filter
    var name = $('#txt_name').val();
    var code = $('#txt_code').val();
    var discount_type = $('#sel_discount_type').val();
    var transaction_type = $('#sel_transaction_type').val();
    var active = $('#sel_active').val();
    var order = $('#sel_order').val();
    //End Filter

    $.ajax({
      url: base_url + 'voucher/get_data',
      type: 'POST',
      data: {
        page: page,
        name: name,
        code: code,
        discount_type: discount_type,
        transaction_type: transaction_type,
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
            <th>Code</th>\
            <th>Discount Type</th>\
            <th>Transaction Type</th>\
            <th>Value</th>\
            <th>Usage</th>\
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
            //Discount Type
            var discount_type = "Flat Discount";
            if(result['discount_type'] == 2){
              discount_type = "Percentage Discount";
            }
            //End Discount Type
            
            //Transaction Type
            var transaction_type = "One Time Transaction";
            if(result['transaction_type'] == 2){
              transaction_type = "Multiple Transaction";
            }
            //End Transaction Type
            
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
                <td>" + result['name'][x] + "</td>\
                <td>" + result['code'][x] + "</td>\
                <td>" + discount_type + "</td>\
                <td>" + transaction_type + "</td>\
                <td>" + result['value'][x] + "</td>\
                <td>" + result['usage'][x] + "</td>\
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
            <td colspan='10'><strong style='color:red;'>" + result['message'] + "</strong></td>\
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
          url: base_url + 'voucher/set_active',
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
          url: base_url + 'voucher/get_specific_data',
          type: 'POST',
          data:{
            id: val
          },
          dataType: 'json',
          success: function (result) {
            if (result['result'] === 'r1') {
              $("#txt_data_id").val(val);
              $("#txt_data_name").val(result['name']);
              $("#txt_data_code").val(result['code']);
              $("#txt_data_description").val(result['description']);
              $("#sel_data_discount_type").val(result['discount_type']);
              $("#sel_data_transaction_type").val(result['transaction_type']);
              $("#txt_data_value").val(result['value']);
              $('#sel_data_category').multiselect('select', result['category']);
              $("#txt_data_min_price").val(result['min_price']);
              $("#txt_data_start_date").val(result['start_date']);
              $("#txt_data_end_date").val(result['end_date']);
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
        $('#remove_message').html("Are you sure you want to remove this voucher?");
        $('#txt_remove_id').val(val);
        $('#modal_remove').modal("show");
      });
    });
  };

  set_state = function (x) {
    state = x;
    if (x == "add") {
      $('#modal_data_title').html("Add Voucher");
      
      $('.form_data').val('');
      $('option', $('#sel_data_category')).each(function(element) {
          $(this).removeAttr('selected').prop('selected', false);
      });
      $('#sel_data_category').multiselect('refresh');

      $('#error_container').hide();
      $('#error_container_message').empty();
    } else {
      $('#modal_data_title').html("Edit Voucher");

      $('.form_data').val('');
      $('option', $('#sel_data_category')).each(function(element) {
          $(this).removeAttr('selected').prop('selected', false);
      });
      $('#sel_data_category').multiselect('refresh');

      $('#error_container').hide();
      $('#error_container_message').empty();
    }
  };

  add_data = function (name, code, description, discount_type, transaction_type, value, category, min_price, start_date, end_date, active) {
    $.ajax({
      url: base_url + 'voucher/add_data',
      type: 'POST',
      data: {
        name: name,
        code: code,
        description: description,
        discount_type: discount_type,
        transaction_type: transaction_type,
        value: value,
        category: category,
        min_price: min_price,
        start_date: start_date,
        end_date: end_date,
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

  edit_data = function (id, name, code, description, discount_type, transaction_type, value, category, min_price, start_date, end_date, active) {
    $.ajax({
      url: base_url + 'voucher/edit_data',
      type: 'POST',
      data: {
        id: id,
        name: name,
        code: code,
        description: description,
        discount_type: discount_type,
        transaction_type: transaction_type,
        value: value,
        category: category,
        min_price: min_price,
        start_date: start_date,
        end_date: end_date,
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
      url: base_url + 'voucher/remove_data',
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