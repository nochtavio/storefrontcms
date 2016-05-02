$(document).ready(function () {
  get_data = function (page) {
    //Filter
    var name = $('#txt_name').val();
    var brand_name = $('#txt_brand_name').val();
    var active = $('#sel_active').val();
    var order = $('#sel_order').val();
    //End Filter

    $.ajax({
      url: base_url + 'products/get_data',
      type: 'POST',
      data: {
        page: page,
        name: name,
        brand_name: brand_name,
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
            <th>Brand</th>\
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
            action += "<a href='"+base_url+"products_variant/?id_products=" + result['id'][x] + "' id='btn_detail" + result['id'][x] + "' class='fa fa-folder-open'></a> &nbsp;";
            if(result['allowed_delete']){
              action += "<a href='#' id='btn_remove" + result['id'][x] + "' class='fa fa-times'></a> &nbsp;";
            }
            //End Action

            $('#table_content').append("\
              <tr>\
                <td>" + (parseInt(no) + parseInt(x)) + "</td>\
                <td>" + result['name'][x] + "</td>\
                <td>" + result['brand_name'][x] + "</td>\
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
            <td colspan='6'><strong style='color:red;'>" + result['message'] + "</strong></td>\
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
          url: base_url + 'products/set_active',
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
              $("#sel_data_brand").val(result['id_brand']);
              $("#txt_data_price").val(result['price']);
              $("#txt_data_sale_price").val(result['sale_price']);
              $("#txt_data_reseller_price").val(result['reseller_price']);
              $("#txt_data_weight").val(result['weight']);
              $("#txt_data_description").summernote('code', result['description']);
              $("#txt_data_short_description").summernote('code', result['short_description']);
              $("#txt_data_info").val(result['info']);
              $("#txt_data_size_guideline").val(result['size_guideline']);
              //Category
              category = result['category'];
              category_child = result['category_child'];
              category_child_ = result['category_child_'];
              $('#sel_data_category').multiselect('select', result['category']);
              generate_category_child();
              generate_category_child_();
              //End Category
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
      $("#sel_data_brand").val('0');
      $("#txt_data_description").summernote('code', '');
      $("#txt_data_short_description").summernote('code', '');
      $('option', $('#sel_data_category')).each(function(element) {
          $(this).removeAttr('selected').prop('selected', false);
      });
      $('#sel_data_category').multiselect('refresh');
      $('#category_child_container').hide();
      $('#sel_data_category_child').empty();
      $('#sel_data_category_child').multiselect('destroy');
      $('#category_child__container').hide();
      $('#sel_data_category_child_').empty();
      $('#sel_data_category_child_').multiselect('destroy');

      $('#error_container').hide();
      $('#error_container_message').empty();
    } else {
      $('#modal_data_title').html("Edit Product");

      $('.form_data').val('');
      $('option', $('#sel_data_category')).each(function(element) {
          $(this).removeAttr('selected').prop('selected', false);
      });
      $('#sel_data_category').multiselect('refresh');

      $('#error_container').hide();
      $('#error_container_message').empty();
    }
  };

  add_data = function (id_brand, name, price, sale_price, reseller_price, weight, attribute, description, short_description, info, size_guideline, category, category_child, category_child_, active) {
    $.ajax({
      url: base_url + 'products/add_data',
      type: 'POST',
      data: {
        id_brand: id_brand,
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
        category_child: category_child,
        category_child_: category_child_,
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

  edit_data = function (id, id_brand, name, price, sale_price, reseller_price, weight, attribute, description, short_description, info, size_guideline, category, category_child, category_child_, active) {
    $.ajax({
      url: base_url + 'products/edit_data',
      type: 'POST',
      data: {
        id: id,
        id_brand: id_brand,
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
        category_child: category_child,
        category_child_: category_child_,
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
  
  unique_array = function(list){
    var result = [];
    $.each(list, function(i, e) {
      if ($.inArray(e, result) == -1) result.push(e);
    });
    return result;
  };
  
  remove_array = function(list, val){
    var result = jQuery.grep(list, function(value) {
      return value != val;
    });
    
    return result;
  };
  
  generate_category_child = function(){
    $.ajax({
      url: base_url + 'products/get_category_child',
      type: 'POST',
      data: {
        id_category: category
      },
      dataType: 'json',
      success: function (result) {
        $('#sel_data_category_child').empty();
        $('#category_child_container').hide();
        if(result['result'] === 'r1'){
          $('#category_child_container').show();
          for (var x = 0; x < result['total']; x++) {
            $('#sel_data_category_child').append("\
              <option value=" + result['id'][x] + ">" + result['name'][x] + "</option>\
            ");
          }
          $('#sel_data_category_child').multiselect('destroy');
          $('#sel_data_category_child').multiselect({
            enableFiltering: true,
            buttonClass: 'btn btn-default',
            maxHeight: 400,
            onChange: function(option, checked, select) {
              if($(option).is(':selected')){
                category_child.push($(option).val());
                category_child = unique_array(category_child);
              }else{
                category_child = remove_array(category_child, $(option).val());
              }
              generate_category_child_();
            }
          });
          $('#sel_data_category_child').multiselect('select', category_child);
        }else{
          $('#sel_data_category_child').empty();
          $('#sel_data_category_child').multiselect('destroy');
        }
      }
    });
  };
  
  generate_category_child_ = function(){
    $.ajax({
      url: base_url + 'products/get_category_child_',
      type: 'POST',
      data: {
        parent: category_child
      },
      dataType: 'json',
      success: function (result) {
        $('#sel_data_category_child_').empty();
        $('#category_child__container').hide();
        if(result['result'] === 'r1'){
          $('#category_child__container').show();
          for (var x = 0; x < result['total']; x++) {
            $('#sel_data_category_child_').append("\
              <option value=" + result['id'][x] + ">" + result['name'][x] + "</option>\
            ");
          }
          $('#sel_data_category_child_').multiselect('destroy');
          $('#sel_data_category_child_').multiselect({
            enableFiltering: true,
            buttonClass: 'btn btn-default',
            maxHeight: 400
          });
          $('#sel_data_category_child_').multiselect('select', category_child_);
        }else{
          $('#sel_data_category_child_').empty();
          $('#sel_data_category_child_').multiselect('destroy');
        }
      }
    });
  };
});