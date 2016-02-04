$(document).ready(function () {
  //Function
  get_data = function (page) {
    //Filter
    var username = $('#txt_username').val();
    var active = $('#sel_active').val();
    var order = $('#sel_order').val();
    //End Filter

    $.ajax({
      url: base_url + 'admin/get_data',
      type: 'POST',
      data:{
        page: page,
        username: username,
        active:active,
        order:order
      },
      dataType: 'json',
      beforeSend: function () {
        $('#paging').empty();
        $('#div_hidden').empty();
        $('#table_content').empty();
        $('#table_content').append("\
          <tr>\
            <th>No</th>\
            <th>Username</th>\
            <th>Status</th>\
            <th>Detail</th>\
            <th>Action</th>\
          </tr>\
        ");
      },
      success: function (result) {
        if (result['result'] === 'r1') {
          //Set Paging
          var no = 1;
          var size = result['size'];
          if (page > 1) {
            no = parseInt(1) + (parseInt(size) * (parseInt(page) - parseInt(1)));
          }

          writePaging(result['totalpage'], page);
          last_page = result['totalpage'];
          clearPagingClass(".page", result['totalpage'], page);
          //End Set Paging

          for (var x = 0; x < result['total']; x++) {
            //Status
            var status = "<span class='label label-success'>Active</span>";
            if (result['active'][x] != 1) {
              status = "<span class='label label-danger'>Not Active</span>";
            }
            //End Status

            //Detail
            var detail = "Created by <strong>" + result['creby'][x] + "</strong> <br/> on <strong>" + result['cretime'][x] + "</strong>";
            if (result['modby'][x] != null) {
              detail += "<br/><br/> Modified by <strong>" + result['modby'][x] + "</strong> <br/> on <strong>" + result['modtime'][x] + "</strong>";
            }
            //End Detail

            $('#table_content').append("\
              <tr>\
                <td>" + (parseInt(no) + parseInt(x)) + "</td>\
                <td>" + result['username'][x] + "</td>\
                <td>" + status + "</td>\
                <td>" + detail + "</td>\
                <td>\
                  <a href='#' id='btn_edit" + result['id'][x] + "' class='fa fa-pencil-square-o'></a> &nbsp;\
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
        } else {
          $('#table_content').append("\
          <tr>\
            <td colspan='5'><strong style='color:red;'>" + result['message'] + "</strong></td>\
          </tr>");
        }
      }
    });
  };
  
  set_edit = function ()
  {
    var id = [];
    for (var x = 0; x < total_data; x++)
    {
      id[x] = $('#object' + x).val();
    }

    $.each(id, function (x, val) {
      $(document).off('click', '#btn_edit' + val);
      $(document).on('click', '#btn_edit' + val, function () {
        set_state("edit");
        $.ajax({
          url: base_url + 'admin/get_specific_data',
          type: 'POST',
          data:
            {
              id: val
            },
          dataType: 'json',
          success: function (result) {
            if (result['result'] === 'r1')
            {
              $("#txt_data_id").val(val);
              $("#txt_data_username").val(result['username']);
              if(result['active'] == "1"){
                $('#txt_data_active').prop('checked', true);
              }else{
                $('#txt_data_active').prop('checked', false);
              }
              $('#modal_data').modal('show');
            }
            else
            {
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
      $('#modal_data_title').html("Add Admin");
      $('#txt_data_username').prop("readonly", false);

      $('#txt_data_username').val('');
      $('#txt_data_password').val('');
      $('#txt_data_conf_password').val('');

      $('#error_container').hide();
      $('#error_container_message').empty();
    } else {
      $('#modal_data_title').html("Edit Admin");
      $('#txt_data_username').prop("readonly", true);
      
      $('#txt_data_password').val('');
      $('#txt_data_conf_password').val('');

      $('#error_container').hide();
      $('#error_container_message').empty();
    }
  };
  
  add_data = function(username, password, conf_password, active){
    $.ajax({
      url: base_url + 'admin/add_data',
      type: 'POST',
      data:{
        username: username,
        password: password,
        conf_password: conf_password,
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
  
  edit_data = function(id, password, conf_password, active){
    $.ajax({
      url: base_url + 'admin/edit_data',
      type: 'POST',
      data:{
        id: id,
        password: password,
        conf_password: conf_password,
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
  //End Function

  //Initial Setup
  page = 1;
  last_page = 0;
  total_data = 0;
  state = "";

  get_data(page);
  //End Initial Setup

  //User Action
  $('#btn_filter').click(function () {
    get_data(page);
  });

  $('#btn_add_data').click(function () {
    set_state("add");
  });

  $('#btn_submit_data').click(function (event) {
    event.preventDefault();

    //Parameter
    var id = $('#txt_data_id').val();
    var username = $('#txt_data_username').val();
    var password = $('#txt_data_password').val();
    var conf_password = $('#txt_data_conf_password').val();
    var active = 2;
    if ($('#txt_data_active').prop('checked')) {
      active = 1;
    }
    //End Parameter
    
    if(state == "add"){
      add_data(username, password, conf_password, active);
    }else{
      edit_data(id, password, conf_password, active);
    }
  });
  //End User Action
});