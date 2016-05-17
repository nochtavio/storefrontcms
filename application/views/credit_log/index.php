<div class="row" style="margin-bottom:5px;">
  <div class="col-xs-12">
    <div id="main_panel" class="panel">
      <header class="panel-heading">
        Credit Log
      </header>
      <div class="panel-body table-responsive">
        <div class="box-tools m-b-15">
          <div class="input-group">
            <input id="txt_email" name="txt_email" type="text" class="form-control input-sm" style="margin-right: 7px;width: 150px;" placeholder="Filter Email">
            <select id="sel_credit_log_type" name="sel_credit_log_type" class="form-control input-sm" style="margin-right: 7px;width: 150px;">
              <option value="-1">All Credit Log Type</option>
              <option value="1">Customer</option>
              <option value="2">Reseller</option>
            </select>
            <select id="sel_type" name="sel_type" class="form-control input-sm" style="margin-right: 7px;width: 150px;">
              <option value="-1">All Type</option>
              <option value="1">Add Type</option>
              <option value="2">Deduct Type</option>
            </select>
            <select id="sel_status" name="sel_status" class="form-control input-sm" style="margin-right: 7px;width: 150px;">
              <option value="-1">All Status</option>
              <option value="0">Request</option>
              <option value="1">Paid</option>
            </select>
            <select id="sel_order" name="sel_order" class="form-control input-sm" style="margin-right: 7px;width: 200px;">
              <option value="-1">Order by Latest Data</option> 
              <option value="1">Order by Oldest Data</option>
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
          <input type="hidden" id="txt_data_id_customer" name="txt_data_id_customer" />
          <div class="form-group">
            <label for="txt_data_email" class="col-lg-3 col-sm-3 control-label">Email</label>
            <div class="col-lg-9 col-sm-9">
              <input type="text" class="form-control form_data" id="txt_data_email" placeholder="" readonly="">
            </div>
          </div>
          <div class="form-group">
            <label for="txt_data_amount" class="col-lg-3 col-sm-3 control-label">Amount</label>
            <div class="col-lg-9 col-sm-9">
              <input type="text" class="form-control form_data" id="txt_data_amount" placeholder="" readonly="">
            </div>
          </div>
          <div class="form-group">
            <label for="sel_data_status" class="col-lg-3 col-sm-3 control-label">Status</label>
            <div class="col-lg-9 col-sm-9">
              <select id="sel_data_status" name="sel_data_status" class="form-control">
                <option value="0">Request</option>
                <option value="1">Paid</option>
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
<!--Modal-->