<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_return extends CI_Controller {

  function __construct() {
    date_default_timezone_set('Asia/Jakarta');
    parent::__construct();
    check_address();
    check_login();
    if(!check_menu()){
      redirect(base_url().'dashboard/');
    }
    $this->load->model('Model_customer');
    $this->load->model('Model_customer_return');
    $this->load->model('Model_products_variant_detail');
    $this->load->model('Model_order');
  }

  public function index() {
    $page = 'Customer_return';
    $sidebar['page'] = $page;
    $content['js'] = array();
    
    $this->Model_customer_return->set_read();
    
    array_push($content['js'], 'customer_return/function.js');
    array_push($content['js'], 'customer_return/init.js');
    array_push($content['js'], 'customer_return/action.js');

    $data['header'] = $this->load->view('header', '', TRUE);
    $data['sidebar'] = $this->load->view('sidebar', $sidebar, TRUE);
    $data['content'] = $this->load->view('customer_return/index', $content, TRUE);
    $this->load->view('template_index', $data);
  }

  public function get_data(){
    //param
    $param['purchase_code'] = ($this->input->post('purchase_code', TRUE)) ? $this->input->post('purchase_code', TRUE) : "";
    $param['customer_email'] = ($this->input->post('customer_email', TRUE)) ? $this->input->post('customer_email', TRUE) : "";
    $param['status'] = ($this->input->post('status', TRUE)) ? $this->input->post('status', TRUE) : 0;
    $param['order'] = ($this->input->post('order', TRUE)) ? $this->input->post('order', TRUE) : -1;
    //end param

    //paging
    $get_data = $this->Model_customer_return->get_data($param);
    $page = ($this->input->post('page', TRUE)) ? $this->input->post('page', TRUE) : 1 ;
    $size = ($this->input->post('size', TRUE)) ? $this->input->post('size', TRUE) : 10 ;
    $limit = ($page - 1) * $size;
    //End Set totalpaging

    if ($get_data->num_rows() > 0) {
      $get_data_paging = $this->Model_customer_return->get_data($param, $limit, $size);
      $temp = 0;
      foreach ($get_data_paging->result() as $row) {
        $data['result'] = "r1";
        $data['id'][$temp] = $row->id;
        $data['purchase_code'][$temp] = $row->purchase_code;
        $data['products_name'][$temp] = $row->products_name;
        $data['SKU'][$temp] = $row->SKU;
        $data['qty'][$temp] = $row->qty;
        $data['customer_email'][$temp] = $row->customer_email;
        $data['status'][$temp] = $row->status;
        $data['cretime'][$temp] = date_format(date_create($row->cretime), 'd F Y H:i:s');
        $data['creby'][$temp] = $row->creby;
        $data['modtime'][$temp] = ($row->modtime == NULL) ? NULL : date_format(date_create($row->modtime), 'd F Y H:i:s');
        $data['modby'][$temp] = $row->modby;
        $temp++;
      }
      $data['allowed_edit'] = check_menu("", 2);
      $data['allowed_delete'] = check_menu("", 3);
      $data['total'] = $temp;
      $data['size'] = $size;
      $data['totalpage'] = ceil($get_data->num_rows() / $size);
    } else {
      $data['result'] = "r2";
      $data['message'] = "No Customer Return";
    }

    echo json_encode($data);
  }

  public function get_specific_data(){
    //param
    $param['id'] = ($this->input->post('id', TRUE)) ? $this->input->post('id', TRUE) : "";
    //end param

    $result_data = $this->Model_customer_return->get_data($param);
    if($result_data->num_rows() > 0){
      $data['result'] = "r1";
      $data['id'] = $result_data->row()->id;
      $data['purchase_code'] = $result_data->row()->purchase_code;
      $data['SKU'] = $result_data->row()->SKU;
      $data['customer_id'] = $result_data->row()->customer_id;
      $data['customer_email'] = $result_data->row()->customer_email;
      $data['order_item_id'] = $result_data->row()->order_item_id;
      $data['qty'] = $result_data->row()->qty;
      $data['reason'] = $result_data->row()->reason;
      $data['status'] = $result_data->row()->status;
    }else{
      $data['result'] = "r2";
      $data['message'] = "No Data";
    }

    echo json_encode($data);
  }

  public function validate_post($param, $pc_edited = TRUE){
    //param
    $purchase_code = (isset($param['purchase_code'])) ? $param['purchase_code'] : "";
    $SKU = (isset($param['SKU'])) ? $param['SKU'] : "";
    $order_item_id = (isset($param['order_item_id'])) ? $param['order_item_id'] : "";
    $qty = (isset($param['qty'])) ? $param['qty'] : "";
    $reason = (isset($param['reason'])) ? $param['reason'] : "";
    //end param

    $data['result'] = "r1";
    $data['result_message'] = "";

    if($purchase_code == ""){
      $data['result'] = "r2";
      $data['result_message'] .= "<strong>Purchase Code</strong> must be filled !<br/>";
    }else{
      if($pc_edited){
        $param_check_pc['purchase_code'] = $purchase_code;
        $param_check_pc['SKU'] = $SKU;
        $result_data = $this->Model_customer_return->get_data($param_check_pc);
        if($result_data->num_rows() > 0){
          $data['result'] = "r2";
          $data['result_message'] .= "<strong>Purchase Code</strong> with this SKU is already returned !<br/>";
        }
      }
    }

    if($order_item_id == ""){
      $data['result'] = "r2";
      $data['result_message'] .= "<strong>Order Item ID</strong> must be filled !<br/>";
    }

    if($qty == ""){
      $data['result'] = "r2";
      $data['result_message'] .= "<strong>Quantity</strong> must be filled !<br/>";
    }else if(!is_numeric($qty)){
      $data['result'] = "r2";
      $data['result_message'] .= "<strong>Quantity</strong> must be a number !<br/>";
    }else{
      $param_check_oi['purchase_code'] = $purchase_code;
      $param_check_oi['SKU'] = $SKU;
      $result_data = $this->Model_customer_return->get_order_item($param_check_oi);
      if($result_data->num_rows() > 0){
        if($qty > $result_data->row()->quantity){
          $data['result'] = "r2";
          $data['result_message'] .= "<strong>Quantity</strong> is larger than ordered !<br/>";
        }
      }else{
        $data['result'] = "r2";
        $data['result_message'] .= "<strong>Purchase Code</strong> is not found<br/>";
      }
    }

    if($reason == ""){
      $data['result'] = "r2";
      $data['result_message'] .= "<strong>Reason</strong> must be filled !<br/>";
    }

    return $data;
  }

  public function add_data(){
    //param
    $param['purchase_code'] = ($this->input->post('purchase_code', TRUE)) ? $this->input->post('purchase_code', TRUE) : "" ;
    $param['customer_id'] = ($this->input->post('customer_id', TRUE)) ? $this->input->post('customer_id', TRUE) : "" ;
    $param['order_item_id'] = ($this->input->post('order_item_id', TRUE)) ? $this->input->post('order_item_id', TRUE) : "" ;
    $param['SKU'] = ($this->input->post('SKU', TRUE)) ? $this->input->post('SKU', TRUE) : "" ;
    $param['qty'] = ($this->input->post('qty', TRUE)) ? $this->input->post('qty', TRUE) : "" ;
    $param['reason'] = ($this->input->post('reason', TRUE)) ? $this->input->post('reason', TRUE) : "" ;
    $param['status'] = ($this->input->post('status', TRUE)) ? $this->input->post('status', TRUE) : "" ;
    //end param

    $validate_post = $this->validate_post($param, TRUE);
    if($validate_post['result'] == "r1"){
      $this->Model_customer_return->add_data($param);
    }

    echo json_encode($validate_post);
  }

  public function edit_data(){
    //param
    $param['id'] = ($this->input->post('id', TRUE)) ? $this->input->post('id', TRUE) : "" ;
    $param['purchase_code'] = ($this->input->post('purchase_code', TRUE)) ? $this->input->post('purchase_code', TRUE) : "" ;
    $param['customer_id'] = ($this->input->post('customer_id', TRUE)) ? $this->input->post('customer_id', TRUE) : "" ;
    $param['order_item_id'] = ($this->input->post('order_item_id', TRUE)) ? $this->input->post('order_item_id', TRUE) : "" ;
    $param['SKU'] = ($this->input->post('SKU', TRUE)) ? $this->input->post('SKU', TRUE) : "" ;
    $param['qty'] = ($this->input->post('qty', TRUE)) ? $this->input->post('qty', TRUE) : "" ;
    $param['reason'] = ($this->input->post('reason', TRUE)) ? $this->input->post('reason', TRUE) : "" ;
    $param['status'] = ($this->input->post('status', TRUE)) ? $this->input->post('status', TRUE) : "" ;
    //end param

    if($param['id'] != ""){
      //check if purchase order edited or not
      $pc_edited = TRUE;
      $param_check['id'] =  $param['id'];
      $param_check['SKU'] =  $param['SKU'];
      $result_data = $this->Model_customer_return->get_data($param_check);
      if($result_data->num_rows() > 0){
        $pc_edited = FALSE;
      }
      //end check

      $validate_post = $this->validate_post($param, $pc_edited);
      if($validate_post['result'] == "r1"){
        //Additional Update

        //Inventory
        $param_inv['sku'] = $param['SKU'];
        $get_product_variant_detail = $this->Model_products_variant_detail->get_data($param_inv);
        if ($get_product_variant_detail->num_rows() > 0) {
          $pvd_qty = $get_product_variant_detail->row()->quantity;
          $pvd_qty_warehouse = $get_product_variant_detail->row()->quantity_warehouse;
        }
        $update_qty = 0;
        $update_qty_warehouse = 0;
        //End Inventory

        //Inventory Logs
        $param_inv_logs['id'] = $param['id'];
        $get_customer_return = $this->Model_customer_return->get_data($param_inv_logs);
        if ($get_customer_return->num_rows() > 0) {
          $cr_product_id = $get_customer_return->row()->product_id;
          $cr_status = $get_customer_return->row()->status;
        }
        $history_type = 0;
        //End Inventory Logs

        //Order Item
        $shipping_status = 0;
        $purchase_status = 1;
        //End Order Item

        //Customer Data
        $param_customer_data['customer_id'] = $param['customer_id'];
        $get_customer = $this->Model_customer->get_data($param_customer_data);
        if ($get_customer->num_rows() > 0) {
          $cc_credit = ($get_customer->row()->customer_credit == NULL) ? 0 : $get_customer->row()->customer_credit;
          $customer_name = $get_customer->row()->customer_fname;
          $customer_email = $get_customer->row()->customer_email;
        }
        //End Customer Data

        //Order Item Data
        $param_order_item['id'] = $param['order_item_id'];
        $get_order_item = $this->Model_order->get_order_item($param_order_item);
        if ($get_order_item->num_rows() > 0) {
          $cc_amount = $get_order_item->row()->each_price * $param['qty'];
        }

        $updated_cc_credit = 0;
        $cc_type = 0;
        //End Order Item Data

        $apply_update = FALSE;
        if($param['status'] != $cr_status){
          if($cr_status == 2){
            //Inventory Out
            $apply_update = TRUE;
            $history_type = 2;
            $update_qty = $pvd_qty - $param['qty'];
            $update_qty_warehouse = $pvd_qty_warehouse - $param['qty'];
            $updated_cc_credit = ($cc_credit - $cc_amount < 0) ? 0 : $cc_credit - $cc_amount;
            $cc_type = 2;
          }else if($param['status'] == 2){
            //Inventory In
            $apply_update = TRUE;
            $history_type = 1;
            $update_qty = $pvd_qty + $param['qty'];
            $update_qty_warehouse = $pvd_qty_warehouse + $param['qty'];
            $shipping_status = 4;
            $purchase_status = 5;
            $updated_cc_credit = $cc_credit + $cc_amount;
            $cc_type = 1;
          }else{
            $apply_update = FALSE;
          }
        }

        //Send Email
        //Set Text Status
        $text_status = "Dibuat";
        if($param['status'] == 1){
          $text_status = "Diproses";
        }elseif($param['status'] == 2){
          $text_status = "Selesai";
        }elseif($param['status'] == 3){
          $text_status = "Ditolak";
        }
        //End Set Text Status

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
        $this->email->set_newline("\r\n");
        $this->email->set_mailtype("html");
        $this->email->from('do-not-reply@storefrontindo.com', 'Storefront Indonesia'); // change it to yours
        $this->email->to($customer_email); // change it to yours
        $this->email->subject("Status Return Anda ".$text_status);
        $this->email->message(""
          . "Dear <strong>".$customer_name."</strong><br/> <br/>"
          . "Status retur barang anda dengan kode SKU <strong>".$param['SKU']."</strong> saat ini telah <strong>".$text_status."</strong> <br/> <br/>"
          . "Salam <br/> <br/>"
          . "Owner FFStore"
          . "");
        $this->email->send();
        //End Send Email

        $param['apply_update'] = $apply_update;
        $param['product_id'] = $cr_product_id;
        $param['history_type'] = $history_type;
        $param['updated_qty'] = $update_qty;
        $param['updated_qty_warehouse'] = $update_qty_warehouse;
        $param['shipping_status'] = $shipping_status;
        $param['purchase_status'] = $purchase_status;
        $param['updated_cc_credit'] = $updated_cc_credit;
        $param['cc_amount'] = $cc_amount;
        $param['cc_type'] = $cc_type;
        //End Additional Update

        $this->Model_customer_return->edit_data($param);
      }
    }else{
      $validate_post['result'] = "r2";
      $validate_post['result_message'] = "<strong>Data ID</strong> is not found, please refresh your browser!<br/>";
    }

    echo json_encode($validate_post);
  }

  public function remove_data(){
    //post
    $param['id'] = ($this->input->post('id', TRUE)) ? $this->input->post('id', TRUE) : "" ;
    //end post

    if($param['id'] != ""){
      $data['result'] = "r1";
      $this->Model_customer_return->remove_data($param);
    }else{
      $data['result'] = "r2";
      $data['result_message'] = "<strong>Data ID</strong> is not found, please refresh your browser!<br/>";
    }

    echo json_encode($data);
  }

  public function get_SKU(){
    //param
    $param['purchase_code'] = ($this->input->post('purchase_code', TRUE)) ? $this->input->post('purchase_code', TRUE) : "";
    //end param

    if($param['purchase_code'] == ""){
      $data['result'] = "r2";
      $data['result_message'] = "Purchase Code must be filled!";
    }else{
      $result_data = $this->Model_customer_return->get_order_item($param);
      if($result_data->num_rows() > 0){
        $data['result'] = "r1";
        $temp = 0;
        foreach ($result_data->result() as $row) {
          $data['result'] = "r1";
          $data['id'][$temp] = $row->order_item_id;
          $data['products_name'][$temp] = $row->products_name;
          $data['SKU'][$temp] = $row->SKU;

          $data['customer_id'] = $row->customer_id;
          $data['customer_email'] = $row->customer_email;
          $temp++;
        }
        $data['total'] = $temp;
      }else{
        $data['result'] = "r2";
        $data['result_message'] = "No Data";
      }
    }

    echo json_encode($data);
  }

  public function get_order_item(){
    //param
    $param['SKU'] = ($this->input->post('SKU', TRUE)) ? $this->input->post('SKU', TRUE) : "";
    //end param

    $result_data = $this->Model_customer_return->get_order_item($param);
    if($result_data->num_rows() > 0){
      $data['result'] = "r1";
      $data['id'] = $result_data->row()->order_item_id;
      $data['quantity'] = $result_data->row()->quantity;
    }else{
      $data['result'] = "r2";
      $data['result_message'] = "No Data";
    }

    echo json_encode($data);
  }
}
