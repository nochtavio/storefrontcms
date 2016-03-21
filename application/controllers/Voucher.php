<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Voucher extends CI_Controller {
  
  function __construct() {
    date_default_timezone_set('Asia/Jakarta');
    parent::__construct();
    check_login();
    if(!check_menu()){
      redirect(base_url().'dashboard/');
    }
    $this->load->model('Model_voucher');
    $this->load->model('Model_category');
    $this->load->model('Model_brand');
  }
  
  public function index() {
    $page = 'Voucher';
    $sidebar['page'] = $page;
    $content['js'] = array();
    array_push($content['js'], 'voucher/function.js');
    array_push($content['js'], 'voucher/init.js');
    array_push($content['js'], 'voucher/action.js');
    
    //Get List Category
    $param['active'] = 1;
    $content['category'] = $this->Model_category->get_data($param, 0, 100)->result();
    $content['brand'] = $this->Model_brand->get_data($param, 0, 100)->result();
    //End Get List Category
    
    $data['header'] = $this->load->view('header', '', TRUE);
    $data['sidebar'] = $this->load->view('sidebar', $sidebar, TRUE);
    $data['content'] = $this->load->view('voucher/index', $content, TRUE);
    $this->load->view('template_index', $data);
  }
  
  public function get_data(){
    //param
    $param['name'] = ($this->input->post('name', TRUE)) ? $this->input->post('name', TRUE) : "";
    $param['code'] = ($this->input->post('code', TRUE)) ? $this->input->post('code', TRUE) : "";
    $param['discount_type'] = ($this->input->post('discount_type', TRUE)) ? $this->input->post('discount_type', TRUE) : 0;
    $param['transaction_type'] = ($this->input->post('transaction_type', TRUE)) ? $this->input->post('transaction_type', TRUE) : 0;
    $param['active'] = ($this->input->post('active', TRUE)) ? $this->input->post('active', TRUE) : 0;
    $param['order'] = ($this->input->post('order', TRUE)) ? $this->input->post('order', TRUE) : -1;
    //end param
    
    //paging
    $get_data = $this->Model_voucher->get_data($param);
    $page = ($this->input->post('page', TRUE)) ? $this->input->post('page', TRUE) : 1 ;
    $size = ($this->input->post('size', TRUE)) ? $this->input->post('size', TRUE) : 10 ;
    $limit = ($page - 1) * $size;
    //End Set totalpaging

    if ($get_data->num_rows() > 0) {
      $get_data_paging = $this->Model_voucher->get_data($param, $limit, $size);
      $temp = 0;
      foreach ($get_data_paging->result() as $row) {
        $data['result'] = "r1";
        $data['id'][$temp] = $row->id;
        $data['name'][$temp] = $row->name;
        $data['code'][$temp] = $row->code;
        $data['discount_type'][$temp] = $row->discount_type;
        $data['transaction_type'][$temp] = $row->transaction_type;
        $data['value'][$temp] = number_format($row->value);
        $data['usage'][$temp] = number_format($row->usage);
        $data['start_date'][$temp] = ($row->start_date == NULL) ? NULL : date_format(date_create($row->start_date), 'd F Y H:i:s');
        $data['end_date'][$temp] = ($row->end_date == NULL) ? NULL : date_format(date_create($row->end_date), 'd F Y H:i:s');
        $data['active'][$temp] = $row->active;
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
      $data['message'] = "No Voucher";
    }
    
    echo json_encode($data);
  }
  
  public function get_specific_data(){
    //param
    $param['id'] = ($this->input->post('id', TRUE)) ? $this->input->post('id', TRUE) : "";
    //end param
    
    $result_data = $this->Model_voucher->get_data($param);
    if($result_data->num_rows() > 0){
      $data['result'] = "r1";
      $data['id'] = $result_data->row()->id;
      $data['name'] = $result_data->row()->name;
      $data['code'] = $result_data->row()->code;
      $data['description'] = $result_data->row()->description;
      $data['discount_type'] = $result_data->row()->discount_type;
      $data['transaction_type'] = $result_data->row()->transaction_type;
      $data['value'] = $result_data->row()->value;
      $data['category'] = ($result_data->row()->category != "") ? array_filter(explode(",", $result_data->row()->category)) : "";
      $data['brand'] = ($result_data->row()->brand != "") ? array_filter(explode(",", $result_data->row()->brand)) : "";
      $data['min_price'] = (is_null($result_data->row()->min_price)) ? "" : $result_data->row()->min_price ;
      $data['start_date'] = (is_null($result_data->row()->start_date)) ? "" : $result_data->row()->start_date ;
      $data['end_date'] = (is_null($result_data->row()->end_date)) ? "" : $result_data->row()->end_date ;
      $data['active'] = $result_data->row()->active;
    }else{
      $data['result'] = "r2";
      $data['message'] = "No Data";
    }
    
    echo json_encode($data);
  }
  
  public function validate_post($param){
    //param
    $name = (isset($param['name'])) ? $param['name'] : "";
    $code = (isset($param['code'])) ? $param['code'] : "";
    $description = (isset($param['description'])) ? $param['description'] : "";
    $value = (isset($param['value'])) ? $param['value'] : "";
    $min_price = (isset($param['min_price'])) ? $param['min_price'] : "";
    //end param
    
    $data['result'] = "r1";
    $data['result_message'] = "";
    
    if($name == ""){
      $data['result'] = "r2";
      $data['result_message'] .= "<strong>Name</strong> must be filled !<br/>";
    }
    
    if($code == ""){
      $data['result'] = "r2";
      $data['result_message'] .= "<strong>Voucher Code</strong> must be filled !<br/>";
    }
    
    if($description == ""){
      $data['result'] = "r2";
      $data['result_message'] .= "<strong>Description</strong> must be filled !<br/>";
    }
    
    if($value == ""){
      $data['result'] = "r2";
      $data['result_message'] .= "<strong>Voucher Value</strong> must be filled !<br/>";
    }else if(!is_numeric($value)){
      $data['result'] = "r2";
      $data['result_message'] .= "<strong>Voucher Value</strong> must be number !<br/>";
    }else if($value <= 0){
      $data['result'] = "r2";
      $data['result_message'] .= "<strong>Voucher Value</strong> must be greater than 0 !<br/>";
    }
    
    if($min_price != ""){
      if(!is_numeric($min_price)){
        $data['result'] = "r2";
        $data['result_message'] .= "<strong>Voucher Minimum Purchase</strong> must be number !<br/>";
      }else if($min_price <= 0){
        $data['result'] = "r2";
        $data['result_message'] .= "<strong>Voucherr Minimum Purchase</strong> must be greater than 0 !<br/>";
      }
    }
    
    return $data;
  }
  
  public function add_data(){
    //param
    $param['name'] = ($this->input->post('name', TRUE)) ? $this->input->post('name', TRUE) : "" ;
    $param['code'] = ($this->input->post('code', TRUE)) ? $this->input->post('code', TRUE) : "" ;
    $param['description'] = ($this->input->post('description', TRUE)) ? $this->input->post('description', TRUE) : "" ;
    $param['discount_type'] = ($this->input->post('discount_type', TRUE)) ? $this->input->post('discount_type', TRUE) : 1 ;
    $param['transaction_type'] = ($this->input->post('transaction_type', TRUE)) ? $this->input->post('transaction_type', TRUE) : 1 ;
    $param['value'] = ($this->input->post('value', TRUE)) ? $this->input->post('value', TRUE) : 0 ;
    $param['category'] = ($this->input->post('category', TRUE)) ? $this->input->post('category', TRUE) : NULL ;
    $param['brand'] = ($this->input->post('brand', TRUE)) ? $this->input->post('brand', TRUE) : NULL ;
    $param['min_price'] = ($this->input->post('min_price', TRUE)) ? $this->input->post('min_price', TRUE) : "" ;
    $param['start_date'] = ($this->input->post('start_date', TRUE)) ? $this->input->post('start_date', TRUE) : "" ;
    $param['end_date'] = ($this->input->post('end_date', TRUE)) ? $this->input->post('end_date', TRUE) : "" ;
    $param['active'] = ($this->input->post('active', TRUE)) ? $this->input->post('active', TRUE) : "" ;
    //end param
    
    $validate_post = $this->validate_post($param);
    if($validate_post['result'] == "r1"){
      $this->Model_voucher->add_data($param);
    }
    
    echo json_encode($validate_post);
  }
  
  public function edit_data(){
    //param
    $param['id'] = ($this->input->post('id', TRUE)) ? $this->input->post('id', TRUE) : "" ;
    $param['name'] = ($this->input->post('name', TRUE)) ? $this->input->post('name', TRUE) : "" ;
    $param['code'] = ($this->input->post('code', TRUE)) ? $this->input->post('code', TRUE) : "" ;
    $param['description'] = ($this->input->post('description', TRUE)) ? $this->input->post('description', TRUE) : "" ;
    $param['discount_type'] = ($this->input->post('discount_type', TRUE)) ? $this->input->post('discount_type', TRUE) : 1 ;
    $param['transaction_type'] = ($this->input->post('transaction_type', TRUE)) ? $this->input->post('transaction_type', TRUE) : 1 ;
    $param['value'] = ($this->input->post('value', TRUE)) ? $this->input->post('value', TRUE) : 0 ;
    $param['category'] = ($this->input->post('category', TRUE)) ? $this->input->post('category', TRUE) : NULL ;
    $param['brand'] = ($this->input->post('brand', TRUE)) ? $this->input->post('brand', TRUE) : NULL ;
    $param['min_price'] = ($this->input->post('min_price', TRUE)) ? $this->input->post('min_price', TRUE) : "" ;
    $param['start_date'] = ($this->input->post('start_date', TRUE)) ? $this->input->post('start_date', TRUE) : "" ;
    $param['end_date'] = ($this->input->post('end_date', TRUE)) ? $this->input->post('end_date', TRUE) : "" ;
    $param['active'] = ($this->input->post('active', TRUE)) ? $this->input->post('active', TRUE) : "" ;
    //end param
    
    if($param['id'] != ""){
      $validate_post = $this->validate_post($param);
      if($validate_post['result'] == "r1"){
        $this->Model_voucher->edit_data($param);
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
    
    $result_data = $this->Model_voucher->get_data($param);
    if($result_data->num_rows() > 0){
      $param_set['id'] = $result_data->row()->id;
      $param_set['name'] = $result_data->row()->name;
      $param_set['code'] = $result_data->row()->code;
      $param_set['description'] = $result_data->row()->description;
      $param_set['discount_type'] = $result_data->row()->discount_type;
      $param_set['transaction_type'] = $result_data->row()->transaction_type;
      $param_set['value'] = $result_data->row()->value;
      $param_set['category'] = ($result_data->row()->category != "") ? array_filter(explode(",", $result_data->row()->category)) : "";
      $param_set['brand'] = ($result_data->row()->brand != "") ? array_filter(explode(",", $result_data->row()->brand)) : "";
      $param_set['min_price'] = (is_null($result_data->row()->min_price)) ? "" : $result_data->row()->min_price ;
      $param_set['start_date'] = (is_null($result_data->row()->start_date)) ? "" : $result_data->row()->start_date ;
      $param_set['end_date'] = (is_null($result_data->row()->end_date)) ? "" : $result_data->row()->end_date ;
      $param_set['active'] = ($result_data->row()->active == 0) ? 1 : 0;
      $this->Model_voucher->edit_data($param_set);
    }else{
      $data['result'] = "r2";
      $data['result_message'] = '<strong>Data ID</strong> is not found, please refresh your browser!<br/>';
    }
    
    echo json_encode($data);
  }
  
  public function remove_data(){
    //post
    $param['id'] = ($this->input->post('id', TRUE)) ? $this->input->post('id', TRUE) : "" ;
    //end post
    
    if($param['id'] != ""){
      $data['result'] = "r1";
      $this->Model_voucher->remove_data($param);
    }else{
      $data['result'] = "r2";
      $data['result_message'] = "<strong>Data ID</strong> is not found, please refresh your browser!<br/>";
    }
    
    echo json_encode($data);
  }
}
