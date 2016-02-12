<div class="row" style="margin-bottom:5px;">
  <div class="col-xs-12">
    <div id="main_panel" class="panel">
      <header class="panel-heading">
        Products
      </header>
      <div class="panel-body table-responsive">
        <div class="box-tools m-b-15">
          <div class="input-group">
            <input id="txt_name" name="txt_name" type="text" class="form-control input-sm" style="margin-right: 7px;width: 150px;" placeholder="Filter Product Name">
            <select id="sel_active" name="sel_active" class="form-control input-sm" style="margin-right: 7px;width: 150px;">
              <option value="-1">All Status</option>
              <option value="1">Active</option>
              <option value="0">Not Active</option>
            </select>
            <select id="sel_order" name="sel_order" class="form-control input-sm" style="margin-right: 7px;width: 175px;">
              <option value="-1">Order by Product Name A-Z</option>
              <option value="1">Order by Product Name Z-A</option>
              <option value="2">Order by Latest Data</option>
              <option value="3">Order by Oldest Data</option>
            </select>
            <button id="btn_filter" type="submit" class="btn btn-default btn-sm">Filter</button>
            <div class="input-group-btn">
              <a id="btn_add_data" href="#modal_data" data-toggle="modal" class="btn btn-info btn-sm pull-right">Add Product</a>
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
  <div class="modal-dialog modal-lg">
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
              <input type="text" class="form-control form_data" id="txt_data_name" placeholder="Enter product name">
            </div>
          </div>
          <div class="form-group">
            <label for="txt_data_price" class="col-lg-3 col-sm-3 control-label">Price</label>
            <div class="col-lg-9 col-sm-9">
              <input type="text" class="form-control form_data" id="txt_data_price" placeholder="Enter product price">
            </div>
          </div>
          <div class="form-group">
            <label for="txt_data_sale_price" class="col-lg-3 col-sm-3 control-label">Sale Price</label>
            <div class="col-lg-9 col-sm-9">
              <input type="text" class="form-control form_data" id="txt_data_sale_price" placeholder="Enter product sale price">
              <p class="help-block" style="margin-bottom: 0;">Leave this 0 if this product is not on sale</p>
            </div>
          </div>
          <div class="form-group">
            <label for="txt_data_reseller_price" class="col-lg-3 col-sm-3 control-label">Reseller Price</label>
            <div class="col-lg-9 col-sm-9">
              <input type="text" class="form-control form_data" id="txt_data_reseller_price" placeholder="Enter product reseller price">
              <p class="help-block" style="margin-bottom: 0;">Leave this 0 if this product is not on reseller program</p>
            </div>
          </div>
          <div class="form-group">
            <label for="txt_data_weight" class="col-lg-3 col-sm-3 control-label">Weight</label>
            <div class="col-lg-9 col-sm-9">
              <input type="text" class="form-control form_data" id="txt_data_weight" placeholder="Enter product weight">
            </div>
          </div>
          <div class="form-group">
            <label for="txt_data_attribute" class="col-lg-3 col-sm-3 control-label">Attribute</label>
            <div class="col-lg-9 col-sm-9">
              <input type="text" class="form-control form_data" id="txt_data_attribute" placeholder="Enter product attribute">
            </div>
          </div>
          <div class="form-group">
            <label for="txt_data_description" class="col-lg-3 col-sm-3 control-label">Description</label>
            <div class="col-lg-9 col-sm-9" id="txt_data_description"></div>
          </div>
          <div class="form-group">
            <label for="txt_data_short_description" class="col-lg-3 col-sm-3 control-label">Short Description</label>
            <div class="col-lg-9 col-sm-9" id="txt_data_short_description"></div>
          </div>
          <div class="form-group">
            <label for="txt_data_info" class="col-lg-3 col-sm-3 control-label">Info</label>
            <div class="col-lg-9 col-sm-9">
              <input type="text" class="form-control form_data" id="txt_data_info" placeholder="Enter product Info">
            </div>
          </div>
          <div class="form-group">
            <label for="txt_data_size_guideline" class="col-lg-3 col-sm-3 control-label">Size Guidelines</label>
            <div class="col-lg-9 col-sm-9">
              <input type="text" class="form-control form_data" id="txt_data_size_guideline" placeholder="Enter product size guideline">
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-3 col-sm-3 control-label">Category</label>
            <div class="col-lg-9 col-sm-9">
              <?php 
                foreach($category as $cat){
                  ?>
                    <div class="col-lg-3 col-sm-3" style="padding: 7px 0 0 0px;">
                      <label><?php echo $cat->name ?></label>
                      <?php 
                        foreach($category_child as $cat_child){
                          if($cat_child->id_category == $cat->id){
                            ?>
                              <div class="checkbox">
                                <label>
                                  <input type="checkbox" name="cb_category" value="<?php echo $cat_child->id; ?>"><?php echo $cat_child->name; ?>
                                </label>
                              </div>
                            <?php
                          }
                        }
                      ?>
                    </div>
                  <?php
                }
              ?>
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