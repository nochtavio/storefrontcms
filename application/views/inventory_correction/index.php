<div class="row" style="margin-bottom:5px;">
  <div class="col-lg-6">
    <section class="panel">
      <header class="panel-heading">
        Inventory Correction
      </header>
      <div class="panel-body">
        <form role="form">
          <div id="div_success" class="alert alert-success alert-dismissible" role="alert" style="display: none;">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <span id="span_success"></span>
          </div>
          <div id="div_alert" class="alert alert-danger alert-dismissible" role="alert" style="display: none;">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <span id="span_error"></span>
          </div>
          <input id="txt_data_id_products" type="hidden" />
          <div class="form-group">
            <label for="txt_data_sku">SKU</label>
            <div class="input-group m-b-10">
              <input id="txt_data_sku" type="text" class="form-control" placeholder="Enter SKU then click apply to see the detail">
              <span class="input-group-btn">
                <button id="btn_apply_sku" class="btn btn-white" type="button">Apply</button>
              </span>
            </div>
          </div>
          <div class="form-group form-hidden" style="display: none;">
            <label>Product Name</label>
            <input id="txt_data_product_name" type="text" class="form-control" readonly="">
          </div>
          <div class="form-group form-hidden" style="display: none;">
            <label>Color</label>
            <input id="txt_data_color" type="text" class="form-control" readonly="">
          </div>
          <div class="form-group form-hidden" style="display: none;">
            <label>Size</label>
            <input id="txt_data_size" type="text" class="form-control" readonly="">
          </div>
          <div class="form-group form-hidden" style="display: none;">
            <label>Current Quantity</label>
            <input id="txt_data_current_quantity" type="text" class="form-control" readonly="">
          </div>
          <div class="form-group form-hidden" style="display: none;">
            <label>Current Quantity Warehouse</label>
            <input id="txt_data_current_quantity_warehouse" type="text" class="form-control" readonly="">
          </div>
          <div class="form-group">
            <label for="sel_data_type">Type</label>
            <select id="sel_data_type" class="form-control m-b-10">
              <option value="3">Correction In</option>
              <option value="4">Correction Out</option>
            </select>
          </div>
          <div class="form-group">
            <label for="txt_data_quantity">Quantity</label>
            <input type="text" class="form-control" id="txt_data_quantity" placeholder="Enter quantity">
          </div>
          <button id="btn_submit_data" type="button" class="btn btn-info">Submit</button>
        </form>

      </div>
    </section>
  </div>
</div>