<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Category_child_ extends CI_Controller {
  
  function __construct() {
    date_default_timezone_set('Asia/Jakarta');
    parent::__construct();
    $this->load->model('Model_category');
    $this->load->model('Model_category_child');
    $this->load->model('Model_category_child_');
  }
  
  public function validate_index(){
    if(!$this->input->get('id_category', TRUE) || !is_numeric($this->input->get('id_category', TRUE)) || !$this->input->get('parent', TRUE) || !is_numeric($this->input->get('parent', TRUE))){
      redirect('/category/');die();
    }
  }
  
  public function get_parent_name($parent){
    $param['id'] = $parent;
    $result_data = $this->Model_category_child->get_data($param);
    if($result_data->num_rows() > 0){
      return $result_data->row()->name;
    }else{
      return false;
    }
  }
  
  public function index() {
    $this->validate_index();
    
    $page = 'Category';
    $sidebar['page'] = $page;
    $content['js'] = array();
    array_push($content['js'], 'category_child_/function.js');
    array_push($content['js'], 'category_child_/init.js');
    array_push($content['js'], 'category_child_/action.js');
    $content['id_category'] = $this->input->get('id_category', TRUE);
    $content['parent'] = $this->input->get('parent', TRUE);
    $content['parent_name'] = $this->get_parent_name($content['parent']);
    if(!$content['parent_name']){
      redirect('/category/');die();
    }
    
    $data['header'] = $this->load->view('header', '', TRUE);
    $data['sidebar'] = $this->load->view('sidebar', $sidebar, TRUE);
    $data['content'] = $this->load->view('category_child_/index', $content, TRUE);
    $this->load->view('template_index', $data);
  }
  
  public function get_data(){
    //param
    $param['parent'] = ($this->input->post('parent', TRUE)) ? $this->input->post('parent', TRUE) : 0;
    $param['name'] = ($this->input->post('name', TRUE)) ? $this->input->post('name', TRUE) : "";
    $param['active'] = ($this->input->post('active', TRUE)) ? $this->input->post('active', TRUE) : 0;
    $param['order'] = ($this->input->post('order', TRUE)) ? $this->input->post('order', TRUE) : -1;
    //end param
    
    //paging
    $get_data = $this->Model_category_child_->get_data($param);
    $page = ($this->input->post('page', TRUE)) ? $this->input->post('page', TRUE) : 1 ;
    $size = ($this->input->post('size', TRUE)) ? $this->input->post('size', TRUE) : 10 ;
    $limit = ($page - 1) * $size;
    //End Set totalpaging

    if ($get_data->num_rows() > 0) {
      $get_data_paging = $this->Model_category_child_->get_data($param, $limit, $size);
      $temp = 0;
      foreach ($get_data_paging->result() as $row) {
        $data['result'] = "r1";
        $data['id'][$temp] = $row->id;
        $data['name'][$temp] = $row->name;
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
      $data['message'] = "No Categories";
    }
    
    echo json_encode($data);
  }
  
  public function get_specific_data(){
    //param
    $param['id'] = ($this->input->post('id', TRUE)) ? $this->input->post('id', TRUE) : "";
    //end param
    
    $result_data = $this->Model_category_child_->get_data($param);
    if($result_data->num_rows() > 0){
      $data['result'] = "r1";
      $data['id'] = $result_data->row()->id;
      $data['name'] = $result_data->row()->name;
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
    //end param
    
    $data['result'] = "r1";
    $data['result_message'] = "";
    
    if($name == ""){
      $data['result'] = "r2";
      $data['result_message'] .= "<strong>Name</strong> must be filled !<br/>";
    }
    
    return $data;
  }
  
  public function add_data(){
    //param
    $param['id_category'] = ($this->input->post('id_category', TRUE)) ? $this->input->post('id_category', TRUE) : 0;
    $param['parent'] = ($this->input->post('parent', TRUE)) ? $this->input->post('parent', TRUE) : 0;
    $param['name'] = ($this->input->post('name', TRUE)) ? $this->input->post('name', TRUE) : "" ;
    $param['active'] = ($this->input->post('active', TRUE)) ? $this->input->post('active', TRUE) : "" ;
    //end param
    
    $validate_post = $this->validate_post($param);
    if($validate_post['result'] == "r1"){
      $this->Model_category_child_->add_data($param);
    }
    
    echo json_encode($validate_post);
  }
  
  public function edit_data(){
    //param
    $param['id'] = ($this->input->post('id', TRUE)) ? $this->input->post('id', TRUE) : "" ;
    $param['name'] = ($this->input->post('name', TRUE)) ? $this->input->post('name', TRUE) : "" ;
    $param['active'] = ($this->input->post('active', TRUE)) ? $this->input->post('active', TRUE) : "" ;
    //end param
    
    if($param['id'] != ""){
      $validate_post = $this->validate_post($param);
      if($validate_post['result'] == "r1"){
        $this->Model_category_child_->edit_data($param);
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
    
    $result_data = $this->Model_category_child_->get_data($param);
    if($result_data->num_rows() > 0){
      $param_set['id'] = $result_data->row()->id;
      $param_set['name'] = $result_data->row()->name;
      $param_set['active'] = ($result_data->row()->active == 0) ? 1 : 0;
      $this->Model_category_child_->edit_data($param_set);
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
      $this->Model_category_child_->remove_data($param);
    }else{
      $data['result'] = "r2";
      $data['result_message'] = "<strong>Data ID</strong> is not found, please refresh your browser!<br/>";
    }
    
    echo json_encode($data);
  }
}
