<?php

class Model_products extends CI_Model {
  function __construct() {
    parent::__construct();
  }

  function get_data($param, $limit = 0, $size = 0) {
    //Set Param
    $id = (isset($param['id'])) ? $param['id'] : 0;
    $name = (isset($param['name'])) ? $param['name'] : "";
    $active = (isset($param['active'])) ? $param['active'] : -1;
    $order = (isset($param['order'])) ? $param['order'] : -1;
    //End Set Param
    
    $this->db->select('products.*');
    $this->db->from('products');
    
    //Validation
    if($id > 0){$this->db->where('products.id', $id);}
    if($name != ""){$this->db->like('products.name', $name);}
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
    $active = (isset($param['active'])) ? $param['active'] : 0;
    //End Set Param
    
    $data = array(
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
      'creby' => 'SYSTEM'
    );
    $this->db->insert('products', $data);
    $insert_id = $this->db->insert_id();
    
    return $insert_id;
  }

  function edit_data($param){
    //Set Param
    $id = (isset($param['id'])) ? $param['id'] : 0;
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
    $active = (isset($param['active'])) ? $param['active'] : 0;
    //End Set Param
    
    $data = array(
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
      'modby' => 'SYSTEM'
    );
    
    $this->db->where('id', $id);
    $this->db->update('products', $data);
  }
  
  function remove_data($param){
    //Set Param
    $id = (isset($param['id'])) ? $param['id'] : 0;
    //End Set Param
    
    $data = array(
      'deleted' => 1,
      'modtime' => date('Y-m-d H:i:s'),
      'modby' => 'SYSTEM'
    );
    
    $this->db->where('id', $id);
    $this->db->update('products', $data);
  }
}
