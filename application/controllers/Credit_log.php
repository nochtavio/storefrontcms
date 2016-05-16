<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Credit_log extends CI_Controller {
  
  function __construct() {
    date_default_timezone_set('Asia/Jakarta');
    parent::__construct();
    check_address();
    check_login();
    if(!check_menu()){
      redirect(base_url().'dashboard/');
    }
    $this->load->model('Model_credit_log');
    $this->load->model('Model_customer');
  }
  
  public function index() {
    $page = 'Credit_log';
    $sidebar['page'] = $page;
    $content['js'] = array();
    array_push($content['js'], 'credit_log/function.js');
    array_push($content['js'], 'credit_log/init.js');
    array_push($content['js'], 'credit_log/action.js');
    
    $data['header'] = $this->load->view('header', '', TRUE);
    $data['sidebar'] = $this->load->view('sidebar', $sidebar, TRUE);
    $data['content'] = $this->load->view('credit_log/index', $content, TRUE);
    $this->load->view('template_index', $data);
  }
  
  public function get_data(){
    //param
    $param['email'] = ($this->input->post('email', TRUE)) ? $this->input->post('email', TRUE) : "";
    $param['type'] = ($this->input->post('type', TRUE)) ? $this->input->post('type', TRUE) : 0;
    $param['credit_log_type'] = ($this->input->post('credit_log_type', TRUE)) ? $this->input->post('credit_log_type', TRUE) : 0;
    $param['status'] = ($this->input->post('status', TRUE)) ? $this->input->post('status', TRUE) : 0;
    $param['order'] = ($this->input->post('order', TRUE)) ? $this->input->post('order', TRUE) : -1;
    //end param
    
    //paging
    $get_data = $this->Model_credit_log->get_data($param);
    $page = ($this->input->post('page', TRUE)) ? $this->input->post('page', TRUE) : 1 ;
    $size = ($this->input->post('size', TRUE)) ? $this->input->post('size', TRUE) : 20 ;
    $limit = ($page - 1) * $size;
    //End Set totalpaging

    if ($get_data->num_rows() > 0) {
      $get_data_paging = $this->Model_credit_log->get_data($param, $limit, $size);
      $temp = 0;
      foreach ($get_data_paging->result() as $row) {
        $data['result'] = "r1";
        $data['id'][$temp] = $row->id;
        $data['email'][$temp] = ($row->customer_email != NULL) ? $row->customer_email : $row->email ;
        $data['credit_log_type'][$temp] = ($row->id_customer == 1) ? 'Customer' : 'Reseller' ;
        $data['amount'][$temp] = number_format($row->amount);
        $data['type'][$temp] = $row->type;
        $data['description'][$temp] = $row->description;
        $data['payment_method'][$temp] = $row->payment_method;
        $data['status'][$temp] = $row->status;
        $data['cretime'][$temp] = date_format(date_create($row->cretime), 'd F Y H:i:s');
        $data['modtime'][$temp] = ($row->modtime == NULL) ? NULL : date_format(date_create($row->modtime), 'd F Y H:i:s');
        $data['modby'][$temp] = $row->modby;
        $temp++;
      }
      $data['allowed_edit'] = check_menu("", 2);
      $data['total'] = $temp;
      $data['size'] = $size;
      $data['totalpage'] = ceil($get_data->num_rows() / $size);
    } else {
      $data['result'] = "r2";
      $data['message'] = "No Credit_log";
    }
    
    echo json_encode($data);
  }
  
  public function get_specific_data(){
    //param
    $param['id'] = ($this->input->post('id', TRUE)) ? $this->input->post('id', TRUE) : "";
    //end param
    
    $result_data = $this->Model_credit_log->get_data($param);
    if($result_data->num_rows() > 0){
      $data['result'] = "r1";
      $data['id'] = $result_data->row()->id;
      $data['id_customer'] = $result_data->row()->id_customer;
      $data['customer_email'] = $result_data->row()->customer_email;
      $data['amount'] = $result_data->row()->amount;
      $data['type'] = $result_data->row()->type;
      $data['type'] = $result_data->row()->type;
      $data['description'] = $result_data->row()->description;
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
    $param['id'] = ($this->input->post('id', TRUE)) ? $this->input->post('id', TRUE) : "" ;
    $param['id_customer'] = ($this->input->post('id_customer', TRUE)) ? $this->input->post('id_customer', TRUE) : "" ;
    $param['amount'] = ($this->input->post('amount', TRUE)) ? $this->input->post('amount', TRUE) : 0 ;
    $param['status'] = ($this->input->post('status', TRUE)) ? $this->input->post('status', TRUE) : "0" ;
    //end param
    
    if($param['id'] != ""){
      $validate_post = $this->validate_post($param);
      if($validate_post['result'] == "r1"){
        //Get Credit Log
        $param_credit_log['id'] = $param['id'];
        $get_credit_log = $this->Model_credit_log->get_data($param_credit_log);
        if($get_credit_log->num_rows() > 0){
          $status = $get_credit_log->row()->status;
        }
        //End Get Credit log
        
        //Get Customer Credit
        $param_customer_credit['customer_id'] = $param['id_customer'];
        $get_customer_credit = $this->Model_customer->get_data($param_customer_credit);
        if($get_customer_credit->num_rows() > 0){
          $customer_credit = $get_customer_credit->row()->customer_credit;
        }
        //End Get Customer Credit
        
        if($param['status'] != $status && $param['status'] != ""){
          if($param['status'] == 1){
            $param['updated_credit'] = $customer_credit + $param['amount'];
          }else{
            $param['updated_credit'] = $customer_credit - $param['amount'];
          }
        }
        
        $this->Model_credit_log->edit_data($param);
      }
    }else{
      $validate_post['result'] = "r2";
      $validate_post['result_message'] = "<strong>Data ID</strong> is not found, please refresh your browser!<br/>";
    }
    
    echo json_encode($validate_post);
  }
}
