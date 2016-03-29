<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Shipping extends CI_Controller {
  
  function __construct() {
    date_default_timezone_set('Asia/Jakarta');
    parent::__construct();
    check_address();
    check_login();
    if(!check_menu()){
      redirect(base_url().'dashboard/');
    }
    $this->load->model('Model_shipping');
  }
  
  public function index() {
    $page = 'Shipping';
    $sidebar['page'] = $page;
    $content['js'] = array();
    array_push($content['js'], 'shipping/function.js');
    array_push($content['js'], 'shipping/init.js');
    array_push($content['js'], 'shipping/action.js');
    
    $data['header'] = $this->load->view('header', '', TRUE);
    $data['sidebar'] = $this->load->view('sidebar', $sidebar, TRUE);
    $data['content'] = $this->load->view('shipping/index', $content, TRUE);
    $this->load->view('template_index', $data);
  }
  
  public function get_data(){
    //param
    $param['name'] = ($this->input->post('name', TRUE)) ? $this->input->post('name', TRUE) : "";
    $param['active'] = ($this->input->post('active', TRUE)) ? $this->input->post('active', TRUE) : 0;
    $param['order'] = ($this->input->post('order', TRUE)) ? $this->input->post('order', TRUE) : -1;
    //end param
    
    //paging
    $get_data = $this->Model_shipping->get_data($param);
    $page = ($this->input->post('page', TRUE)) ? $this->input->post('page', TRUE) : 1 ;
    $size = ($this->input->post('size', TRUE)) ? $this->input->post('size', TRUE) : 20 ;
    $limit = ($page - 1) * $size;
    //End Set totalpaging

    if ($get_data->num_rows() > 0) {
      $get_data_paging = $this->Model_shipping->get_data($param, $limit, $size);
      $temp = 0;
      foreach ($get_data_paging->result() as $row) {
        $data['result'] = "r1";
        $data['id'][$temp] = $row->id;
        $data['name'][$temp] = $row->name;
        $data['reguler'][$temp] = number_format($row->reguler);
        $data['oke'][$temp] = number_format($row->oke);
        $data['yes'][$temp] = number_format($row->yes);
        $data['active'][$temp] = $row->active;
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
    
    $result_data = $this->Model_shipping->get_data($param);
    if($result_data->num_rows() > 0){
      $data['result'] = "r1";
      $data['id'] = $result_data->row()->id;
      $data['name'] = $result_data->row()->name;
      $data['reguler'] = $result_data->row()->reguler;
      $data['oke'] = $result_data->row()->oke;
      $data['yes'] = $result_data->row()->yes;
      $data['active'] = $result_data->row()->active;
    }else{
      $data['result'] = "r2";
      $data['message'] = "No Data";
    }
    
    echo json_encode($data);
  }
  
  public function validate_post($param){
    //param
    $reguler = (isset($param['reguler'])) ? $param['reguler'] : "";
    $oke = (isset($param['oke'])) ? $param['oke'] : "";
    $yes = (isset($param['yes'])) ? $param['yes'] : "";
    //end param
    
    $data['result'] = "r1";
    $data['result_message'] = "";
    
    if($reguler == ""){
      $data['result'] = "r2";
      $data['result_message'] .= "<strong>Reguler</strong> price must be filled !<br/>";
    }else if(!is_numeric($reguler)){
      $data['result'] = "r2";
      $data['result_message'] .= "<strong>Reguler</strong> price must be number !<br/>";
    }
    
    if($oke == ""){
      $data['result'] = "r2";
      $data['result_message'] .= "<strong>OKE</strong> price must be filled !<br/>";
    }else if(!is_numeric($oke)){
      $data['result'] = "r2";
      $data['result_message'] .= "<strong>OKE</strong> price must be number !<br/>";
    }
    
    if($yes == ""){
      $data['result'] = "r2";
      $data['result_message'] .= "<strong>YES</strong> price must be filled !<br/>";
    }else if(!is_numeric($yes)){
      $data['result'] = "r2";
      $data['result_message'] .= "<strong>YES</strong> price must be number !<br/>";
    }
    
    return $data;
  }
  
  public function edit_data(){
    //param
    $param['id'] = ($this->input->post('id', TRUE)) ? $this->input->post('id', TRUE) : "" ;
    $param['reguler'] = ($this->input->post('reguler', TRUE)) ? $this->input->post('reguler', TRUE) : "" ;
    $param['oke'] = ($this->input->post('oke', TRUE)) ? $this->input->post('oke', TRUE) : "" ;
    $param['yes'] = ($this->input->post('yes', TRUE)) ? $this->input->post('yes', TRUE) : "" ;
    $param['active'] = ($this->input->post('active', TRUE)) ? $this->input->post('active', TRUE) : "" ;
    //end param
    
    if($param['id'] != ""){
      $validate_post = $this->validate_post($param);
      if($validate_post['result'] == "r1"){
        $this->Model_shipping->edit_data($param);
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
    
    $result_data = $this->Model_shipping->get_data($param);
    if($result_data->num_rows() > 0){
      $param_set['id'] = $result_data->row()->id;
      $param_set['name'] = $result_data->row()->name;
      $param_set['reguler'] = $result_data->row()->reguler;
      $param_set['oke'] = $result_data->row()->oke;
      $param_set['yes'] = $result_data->row()->yes;
      $param_set['active'] = ($result_data->row()->active == 0) ? 1 : 0;
      $this->Model_shipping->edit_data($param_set);
    }else{
      $data['result'] = "r2";
      $data['result_message'] = '<strong>Data ID</strong> is not found, please refresh your browser!<br/>';
    }
    
    echo json_encode($data);
  }
}
