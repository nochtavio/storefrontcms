<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Payment extends CI_Controller {
  
  function __construct() {
    date_default_timezone_set('Asia/Jakarta');
    parent::__construct();
    check_login();
    if(!check_menu()){
      redirect(base_url().'dashboard/');
    }
    $this->load->model('Model_payment');
    $this->load->model('Model_category');
    $this->load->library('image_lib');
  }
  
  public function resize_image($source){
    $config_resize['image_library'] = 'gd2';
    $config_resize['source_image'] = $source;
    $config_resize['create_thumb'] = FALSE;
    $config_resize['maintain_ratio'] = FALSE;
    $config_resize['width']         = 100;
    $config_resize['height']       = 100;

    $this->image_lib->initialize($config_resize);
    $this->image_lib->resize();
  }
  
  public function index() {
    $page = 'Payment';
    $sidebar['page'] = $page;
    $content['js'] = array();
    array_push($content['js'], 'payment/function.js');
    array_push($content['js'], 'payment/init.js');
    array_push($content['js'], 'payment/action.js');
    
    $data['header'] = $this->load->view('header', '', TRUE);
    $data['sidebar'] = $this->load->view('sidebar', $sidebar, TRUE);
    $data['content'] = $this->load->view('payment/index', $content, TRUE);
    $this->load->view('template_index', $data);
  }
  
  public function get_data(){
    //param
    $param['name'] = ($this->input->post('name', TRUE)) ? $this->input->post('name', TRUE) : "";
    $param['type'] = ($this->input->post('type', TRUE)) ? $this->input->post('type', TRUE) : 0;
    $param['active'] = ($this->input->post('active', TRUE)) ? $this->input->post('active', TRUE) : 0;
    $param['order'] = ($this->input->post('order', TRUE)) ? $this->input->post('order', TRUE) : -1;
    //end param
    
    //paging
    $get_data = $this->Model_payment->get_data($param);
    $page = ($this->input->post('page', TRUE)) ? $this->input->post('page', TRUE) : 1 ;
    $size = ($this->input->post('size', TRUE)) ? $this->input->post('size', TRUE) : 10 ;
    $limit = ($page - 1) * $size;
    //End Set totalpaging

    if ($get_data->num_rows() > 0) {
      $get_data_paging = $this->Model_payment->get_data($param, $limit, $size);
      $temp = 0;
      foreach ($get_data_paging->result() as $row) {
        $data['result'] = "r1";
        $data['id'][$temp] = $row->id;
        $data['name'][$temp] = $row->name;
        $data['logo'][$temp] = $row->logo;
        $data['type'][$temp] = $row->type;
        $data['show_order'][$temp] = $row->show_order;
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
      $data['message'] = "No Payments";
    }
    
    echo json_encode($data);
  }
  
  public function get_specific_data(){
    //param
    $param['id'] = ($this->input->post('id', TRUE)) ? $this->input->post('id', TRUE) : "";
    //end param
    
    $result_data = $this->Model_payment->get_data($param);
    if($result_data->num_rows() > 0){
      $data['result'] = "r1";
      $data['id'] = $result_data->row()->id;
      $data['name'] = $result_data->row()->name;
      $data['description'] = $result_data->row()->description;
      $data['logo'] = $result_data->row()->logo;
      $data['type'] = $result_data->row()->type;
      $data['minimum_grand_total'] = (is_null($result_data->row()->minimum_grand_total)) ? "" : $result_data->row()->minimum_grand_total ;
      $data['show_order'] = $result_data->row()->show_order;
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
    $description = (isset($param['description'])) ? $param['description'] : "";
    $minimum_grand_total = (isset($param['minimum_grand_total'])) ? $param['minimum_grand_total'] : "";
    $show_order = (isset($param['show_order'])) ? $param['show_order'] : 0;
    //end param
    
    $data['result'] = "r1";
    $data['result_message'] = "";
    
    if($name == ""){
      $data['result'] = "r2";
      $data['result_message'] .= "<strong>Name</strong> must be filled !<br/>";
    }
    
    if($description == ""){
      $data['result'] = "r2";
      $data['result_message'] .= "<strong>Description</strong> must be filled !<br/>";
    }
    
    if($minimum_grand_total != "" && !is_numeric($minimum_grand_total)){
      $data['result'] = "r2";
      $data['result_message'] .= "<strong>Minimum Grand Total</strong> must be filled !<br/>";
    }
    
    if(!is_numeric($show_order)){
      $data['result'] = "r2";
      $data['result_message'] .= "<strong>Show Order</strong> must be filled !<br/>";
    }
    
    return $data;
  }
  
  public function add_data(){
    //param
    $param['name'] = ($this->input->post('name', TRUE)) ? $this->input->post('name', TRUE) : "" ;
    $param['description'] = ($this->input->post('description', FALSE)) ? $this->input->post('description', FALSE) : "" ;
    $param['type'] = ($this->input->post('type', TRUE)) ? $this->input->post('type', TRUE) : "" ;
    $param['minimum_grand_total'] = ($this->input->post('minimum_grand_total', TRUE)) ? $this->input->post('minimum_grand_total', TRUE) : "" ;
    $param['show_order'] = ($this->input->post('show_order', TRUE)) ? $this->input->post('show_order', TRUE) : "" ;
    $param['active'] = ($this->input->post('active', TRUE)) ? $this->input->post('active', TRUE) : "" ;
    //end param
    
    //Check Directory
    if (!is_dir('images/payment/')){
      mkdir('./images/payment/', 0777, true);
    }
    //End Check Directory
    
    $validate_post = $this->validate_post($param);
    if($validate_post['result'] == "r1"){
      //Upload Image
      $config['upload_path'] = './images/payment/';
      $config['allowed_types'] = 'jpg|png';
      $config['max_size'] = 500;
      $config['overwrite'] = TRUE;
      
      foreach ($_FILES['txt_data_add_file']['name'] as $key => $image) {
        $_FILES['txt_data_add_file[]']['name']= $_FILES['txt_data_add_file']['name'][$key];
        $_FILES['txt_data_add_file[]']['type']= $_FILES['txt_data_add_file']['type'][$key];
        $_FILES['txt_data_add_file[]']['tmp_name']= $_FILES['txt_data_add_file']['tmp_name'][$key];
        $_FILES['txt_data_add_file[]']['error']= $_FILES['txt_data_add_file']['error'][$key];
        $_FILES['txt_data_add_file[]']['size']= $_FILES['txt_data_add_file']['size'][$key];
        
        $this->upload->initialize($config);
        if (!$this->upload->do_upload('txt_data_add_file[]')) {
          $validate_post['result'] = "r2";
          $validate_post['result_message'] = $this->upload->display_errors('', '');
        } else {
          $this->upload->data();
          $file_name = $this->upload->data('file_name');
          $param['logo'] = '/images/payment/'.$file_name;
          $this->resize_image('./images/payment/'.$file_name);
          $this->Model_payment->add_data($param);
        }
        @unlink($_FILES['txt_data_add_file[]']);
      }
      //End Upload Image
    }
    
    echo json_encode($validate_post);
  }
  
  public function edit_data(){
    //param
    $param['id'] = ($this->input->post('id', TRUE)) ? $this->input->post('id', TRUE) : "" ;
    $param['name'] = ($this->input->post('name', TRUE)) ? $this->input->post('name', TRUE) : "" ;
    $param['description'] = ($this->input->post('description', FALSE)) ? $this->input->post('description', FALSE) : "" ;
    $param['type'] = ($this->input->post('type', TRUE)) ? $this->input->post('type', TRUE) : "" ;
    $param['minimum_grand_total'] = ($this->input->post('minimum_grand_total', TRUE)) ? $this->input->post('minimum_grand_total', TRUE) : "" ;
    $param['show_order'] = ($this->input->post('show_order', TRUE)) ? $this->input->post('show_order', TRUE) : "" ;
    $param['active'] = ($this->input->post('active', TRUE)) ? $this->input->post('active', TRUE) : "" ;
    //end param
    
    if($param['id'] != ""){
      $validate_post = $this->validate_post($param);
      if($validate_post['result'] == "r1"){
        //Upload Image
        $file_element_name = 'txt_data_edit_file';
        $config['upload_path'] = './images/payment/';
        $config['allowed_types'] = 'jpg|png';
        $config['max_size'] = 500;
        $config['overwrite'] = TRUE;

        $this->upload->initialize($config);
        if (!$this->upload->do_upload($file_element_name)) {
          if($this->upload->display_errors('', '') !== "You did not select a file to upload."){
            $validate_post['result'] = "r2";
            $validate_post['result_message'] = $this->upload->display_errors('', '');
          }else{
            $this->Model_payment->edit_data($param);
          }
        } else {
          $this->upload->data();
          $file_name = $this->upload->data('file_name');
          $param['logo'] = '/images/payment/'.$file_name;
          $this->resize_image('./images/payment/'.$file_name);
          $this->Model_payment->edit_data($param);
        }
        @unlink($_FILES[$file_element_name]);
        //End Upload Image
        
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
    
    $result_data = $this->Model_payment->get_data($param);
    if($result_data->num_rows() > 0){
      $param_set['id'] = $result_data->row()->id;
      $param_set['name'] = $result_data->row()->name;
      $param_set['description'] = $result_data->row()->description;
      $param_set['logo'] = $result_data->row()->logo;
      $param_set['type'] = $result_data->row()->type;
      $param_set['minimum_grand_total'] = $result_data->row()->minimum_grand_total;
      $param_set['show_order'] = $result_data->row()->show_order;
      $param_set['active'] = ($result_data->row()->active == 0) ? 1 : 0;
      $this->Model_payment->edit_data($param_set);
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
      $this->Model_payment->remove_data($param);
    }else{
      $data['result'] = "r2";
      $data['result_message'] = "<strong>Data ID</strong> is not found, please refresh your browser!<br/>";
    }
    
    echo json_encode($data);
  }
}
