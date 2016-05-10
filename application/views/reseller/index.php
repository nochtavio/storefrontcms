<div class="row" style="margin-bottom:5px;">
  <div class="col-xs-12">
    <div id="main_panel" class="panel">
      <header class="panel-heading">
        Reseller 
      </header>
      <div class="panel-body table-responsive">
        <div class="box-tools m-b-15">
          <div class="input-group">
            <input id="txt_name" name="txt_name" type="text" class="form-control input-sm" style="margin-right: 7px;width: 150px;" placeholder="Filter Reseller Name">
            <input id="txt_store_name" name="txt_store_name" type="text" class="form-control input-sm" style="margin-right: 7px;width: 175px;" placeholder="Filter Reseller Store Name">
            <input id="txt_email" name="txt_email" type="text" class="form-control input-sm" style="margin-right: 7px;width: 150px;" placeholder="Filter Reseller Email">
            <input id="txt_phone" name="txt_phone" type="text" class="form-control input-sm" style="margin-right: 7px;width: 150px;" placeholder="Filter Reseller Phone">
            <select id="sel_status" name="sel_status" class="form-control input-sm" style="margin-right: 7px;width: 150px;">
              <option value="-1">All Status</option>
              <option value="1">Active</option>
              <option value="2">Banned</option>
            </select>
            <select id="sel_order" name="sel_order" class="form-control input-sm" style="margin-right: 7px;width: 200px;">
              <option value="-1">Order by Reseller Name A-Z</option>
              <option value="1">Order by Reseller Name Z-A</option>
              <option value="2">Order by Reseller Store Name A-Z</option>
              <option value="3">Order by Reseller Store Name Z-A</option>
              <option value="4">Order by Latest Data</option> 
              <option value="5">Order by Oldest Data</option>
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
            <label for="txt_data_name" class="col-lg-3 col-sm-3 control-label">Name</label>
            <div class="col-lg-9 col-sm-9">
              <input type="text" class="form-control form_data" id="txt_data_name" placeholder="Enter reseller name" readonly="">
            </div>
          </div>
          <div class="form-group">
            <label for="txt_data_store_name" class="col-lg-3 col-sm-3 control-label">Store Name</label>
            <div class="col-lg-9 col-sm-9">
              <input type="text" class="form-control form_data" id="txt_data_store_name" placeholder="Enter reseller store name" readonly="">
            </div>
          </div>
          <div class="form-group">
            <label for="txt_data_email" class="col-lg-3 col-sm-3 control-label">Email</label>
            <div class="col-lg-9 col-sm-9">
              <input type="text" class="form-control form_data" id="txt_data_email" placeholder="Enter reseller email" readonly="">
            </div>
          </div>
          <div class="form-group">
            <label for="txt_data_phone" class="col-lg-3 col-sm-3 control-label">Phone</label>
            <div class="col-lg-9 col-sm-9">
              <input type="text" class="form-control form_data" id="txt_data_phone" placeholder="Enter reseller phone" readonly="">
            </div>
          </div>
          <div class="form-group">
            <label for="txt_data_street" class="col-lg-3 col-sm-3 control-label">Street</label>
            <div class="col-lg-9 col-sm-9">
              <input type="text" class="form-control form_data" id="txt_data_street" placeholder="Enter reseller street" readonly="">
            </div>
          </div>
          <div class="form-group">
            <label for="txt_data_province" class="col-lg-3 col-sm-3 control-label">Province</label>
            <div class="col-lg-9 col-sm-9">
              <input type="text" class="form-control form_data" id="txt_data_province" placeholder="Enter reseller province" readonly="">
            </div>
          </div>
          <div class="form-group">
            <label for="txt_data_city" class="col-lg-3 col-sm-3 control-label">City</label>
            <div class="col-lg-9 col-sm-9">
              <input type="text" class="form-control form_data" id="txt_data_city" placeholder="Enter reseller city" readonly="">
            </div>
          </div>
          <div class="form-group">
            <label for="txt_data_zipcode" class="col-lg-3 col-sm-3 control-label">Zipcode</label>
            <div class="col-lg-9 col-sm-9">
              <input type="text" class="form-control form_data" id="txt_data_zipcode" placeholder="Enter reseller zipcode" readonly="">
            </div>
          </div>
          <div id="error_container" class="alert alert-block alert-danger" style="display: none;">
            <div id="error_container_message">

            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal_status" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">Reseller Status</h4>
      </div>
      <div class="modal-body">
        <span id="remove_message"></span>
      </div>
      <div class="modal-footer">
        <input type="hidden" id="txt_status_id" name="txt_remove_id" />
        <button data-dismiss="modal" class="btn btn-default" type="button">No</button>
        <button id="btn_status" class="btn btn-danger" type="button"> Yes</button>
      </div>
    </div>
  </div>
</div>
<!--Modal-->