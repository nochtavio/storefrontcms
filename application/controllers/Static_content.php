<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Static_content extends CI_Controller {
  
  function __construct() {
    date_default_timezone_set('Asia/Jakarta');
    parent::__construct();
    check_login();
    if(!check_menu()){
      redirect(base_url().'dashboard/');
    }
    $this->load->model('Model_static_content');
  }
  
  public function index() {
    $page = 'Static_content';
    $sidebar['page'] = $page;
    $content['js'] = array();
    array_push($content['js'], 'static_content/function.js');
    array_push($content['js'], 'static_content/init.js');
    array_push($content['js'], 'static_content/action.js');
    
    $data['header'] = $this->load->view('header', '', TRUE);
    $data['sidebar'] = $this->load->view('sidebar', $sidebar, TRUE);
    $data['content'] = $this->load->view('static_content/index', $content, TRUE);
    $this->load->view('template_index', $data);
  }
  
  public function get_type_name($type){
    $type_name = "About Us";
    if($type == 1){
      $type_name = "Contact Us";
    }else if($type == 2){
      $type_name = "Terms and Condition";
    }else if($type == 3){
      $type_name = "Privacy Policy";
    }
    
    return $type_name;
  }
  
  public function get_data(){
    //param
    $param['type'] = ($this->input->post('type', TRUE)) ? $this->input->post('type', TRUE) : 0;
    //end param
    
    //paging
    $get_data = $this->Model_static_content->get_data($param);
    $page = ($this->input->post('page', TRUE)) ? $this->input->post('page', TRUE) : 1 ;
    $size = ($this->input->post('size', TRUE)) ? $this->input->post('size', TRUE) : 10 ;
    $limit = ($page - 1) * $size;
    //End Set totalpaging

    if ($get_data->num_rows() > 0) {
      $get_data_paging = $this->Model_static_content->get_data($param, $limit, $size);
      $temp = 0;
      foreach ($get_data_paging->result() as $row) {
        $data['result'] = "r1";
        $data['id'][$temp] = $row->id;
        $data['type'][$temp] = $row->type;
        $data['type_name'][$temp] = $this->get_type_name($row->type);
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
      $data['message'] = "No Data";
    }
    
    echo json_encode($data);
  }
  
  public function get_specific_data(){
    //param
    $param['id'] = ($this->input->post('id', TRUE)) ? $this->input->post('id', TRUE) : "";
    //end param
    
    $result_data = $this->Model_static_content->get_data($param);
    if($result_data->num_rows() > 0){
      $data['result'] = "r1";
      $data['id'] = $result_data->row()->id;
      $data['type'] = $result_data->row()->type;
      $data['type_name'] = $this->get_type_name($result_data->row()->type);
      $data['content'] = $result_data->row()->content;
    }else{
      $data['result'] = "r2";
      $data['message'] = "No Data";
    }
    
    echo json_encode($data);
  }
  
  public function validate_post($param){
    //param
    $content = (isset($param['content'])) ? $param['content'] : "";
    //end param
    
    $data['result'] = "r1";
    $data['result_message'] = "";
    
    if($content == ""){
      $data['result'] = "r2";
      $data['result_message'] .= "<strong>Content</strong> must be filled !<br/>";
    }
    
    return $data;
  }
  
  public function edit_data(){
    //param
    $param['id'] = ($this->input->post('id', TRUE)) ? $this->input->post('id', TRUE) : "" ;
    $param['content'] = ($this->input->post('content', FALSE)) ? $this->input->post('content', FALSE) : "" ;
    //end param
    
    if($param['id'] != ""){
      $validate_post = $this->validate_post($param);
      if($validate_post['result'] == "r1"){
        $this->Model_static_content->edit_data($param);
      }
    }else{
      $validate_post['result'] = "r2";
      $validate_post['result_message'] = "<strong>Data ID</strong> is not found, please refresh your browser!<br/>";
    }
    
    echo json_encode($validate_post);
  }
}
