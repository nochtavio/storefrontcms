<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Slider extends CI_Controller {

  function __construct() {
    date_default_timezone_set('Asia/Jakarta');
    parent::__construct();
    check_address();
    check_login();
    if(!check_menu()){
      redirect(base_url().'dashboard/');
    }
    $this->load->model('Model_slider');
    $this->load->library('image_lib');
  }
  
  public function resize_image($source){
    $config_resize['image_library'] = 'gd2';
    $config_resize['source_image'] = $source;
    $config_resize['create_thumb'] = FALSE;
    $config_resize['maintain_ratio'] = FALSE;
    $config_resize['width']         = 1920;
    $config_resize['height']       = 800;

    $this->image_lib->initialize($config_resize);
    $this->image_lib->resize();
  }

  public function index() {
    $page = 'Slider';
    $sidebar['page'] = $page;
    $content['js'] = array();
    array_push($content['js'], 'slider/function.js');
    array_push($content['js'], 'slider/init.js');
    array_push($content['js'], 'slider/action.js');

    $data['header'] = $this->load->view('header', '', TRUE);
    $data['sidebar'] = $this->load->view('sidebar', $sidebar, TRUE);
    $data['content'] = $this->load->view('slider/index', $content, TRUE);
    $this->load->view('template_index', $data);
  }

  public function get_data() {
    //param
    $param['url'] = ($this->input->post('url', TRUE)) ? $this->input->post('url', TRUE) : '';
    $param['link'] = ($this->input->post('link', TRUE)) ? $this->input->post('link', TRUE) : '';
    $param['active'] = ($this->input->post('active', TRUE)) ? $this->input->post('active', TRUE) : 0;
    $param['order'] = ($this->input->post('order', TRUE)) ? $this->input->post('order', TRUE) : -1;
    //end param
    //paging
    $get_data = $this->Model_slider->get_data($param);
    $page = ($this->input->post('page', TRUE)) ? $this->input->post('page', TRUE) : 1;
    $size = ($this->input->post('size', TRUE)) ? $this->input->post('size', TRUE) : 10;
    $limit = ($page - 1) * $size;
    //End Set totalpaging

    if ($get_data->num_rows() > 0) {
      $get_data_paging = $this->Model_slider->get_data($param, $limit, $size);
      $temp = 0;
      foreach ($get_data_paging->result() as $row) {
        $data['result'] = "r1";
        $data['id'][$temp] = $row->id;
        $data['show_order'][$temp] = $row->show_order;
        $data['url'][$temp] = $row->url;
        $data['link'][$temp] = $row->link;
        $data['target'][$temp] = $row->target;
        $data['title'][$temp] = $row->title;
        $data['description'][$temp] = $row->description;
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
      $data['message'] = "No Slider";
    }

    echo json_encode($data);
  }

  public function get_specific_data() {
    //param
    $param['id'] = ($this->input->post('id', TRUE)) ? $this->input->post('id', TRUE) : "";
    //end param

    $result_data = $this->Model_slider->get_data($param);
    if ($result_data->num_rows() > 0) {
      $data['result'] = "r1";
      $data['id'] = $result_data->row()->id;
      $data['show_order'] = $result_data->row()->show_order;
      $data['url'] = $result_data->row()->url;
      $data['link'] = $result_data->row()->link;
      $data['target'] = $result_data->row()->target;
      $data['title'] = $result_data->row()->title;
      $data['description'] = $result_data->row()->description;
      $data['active'] = $result_data->row()->active;
    } else {
      $data['result'] = "r2";
      $data['message'] = "No Data";
    }

    echo json_encode($data);
  }

  public function validate_post($param) {
    //param
    $link = (isset($param['link'])) ? $param['link'] : '';
    $show_order = (isset($param['show_order'])) ? $param['show_order'] : '';
    //end param

    $data['result'] = "r1";
    $data['result_message'] = "";
    
    if($link == ''){
      $data['result'] = "r2";
      $data['result_message'] = "Link must be filled!";
    }
    
    if(!is_numeric($show_order)){
      $data['result'] = "r2";
      $data['result_message'] = "Show Order must be a number!";
    }

    return $data;
  }

  public function add_data() {
    //param
    $param['show_order'] = ($this->input->post('show_order', TRUE)) ? $this->input->post('show_order', TRUE) : 0;
    $param['link'] = ($this->input->post('link', TRUE)) ? $this->input->post('link', TRUE) : '';
    $param['target'] = ($this->input->post('target', TRUE)) ? $this->input->post('target', TRUE) : 0;
    $param['title'] = ($this->input->post('title', TRUE)) ? $this->input->post('title', TRUE) : '';
    $param['description'] = ($this->input->post('description', TRUE)) ? $this->input->post('description', TRUE) : '';
    $param['active'] = ($this->input->post('active', TRUE)) ? $this->input->post('active', TRUE) : "";
    //end param
    
    //Check Directory
    if (!is_dir('images/slider/')){
      mkdir('./images/slider/', 0777, true);
    }
    //End Check Directory
    
    $validate_post = $this->validate_post($param);
    if ($validate_post['result'] == "r1") {
      //Upload Image
      $config['upload_path'] = './images/slider/';
      $config['allowed_types'] = 'jpg|png';
      $config['max_size'] = 1000;
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
          $param['url'] = '/images/slider/'.$file_name;
          $this->resize_image('./images/slider/'.$file_name);
          $this->Model_slider->add_data($param);
        }
        @unlink($_FILES['txt_data_add_file[]']);
      }
      //End Upload Image
    }

    echo json_encode($validate_post);
  }

  public function edit_data() {
    //param
    $param['id'] = ($this->input->post('id', TRUE)) ? $this->input->post('id', TRUE) : "";
    $param['show_order'] = ($this->input->post('show_order', TRUE)) ? $this->input->post('show_order', TRUE) : 0;
    $param['link'] = ($this->input->post('link', TRUE)) ? $this->input->post('link', TRUE) : '';
    $param['target'] = ($this->input->post('target', TRUE)) ? $this->input->post('target', TRUE) : 0;
    $param['title'] = ($this->input->post('title', TRUE)) ? $this->input->post('title', TRUE) : '';
    $param['description'] = ($this->input->post('description', TRUE)) ? $this->input->post('description', TRUE) : '';
    $param['active'] = ($this->input->post('active', TRUE)) ? $this->input->post('active', TRUE) : "";
    //end param

    if ($param['id'] != "") {
      $validate_post = $this->validate_post($param);
      if ($validate_post['result'] == "r1") {
        //Upload Image
        $file_element_name = 'txt_data_edit_file';
        $config['upload_path'] = './images/slider/';
        $config['allowed_types'] = 'jpg';
        $config['max_size'] = 1000;
        $config['overwrite'] = TRUE;

        $this->upload->initialize($config);
        if (!$this->upload->do_upload($file_element_name)) {
          if($this->upload->display_errors('', '') !== "You did not select a file to upload."){
            $validate_post['result'] = "r2";
            $validate_post['result_message'] = $this->upload->display_errors('', '');
          }else{
            $this->Model_slider->edit_data($param);
          }
        } else {
          $this->upload->data();
          $file_name = $this->upload->data('file_name');
          $param['url'] = '/images/slider/'.$file_name;
          $this->resize_image('./images/slider/'.$file_name);
          $this->Model_slider->edit_data($param);
        }
        @unlink($_FILES[$file_element_name]);
        //End Upload Image
      }
    } else {
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
    
    $result_data = $this->Model_slider->get_data($param);
    if($result_data->num_rows() > 0){
      $param_set['id'] = $result_data->row()->id;
      $param_set['show_order'] = $result_data->row()->show_order;
      $param_set['url'] = $result_data->row()->url;
      $param_set['link'] = $result_data->row()->link;
      $param_set['target'] = $result_data->row()->target;
      $param_set['title'] = $result_data->row()->title;
      $param_set['description'] = $result_data->row()->description;
      $param_set['active'] = ($result_data->row()->active == 0) ? 1 : 0;
      $this->Model_slider->edit_data($param_set);
    }else{
      $data['result'] = "r2";
      $data['result_message'] = '<strong>Data ID</strong> is not found, please refresh your browser!<br/>';
    }
    
    echo json_encode($data);
  }

  public function remove_data() {
    //post
    $param['id'] = ($this->input->post('id', TRUE)) ? $this->input->post('id', TRUE) : "";
    //end post

    if ($param['id'] != "") {
      $data['result'] = "r1";
      $this->Model_slider->remove_data($param);
    } else {
      $data['result'] = "r2";
      $data['result_message'] = "<strong>Data ID</strong> is not found, please refresh your browser!<br/>";
    }

    echo json_encode($data);
  }

}
