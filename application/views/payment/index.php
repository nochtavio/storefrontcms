<div class="row" style="margin-bottom:5px;">
  <div class="col-xs-12">
    <div id="main_panel" class="panel">
      <header class="panel-heading">
        Payment
      </header>
      <div class="panel-body table-responsive">
        <div class="box-tools m-b-15">
          <div class="input-group">
            <input id="txt_name" name="txt_name" type="text" class="form-control input-sm" style="margin-right: 7px;width: 150px;" placeholder="Filter Payment Name">
            <select id="sel_type" name="sel_type" class="form-control input-sm" style="margin-right: 7px;width: 150px;">
              <option value="-1">All Type</option>
              <option value="1">Transfer</option>
              <option value="2">Non-Transfer</option>
            </select>
            <select id="sel_active" name="sel_active" class="form-control input-sm" style="margin-right: 7px;width: 150px;">
              <option value="-1">All Status</option>
              <option value="1">Active</option>
              <option value="0">Not Active</option>
            </select>
            <select id="sel_order" name="sel_order" class="form-control input-sm" style="margin-right: 7px;width: 200px;">
              <option value="-1">Order by Payment Name A-Z</option>
              <option value="1">Order by Payment Name Z-A</option>
              <option value="2">Order by Latest Data</option> 
              <option value="3">Order by Oldest Data</option>
              <option value="4">Order by Show Order 0-9</option>
              <option value="5">Order by Show Order 9-0</option>
            </select>
            <button id="btn_filter" type="submit" class="btn btn-default btn-sm">Filter</button>
            <?php 
              if(check_menu("", 1)){
                ?>
                  <div class="input-group-btn">
                    <a id="btn_add_data" href="#modal_data" data-toggle="modal" class="btn btn-info btn-sm pull-right">Add Payment</a>
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
          <div class="form-group">
            <label for="txt_data_name" class="col-lg-3 col-sm-3 control-label">Name</label>
            <div class="col-lg-9 col-sm-9">
              <input type="text" class="form-control form_data" id="txt_data_name" placeholder="Enter payment name">
            </div>
          </div>
          <div class="form-group">
            <label for="txt_data_description" class="col-lg-3 col-sm-3 control-label">Description</label>
            <div class="col-lg-9 col-sm-9">
              <div id="txt_data_description"></div>
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-3 col-sm-3 control-label">Logo</label>
            <div class="col-lg-9 col-sm-9">
              <img id="txt_data_img" width="100px" height="100px" alt="No Image" />
              <input type="file" multiple="multiple" class="form-control form_data" name="txt_data_add_file[]" id="txt_data_add_file" size="20" />
              <input type="file" class="form-control form_data" name="txt_data_edit_file" id="txt_data_edit_file" size="20" />
              <p class="help-block" style="margin-bottom: 0;">Allowed file is JPG, PNG | Maximum size is 500 KB | Recommended resolution is 100 x 100 px</p>
            </div>
          </div>
          <div class="form-group">
            <label for="sel_data_type" class="col-lg-3 col-sm-3 control-label">Type</label>
            <div class="col-lg-9 col-sm-9">
              <select id="sel_data_type" name="sel_data_type" class="form-control">
                <option value="1">Transfer</option>
                <option value="2">Non-Transfer</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label for="txt_data_minimum_grand_total" class="col-lg-3 col-sm-3 control-label">Minimum Grand Total</label>
            <div class="col-lg-9 col-sm-9">
              <input type="text" class="form-control form_data" id="txt_data_minimum_grand_total" placeholder="Enter payment minimum grand total">
              <p class="help-block" style="margin-bottom: 0;">Optional.</p>
            </div>
          </div>
          <div class="form-group">
            <label for="txt_data_show_order" class="col-lg-3 col-sm-3 control-label">Show Order</label>
            <div class="col-lg-9 col-sm-9">
              <input type="text" class="form-control form_data" id="txt_data_show_order" placeholder="Enter payment show order">
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