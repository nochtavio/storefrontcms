$(document).ready(function () {
  get_data = function (page) {
    //Filter
    var name = $('#txt_name').val();
    var email = $('#txt_email').val();
    var phone = $('#txt_phone').val();
    var status = $('#sel_status').val();
    var order = $('#sel_order').val();
    //End Filter

    $.ajax({
      url: base_url + 'reseller/get_data',
      type: 'POST',
      data: {
        page: page,
        name: name,
        email: email,
        phone: phone,
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
            <th>Name</th>\
            <th>Email</th>\
            <th>Phone</th>\
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
              if (result['status'][x] != 1) {
                status = "<a href='#' id='btn_active" + result['id'][x] + "' class='label label-danger'>Banned</a>";
              }
            }else{
              status = "<span class='label label-success'>Active</span>";
              if (result['status'][x] != 1) {
                status = "<span class='label label-danger'>Banned</span>";
              }
            }
            //End Status
            
            //Date
            var date = "Created on <br/> <strong>" + result['cretime'][x] + "</strong>";
            //End Date
            
            //Action
            var action = "";
            if(result['allowed_edit']){
              action += "<a href='#' id='btn_edit" + result['id'][x] + "' class='fa fa-pencil-square-o'></a> &nbsp;";
            }
            //End Action

            $('#table_content').append("\
              <tr>\
                <td>" + (parseInt(no) + parseInt(x)) + "</td>\
                <td>" + result['name'][x] + "</td>\
                <td>" + result['email'][x] + "</td>\
                <td>" + result['phone'][x] + "</td>\
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
          set_status();
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
          url: base_url + 'reseller/get_specific_data',
          type: 'POST',
          data:{
            id: val
          },
          dataType: 'json',
          success: function (result) {
            if (result['result'] === 'r1') {
              $("#txt_data_id").val(val);
              $("#txt_data_name").val(result['name']);
              $("#txt_data_email").val(result['email']);
              $("#txt_data_phone").val(result['phone']);
              $("#txt_data_street").val(result['street']);
              $("#txt_data_province").val(result['province']);
              $("#txt_data_city").val(result['city']);
              $("#txt_data_zipcode").val(result['zipcode']);
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
  
  set_status = function () {
    var id = [];
    for (var x = 0; x < total_data; x++) {
      id[x] = $('#object' + x).val();
    }

    $.each(id, function (x, val) {
      $(document).off('click', '#btn_active' + val);
      $(document).on('click', '#btn_active' + val, function (event) {
        event.preventDefault();
        $('#remove_message').html("Are you sure you want to change this reseller status?");
        $('#txt_status_id').val(val);
        $('#modal_status').modal("show");
      });
    });
  };

  set_state = function (x) {
    state = x;
    if (x == "add") {
      $('#modal_data_title').html("Add Reseller");
      
      $('.form_data').val('');

      $('#error_container').hide();
      $('#error_container_message').empty();
    } else {
      $('#modal_data_title').html("Reseller");

      $('.form_data').val('');

      $('#error_container').hide();
      $('#error_container_message').empty();
    }
  };
  
  change_status = function () {
    //param
    var id = $('#txt_approval_id').val();
    //end param
    
    $.ajax({
      url: base_url + 'reseller/set_status',
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
        $('#modal_status').modal("hide");
      }
    });
  };
});