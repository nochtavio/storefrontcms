$(document).ready(function () {
  get_data = function (page) {
    //Filter
    var name = $('#txt_name').val();
    var type = $('#sel_type').val();
    var active = $('#sel_active').val();
    var order = $('#sel_order').val();
    //End Filter

    $.ajax({
      url: base_url + 'payment/get_data',
      type: 'POST',
      data: {
        page: page,
        name: name,
        type: type,
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
            <th>Logo</th>\
            <th>Type</th>\
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
            
            //Type
            var type = "Transfer";
            if(result['type'][x] == 2){
              type = "Non-Transfer";
            }
            //End Type

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
            
            var d = new Date();
            var time = d.getTime(); 

            $('#table_content').append("\
              <tr>\
                <td>" + (parseInt(no) + parseInt(x)) + "</td>\
                <td>" + result['name'][x] + "</td>\
                <td><img src='" + base_url + result['logo'][x] + "?"+time+"' width='100px' height='100px' /> <br/> <strong>URL : </strong> " + result['logo'][x] + "</td>\
                <td>" + type + "</td>\
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
          url: base_url + 'payment/set_active',
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
          url: base_url + 'payment/get_specific_data',
          type: 'POST',
          data:{
            id: val
          },
          dataType: 'json',
          success: function (result) {
            if (result['result'] === 'r1') {
              var d = new Date();
              var time = d.getTime(); 
              
              $("#txt_data_id").val(val);
              $("#txt_data_name").val(result['name']);
              $("#txt_data_description").summernote('code', result['description']);
              $("#txt_data_img").attr("src", base_url + result['logo'] + "?" + time);
              $('#sel_data_type').val(result['type']);
              $('#txt_data_minimum_grand_total').val(result['minimum_grand_total']);
              $('#txt_data_show_order').val(result['show_order']);
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
        $('#remove_message').html("Are you sure you want to remove this payment?");
        $('#txt_remove_id').val(val);
        $('#modal_remove').modal("show");
      });
    });
  };

  set_state = function (x) {
    state = x;
    if (x == "add") {
      $('#modal_data_title').html("Add Payment");
      
      $('.form_data').val('');
      $("#txt_data_description").summernote('code', '');
      $("#txt_data_img").hide();
      $('#txt_data_add_file').show();
      $('#txt_data_edit_file').hide();
      $('#sel_data_type').val(1);

      $('#error_container').hide();
      $('#error_container_message').empty();
    } else {
      $('#modal_data_title').html("Edit Payment");

      $('.form_data').val('');
      $("#txt_data_img").show();
      $('#txt_data_add_file').hide();
      $('#txt_data_edit_file').show();

      $('#error_container').hide();
      $('#error_container_message').empty();
    }
  };

  add_data = function (name, description, type, minimum_grand_total, show_order, active) {
    $.ajaxFileUpload({
      url: base_url + 'payment/add_data',
      secureuri: false,
      fileElementId: 'txt_data_add_file',
      dataType: 'json',
      data:{
        name: name,
        description: description,
        type: type,
        minimum_grand_total: minimum_grand_total,
        show_order: show_order,
        active: active
      },
      success: function (result){
        $('#error_container').hide();
        $('#error_container_message').empty();
        if (result['result'] === 'r1') {
          $('#modal_data').modal('hide');
          get_data(page);
        } else {
          $('#error_container').show();
          $('#error_container_message').append(result['result_message']);
          get_data(page);
        }
      }
    });
  };

  edit_data = function (id, name, description, type, minimum_grand_total, show_order, active) {
    $.ajaxFileUpload({
      url: base_url + 'payment/edit_data',
      secureuri: false,
      fileElementId: 'txt_data_edit_file',
      dataType: 'json',
      data:{
        id: id,
        name: name,
        description: description,
        type: type,
        minimum_grand_total: minimum_grand_total,
        show_order: show_order,
        active: active
      },
      success: function (result){
        $('#error_container').hide();
        $('#error_container_message').empty();
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
      url: base_url + 'payment/remove_data',
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