<input type="hidden" id="txt_id_products" name="txt_id_products" value="<?php echo $id_products ?>" />

<div class="row" style="margin-bottom:5px;">
  <div class="col-xs-12">
    <div id="main_panel" class="panel">
      <header class="panel-heading">
        <?php echo $products_name ?> Variants
      </header>
      <div class="panel-body table-responsive">
        <div class="box-tools m-b-15">
          <div class="input-group">
            <select id="sel_active" name="sel_active" class="form-control input-sm" style="margin-right: 7px;width: 150px;">
              <option value="-1">All Status</option>
              <option value="1">Active</option>
              <option value="0">Not Active</option>
            </select>
            <select id="sel_order" name="sel_order" class="form-control input-sm" style="margin-right: 7px;width: 200px;">
              <option value="-1">Order by Latest Data</option>
              <option value="1">Order by Oldest Data</option>
              <option value="2">Order by Quantity &uarr;</option> 
              <option value="3">Order by Quantity &darr;</option>
            </select>
            <button id="btn_filter" type="submit" class="btn btn-default btn-sm">Filter</button>
            <div class="input-group-btn">
              <a id="btn_add_data" href="#modal_data" data-toggle="modal" class="btn btn-info btn-sm">Add Variant</a>
            </div>
            <div class="input-group-btn">
              <a href="<?php echo base_url() ?>products/" data-toggle="modal" class="btn btn-warning btn-sm">Back</a>
            </div>
          </div>
        </div>
        <table class="table table-hover">
          <div id="div_hidden" style="display: none;"></div>
          <tbody id="table_content">

          </tbody>
        </table>
        <div class="table-foot">
          <ul id="paging" class="pagination pagination-sm no-margin pull-right">

          </ul>
        </div>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
  </div>
</div>

<!--Modal-->
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="modal_data" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
        <h4 id="modal_data_title" class="modal-title"></h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" role="form">
          <input type="hidden" id="txt_data_id" name="txt_data_id" />
          <div class="form-group">
            <label for="txt_data_name" class="col-lg-3 col-sm-3 control-label">Color</label>
            <div class="col-lg-9 col-sm-9">
              <select id="txt_data_id_color" name="txt_data_id_color" class="form-control">
                <option value="0">Select Color</option>
                <?php 
                  foreach($color as $col){
                    ?>
                      <option value="<?php echo $col->id ?>"><?php echo $col->name ?></option>
                    <?php
                  }
                ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label for="txt_data_size" class="col-lg-3 col-sm-3 control-label">Size</label>
            <div class="col-lg-9 col-sm-9">
              <input type="text" class="form-control form_data" id="txt_data_size" placeholder="Enter size">
              <p class="help-block" style="margin-bottom: 0;">Leave this empty if the product is all size.</p>
            </div>
          </div>
          <div class="form-group">
            <label for="txt_data_quantity" class="col-lg-3 col-sm-3 control-label">Quantity</label>
            <div class="col-lg-9 col-sm-9">
              <input type="text" class="form-control form_data" id="txt_data_quantity" placeholder="Enter product quantity">
            </div>
          </div>
          <div class="form-group">
            <label for="txt_data_active" class="col-lg-3 col-sm-3 control-label">Status</label>
            <div class="col-lg-9 col-sm-9">
              <div class="checkbox">
                <label>
                  <input type="checkbox" id="txt_data_active" value=""> Active
                </label>
              </div>
            </div>
          </div>
          <div id="error_container" class="alert alert-block alert-danger" style="display: none;">
            <div id="error_container_message">

            </div>
          </div>
          <div class="form-group">
            <div class="col-lg-offset-10 col-lg-2">
              <button id="btn_submit_data" type="submit" class="btn btn-default">Submit</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal_remove" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">Remove Data</h4>
      </div>
      <div class="modal-body">
        <span id="remove_message"></span>
      </div>
      <div class="modal-footer">
        <input type="hidden" id="txt_remove_id" name="txt_remove_id" />
        <button data-dismiss="modal" class="btn btn-default" type="button">No</button>
        <button id="btn_remove_data" class="btn btn-danger" type="button"> Yes</button>
      </div>
    </div>
  </div>
</div>
<!--Modal-->