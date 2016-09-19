<div class="row" style="margin-bottom:5px;">
  <div class="col-xs-12">
    <div id="main_panel" class="panel">
      <header class="panel-heading">
        Order
      </header>
      <div class="panel-body table-responsive">
        <div class="box-tools m-b-15">
          <div class="input-group">
            <input id="txt_purchase_code" name="txt_purchase_code" type="text" class="form-control input-sm" style="margin-right: 7px;width: 150px;" placeholder="Filter Purchase Code">
            <input id="txt_customer_email" name="txt_customer_email" type="text" class="form-control input-sm" style="margin-right: 7px;width: 150px;" placeholder="Filter Customer Email">
            <input id="txt_order_date" name="txt_order_date" type="text" class="form-control input-sm" style="margin-right: 7px;width: 160px;" placeholder="">
            <select id="sel_status_payment" name="sel_status_payment" class="form-control input-sm" style="margin-right: 7px;width: 150px;">
              <option value="-1">All Status Payment</option>
              <option value="1">Confirmed</option>
              <option value="2">Not Confirmed</option>
            </select>
            <select id="sel_status" name="sel_status" class="form-control input-sm" style="margin-right: 7px;width: 150px;">
              <option value="-1">All Status</option>
              <option value="1">Paid</option>
              <option value="0">Unpaid</option>
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
          <div class="form-group">
            <label for="txt_data_purchase_code" class="col-lg-3 col-sm-3 control-label">Purchase Code</label>
            <div class="col-lg-9 col-sm-9">
              <input type="text" class="form-control form_data" id="txt_data_purchase_code" placeholder="" readonly="">
            </div>
          </div>
          <div class="form-group">
            <label for="sel_data_status" class="col-lg-3 col-sm-3 control-label">Status</label>
            <div class="col-lg-9 col-sm-9">
              <select id="sel_data_status" name="sel_data_status" class="form-control">
                <option value="0">Unpaid</option>
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

<div class="modal fade" id="modal_detail_order" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
        <h4 class="modal-title">Detail Purchase Code:  <span id="txt_detail_purchase_code" style="font-weight: bold;"></span></h4>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table class="table table-hover">
            <div id="div_hidden_detail" style="display: none;"></div>
            <div id="div_alert" class="alert alert-success alert-dismissible" role="alert">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <strong>Update Success!</strong>
            </div>
            <tbody id="table_content_detail">

            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <table class="pull-right">
          <tr>
            <td style="padding: 5px"><strong><span>Subtotal</span></strong></td>
            <td><strong><span style="margin: 0 7px 0 7px;">:</span></strong></td>
            <td><span id="txt_detail_subtotal"></span></td>
            <td></td>
          </tr>
          <tr>
            <td style="padding: 5px"><strong><span>Paycode</span></strong></td>
            <td><strong><span style="margin: 0 7px 0 7px;">:</span></strong></td>
            <td><span id="txt_detail_paycode"></span> </td>
            <td><i class="fa fa-plus-square-o" style="margin: 0 0 0 7px;"></i></td>
          </tr>
          <tr>
            <td style="padding: 5px"><strong><span>Shipping Cost</span></strong></td>
            <td><strong><span style="margin: 0 7px 0 7px;">:</span></strong></td>
            <td><span id="txt_detail_shipping_cost"></span> </td>
            <td><i class="fa fa-plus-square-o" style="margin: 0 0 0 7px;"></i></td>
          </tr>
          <tr>
            <td style="padding: 5px"><strong><span>Discount</span></strong></td>
            <td><strong><span style="margin: 0 7px 0 7px;">:</span></strong></td>
            <td><span id="txt_detail_discount"></span> </td>
            <td><i class="fa fa-minus-square-o" style="margin: 0 0 0 7px;"></i></td>
          </tr>
          <tr>
            <td style="padding: 5px"><strong><span>Credit Use</span></strong></td>
            <td><strong><span style="margin: 0 7px 0 7px;">:</span></strong></td>
            <td><span id="txt_detail_credit_use"></span> </td>
            <td><i class="fa fa-minus-square-o" style="margin: 0 0 0 7px;"></i></td>
          </tr>
          <tr>
            <td style="padding: 5px"><strong><span>Grand Total</span></strong></td>
            <td><strong><span style="margin: 0 7px 0 7px;">:</span></strong></td>
            <td><span id="txt_detail_grand_total" style="font-weight: bold; color: orange;"></span> </td>
            <td></td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</div>
<!--Modal-->