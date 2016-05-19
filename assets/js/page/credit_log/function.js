$(document).ready(function () {
  get_data = function (page) {
    //Filter
    var email = $('#txt_email').val();
    var type = $('#sel_type').val();
    var credit_log_type = $('#sel_credit_log_type').val();
    var status = $('#sel_status').val();
    var order = $('#sel_order').val();
    //End Filter

    $.ajax({
      url: base_url + 'credit_log/get_data',
      type: 'POST',
      data: {
        page: page,
        email: email,
        type: type,
        credit_log_type: credit_log_type,
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
            <th>Email</th>\
            <th>Credit Log Type</th>\
            <th>Amount</th>\
            <th>Type</th>\
            <th>Description</th>\
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
            //Type
            var type = "Add";
            if(result['type'][x] == 2){
              type = "Deduct";
            }
            //End Type
            
            //Status
            var status = "Request";
            if(result['status'][x] == 1){
              status = "Paid";
            }
            //End Status
            
            //Date
            var date = "Created <br/> on <strong>" + result['cretime'][x] + "</strong>";
            if (result['modby'][x] != null) {
              date += "<br/><br/> Modified by <strong>" + result['modby'][x] + "</strong> <br/> on <strong>" + result['modtime'][x] + "</strong>";
            }
            //End Date
            
            //Action
            var action = "";
            if(result['allowed_edit'] && result['type'][x] == 1){
              action += "<a href='#' id='btn_edit" + result['id'][x] + "' class='fa fa-pencil-square-o'></a> &nbsp;";
            }
            //End Action

            $('#table_content').append("\
              <tr>\
                <td>" + (parseInt(no) + parseInt(x)) + "</td>\
                <td>" + result['email'][x] + "</td>\
                <td>" + result['credit_log_type'][x] + "</td>\
                <td>" + result['amount'][x] + "</td>\
                <td>" + type + "</td>\
                <td>" + result['description'][x] + "</td>\
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
        } else {
          $('#table_content').append("\
          <tr>\
            <td colspan='9'><strong style='color:red;'>" + result['message'] + "</strong></td>\
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
          url: base_url + 'credit_log/get_specific_data',
          type: 'POST',
          data:{
            id: val
          },
          dataType: 'json',
          success: function (result) {
            if (result['result'] === 'r1') {
              $("#txt_data_id").val(val);
              $("#txt_data_id_customer").val(result['id_customer']);
              $("#txt_data_id_reseller").val(result['id_reseller']);
              $("#txt_data_email").val(result['email']);
              $("#txt_data_amount").val(result['amount']);
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

  set_state = function (x) {
    state = x;
    if (x == "add") {
      $('#modal_data_title').html("Add Credit Log");
      
      $('.form_data').val('');

      $('#error_container').hide();
      $('#error_container_message').empty();
    } else {
      $('#modal_data_title').html("Edit Credit Log");

      $('.form_data').val('');

      $('#error_container').hide();
      $('#error_container_message').empty();
    }
  };

  edit_data = function (id, id_customer, id_reseller, amount, status) {
    $.ajax({
      url: base_url + 'credit_log/edit_data',
      type: 'POST',
      data: {
        id: id,
        id_customer: id_customer,
        id_reseller: id_reseller,
        amount: amount,
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