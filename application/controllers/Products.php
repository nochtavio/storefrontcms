<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends CI_Controller {
  
  function __construct() {
    date_default_timezone_set('Asia/Jakarta');
    parent::__construct();
    $this->load->model('Model_products');
  }
  
  public function index() {
    $page = 'Products';
    $sidebar['page'] = $page;
    $content['js'] = array();
    array_push($content['js'], 'products/function.js');
    array_push($content['js'], 'products/init.js');
    array_push($content['js'], 'products/action.js');
    
    $data['header'] = $this->load->view('header', '', TRUE);
    $data['sidebar'] = $this->load->view('sidebar', $sidebar, TRUE);
    $data['content'] = $this->load->view('products/index', $content, TRUE);
    $this->load->view('template_index', $data);
  }
  
  public function get_data(){
    //param
    $param['id'] = 0;
    $param['name'] = ($this->input->post('name', TRUE)) ? $this->input->post('name', TRUE) : "";
    $param['active'] = ($this->input->post('active', TRUE)) ? $this->input->post('active', TRUE) : 0;
    $param['order'] = ($this->input->post('order', TRUE)) ? $this->input->post('order', TRUE) : -1;
    //end param
    
    //paging
    $get_data = $this->Model_products->get_data($param);
    $page = ($this->input->post('page', TRUE)) ? $this->input->post('page', TRUE) : 1 ;
    $size = ($this->input->post('size', TRUE)) ? $this->input->post('size', TRUE) : 10 ;
    $limit = ($page - 1) * $size;
    //End Set totalpaging

    if ($get_data->num_rows() > 0) {
      $get_data_paging = $this->Model_products->get_data($param, $limit, $size);
      $temp = 0;
      foreach ($get_data_paging->result() as $row) {
        $data['result'] = "r1";
        $data['id'][$temp] = $row->id;
        $data['username'][$temp] = $row->username;
        $data['active'][$temp] = $row->active;
        $data['cretime'][$temp] = date_format(date_create($row->cretime), 'd F Y H:i:s');
        $data['creby'][$temp] = $row->creby;
        $data['modtime'][$temp] = ($row->modtime == NULL) ? NULL : date_format(date_create($row->modtime), 'd F Y H:i:s');
        $data['modby'][$temp] = $row->modby;
        $temp++;
      }
      $data['total'] = $temp;
      $data['size'] = $size;
      $data['totalpage'] = ceil($get_data->num_rows() / $size);
    } else {
      $data['result'] = "r2";
      $data['message'] = "No Admin";
    }
    
    echo json_encode($data);
  }
  
  public function get_specific_data(){
    //param
    $param['id'] = ($this->input->post('id', TRUE)) ? $this->input->post('id', TRUE) : "";
    $param['username'] = "";
    $param['active'] = -1;
    $param['order'] = -1;
    //end param
    
    $result_data = $this->Model_products->get_data($param);
    if($result_data->num_rows() > 0){
      $data['result'] = "r1";
      $data['id'] = $result_data->row()->id;
      $data['username'] = $result_data->row()->username;
      $data['active'] = $result_data->row()->active;
    }else{
      $data['result'] = "r2";
      $data['message'] = "No Data";
    }
    
    echo json_encode($data);
  }
  
  public function validate_post($param, $state = "add", $edit_password = TRUE){
    $data['result'] = "r1";
    $data['result_message'] = "";
    
    if($state == "add"){
      if($param['username'] == ""){
        $data['result'] = "r2";
        $data['result_message'] .= "<strong>Username</strong> must be filled !<br/>";
      }
    }
    
    if($edit_password){
      if($param['password'] == ""){
        $data['result'] = "r2";
        $data['result_message'] .= "<strong>Password</strong> must be filled !<br/>";
      }elseif($param['password'] != $param['conf_password']){
        $data['result'] = "r2";
        $data['result_message'] .= "<strong>Password</strong> and <strong>Confirmation Password</strong> must match !<br/>";
      }
    }
    
    return $data;
  }
  
  public function add_data(){
    //param
    $param['username'] = ($this->input->post('username', TRUE)) ? $this->input->post('username', TRUE) : "" ;
    $param['password'] = ($this->input->post('password', TRUE)) ? $this->input->post('password', TRUE) : "" ;
    $param['conf_password'] = ($this->input->post('conf_password', TRUE)) ? $this->input->post('conf_password', TRUE) : "" ;
    $param['active'] = ($this->input->post('active', TRUE)) ? $this->input->post('active', TRUE) : "" ;
    //end param
    
    $validate_post = $this->validate_post($param, "add", TRUE);
    if($validate_post['result'] == "r1"){
      $this->Model_products->add_data($param);
    }
    
    echo json_encode($validate_post);
  }
  
  public function edit_data(){
    //param
    $param['id'] = ($this->input->post('id', TRUE)) ? $this->input->post('id', TRUE) : "" ;
    $param['username'] = "";
    $param['password'] = ($this->input->post('password', TRUE)) ? $this->input->post('password', TRUE) : "" ;
    $param['conf_password'] = ($this->input->post('conf_password', TRUE)) ? $this->input->post('conf_password', TRUE) : "" ;
    $param['active'] = ($this->input->post('active', TRUE)) ? $this->input->post('active', TRUE) : "" ;
    //end param
    
    //check password is edited or not
    $edit_password = TRUE;
    if($param['password'] == ""){
      $edit_password = FALSE;
    }
    //end check
    
    if($param['id'] != ""){
      $validate_post = $this->validate_post($param, "edit", $edit_password);
      if($validate_post['result'] == "r1"){
        $this->Model_products->edit_data($param);
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
      $this->Model_products->remove_data($param);
    }else{
      $data['result'] = "r2";
      $data['result_message'] = "<strong>Data ID</strong> is not found, please refresh your browser!<br/>";
    }
    
    echo json_encode($data);
  }
}