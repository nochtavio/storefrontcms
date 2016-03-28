<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Customer extends CI_Controller {
  
  function __construct() {
    date_default_timezone_set('Asia/Jakarta');
    parent::__construct();
    check_login();
    if(!check_menu()){
      redirect(base_url().'dashboard/');
    }
    $this->load->model('Model_customer');
  }
  
  public function index() {
    $page = 'Customer';
    $sidebar['page'] = $page;
    $content['js'] = array();
    array_push($content['js'], 'customer/function.js');
    array_push($content['js'], 'customer/init.js');
    array_push($content['js'], 'customer/action.js');
    
    $data['header'] = $this->load->view('header', '', TRUE);
    $data['sidebar'] = $this->load->view('sidebar', $sidebar, TRUE);
    $data['content'] = $this->load->view('customer/index', $content, TRUE);
    $this->load->view('template_index', $data);
  }
  
  public function get_data(){
    //param
    $param['customer_email'] = ($this->input->post('customer_email', TRUE)) ? $this->input->post('customer_email', TRUE) : "";
    $param['name'] = ($this->input->post('name', TRUE)) ? $this->input->post('name', TRUE) : "";
    $param['customer_gender'] = ($this->input->post('customer_gender', TRUE)) ? $this->input->post('customer_gender', TRUE) : -1;
    $param['customer_province'] = ($this->input->post('customer_province', TRUE)) ? $this->input->post('customer_province', TRUE) : "";
    $param['customer_city'] = ($this->input->post('customer_city', TRUE)) ? $this->input->post('customer_city', TRUE) : "";
    $param['customer_status'] = ($this->input->post('customer_status', TRUE)) ? $this->input->post('customer_status', TRUE) : 0;
    $param['order'] = ($this->input->post('order', TRUE)) ? $this->input->post('order', TRUE) : -1;
    //end param
    
    //paging
    $get_data = $this->Model_customer->get_data($param);
    $page = ($this->input->post('page', TRUE)) ? $this->input->post('page', TRUE) : 1 ;
    $size = ($this->input->post('size', TRUE)) ? $this->input->post('size', TRUE) : 10 ;
    $limit = ($page - 1) * $size;
    //End Set totalpaging

    if ($get_data->num_rows() > 0) {
      $get_data_paging = $this->Model_customer->get_data($param, $limit, $size);
      $temp = 0;
      foreach ($get_data_paging->result() as $row) {
        $data['result'] = "r1";
        $data['customer_id'][$temp] = $row->customer_id;
        $data['customer_email'][$temp] = $row->customer_email;
        $data['name'][$temp] = $row->customer_fname." ".$row->customer_lname;
        $data['customer_province'][$temp] = $row->customer_province;
        $data['customer_city'][$temp] = $row->customer_city;
        $data['customer_status'][$temp] = $row->customer_status;
        $data['customer_registration_date'][$temp] = date_format(date_create($row->customer_registration_date), 'd F Y H:i:s');
        $data['last_modified'][$temp] = ($row->last_modified == NULL) ? NULL : date_format(date_create($row->last_modified), 'd F Y H:i:s');
        $temp++;
      }
      $data['allowed_edit'] = check_menu("", 2);
      $data['total'] = $temp;
      $data['size'] = $size;
      $data['totalpage'] = ceil($get_data->num_rows() / $size);
    } else {
      $data['result'] = "r2";
      $data['message'] = "No Customer";
    }
    
    echo json_encode($data);
  }
  
  public function get_specific_data(){
    //param
    $param['customer_id'] = ($this->input->post('customer_id', TRUE)) ? $this->input->post('customer_id', TRUE) : "";
    //end param
    
    $result_data = $this->Model_customer->get_data($param);
    if($result_data->num_rows() > 0){
      $data['result'] = "r1";
      $data['customer_id'] = $result_data->row()->customer_id;
      $data['customer_email'] = $result_data->row()->customer_email;
      $data['customer_fname'] = $result_data->row()->customer_fname;
      $data['customer_lname'] = $result_data->row()->customer_lname;
      $data['customer_street'] = $result_data->row()->customer_street;
      $data['customer_province'] = $result_data->row()->customer_province;
      $data['customer_city'] = $result_data->row()->customer_city;
      $data['customer_zipcode'] = $result_data->row()->customer_zipcode;
      $data['customer_phone'] = $result_data->row()->customer_phone;
      $data['customer_status'] = $result_data->row()->customer_status;
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
    $param['customer_id'] = ($this->input->post('customer_id', TRUE)) ? $this->input->post('customer_id', TRUE) : "" ;
    $param['customer_status'] = ($this->input->post('customer_status', TRUE)) ? $this->input->post('customer_status', TRUE) : "" ;
    //end param
    
    if($param['customer_id'] != ""){
      $validate_post = $this->validate_post($param);
      if($validate_post['result'] == "r1"){
        $this->Model_customer->edit_data($param);
      }
    }else{
      $validate_post['result'] = "r2";
      $validate_post['result_message'] = "<strong>Data ID</strong> is not found, please refresh your browser!<br/>";
    }
    
    echo json_encode($validate_post);
  }
  
  public function set_active(){
    //param
    $param['id'] = ($this->input->post('id', TRUE)) ? $this->input->post('id', TRUE) : "" ;
    //end param
    
    $data['result'] = "r1";
    $data['result_message'] = '';
    
    $result_data = $this->Model_customer->get_data($param);
    if($result_data->num_rows() > 0){
      $param_set['customer_id'] = $result_data->row()->customer_id;
      $param_set['customer_status'] = ($result_data->row()->customer_status == 1) ? 2 : 1;
      $this->Model_customer->edit_data($param_set);
    }else{
      $data['result'] = "r2";
      $data['result_message'] = '<strong>Data ID</strong> is not found, please refresh your browser!<br/>';
    }
    
    echo json_encode($data);
  }
}
