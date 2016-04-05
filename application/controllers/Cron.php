<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends CI_Controller {

  function __construct() {
    date_default_timezone_set('Asia/Jakarta');
    parent::__construct();
  }

  function check_order() {
    $this->load->model('Model_order');
    $this->load->model('Model_products_variant_detail');
    
    $get_unconfirmed_order = $this->Model_order->get_unconfirmed_order();
    if ($get_unconfirmed_order->num_rows() > 0) {
      foreach ($get_unconfirmed_order->result() as $row) {
        $param_cancel_order['purchase_code'] = $row->purchase_code;
        $this->Model_order->cancel_order($param_cancel_order); //Set Purchase Status to 4
        
        $get_order_item = $this->Model_order->get_order_item($param_cancel_order);
        foreach ($get_order_item->result() as $row) {
          $qty = $row->quantity;
          $product_id = $row->product_id;
          $SKU = $row->SKU;
          
          //Get Current Quantity
          $param_current_qty['SKU'] = $SKU;
          $get_current_qty = $this->Model_products_variant_detail->get_data($param_current_qty);
          //End Get Current Quantity
          
          //Add Quantity
          $param_update_quantity['purchase_code'] = $param_cancel_order['purchase_code'];
          $param_update_quantity['SKU'] = $SKU;
          $param_update_quantity['product_id'] = $product_id;
          $param_update_quantity['updated_quantity'] = $get_current_qty->row()->quantity + $qty;
          
          $this->Model_order->update_quantity($param_update_quantity);
          //End Add Quantity
        }
      }
    }
  }

}
