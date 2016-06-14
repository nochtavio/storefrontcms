<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Realtime extends CI_Controller {
  
  function __construct() {
    date_default_timezone_set('Asia/Jakarta');
    parent::__construct();
    check_address();
    check_login();
  }
  
  public function get_unread_order(){
    $this->load->model('Model_order');
    
    $param['read'] = 0;
    $unread_data = $this->Model_order->get_data($param)->num_rows();
    
    $data['result'] = ($unread_data > 0) ? 'r1' : 'r2' ;
    $data['unread_data'] = $unread_data;
    
    echo json_encode($data);
  }
  
  public function get_unread_credit_log(){
    $this->load->model('Model_credit_log');
    
    $param['type'] = 1;
    $param['status'] = 0;
    $param['read'] = 0;
    $unread_data = $this->Model_credit_log->get_data($param)->num_rows();
    
    $data['result'] = ($unread_data > 0) ? 'r1' : 'r2' ;
    $data['unread_data'] = $unread_data;
    
    echo json_encode($data);
  }
  
  public function get_unread_customer_return(){
    $this->load->model('Model_customer_return');
    
    $param['status'] = 0;
    $param['read'] = 0;
    $unread_data = $this->Model_customer_return->get_data($param)->num_rows();
    
    $data['result'] = ($unread_data > 0) ? 'r1' : 'r2' ;
    $data['unread_data'] = $unread_data;
    
    echo json_encode($data);
  }
  
  public function get_unread_reseller_request(){
    $this->load->model('Model_reseller_request');
    
    $param['status'] = 0;
    $param['read'] = 0;
    $unread_data = $this->Model_reseller_request->get_data($param)->num_rows();
    
    $data['result'] = ($unread_data > 0) ? 'r1' : 'r2' ;
    $data['unread_data'] = $unread_data;
    
    echo json_encode($data);
  }
}
