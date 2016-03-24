<div class="row" style="margin-bottom:5px;">
  <div class="col-xs-12">
    <div id="main_panel" class="panel">
      <header class="panel-heading">
        Customer Return
      </header>
      <div class="panel-body table-responsive">
        <div class="box-tools m-b-15">
          <div class="input-group">
            <input id="txt_purchase_code" name="txt_purchase_code" type="text" class="form-control input-sm" style="margin-right: 7px;width: 150px;" placeholder="Filter Purchase Code">
            <input id="txt_customer_email" name="txt_customer_email" type="text" class="form-control input-sm" style="margin-right: 7px;width: 150px;" placeholder="Filter Customer Email">
            <select id="sel_status" name="sel_status" class="form-control input-sm" style="margin-right: 7px;width: 150px;">
              <option value="-1">All Status</option>
              <option value="0">New</option>
              <option value="2">Processed</option>
              <option value="3">Finished</option>
            </select>
            <select id="sel_order" name="sel_order" class="form-control input-sm" style="margin-right: 7px;width: 200px;">
              <option value="-1">Order by Latest Data</option> 
              <option value="1">Order by Oldest Data</option>
              <option value="2">Order by Customer Email A-Z</option>
              <option value="3">Order by Customer Email Z-A</option>
              <option value="4">Order by Purchase Code A-Z</option>
              <option value="5">Order by Purchase Code A-Z</option>
            </select>
            <button id="btn_filter" type="submit" class="btn btn-default btn-sm">Filter</button>
            <?php 
              if(check_menu("", 1)){
                ?>
                  <div class="input-group-btn">
                    <a id="btn_add_data" href="#modal_data" data-toggle="modal" class="btn btn-info btn-sm pull-right">Add Customer Return</a>
                  </div>
                <?php
              }
            ?>
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
          <input type="hidden" id="txt_data_order_item_id" name="txt_data_order_item_id" />
          <input type="hidden" id="txt_data_customer_id" name="txt_data_customer_id" />
          <div class="form-group">
            <label for="txt_data_purchase_code" class="col-lg-3 col-sm-3 control-label">Purchase Code</label>
            <div class="col-lg-7 col-sm-7">
              <input type="text" class="form-control" id="txt_data_purchase_code" placeholder="Enter valid purchase code">
            </div>
            <div class="col-lg-2 col-sm-2">
              <button id="btn_purchase_code" type="button" class="btn btn-default pull-right" style="width: 70px;">Apply</button>
            </div>
          </div>
          
          <!--SHOWN AFTER PURCHASE CODE APPLIED-->
          <div class="form-group hidden-div-2">
            <label for="txt_data_customer_email" class="col-lg-3 col-sm-3 control-label">Customer Email</label>
            <div class="col-lg-9 col-sm-9">
              <input type="text" class="form-control form_data" id="txt_data_customer_email" placeholder="" readonly="">
            </div>
          </div>
          <div class="form-group hidden-div-2">
            <label for="sel_data_sku" class="col-lg-3 col-sm-3 control-label">List Item</label>
            <div class="col-lg-9 col-sm-9">
              <select id="sel_data_sku" name="sel_data_sku" class="form-control">
                
              </select>
            </div>
          </div>
          <div class="form-group hidden-div">
            <label for="txt_data_qty" class="col-lg-3 col-sm-3 control-label">Quantity</label>
            <div class="col-lg-9 col-sm-9">
              <input type="text" class="form-control form_data" id="txt_data_qty" placeholder="">
            </div>
          </div>
          <div class="form-group hidden-div">
            <label for="txt_data_reason" class="col-lg-3 col-sm-3 control-label">Reason</label>
            <div class="col-lg-9 col-sm-9">
              <input type="text" class="form-control form_data" id="txt_data_reason" placeholder="">
            </div>
          </div>
          <div class="form-group hidden-div">
            <label for="sel_data_status" class="col-lg-3 col-sm-3 control-label">Status</label>
            <div class="col-lg-9 col-sm-9">
              <select id="sel_data_status" name="sel_data_status" class="form-control">
                <option value="0">New</option>
                <option value="1">Processed</option>
                <option value="2">Finished</option>
              </select>
            </div>
          </div>
          <!--SHOWN AFTER PURCHASE CODE APPLIED-->
          
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