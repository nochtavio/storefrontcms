<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Products_image extends CI_Controller {

  function __construct() {
    date_default_timezone_set('Asia/Jakarta');
    parent::__construct();
    check_address();
    check_login();
    if(!check_menu()){
      redirect(base_url().'dashboard/');
    }
    $this->load->model('Model_color');
    $this->load->model('Model_products');
    $this->load->model('Model_color');
    $this->load->model('Model_products_image');
    $this->load->library('image_lib');
  }

  public function validate_index() {
    if (!$this->input->get('id_products', TRUE) || !is_numeric($this->input->get('id_products', TRUE)) || !$this->input->get('id_color', TRUE) || !is_numeric($this->input->get('id_color', TRUE))) {
      redirect('/products/');
      die();
    }
  }

  public function get_products_name($id_products) {
    $param['id'] = $id_products;
    $result_data = $this->Model_products->get_data($param);
    if ($result_data->num_rows() > 0) {
      return $result_data->row()->name;
    } else {
      return false;
    }
  }
  
  public function get_color_name($id_color) {
    $param['id'] = $id_color;
    $result_data = $this->Model_color->get_data($param);
    if ($result_data->num_rows() > 0) {
      return $result_data->row()->name;
    } else {
      return false;
    }
  }
  
  public function resize_image($source){
    $config_resize['image_library'] = 'gd2';
    $config_resize['source_image'] = $source;
    $config_resize['create_thumb'] = FALSE;
    $config_resize['maintain_ratio'] = FALSE;
    $config_resize['width']         = 540;
    $config_resize['height']       = 660;

    $this->image_lib->initialize($config_resize);
    $this->image_lib->resize();
  }

  public function index() {
    $this->validate_index();

    $page = 'Products';
    $sidebar['page'] = $page;
    $content['js'] = array();
    array_push($content['js'], 'products_image/function.js');
    array_push($content['js'], 'products_image/init.js');
    array_push($content['js'], 'products_image/action.js');
    $content['id_products'] = $this->input->get('id_products', TRUE);
    $content['products_name'] = $this->get_products_name($content['id_products']);
    if (!$content['products_name']) {
      redirect('/products/');
      die();
    }
    $content['id_color'] = $this->input->get('id_color', TRUE);
    $content['color_name'] = $this->get_color_name($content['id_color']);
    if (!$content['color_name']) {
      redirect('/products/');
      die();
    }

    $data['header'] = $this->load->view('header', '', TRUE);
    $data['sidebar'] = $this->load->view('sidebar', $sidebar, TRUE);
    $data['content'] = $this->load->view('products_image/index', $content, TRUE);
    $this->load->view('template_index', $data);
  }

  public function get_data() {
    //param
    $param['id_products'] = ($this->input->post('id_products', TRUE)) ? $this->input->post('id_products', TRUE) : 0;
    $param['id_color'] = ($this->input->post('id_color', TRUE)) ? $this->input->post('id_color', TRUE) : 0;
    $param['url'] = ($this->input->post('url', TRUE)) ? $this->input->post('url', TRUE) : '';
    $param['active'] = ($this->input->post('active', TRUE)) ? $this->input->post('active', TRUE) : 0;
    $param['order'] = ($this->input->post('order', TRUE)) ? $this->input->post('order', TRUE) : -1;
    //end param
    //paging
    $get_data = $this->Model_products_image->get_data($param);
    $page = ($this->input->post('page', TRUE)) ? $this->input->post('page', TRUE) : 1;
    $size = ($this->input->post('size', TRUE)) ? $this->input->post('size', TRUE) : 10;
    $limit = ($page - 1) * $size;
    //End Set totalpaging

    if ($get_data->num_rows() > 0) {
      $get_data_paging = $this->Model_products_image->get_data($param, $limit, $size);
      $temp = 0;
      foreach ($get_data_paging->result() as $row) {
        $data['result'] = "r1";
        $data['id'][$temp] = $row->id;
        $data['url'][$temp] = $row->url;
        $data['default'][$temp] = $row->default;
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
      $data['message'] = "No Images";
    }

    echo json_encode($data);
  }

  public function get_specific_data() {
    //param
    $param['id'] = ($this->input->post('id', TRUE)) ? $this->input->post('id', TRUE) : "";
    //end param

    $result_data = $this->Model_products_image->get_data($param);
    if ($result_data->num_rows() > 0) {
      $data['result'] = "r1";
      $data['id'] = $result_data->row()->id;
      $data['url'] = $result_data->row()->url;
      $data['default'] = $result_data->row()->default;
      $data['show_order'] = $result_data->row()->show_order;
      $data['active'] = $result_data->row()->active;
    } else {
      $data['result'] = "r2";
      $data['message'] = "No Data";
    }

    echo json_encode($data);
  }

  public function validate_post($param, $state) {
    //param
    $id_products = (isset($param['id_products'])) ? $param['id_products'] : 0;
    $id_color = (isset($param['id_color'])) ? $param['id_color'] : 0;
    $total_uploads = (isset($param['total_uploads'])) ? $param['total_uploads'] : 0;
    //end param

    $data['result'] = "r1";
    $data['result_message'] = "";
    
    if($state == 'add'){
      $param_check['id_products'] = $id_products;
      $param_check['id_color'] = $id_color;
      $result_data = $this->Model_products_image->get_data($param_check);
      if ($result_data->num_rows() + $total_uploads > 5) {
        $data['result'] = "r2";
        $data['result_message'] = "Maximum images per product variant is exceeded (5 out 5 images)!";
      }
    }

    return $data;
  }

  public function add_data() {
    //param
    $param['id_products'] = ($this->input->post('id_products', TRUE)) ? $this->input->post('id_products', TRUE) : 0;
    $param['id_color'] = ($this->input->post('id_color', TRUE)) ? $this->input->post('id_color', TRUE) : 0;
    $param['default'] = ($this->input->post('default', TRUE)) ? $this->input->post('default', TRUE) : 0;
    $param['show_order'] = ($this->input->post('show_order', TRUE)) ? $this->input->post('show_order', TRUE) : 0;
    $param['active'] = ($this->input->post('active', TRUE)) ? $this->input->post('active', TRUE) : "";
    
    $param['total_uploads'] = count($_FILES['txt_data_add_file']['name']);
    //end param
    
    //Check Directory
    if (!is_dir('images/products/'.$param['id_products'])){
      mkdir('./images/products/'.$param['id_products'].'/', 0777, true);
    }
    
    if(!is_dir('images/products/'.$param['id_products'].'/'.$param['id_color'])){
      mkdir('./images/products/'.$param['id_products'].'/'.$param['id_color'].'/', 0777, true);
    }
    //End Check Directory
    
    $validate_post = $this->validate_post($param, "add");
    if ($validate_post['result'] == "r1") {
      //Upload Image
      $config['upload_path'] = './images/products/'.$param['id_products'].'/'.$param['id_color'].'/';
      $config['allowed_types'] = 'jpg';
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
          $param['url'] = '/images/products/'.$param['id_products'].'/'.$param['id_color'].'/'.$file_name;
          $this->resize_image('./images/products/'.$param['id_products'].'/'.$param['id_color'].'/'.$file_name);
          $this->Model_products_image->add_data($param);
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
    $param['id_products'] = ($this->input->post('id_products', TRUE)) ? $this->input->post('id_products', TRUE) : 0;
    $param['id_color'] = ($this->input->post('id_color', TRUE)) ? $this->input->post('id_color', TRUE) : 0;
    $param['default'] = ($this->input->post('default', TRUE)) ? $this->input->post('default', TRUE) : 0;
    $param['show_order'] = ($this->input->post('show_order', TRUE)) ? $this->input->post('show_order', TRUE) : 0;
    $param['active'] = ($this->input->post('active', TRUE)) ? $this->input->post('active', TRUE) : "";
    //end param

    if ($param['id'] != "") {
      $validate_post = $this->validate_post($param, 'edit');
      if ($validate_post['result'] == "r1") {
        //Upload Image
        $file_element_name = 'txt_data_edit_file';
        $config['upload_path'] = './images/products/'.$param['id_products'].'/'.$param['id_color'].'/';
        $config['allowed_types'] = 'jpg';
        $config['max_size'] = 500;
        $config['overwrite'] = TRUE;

        $this->upload->initialize($config);
        if (!$this->upload->do_upload($file_element_name)) {
          if($this->upload->display_errors('', '') !== "You did not select a file to upload."){
            $validate_post['result'] = "r2";
            $validate_post['result_message'] = $this->upload->display_errors('', '');
          }else{
            $this->Model_products_image->edit_data($param);
          }
        } else {
          $this->upload->data();
          $file_name = $this->upload->data('file_name');
          $param['url'] = '/images/products/'.$param['id_products'].'/'.$param['id_color'].'/'.$file_name;
          $this->resize_image('./images/products/'.$param['id_products'].'/'.$param['id_color'].'/'.$file_name);
          $this->Model_products_image->edit_data($param);
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
    
    $result_data = $this->Model_products_image->get_data($param);
    if($result_data->num_rows() > 0){
      $param_set['id'] = $result_data->row()->id;
      $param_set['url'] = $result_data->row()->url;
      $param_set['default'] = $result_data->row()->default;
      $param_set['show_order'] = $result_data->row()->show_order;
      $param_set['active'] = ($result_data->row()->active == 0) ? 1 : 0;
      $this->Model_products_image->edit_data($param_set);
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
      $this->Model_products_image->remove_data($param);
    } else {
      $data['result'] = "r2";
      $data['result_message'] = "<strong>Data ID</strong> is not found, please refresh your browser!<br/>";
    }

    echo json_encode($data);
  }

}
