<?php

class Model_products extends CI_Model {
  function __construct() {
    parent::__construct();
  }

  function get_data($param, $limit = 0, $size = 0) {
    //Set Param
    $id = (isset($param['id'])) ? $param['id'] : 0;
    $name = (isset($param['name'])) ? $param['name'] : "";
    $brand_name = (isset($param['brand_name'])) ? $param['brand_name'] : "";
    $active = (isset($param['active'])) ? $param['active'] : -1;
    $order = (isset($param['order'])) ? $param['order'] : -1;
    //End Set Param
    
    $this->db->select('products.*, brand.name AS brand_name');
    $this->db->from('products');
    $this->db->join('brand', 'brand.id = products.id_brand');
    
    //Validation
    if($id > 0){$this->db->where('products.id', $id);}
    if($name != ""){$this->db->like('products.name', $name);}
    if($brand_name != ""){$this->db->like('brand.name', $brand_name);}
    if($active > -1){$this->db->where('products.active', $active);}
    //End Validation
    
    $this->db->where('products.deleted', 0);
    if($order == 1){
      $this->db->order_by("products.name", "desc");
    }else if($order == 2){
      $this->db->order_by("products.cretime", "desc");
    }else if($order == 3){
      $this->db->order_by("products.cretime", "asc");
    }else{
      $this->db->order_by("products.name", "asc");
    }
    
    if($size >= 0){$this->db->limit($size, $limit);}
    $query = $this->db->get();
    
    return $query;
  }
  
  function add_data($param){
    //Set Param
    $id_brand = (isset($param['id_brand'])) ? $param['id_brand'] : 0;
    $name = (isset($param['name'])) ? $param['name'] : "";
    $price = (isset($param['price'])) ? $param['price'] : 0;
    $sale_price = (isset($param['sale_price'])) ? $param['sale_price'] : 0;
    $reseller_price = (isset($param['reseller_price'])) ? $param['reseller_price'] : 0;
    $weight = (isset($param['weight'])) ? $param['weight'] : 0;
    $attribute = (isset($param['attribute'])) ? $param['attribute'] : "";
    $description = (isset($param['description'])) ? $param['description'] : "";
    $short_description = (isset($param['short_description'])) ? $param['short_description'] : "";
    $info = (isset($param['info'])) ? $param['info'] : "";
    $size_guideline = (isset($param['size_guideline'])) ? $param['size_guideline'] : "";
    $category = (isset($param['category'])) ? $param['category'] : array();
    $category_child = (isset($param['category_child'])) ? $param['category_child'] : array();
    $category_child_ = (isset($param['category_child_'])) ? $param['category_child_'] : array();
    $active = (isset($param['active'])) ? $param['active'] : 0;
    //End Set Param
    
    $data = array(
      'id_brand' => $id_brand,
      'name' => $name,
      'price' => $price,
      'sale_price' => $sale_price,
      'reseller_price' => $reseller_price,
      'weight' => $weight,
      'attribute' => $attribute,
      'description' => $description,
      'short_description' => $short_description,
      'info' => $info,
      'price' => $price,
      'size_guideline' => $size_guideline,
      'active' => $active,
      'cretime' => date('Y-m-d H:i:s'),
      'creby' => $this->session->userdata('username')
    );
    $this->db->insert('products', $data);
    $insert_id = $this->db->insert_id();
    
    //add new category
    if(!empty($category)){
      foreach ($category as $cat) {
        $data = array(
          'id_category' => $cat,
          'id_products' => $insert_id,
          'cretime' => date('Y-m-d H:i:s'),
          'creby' => $this->session->userdata('username')
        );
        $this->db->insert('category_detail', $data);
      }
    }
    
    if(!empty($category_child)){
      foreach ($category_child as $cat_child) {
        $data = array(
          'id_category_child' => $cat_child,
          'id_products' => $insert_id,
          'cretime' => date('Y-m-d H:i:s'),
          'creby' => $this->session->userdata('username')
        );
        $this->db->insert('category_detail', $data);
      }
    }
    
    if(!empty($category_child_)){
      foreach ($category_child_ as $cat_child_) {
        $data = array(
          'id_category_child_' => $cat_child_,
          'id_products' => $insert_id,
          'cretime' => date('Y-m-d H:i:s'),
          'creby' => $this->session->userdata('username')
        );
        $this->db->insert('category_detail', $data);
      }
    }
    
    return $insert_id;
  }

  function edit_data($param){
    //Set Param
    $id = (isset($param['id'])) ? $param['id'] : 0;
    $id_brand = (isset($param['id_brand'])) ? $param['id_brand'] : 0;
    $name = (isset($param['name'])) ? $param['name'] : "";
    $price = (isset($param['price'])) ? $param['price'] : 0;
    $sale_price = (isset($param['sale_price'])) ? $param['sale_price'] : 0;
    $reseller_price = (isset($param['reseller_price'])) ? $param['reseller_price'] : 0;
    $weight = (isset($param['weight'])) ? $param['weight'] : 0;
    $attribute = (isset($param['attribute'])) ? $param['attribute'] : "";
    $description = (isset($param['description'])) ? $param['description'] : "";
    $short_description = (isset($param['short_description'])) ? $param['short_description'] : "";
    $info = (isset($param['info'])) ? $param['info'] : "";
    $size_guideline = (isset($param['size_guideline'])) ? $param['size_guideline'] : "";
    $category = (isset($param['category'])) ? $param['category'] : array();
    $category_child = (isset($param['category_child'])) ? $param['category_child'] : array();
    $category_child_ = (isset($param['category_child_'])) ? $param['category_child_'] : array();
    $price_logs_type = (isset($param['price_logs_type'])) ? $param['price_logs_type'] : array();
    $price_logs_initial_value = (isset($param['price_logs_initial_value'])) ? $param['price_logs_initial_value'] : array();
    $price_logs_changed_value = (isset($param['price_logs_changed_value'])) ? $param['price_logs_changed_value'] : array();
    $active = (isset($param['active'])) ? $param['active'] : 0;
    //End Set Param
    
    //Insert Price Logs
    if(!empty($price_logs_type)){
      for($i=0;$i<count($price_logs_type);$i++){
        $data = array(
          'product_id' => $id,
          'type' => $price_logs_type[$i],
          'initial_value' => $price_logs_initial_value[$i],
          'changed_value' => $price_logs_changed_value[$i],
          'modtime' => date('Y-m-d H:i:s'),
          'modby' => $this->session->userdata('username')
        );
        $this->db->insert('price_log', $data);
      }
    }
    //End Insert Price Logs
    
    $data = array(
      'id_brand' => $id_brand,
      'name' => $name,
      'price' => $price,
      'sale_price' => $sale_price,
      'reseller_price' => $reseller_price,
      'weight' => $weight,
      'attribute' => $attribute,
      'description' => $description,
      'short_description' => $short_description,
      'info' => $info,
      'price' => $price,
      'size_guideline' => $size_guideline,
      'active' => $active,
      'modtime' => date('Y-m-d H:i:s'),
      'modby' => $this->session->userdata('username')
    );
    
    $this->db->where('id', $id);
    $this->db->update('products', $data);
    
    //remove category
    $this->db->where('id_products', $id);
    $this->db->delete('category_detail');
    
    //add new category
    if(!empty($category)){
      foreach ($category as $cat) {
        $data = array(
          'id_category' => $cat,
          'id_products' => $id,
          'cretime' => date('Y-m-d H:i:s'),
          'creby' => $this->session->userdata('username')
        );
        $this->db->insert('category_detail', $data);
      }
    }
    
    if(!empty($category_child)){
      foreach ($category_child as $cat_child) {
        $data = array(
          'id_category_child' => $cat_child,
          'id_products' => $id,
          'cretime' => date('Y-m-d H:i:s'),
          'creby' => $this->session->userdata('username')
        );
        $this->db->insert('category_detail', $data);
      }
    }
    
    if(!empty($category_child_)){
      foreach ($category_child_ as $cat_child_) {
        $data = array(
          'id_category_child_' => $cat_child_,
          'id_products' => $id,
          'cretime' => date('Y-m-d H:i:s'),
          'creby' => $this->session->userdata('username')
        );
        $this->db->insert('category_detail', $data);
      }
    }
  }
  
  function remove_data($param){
    //Set Param
    $id = (isset($param['id'])) ? $param['id'] : 0;
    //End Set Param
    
    $data = array(
      'deleted' => 1,
      'modtime' => date('Y-m-d H:i:s'),
      'modby' => $this->session->userdata('username')
    );
    
    $this->db->where('id', $id);
    $this->db->update('products', $data);
  }
  
  function get_category_detail($param){
    //Set Param
    $id = (isset($param['id'])) ? $param['id'] : 0;
    //End Set Param
    
    $this->db->select('category_detail.*');
    $this->db->from('category_detail');
    
    //Validation
    $this->db->where('category_detail.id_products', $id);
    //End Validation
    
    $query = $this->db->get();
    
    return $query;
  }
}
