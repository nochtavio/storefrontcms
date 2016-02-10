<?php

class Model_category_detail extends CI_Model {
  function __construct() {
    parent::__construct();
  }

  function get_data($param, $limit = 0, $size = 0) {
    //Set Param
    $id = (isset($param['id'])) ? $param['id'] : 0;
    $id_category_child = (isset($param['$id_category_child'])) ? $param['$id_category_child'] : 0;
    $name = (isset($param['name'])) ? $param['name'] : "";
    $active = (isset($param['active'])) ? $param['active'] : -1;
    $order = (isset($param['order'])) ? $param['order'] : -1;
    //End Set Param
    
    $this->db->select('category_detail.*, products.name AS products_name');
    $this->db->from('category_detail');
    $this->db->join('products', 'products.id = category_detail.id_products');
    
    //Validation
    if($id > 0){$this->db->where('category_detail.id', $id);}
    if($id_category_child > 0){$this->db->where('category_detail.id_category', $id_category_child);}
    if($name != ""){$this->db->like('products.name', $name);}
    if($active > -1){$this->db->where('category_detail.active', $active);}
    //End Validation
    
    $this->db->where('category_detail.deleted', 0);
    if($order == 1){
      $this->db->order_by("products.name", "desc");
    }else if($order == 2){
      $this->db->order_by("category_detail.cretime", "desc");
    }else if($order == 3){
      $this->db->order_by("products.cretime", "asc");
    }else{
      $this->db->order_by("category_detail.name", "asc");
    }
    
    if($size >= 0){$this->db->limit($size, $limit);}
    $query = $this->db->get();
    
    return $query;
  }
  
  function add_data($param){
    //Set Param
    $id_category_child = (isset($param['id_category_child'])) ? $param['id_category_child'] : 0;
    $id_products = (isset($param['id_products'])) ? $param['id_products'] : 0;
    $active = (isset($param['active'])) ? $param['active'] : 0;
    //End Set Param
    
    $data = array(
      'id_category_child' => $id_category_child,
      'id_products' => $id_products,
      'active' => $active,
      'cretime' => date('Y-m-d H:i:s'),
      'creby' => 'SYSTEM'
    );
    $this->db->insert('category_detail', $data);
    $insert_id = $this->db->insert_id();
    
    return $insert_id;
  }

  function edit_data($param){
    //Set Param
    $id = (isset($param['id'])) ? $param['id'] : 0;
    $id_products = (isset($param['id_products'])) ? $param['id_products'] : 0;
    $active = (isset($param['active'])) ? $param['active'] : 0;
    //End Set Param
    
    $data = array(
      'id_products' => $id_products,
      'active' => $active,
      'modtime' => date('Y-m-d H:i:s'),
      'modby' => 'SYSTEM'
    );
    
    $this->db->where('id', $id);
    $this->db->update('category_detail', $data);
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
    $this->db->update('category_detail', $data);
  }
}
