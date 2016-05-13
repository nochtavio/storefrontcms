$(document).ready(function () {
  get_data = function (page) {
    //Filter
    var products_name = $('#txt_products_name').val();
    var SKU = $('#txt_SKU').val();
    var reseller_email = $('#txt_reseller_email').val();
    var reseller_name = $('#txt_reseller_name').val();
    var order = $('#sel_order').val();
    //End Filter

    $.ajax({
      url: base_url + 'order_item/get_data',
      type: 'POST',
      data: {
        page: page,
        products_name: products_name,
        SKU: SKU,
        reseller_email: reseller_email,
        reseller_name: reseller_name,
        order: order
      },
      dataType: 'json',
      success: function (result) {
        $('#div_hidden').empty();
        $('#table_content').empty();
        $('#table_content').append("\
          <tr>\
            <th>No</th>\
            <th>Products Name</th>\
            <th>SKU</th>\
            <th>Color Name</th>\
            <th>Reseller Name</th>\
            <th>Reseller Email</th>\
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

            $('#table_content').append("\
              <tr>\
                <td>" + (parseInt(no) + parseInt(x)) + "</td>\
                <td>" + result['products_name'][x] + "</td>\
                <td>" + result['SKU'][x] + "</td>\
                <td>" + result['color_name'][x] + "</td>\
                <td>" + result['reseller_name'][x] + "</td>\
                <td>" + result['reseller_email'][x] + "</td>\
              </tr>");

            //Set Object ID
            $('#div_hidden').append("\
              <input type='hidden' id='object" + x + "' value='" + result['id'][x] + "' />\
            ");
            total_data++;
            //End Set Object ID
          }
        } else {
          $('#table_content').append("\
          <tr>\
            <td colspan='6'><strong style='color:red;'>" + result['message'] + "</strong></td>\
          </tr>");
        }
      }
    });
  };
});