<div class="row" style="margin-bottom:5px;">
  <div class="col-xs-12">
    <div id="main_panel" class="panel">
      <header class="panel-heading">
        Admin
      </header>
      <div class="panel-body table-responsive">
        <div class="box-tools m-b-15">
          <div class="input-group">
            <input id="txt_username" name="txt_username" type="text" class="form-control input-sm" style="margin-right: 7px;width: 150px;" placeholder="Filter Username">
            <select id="sel_active" name="sel_active" class="form-control input-sm" style="margin-right: 7px;width: 150px;">
              <option value="-1">All Status</option>
              <option value="1">Active</option>
              <option value="2">Not Active</option>
            </select>
            <select id="sel_order" name="sel_order" class="form-control input-sm" style="margin-right: 7px;width: 175px;">
              <option value="-1">Order by Username A-Z</option>
              <option value="1">Order by Username Z-A</option>
              <option value="2">Order by Latest Data</option>
              <option value="3">Order by Oldest Data</option>
            </select>
            <button id="btn_filter" type="submit" class="btn btn-default btn-sm">Filter</button>
            <div class="input-group-btn">
              <a id="btn_add_data" href="#modal_data" data-toggle="modal" class="btn btn-info btn-sm pull-right">Add Admin</a>
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
            <label for="txt_data_username" class="col-lg-4 col-sm-4 control-label">Username</label>
            <div class="col-lg-8 col-sm-8">
              <input type="text" class="form-control" id="txt_data_username" placeholder="Enter username">
            </div>
          </div>
          <div class="form-group">
            <label for="txt_data_password" class="col-lg-4 col-sm-4 control-label">Password</label>
            <div class="col-lg-8 col-sm-8">
              <input type="password" class="form-control" id="txt_data_password" placeholder="Enter password">
            </div>
          </div>
          <div class="form-group">
            <label for="txt_data_conf_password" class="col-lg-4 col-sm-4 control-label">Confirmation Password</label>
            <div class="col-lg-8 col-sm-8">
              <input type="password" class="form-control" id="txt_data_conf_password" placeholder="Enter confirmation password">
              <p class="help-block" style="margin-bottom: 0;">Enter your password again</p>
            </div>
          </div>
          <div class="form-group">
            <label for="txt_data_active" class="col-lg-4 col-sm-4 control-label">Status</label>
            <div class="col-lg-8 col-sm-8">
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
<!--Modal-->