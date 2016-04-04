<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Inventory_correction extends CI_Controller {
  
  function __construct() {
    date_default_timezone_set('Asia/Jakarta');
    parent::__construct();
    check_address();
    check_login();
    if(!check_menu()){
      redirect(base_url().'dashboard/');
    }
    $this->load->model('Model_inventory_correction');
    $this->load->model('Model_products_variant_detail');
  }
  
  public function index() {
    $page = 'Inventory_correction';
    $sidebar['page'] = $page;
    $content['js'] = array();
    array_push($content['js'], 'inventory_correction/function.js');
    array_push($content['js'], 'inventory_correction/init.js');
    array_push($content['js'], 'inventory_correction/action.js');
    
    $data['header'] = $this->load->view('header', '', TRUE);
    $data['sidebar'] = $this->load->view('sidebar', $sidebar, TRUE);
    $data['content'] = $this->load->view('inventory_correction/index', $content, TRUE);
    $this->load->view('template_index', $data);
  }
  
  public function get_sku_detail(){
    //param
    $param['sku'] = ($this->input->post('sku', TRUE)) ? $this->input->post('sku', TRUE) : "" ;
    //end param
    
    if($param['sku'] == ""){
      $data['result'] = "r2";
      $data['message'] = "No Data";
    }else{
      $get_data = $this->Model_products_variant_detail->get_data($param);
      if ($get_data->num_rows() > 0) {
        $data['result'] = "r1";
        $data['id'] = $get_data->row()->id;
        $data['id_products'] = $get_data->row()->id_products;
        $data['products_name'] = $get_data->row()->products_name;
        $data['color_name'] = $get_data->row()->color_name;
        $data['size'] = $get_data->row()->size;
        $data['quantity'] = $get_data->row()->quantity;
        $data['quantity_warehouse'] = $get_data->row()->quantity_warehouse;
      } else {
        $data['result'] = "r2";
        $data['message'] = "No Data";
      }
    }
    
    echo json_encode($data);
  }
  
  public function validate_post($param){
    //param
    $product_id = (isset($param['product_id'])) ? $param['product_id'] : 0;
    $updated_quantity = (isset($param['updated_quantity'])) ? $param['updated_quantity'] : 0;
    //end param
    
    $data['result'] = "r1";
    $data['result_message'] = "";
    
    if($product_id == 0){
      $data['result'] = "r2";
      $data['result_message'] .= "<strong>SKU</strong> must be successfully applied !<br/>";
    }
    
    if($updated_quantity == 0 || !is_numeric($updated_quantity)){
      $data['result'] = "r2";
      $data['result_message'] .= "<strong>Quantity</strong> is not valid !<br/>";
    }
    
    return $data;
  }
  
  public function add_data(){
    //param
    $param['SKU'] = ($this->input->post('SKU', TRUE)) ? $this->input->post('SKU', TRUE) : "" ;
    $param['product_id'] = ($this->input->post('product_id', TRUE)) ? $this->input->post('product_id', TRUE) : 0 ;
    $param['quantity'] = ($this->input->post('quantity', TRUE)) ? $this->input->post('quantity', TRUE) : 0 ;
    $param['quantity_warehouse'] = ($this->input->post('quantity_warehouse', TRUE)) ? $this->input->post('quantity_warehouse', TRUE) : 0 ;
    $param['updated_quantity'] = ($this->input->post('updated_quantity', TRUE)) ? $this->input->post('updated_quantity', TRUE) : 0 ;
    $param['history_type'] = ($this->input->post('history_type', TRUE)) ? $this->input->post('history_type', TRUE) : 0 ;
    //end param
    
    $validate_post = $this->validate_post($param);
    if($validate_post['result'] == "r1"){
      if($param['history_type'] == 3){
        //Correction In
        $param['quantity'] = $param['quantity']+$param['updated_quantity'];
        $param['quantity_warehouse'] = $param['quantity_warehouse']+$param['updated_quantity'];
      }else{
        //Correction Out
        $param['quantity'] = $param['quantity']-$param['updated_quantity'];
        $param['quantity_warehouse'] = $param['quantity_warehouse']-$param['updated_quantity'];
      }
      $this->Model_inventory_correction->add_data($param);
    }
    
    echo json_encode($validate_post);
  }
}
