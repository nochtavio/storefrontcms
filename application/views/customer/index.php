<div class="row" style="margin-bottom:5px;">
  <div class="col-xs-12">
    <div id="main_panel" class="panel">
      <header class="panel-heading">
        Customer
      </header>
      <div class="panel-body table-responsive">
        <div class="box-tools m-b-15">
          <div class="input-group">
            <input id="txt_customer_email" name="txt_customer_email" type="text" class="form-control input-sm" style="margin-right: 7px;width: 150px;" placeholder="Filter Customer Email">
            <input id="txt_name" name="txt_name" type="text" class="form-control input-sm" style="margin-right: 7px;width: 150px;" placeholder="Filter Customer Name">
            <input id="txt_customer_province" name="txt_customer_province" type="text" class="form-control input-sm" style="margin-right: 7px;width: 150px;" placeholder="Filter Province">
            <input id="txt_customer_city" name="txt_customer_city" type="text" class="form-control input-sm" style="margin-right: 7px;width: 150px;" placeholder="Filter City">
            <select id="sel_customer_status" name="sel_customer_status" class="form-control input-sm" style="margin-right: 7px;width: 150px;">
              <option value="-1">All Status</option>
              <option value="1">Enabled</option>
              <option value="2">Blocked</option>
            </select>
            <select id="sel_order" name="sel_order" class="form-control input-sm" style="margin-right: 7px;width: 200px;">
              <option value="-1">Order by Latest Registration Date</option>
              <option value="1">Order by Oldest Registration Date</option>
              <option value="2">Order by Customer Email A-Z</option> 
              <option value="3">Order by Customer Email Z-A</option>
              <option value="4">Order by Customer Name A-Z</option> 
              <option value="5">Order by Customer Name Z-A</option>
            </select>
            <button id="btn_filter" type="submit" class="btn btn-default btn-sm">Filter</button>
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
            <label for="txt_data_customer_email" class="col-lg-3 col-sm-3 control-label">Email</label>
            <div class="col-lg-9 col-sm-9">
              <input type="text" class="form-control form_data" id="txt_data_customer_email" placeholder="" readonly="">
            </div>
          </div>
          <div class="form-group">
            <label for="txt_data_customer_fname" class="col-lg-3 col-sm-3 control-label">First Name</label>
            <div class="col-lg-9 col-sm-9">
              <input type="text" class="form-control form_data" id="txt_data_customer_fname" placeholder="" readonly="">
            </div>
          </div>
          <div class="form-group">
            <label for="txt_data_customer_lname" class="col-lg-3 col-sm-3 control-label">Last Name</label>
            <div class="col-lg-9 col-sm-9">
              <input type="text" class="form-control form_data" id="txt_data_customer_lname" placeholder="" readonly="">
            </div>
          </div>
          <div class="form-group">
            <label for="txt_data_customer_street" class="col-lg-3 col-sm-3 control-label">Street</label>
            <div class="col-lg-9 col-sm-9">
              <input type="text" class="form-control form_data" id="txt_data_customer_street" placeholder="" readonly="">
            </div>
          </div>
          <div class="form-group">
            <label for="txt_data_customer_province" class="col-lg-3 col-sm-3 control-label">Province</label>
            <div class="col-lg-9 col-sm-9">
              <input type="text" class="form-control form_data" id="txt_data_customer_province" placeholder="" readonly="">
            </div>
          </div>
          <div class="form-group">
            <label for="txt_data_customer_city" class="col-lg-3 col-sm-3 control-label">City</label>
            <div class="col-lg-9 col-sm-9">
              <input type="text" class="form-control form_data" id="txt_data_customer_city" placeholder="" readonly="">
            </div>
          </div>
          <div class="form-group">
            <label for="txt_data_customer_zipcode" class="col-lg-3 col-sm-3 control-label">Zip Code</label>
            <div class="col-lg-9 col-sm-9">
              <input type="text" class="form-control form_data" id="txt_data_customer_zipcode" placeholder="" readonly="">
            </div>
          </div>
          <div class="form-group">
            <label for="txt_data_customer_phone" class="col-lg-3 col-sm-3 control-label">Phone</label>
            <div class="col-lg-9 col-sm-9">
              <input type="text" class="form-control form_data" id="txt_data_customer_phone" placeholder="" readonly="">
            </div>
          </div>
          <div class="form-group">
            <label for="sel_data_customer_status" class="col-lg-3 col-sm-3 control-label">Status</label>
            <div class="col-lg-9 col-sm-9">
              <select id="sel_data_customer_status" name="sel_data_customer_status" class="form-control">
                <option value="1">Enabled</option>
                <option value="2">Blocked</option>
              </select>
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