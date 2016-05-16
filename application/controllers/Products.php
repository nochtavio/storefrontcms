<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends CI_Controller {
  
  function __construct() {
    date_default_timezone_set('Asia/Jakarta');
    parent::__construct();
    check_address();
    check_login();
    if(!check_menu()){
      redirect(base_url().'dashboard/');
    }
    $this->load->model('Model_products');
    $this->load->model('Model_brand');
    $this->load->model('Model_category');
    $this->load->model('Model_category_child');
    $this->load->model('Model_category_child_');
    $this->load->model('Model_static_content');
  }
  
  public function get_default_potongan($type){
    //1: Gold | 2: Silver | 3: Bronze
    $id = 11;
    if($type == 1){
      $id = 9;
    }else if($type == 2){
      $id = 10;
    }
    
    $param['id'] = $id;
    $result_data = $this->Model_static_content->get_data($param);
    if($result_data->num_rows() > 0){
      return $result_data->row()->content;
    }else{
      return 0;
    }
  }
  
  public function index() {
    $page = 'Products';
    $sidebar['page'] = $page;
    $content['js'] = array();
    array_push($content['js'], 'products/function.js');
    array_push($content['js'], 'products/init.js');
    array_push($content['js'], 'products/action.js');
    
    //get list category
    $param['active'] = 1;
    $content['brand'] = $this->Model_brand->get_data($param, 0, 100)->result();
    $content['category'] = $this->Model_category->get_data($param, 0, 100)->result();
    $content['category_child_'] = $this->Model_category_child_->get_data($param, 0, 100)->result();
    $content['default_potongan_gold'] = $this->get_default_potongan(1);
    $content['default_potongan_silver'] = $this->get_default_potongan(2);
    $content['default_potongan_bronze'] = $this->get_default_potongan(3);
    //end get list category
    
    $data['header'] = $this->load->view('header', '', TRUE);
    $data['sidebar'] = $this->load->view('sidebar', $sidebar, TRUE);
    $data['content'] = $this->load->view('products/index', $content, TRUE);
    $this->load->view('template_index', $data);
  }
  
  public function get_data(){
    //param
    $param['name'] = ($this->input->post('name', TRUE)) ? $this->input->post('name', TRUE) : "";
    $param['brand_name'] = ($this->input->post('brand_name', TRUE)) ? $this->input->post('brand_name', TRUE) : "";
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
        $data['name'][$temp] = $row->name;
        $data['brand_name'][$temp] = $row->brand_name;
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
      $data['message'] = "No Products";
    }
    
    echo json_encode($data);
  }
  
  public function get_specific_data(){
    //param
    $param['id'] = ($this->input->post('id', TRUE)) ? $this->input->post('id', TRUE) : "";
    //end param
    
    $result_data = $this->Model_products->get_data($param);
    $result_category = $this->Model_products->get_category_detail($param);
    $category = array();
    foreach ($result_category->result() as $row) {
      array_push($category, $row->id_category_child);
    }
    
    if($result_data->num_rows() > 0){
      $data['result'] = "r1";
      $data['id'] = $result_data->row()->id;
      $data['id_brand'] = $result_data->row()->id_brand;
      $data['name'] = $result_data->row()->name;
      $data['price'] = $result_data->row()->price;
      $data['sale_price'] = $result_data->row()->sale_price;
      $data['reseller_price'] = $result_data->row()->reseller_price;
      $data['potongan_gold'] = $result_data->row()->potongan_gold;
      $data['potongan_silver'] = $result_data->row()->potongan_silver;
      $data['potongan_bronze'] = $result_data->row()->potongan_bronze;
      $data['weight'] = $result_data->row()->weight;
      $data['attribute'] = $result_data->row()->attribute;
      $data['description'] = $result_data->row()->description;
      $data['short_description'] = $result_data->row()->short_description;
      $data['info'] = $result_data->row()->info;
      $data['size_guideline'] = $result_data->row()->size_guideline;
      $category = array();
      $category_child = array();
      $category_child_ = array();
      $result_category_detail = $this->Model_products->get_category_detail($param);
      foreach ($result_category_detail->result() as $row) {
        if($row->id_category != 0){
          array_push($category, $row->id_category);
        }else if($row->id_category_child != 0){
          array_push($category_child, $row->id_category_child);
        }else if($row->id_category_child_ != 0){
          array_push($category_child_, $row->id_category_child_);
        }
      }
      $data['category'] = $category;
      $data['category_child'] = $category_child;
      $data['category_child_'] = $category_child_;
      $data['active'] = $result_data->row()->active;
    }else{
      $data['result'] = "r2";
      $data['message'] = "No Data";
    }
    
    echo json_encode($data);
  }
  
  public function validate_post($param){
    //param
    $id_brand = (isset($param['id_brand'])) ? $param['id_brand'] : 0;
    $name = (isset($param['name'])) ? $param['name'] : "";
    $price = (isset($param['price'])) ? $param['price'] : 0;
    $weight = (isset($param['weight'])) ? $param['weight'] : 0;
    //end param
    
    $data['result'] = "r1";
    $data['result_message'] = "";
    
    if($id_brand == 0){
      $data['result'] = "r2";
      $data['result_message'] .= "<strong>Brand</strong> must be choosen !<br/>";
    }
    
    if($name == ""){
      $data['result'] = "r2";
      $data['result_message'] .= "<strong>Name</strong> must be filled !<br/>";
    }
    
    if($price == ""){
      $data['result'] = "r2";
      $data['result_message'] .= "<strong>Price</strong> must be filled !<br/>";
    }elseif(!is_numeric($price)){
      $data['result'] = "r2";
      $data['result_message'] .= "<strong>Price</strong> must be a number !<br/>";
    }
    
    if($weight == ""){
      $data['result'] = "r2";
      $data['result_message'] .= "<strong>Weight</strong> must be filled !<br/>";
    }elseif(!is_numeric($weight)){
      $data['result'] = "r2";
      $data['result_message'] .= "<strong>Weight</strong> must be a number !<br/>";
    }
    
    return $data;
  }
  
  public function add_data(){
    //param
    $param['id_brand'] = ($this->input->post('id_brand', TRUE)) ? $this->input->post('id_brand', TRUE) : 0 ;
    $param['name'] = ($this->input->post('name', TRUE)) ? $this->input->post('name', TRUE) : "" ;
    $param['price'] = ($this->input->post('price', TRUE)) ? $this->input->post('price', TRUE) : 0 ;
    $param['sale_price'] = ($this->input->post('sale_price', TRUE)) ? $this->input->post('sale_price', TRUE) : 0 ;
    $param['reseller_price'] = ($this->input->post('reseller_price', TRUE)) ? $this->input->post('reseller_price', TRUE) : 0 ;
    $param['potongan_gold'] = ($this->input->post('potongan_gold', TRUE)) ? $this->input->post('potongan_gold', TRUE) : $this->get_default_potongan(1) ;
    $param['potongan_silver'] = ($this->input->post('potongan_silver', TRUE)) ? $this->input->post('potongan_silver', TRUE) : $this->get_default_potongan(2) ;
    $param['potongan_bronze'] = ($this->input->post('potongan_bronze', TRUE)) ? $this->input->post('potongan_bronze', TRUE) : $this->get_default_potongan(3) ;
    $param['weight'] = ($this->input->post('weight', TRUE)) ? $this->input->post('weight', TRUE) : 0 ;
    $param['attribute'] = ($this->input->post('attribute', TRUE)) ? $this->input->post('attribute', TRUE) : "" ;
    $param['description'] = ($this->input->post('description', FALSE)) ? $this->input->post('description', FALSE) : "" ;
    $param['short_description'] = ($this->input->post('short_description', FALSE)) ? $this->input->post('short_description', FALSE) : "" ;
    $param['info'] = ($this->input->post('info', TRUE)) ? $this->input->post('info', TRUE) : "" ;
    $param['size_guideline'] = ($this->input->post('size_guideline', TRUE)) ? $this->input->post('size_guideline', TRUE) : "" ;
    $param['category'] = ($this->input->post('category', TRUE)) ? $this->input->post('category', TRUE) : "" ;
    $param['category_child'] = ($this->input->post('category_child', TRUE)) ? $this->input->post('category_child', TRUE) : "" ;
    $param['category_child_'] = ($this->input->post('category_child_', TRUE)) ? $this->input->post('category_child_', TRUE) : "" ;
    $param['active'] = ($this->input->post('active', TRUE)) ? $this->input->post('active', TRUE) : "" ;
    //end param
    
    $validate_post = $this->validate_post($param);
    if($validate_post['result'] == "r1"){
      $this->Model_products->add_data($param);
    }
    
    echo json_encode($validate_post);
  }
  
  public function edit_data(){
    //param
    $param['id'] = ($this->input->post('id', TRUE)) ? $this->input->post('id', TRUE) : "" ;
    $param['id_brand'] = ($this->input->post('id_brand', TRUE)) ? $this->input->post('id_brand', TRUE) : 0 ;
    $param['name'] = ($this->input->post('name', TRUE)) ? $this->input->post('name', TRUE) : "" ;
    $param['price'] = ($this->input->post('price', TRUE)) ? $this->input->post('price', TRUE) : 0 ;
    $param['sale_price'] = ($this->input->post('sale_price', TRUE)) ? $this->input->post('sale_price', TRUE) : 0 ;
    $param['reseller_price'] = ($this->input->post('reseller_price', TRUE)) ? $this->input->post('reseller_price', TRUE) : 0 ;
    $param['potongan_gold'] = ($this->input->post('potongan_gold', TRUE)) ? $this->input->post('potongan_gold', TRUE) : $this->get_default_potongan(1) ;
    $param['potongan_silver'] = ($this->input->post('potongan_silver', TRUE)) ? $this->input->post('potongan_silver', TRUE) : $this->get_default_potongan(2) ;
    $param['potongan_bronze'] = ($this->input->post('potongan_bronze', TRUE)) ? $this->input->post('potongan_bronze', TRUE) : $this->get_default_potongan(3) ;
    $param['weight'] = ($this->input->post('weight', TRUE)) ? $this->input->post('weight', TRUE) : 0 ;
    $param['attribute'] = ($this->input->post('attribute', TRUE)) ? $this->input->post('attribute', TRUE) : "" ;
    $param['description'] = ($this->input->post('description', FALSE)) ? $this->input->post('description', FALSE) : "" ;
    $param['short_description'] = ($this->input->post('short_description', FALSE)) ? $this->input->post('short_description', FALSE) : "" ;
    $param['info'] = ($this->input->post('info', TRUE)) ? $this->input->post('info', TRUE) : "" ;
    $param['size_guideline'] = ($this->input->post('size_guideline', TRUE)) ? $this->input->post('size_guideline', TRUE) : "" ;
    $param['category'] = ($this->input->post('category', TRUE)) ? $this->input->post('category', TRUE) : "" ;
    $param['category_child'] = ($this->input->post('category_child', TRUE)) ? $this->input->post('category_child', TRUE) : "" ;
    $param['category_child_'] = ($this->input->post('category_child_', TRUE)) ? $this->input->post('category_child_', TRUE) : "" ;
    $param['active'] = ($this->input->post('active', TRUE)) ? $this->input->post('active', TRUE) : "" ;
    
    //get initial price
    $param_price['id'] = $param['id'];
    $result_price = $this->Model_products->get_data($param_price);
    if($result_price->num_rows() > 0){
      $price_logs_type = array();
      $price_logs_initial_value = array();
      $price_logs_changed_value = array();
      
      $initial_price = $result_price->row()->price;
      $initial_sale_price = $result_price->row()->sale_price;
      $initial_reseller_price = $result_price->row()->reseller_price;
      
      if($initial_price != $param['price']){
        array_push($price_logs_type, 1);
        array_push($price_logs_initial_value, $initial_price);
        array_push($price_logs_changed_value, $param['price']);
      }
      
      if($initial_sale_price != $param['sale_price']){
        array_push($price_logs_type, 2);
        array_push($price_logs_initial_value, $initial_sale_price);
        array_push($price_logs_changed_value, $param['sale_price']);
      }
      
      if($initial_reseller_price != $param['reseller_price']){
        array_push($price_logs_type, 3);
        array_push($price_logs_initial_value, $initial_reseller_price);
        array_push($price_logs_changed_value, $param['reseller_price']);
      }
    }
    $param['price_logs_type'] = $price_logs_type;
    $param['price_logs_initial_value'] = $price_logs_initial_value;
    $param['price_logs_changed_value'] = $price_logs_changed_value;
    //end initial price
    
    //end param
    
    if($param['id'] != ""){
      $validate_post = $this->validate_post($param);
      if($validate_post['result'] == "r1"){
        $this->Model_products->edit_data($param);
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
    
    $result_data = $this->Model_products->get_data($param);
    if($result_data->num_rows() > 0){
      $param_set['id'] = $result_data->row()->id;
      $param_set['id_brand'] = $result_data->row()->id_brand;
      $param_set['name'] = $result_data->row()->name;
      $param_set['price'] = $result_data->row()->price;
      $param_set['sale_price'] = $result_data->row()->sale_price;
      $param_set['reseller_price'] = $result_data->row()->reseller_price;
      $param_set['potongan_gold'] = $result_data->row()->potongan_gold;
      $param_set['potongan_silver'] = $result_data->row()->potongan_silver;
      $param_set['potongan_bronze'] = $result_data->row()->potongan_bronze;
      $param_set['weight'] = $result_data->row()->weight;
      $param_set['attribute'] = $result_data->row()->attribute;
      $param_set['description'] = $result_data->row()->description;
      $param_set['short_description'] = $result_data->row()->short_description;
      $param_set['info'] = $result_data->row()->info;
      $param_set['size_guideline'] = $result_data->row()->size_guideline;
      $category = array();
      $category_child = array();
      $category_child_ = array();
      $result_category_detail = $this->Model_products->get_category_detail($param);
      foreach ($result_category_detail->result() as $row) {
        if($row->id_category != 0){
          array_push($category, $row->id_category);
        }else if($row->id_category_child != 0){
          array_push($category_child, $row->id_category_child);
        }else if($row->id_category_child_ != 0){
          array_push($category_child_, $row->id_category_child_);
        }
      }
      $param_set['category'] = $category;
      $param_set['category_child'] = $category_child;
      $param_set['category_child_'] = $category_child_;
      $param_set['active'] = ($result_data->row()->active == 0) ? 1 : 0;
      $this->Model_products->edit_data($param_set);
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
      $this->Model_products->remove_data($param);
    }else{
      $data['result'] = "r2";
      $data['result_message'] = "<strong>Data ID</strong> is not found, please refresh your browser!<br/>";
    }
    
    echo json_encode($data);
  }
  
  public function get_category_detail(){
    //param
    $param['id_products'] = ($this->input->post('id_products', TRUE)) ? $this->input->post('id_products', TRUE) : 0;
    //end param
    
    //paging
    $get_data = $this->Model_products->get_category_detail($param);
    //End Set totalpaging

    if ($get_data->num_rows() > 0) {
      $temp = 0;
      foreach ($get_data->result() as $row) {
        $data['result'] = "r1";
        $data['id_category_child'][$temp] = $row->id_category_child;
        $temp++;
      }
      $data['total'] = $temp;
    } else {
      $data['result'] = "r2";
      $data['message'] = "No Category";
    }
    
    echo json_encode($data);
  }
  
  public function get_category_child(){
    $category = ($this->input->post('id_category', TRUE)) ? $this->input->post('id_category', TRUE) : array();
    $temp = 0;
    if(!empty($category)){
      foreach($category as $cat){
        $param['id_category'] = $cat;
        $param['active'] = 1;
        $get_data = $this->Model_category_child->get_data($param, 0, 100);
        if ($get_data->num_rows() > 0) {
          foreach ($get_data->result() as $row) {
            $data['result'] = "r1";
            $data['id'][$temp] = $row->id;
            $data['name'][$temp] = $row->name;
            $temp++;
          }
          $data['total'] = $temp;
        }
      }

      if($temp == 0){
        $data['result'] = "r2";
      }
    }else{
      $data['result'] = "r2";
    }
    
    echo json_encode($data);
  }
  
  public function get_category_child_(){
    $parent = ($this->input->post('parent', TRUE)) ? $this->input->post('parent', TRUE) : array();
    $temp = 0;
    if(!empty($parent)){
      foreach($parent as $par){
        $param['parent'] = $par;
        $param['active'] = 1;
        $get_data = $this->Model_category_child_->get_data($param, 0, 100);
        if ($get_data->num_rows() > 0) {
          foreach ($get_data->result() as $row) {
            $data['result'] = "r1";
            $data['id'][$temp] = $row->id;
            $data['name'][$temp] = $row->name;
            $temp++;
          }
          $data['total'] = $temp;
        }
      }

      if($temp == 0){
        $data['result'] = "r2";
      }
    }else{
      $data['result'] = "r2";
    }
    
    echo json_encode($data);
  }
}
