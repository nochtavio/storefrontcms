<div class="row" style="margin-bottom:5px;">
  <div class="col-xs-12">
    <div id="main_panel" class="panel">
      <header class="panel-heading">
        Reseller Item
      </header>
      <div class="panel-body table-responsive">
        <div class="box-tools m-b-15">
          <div class="input-group">
            <input id="txt_products_name" name="txt_products_name" type="text" class="form-control input-sm" style="margin-right: 7px;width: 150px;" placeholder="Filter Products Name">
            <input id="txt_SKU" name="txt_SKU" type="text" class="form-control input-sm" style="margin-right: 7px;width: 150px;" placeholder="Filter SKU">
            <input id="txt_reseller_email" name="txt_reseller_email" type="text" class="form-control input-sm" style="margin-right: 7px;width: 150px;" placeholder="Filter Reseller Email">
            <input id="txt_reseller_name" name="txt_reseller_name" type="text" class="form-control input-sm" style="margin-right: 7px;width: 150px;" placeholder="Filter Reseller Name">
            <select id="sel_order" name="sel_order" class="form-control input-sm" style="margin-right: 7px;width: 200px;">
              <option value="-1">Order by Latest Data</option>
              <option value="1">Order by Oldest Data</option>
              <option value="2">Order by Products Name A-Z</option> 
              <option value="3">Order by Products Name Z-A</option>
              <option value="4">Order by Reseller Email A-Z</option>
              <option value="5">Order by Reseller Email Z-A</option>
              <option value="6">Order by Reseller Name A-Z</option>
              <option value="7">Order by Reseller Name Z-A</option>
              <option value="8">Order by Least Quantity</option>
              <option value="9">Order by Higher Quantity</option>
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