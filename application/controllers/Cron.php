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
  
  function new_category(){
    $this->load->model('Model_reseller');
    
    //Send Email to Active Reseller
    $param_reseller_data['status'] = 1;
    $get_reseller_data = $this->Model_reseller->get_data($param_reseller_data);
    if ($get_reseller_data->num_rows() > 0) {
      $config = Array(
        'protocol' => 'smtp',
        'smtp_host' => 'mail.storefrontindo.com',
        'smtp_port' => 25,
        'smtp_user' => 'do-not-reply@storefrontindo.com', // change it to yours
        'smtp_pass' => 'v0AOsm[viHJB', // change it to yours
        'mailtype' => 'html',
        'charset' => 'iso-8859-1',
        'wordwrap' => TRUE
      );
      
      $this->load->library('email', $config);
      
      foreach ($get_reseller_data->result() as $row) {
        $this->email->clear();
        $this->email->set_newline("\r\n");
        $this->email->set_mailtype("html");
        $this->email->from('do-not-reply@storefrontindo.com', 'Storefront Indonesia'); // change it to yours
        $this->email->to($row->email); // change it to yours
        $this->email->subject("Info Penambahan Kategori");
        $this->email->message(""
          . "Dear <strong>".$row->name."</strong><br/> <br/>"
          . "Berikut adalah daftar kategori baru yang bisa anda pilih untuk dijual di web anda. <br/> <br/>"
          . "<strong>Email: </strong> ".$param['email']."<br/> <br/>"
          . "<strong>Password: </strong> ".$approval['password']."<br/> <br/>"
          . "Silahkan login ke admin panel <a href='http://www.storefrontindo.com/front/reseller/login/' target='_blank'>http://www.storefrontindo.com/front/reseller/login/</a>  dengan menggunakan email dan password diatas. <br/> <br/>"
          . "Salam <br/> <br/>"
          . "Owner FFStore"
          . "");
        $this->email->send();
      }
    }
  }

}
