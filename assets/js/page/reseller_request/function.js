$(document).ready(function () {
  get_data = function (page) {
    //Filter
    var name = $('#txt_name').val();
    var store_name = $('#txt_store_name').val();
    var email = $('#txt_email').val();
    var phone = $('#txt_phone').val();
    var status = $('#sel_status').val();
    var order = $('#sel_order').val();
    //End Filter

    $.ajax({
      url: base_url + 'reseller_request/get_data',
      type: 'POST',
      data: {
        page: page,
        name: name,
        store_name: store_name,
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
            <th>Store Name</th>\
            <th>Email</th>\
            <th>Phone</th>\
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
            //Date
            var date = "Created on <br/> <strong>" + result['cretime'][x] + "</strong>";
            //End Date
            
            //Action
            var action = "";
            if(result['allowed_edit']){
              action += "<a href='#' id='btn_edit" + result['id'][x] + "' class='fa fa-pencil-square-o'></a> &nbsp;";
              if(result['status'] == 0){
                action += "<a href='#' id='btn_approval" + result['id'][x] + "' class='fa fa-check'></a> &nbsp;";
              }
            }
            //End Action

            $('#table_content').append("\
              <tr>\
                <td>" + (parseInt(no) + parseInt(x)) + "</td>\
                <td>" + result['name'][x] + "</td>\
                <td>" + result['store_name'][x] + "</td>\
                <td>" + result['email'][x] + "</td>\
                <td>" + result['phone'][x] + "</td>\
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
          set_approval();
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
          url: base_url + 'reseller_request/get_specific_data',
          type: 'POST',
          data:{
            id: val
          },
          dataType: 'json',
          success: function (result) {
            if (result['result'] === 'r1') {
              $("#txt_data_id").val(val);
              $("#txt_data_name").val(result['name']);
              $("#txt_data_store_name").val(result['store_name']);
              $("#txt_data_email").val(result['email']);
              $("#txt_data_phone").val(result['phone']);
              $("#txt_data_barang").val(result['barang']);
              $("#txt_data_promosi").val(result['promosi']);
              $("#txt_data_domain").val(result['domain']);
              $("#txt_data_keterangan").val(result['keterangan']);
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
  
  set_approval = function () {
    var id = [];
    for (var x = 0; x < total_data; x++) {
      id[x] = $('#object' + x).val();
    }

    $.each(id, function (x, val) {
      $(document).off('click', '#btn_approval' + val);
      $(document).on('click', '#btn_approval' + val, function (event) {
        event.preventDefault();
        $('#remove_message').html("Are you sure you want to approve this reseller?");
        $('#txt_approval_id').val(val);
        $('#modal_approval').modal("show");
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
  
  approval = function () {
    //param
    var id = $('#txt_approval_id').val();
    //end param
    
    $.ajax({
      url: base_url + 'reseller_request/approval',
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
        $('#modal_approval').modal("hide");
      }
    });
  };
});