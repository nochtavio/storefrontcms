$(document).ready(function () {
  get_data = function (page) {
    //Filter
    var customer_email = $('#txt_customer_email').val();
    var name = $('#txt_name').val();
    var customer_province = $('#txt_customer_province').val();
    var customer_city = $('#txt_customer_city').val();
    var customer_status = $('#sel_customer_status').val();
    var order = $('#sel_order').val();
    //End Filter

    $.ajax({
      url: base_url + 'customer/get_data',
      type: 'POST',
      data: {
        page: page,
        customer_email: customer_email,
        name: name,
        customer_province: customer_province,
        customer_city: customer_city,
        customer_status: customer_status,
        order: order
      },
      dataType: 'json',
      success: function (result) {
        $('#div_hidden').empty();
        $('#table_content').empty();
        $('#table_content').append("\
          <tr>\
            <th>No</th>\
            <th>Email</th>\
            <th>Name</th>\
            <th>Province</th>\
            <th>City</th>\
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
              status = "<a href='#' id='btn_active" + result['customer_id'][x] + "' class='label label-success'>Enabled</a>";
              if (result['customer_status'][x] != 1) {
                status = "<a href='#' id='btn_active" + result['customer_id'][x] + "' class='label label-danger'>Blocked</a>";
              }
            }else{
              status = "<span class='label label-success'>Enabled</span>";
              if (result['customer_status'][x] != 1) {
                status = "<span class='label label-danger'>Blocked</span>";
              }
            }
            //End Status

            //Date
            var date = "Registered on <br/> <strong>" + result['customer_registration_date'][x] + "</strong>";
            if (result['last_modified'][x] != null) {
              date += "<br/><br/> Modified on <br/> <strong>" + result['last_modified'][x] + "</strong>";
            }
            //End Date
            
            //Action
            var action = "";
            if(result['allowed_edit']){
              action += "<a href='#' id='btn_edit" + result['customer_id'][x] + "' class='fa fa-pencil-square-o'></a> &nbsp;";
            }
            //End Action

            $('#table_content').append("\
              <tr>\
                <td>" + (parseInt(no) + parseInt(x)) + "</td>\
                <td>" + result['customer_email'][x] + "</td>\
                <td>" + result['name'][x] + "</td>\
                <td>" + result['customer_province'][x] + "</td>\
                <td>" + result['customer_city'][x] + "</td>\
                <td>" + status + "</td>\
                <td>" + date + "</td>\
                <td>" + action + "</td>\
              </tr>");

            //Set Object ID
            $('#div_hidden').append("\
              <input type='hidden' id='object" + x + "' value='" + result['customer_id'][x] + "' />\
            ");
            total_data++;
            //End Set Object ID
          }
          
          set_active();
          set_edit();
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
          url: base_url + 'customer/set_active',
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
          url: base_url + 'customer/get_specific_data',
          type: 'POST',
          data:{
            id: val
          },
          dataType: 'json',
          success: function (result) {
            if (result['result'] === 'r1') {
              $("#txt_data_id").val(val);
              $("#txt_data_customer_email").val(result['customer_email']);
              $("#txt_data_customer_fname").val(result['customer_fname']);
              $("#txt_data_customer_lname").val(result['customer_lname']);
              $("#txt_data_customer_street").val(result['customer_street']);
              $("#txt_data_customer_province").val(result['customer_province']);
              $("#txt_data_customer_city").val(result['customer_city']);
              $("#txt_data_customer_zipcode").val(result['customer_zipcode']);
              $("#txt_data_customer_phone").val(result['customer_phone']);
              $("#sel_data_customer_status").val(result['customer_status']);
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

  set_state = function (x) {
    state = x;
    if (x == "add") {

    } else {
      $('#modal_data_title').html("Detail Customer");

      $('.form_data').val('');

      $('#error_container').hide();
      $('#error_container_message').empty();
    }
  };

  edit_data = function (customer_id, customer_status) {
    $.ajax({
      url: base_url + 'customer/edit_data',
      type: 'POST',
      data: {
        customer_id: customer_id,
        customer_status: customer_status
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