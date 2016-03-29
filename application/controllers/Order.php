<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends CI_Controller {
  
  function __construct() {
    date_default_timezone_set('Asia/Jakarta');
    parent::__construct();
    check_address();
    check_login();
    if(!check_menu()){
      redirect(base_url().'dashboard/');
    }
    $this->load->model('Model_order');
  }
  
  public function index() {
    $page = 'Order';
    $sidebar['page'] = $page;
    $content['js'] = array();
    array_push($content['js'], 'order/function.js');
    array_push($content['js'], 'order/init.js');
    array_push($content['js'], 'order/action.js');
    
    $data['header'] = $this->load->view('header', '', TRUE);
    $data['sidebar'] = $this->load->view('sidebar', $sidebar, TRUE);
    $data['content'] = $this->load->view('order/index', $content, TRUE);
    $this->load->view('template_index', $data);
  }
  
  public function get_data(){
    //param
    $param['purchase_code'] = ($this->input->post('purchase_code', TRUE)) ? $this->input->post('purchase_code', TRUE) : "";
    $param['customer_email'] = ($this->input->post('customer_email', TRUE)) ? $this->input->post('customer_email', TRUE) : "";
    $param['status_payment'] = ($this->input->post('status_payment', TRUE)) ? $this->input->post('status_payment', TRUE) : -1;
    $param['status'] = ($this->input->post('status', TRUE)) ? $this->input->post('status', TRUE) : 0;
    $param['order'] = ($this->input->post('order', TRUE)) ? $this->input->post('order', TRUE) : -1;
    //end param
    
    //paging
    $get_data = $this->Model_order->get_data($param);
    $page = ($this->input->post('page', TRUE)) ? $this->input->post('page', TRUE) : 1 ;
    $size = ($this->input->post('size', TRUE)) ? $this->input->post('size', TRUE) : 10 ;
    $limit = ($page - 1) * $size;
    //End Set totalpaging

    if ($get_data->num_rows() > 0) {
      $get_data_paging = $this->Model_order->get_data($param, $limit, $size);
      $temp = 0;
      foreach ($get_data_paging->result() as $row) {
        $data['result'] = "r1";
        $data['id'][$temp] = $row->order_id;
        $data['purchase_code'][$temp] = $row->purchase_code;
        $data['customer_email'][$temp] = $row->customer_email;
        $data['payment_name'][$temp] = $row->payment_name;
        $data['confirm_transfer_by'][$temp] = ($row->confirm_transfer_by == NULL) ? "" : $row->confirm_transfer_by;
        $data['confirm_transfer_bank'][$temp] = ($row->confirm_transfer_bank == NULL) ? "" : $row->confirm_transfer_bank;
        $data['confirm_transfer_amount'][$temp] = ($row->confirm_transfer_amount == NULL) ? "" : number_format($row->confirm_transfer_amount);
        $data['status'][$temp] = $row->status ;
        $data['purchase_date'][$temp] = date_format(date_create($row->purchase_date), 'd F Y H:i:s');
        $data['updated_by'][$temp] = ($row->updated_by == NULL) ? "" : $row->updated_by;
        $temp++;
      }
      $data['allowed_edit'] = check_menu("", 2);
      $data['total'] = $temp;
      $data['size'] = $size;
      $data['totalpage'] = ceil($get_data->num_rows() / $size);
    } else {
      $data['result'] = "r2";
      $data['message'] = "No Order";
    }
    
    echo json_encode($data);
  }
  
  public function get_order_item(){
    //param
    $param['purchase_code'] = ($this->input->post('purchase_code', TRUE)) ? $this->input->post('purchase_code', TRUE) : "";
    //end param
    
    $get_data = $this->Model_order->get_order_item($param);
    if ($get_data->num_rows() > 0) {
      $temp = 0;
      $subtotal = 0;
      foreach ($get_data->result() as $row) {
        $data['result'] = "r1";
        $data['id'][$temp] = $row->order_item_id;
        $data['product_name'][$temp] = $row->product_name;
        $data['SKU'][$temp] = $row->SKU;
        $data['quantity'][$temp] = number_format($row->quantity);
        $data['each_price'][$temp] = number_format($row->each_price);
        $data['total_price'][$temp] = number_format($row->total_price);
        
        $data['shipping_cost_before_format'] = ($row->shipping_cost == NULL) ? 0 : $row->shipping_cost;
        $data['paycode_before_format'] = ($row->paycode == NULL) ? 0 : $row->paycode;
        $data['discount_before_format'] = ($row->discount == NULL) ? 0 : $row->discount;
        $data['credit_use_before_format'] = ($row->credit_use == NULL) ? 0 : $row->credit_use;
        
        $data['shipping_cost'] = number_format($data['shipping_cost_before_format']);
        $data['paycode'] = number_format($data['paycode_before_format']);
        $data['discount'] = number_format($data['discount_before_format']);
        $data['credit_use'] = number_format($data['credit_use_before_format']);
        
        $subtotal = $subtotal + $row->total_price;
        $temp++;
      }
      $data['subtotal'] = number_format($subtotal);
      $data['grandtotal'] = number_format($subtotal+$data['paycode_before_format']+$data['shipping_cost_before_format']-$data['discount_before_format']-$data['credit_use_before_format']);
      $data['total'] = $temp;
    } else {
      $data['result'] = "r2";
      $data['message'] = "No Order";
    }
    
    echo json_encode($data);
  }
  
  public function get_specific_data(){
    //param
    $param['purchase_code'] = ($this->input->post('purchase_code', TRUE)) ? $this->input->post('purchase_code', TRUE) : "";
    //end param
    
    $result_data = $this->Model_order->get_data($param);
    if($result_data->num_rows() > 0){
      $data['result'] = "r1";
      $data['id'] = $result_data->row()->order_id;
      $data['purchase_code'] = $result_data->row()->purchase_code;
      $data['status'] = $result_data->row()->status;
    }else{
      $data['result'] = "r2";
      $data['message'] = "No Data";
    }
    
    echo json_encode($data);
  }
  
  public function validate_post($param){
    //param
    
    //end param
    
    $data['result'] = "r1";
    $data['result_message'] = "";
    
    return $data;
  }
  
  public function edit_data(){
    //param
    $param['purchase_code'] = ($this->input->post('purchase_code', TRUE)) ? $this->input->post('purchase_code', TRUE) : "" ;
    $param['status'] = ($this->input->post('status', TRUE)) ? $this->input->post('status', TRUE) : "" ;
    //end param
    
    if($param['purchase_code'] != ""){
      $validate_post = $this->validate_post($param);
      if($validate_post['result'] == "r1"){
        $this->Model_order->edit_data($param);
      }
    }else{
      $validate_post['result'] = "r2";
      $validate_post['result_message'] = "<strong>Data ID</strong> is not found, please refresh your browser!<br/>";
    }
    
    echo json_encode($validate_post);
  }
}
