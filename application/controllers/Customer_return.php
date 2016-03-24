<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_return extends CI_Controller {
  
  function __construct() {
    date_default_timezone_set('Asia/Jakarta');
    parent::__construct();
    check_login();
    if(!check_menu()){
      redirect(base_url().'dashboard/');
    }
    $this->load->model('Model_customer_return');
  }
  
  public function index() {
    $page = 'Customer_return';
    $sidebar['page'] = $page;
    $content['js'] = array();
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
  
  public function validate_post($param){
    //param
    $purchase_code = (isset($param['purchase_code'])) ? $param['purchase_code'] : "";
    $order_item_id = (isset($param['order_item_id'])) ? $param['order_item_id'] : "";
    $qty = (isset($param['qty'])) ? $param['qty'] : "";
    $reason = (isset($param['reason'])) ? $param['reason'] : "";
    //end param
    
    $data['result'] = "r1";
    $data['result_message'] = "";
    
    if($purchase_code == ""){
      $data['result'] = "r2";
      $data['result_message'] .= "<strong>Purchase Code</strong> must be filled !<br/>";
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
    
    $validate_post = $this->validate_post($param);
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
      $validate_post = $this->validate_post($param);
      if($validate_post['result'] == "r1"){
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
