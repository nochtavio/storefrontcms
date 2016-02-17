<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Products_variant extends CI_Controller {

  function __construct() {
    date_default_timezone_set('Asia/Jakarta');
    parent::__construct();
    $this->load->model('Model_color');
    $this->load->model('Model_products');
    $this->load->model('Model_products_variant');
  }

  public function validate_index() {
    if (!$this->input->get('id_products', TRUE) || !is_numeric($this->input->get('id_products', TRUE))) {
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

  public function index() {
    $this->validate_index();

    $page = 'Products';
    $sidebar['page'] = $page;
    $content['js'] = array();
    array_push($content['js'], 'products_variant/function.js');
    array_push($content['js'], 'products_variant/init.js');
    array_push($content['js'], 'products_variant/action.js');
    $content['id_products'] = $this->input->get('id_products', TRUE);
    $content['products_name'] = $this->get_products_name($content['id_products']);
    if (!$content['products_name']) {
      redirect('/products/');
      die();
    }
    $param['active'] = 1;
    $content['color'] = $this->Model_color->get_data($param, 0, 100)->result();

    $data['header'] = $this->load->view('header', '', TRUE);
    $data['sidebar'] = $this->load->view('sidebar', $sidebar, TRUE);
    $data['content'] = $this->load->view('products_variant/index', $content, TRUE);
    $this->load->view('template_index', $data);
  }

  public function get_data() {
    //param
    $param['id_products'] = ($this->input->post('id_products', TRUE)) ? $this->input->post('id_products', TRUE) : 0;
    $param['active'] = ($this->input->post('active', TRUE)) ? $this->input->post('active', TRUE) : 0;
    $param['order'] = ($this->input->post('order', TRUE)) ? $this->input->post('order', TRUE) : -1;
    //end param
    //paging
    $get_data = $this->Model_products_variant->get_data($param);
    $page = ($this->input->post('page', TRUE)) ? $this->input->post('page', TRUE) : 1;
    $size = ($this->input->post('size', TRUE)) ? $this->input->post('size', TRUE) : 10;
    $limit = ($page - 1) * $size;
    //End Set totalpaging

    if ($get_data->num_rows() > 0) {
      $get_data_paging = $this->Model_products_variant->get_data($param, $limit, $size);
      $temp = 0;
      foreach ($get_data_paging->result() as $row) {
        $data['result'] = "r1";
        $data['id'][$temp] = $row->id;
        $data['color_name'][$temp] = ($row->color_name == NULL) ? '-' : $row->color_name;
        $data['variant_size'][$temp] = ($row->size == NULL) ? '-' : $row->size;
        $data['quantity'][$temp] = $row->quantity;
        $data['quantity_warehouse'][$temp] = $row->quantity_warehouse;
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
      $data['message'] = "No Variants";
    }

    echo json_encode($data);
  }

  public function get_specific_data() {
    //param
    $param['id'] = ($this->input->post('id', TRUE)) ? $this->input->post('id', TRUE) : "";
    //end param

    $result_data = $this->Model_products_variant->get_data($param);
    if ($result_data->num_rows() > 0) {
      $data['result'] = "r1";
      $data['id'] = $result_data->row()->id;
      $data['id_color'] = $result_data->row()->id_color;
      $data['size'] = ($result_data->row()->size == NULL) ? '-' : $result_data->row()->size;
      $data['quantity'] = $result_data->row()->quantity;
      $data['quantity_warehouse'] = $result_data->row()->quantity_warehouse;
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
    $quantity = (isset($param['quantity'])) ? $param['quantity'] : 0;
    $quantity_warehouse = (isset($param['quantity_warehouse'])) ? $param['quantity_warehouse'] : 0;
    //end param

    $data['result'] = "r1";
    $data['result_message'] = "";
    
    if($state == "add"){
      if ($id_color == 0) {
        $data['result'] = "r2";
        $data['result_message'] .= "<strong>Color</strong> must be filled !<br/>";
      }else{
        //check duplicate variant
        $param['id_products'] = $id_products;
        $param['id_color'] = $id_color;
        $param['active'] = 1;
        $check_duplicate_variant = $this->Model_products_variant->get_data($param);
        if($check_duplicate_variant->num_rows() > 0){
          $data['result'] = "r2";
          $data['result_message'] .= "<strong>Color</strong> is already exist!<br/>";
        }
      }
    }
    
    if (!is_numeric($quantity)) {
      $data['result'] = "r2";
      $data['result_message'] .= "<strong>Quantity</strong> must be a number !<br/>";
    }
    
    if (!is_numeric($quantity_warehouse)) {
      $data['result'] = "r2";
      $data['result_message'] .= "<strong>Quantity Warehouse</strong> must be a number !<br/>";
    }

    return $data;
  }

  public function add_data() {
    //param
    $param['id_products'] = ($this->input->post('id_products', TRUE)) ? $this->input->post('id_products', TRUE) : 0;
    $param['id_color'] = ($this->input->post('id_color', TRUE)) ? $this->input->post('id_color', TRUE) : 0;
    $param['size'] = ($this->input->post('size', TRUE)) ? $this->input->post('size', TRUE) : NULL;
    $param['quantity'] = ($this->input->post('quantity', TRUE)) ? $this->input->post('quantity', TRUE) : 0;
    $param['quantity_warehouse'] = ($this->input->post('quantity_warehouse', TRUE)) ? $this->input->post('quantity_warehouse', TRUE) : 0;
    $param['active'] = ($this->input->post('active', TRUE)) ? $this->input->post('active', TRUE) : "";
    //end param

    $validate_post = $this->validate_post($param, "add");
    if ($validate_post['result'] == "r1") {
      $this->Model_products_variant->add_data($param);
    }

    echo json_encode($validate_post);
  }

  public function edit_data() {
    //param
    $param['id'] = ($this->input->post('id', TRUE)) ? $this->input->post('id', TRUE) : "";
    $param['quantity'] = ($this->input->post('quantity', TRUE)) ? $this->input->post('quantity', TRUE) : 0;
    $param['quantity_warehouse'] = ($this->input->post('quantity_warehouse', TRUE)) ? $this->input->post('quantity_warehouse', TRUE) : 0;
    $param['active'] = ($this->input->post('active', TRUE)) ? $this->input->post('active', TRUE) : "";
    //end param

    if ($param['id'] != "") {
      $validate_post = $this->validate_post($param, "edit");
      if ($validate_post['result'] == "r1") {
        $this->Model_products_variant->edit_data($param);
      }
    } else {
      $validate_post['result'] = "r2";
      $validate_post['result_message'] = "<strong>Data ID</strong> is not found, please refresh your browser!<br/>";
    }

    echo json_encode($validate_post);
  }

  public function remove_data() {
    //post
    $param['id'] = ($this->input->post('id', TRUE)) ? $this->input->post('id', TRUE) : "";
    //end post

    if ($param['id'] != "") {
      $data['result'] = "r1";
      $this->Model_products_variant->remove_data($param);
    } else {
      $data['result'] = "r2";
      $data['result_message'] = "<strong>Data ID</strong> is not found, please refresh your browser!<br/>";
    }

    echo json_encode($data);
  }

}
