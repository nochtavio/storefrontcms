<?php

class Model_products extends CI_Model {
  function __construct() {
    parent::__construct();
  }

  function get_data($param, $limit = 0, $size = 0) {
    $this->db->select('products.*');
    $this->db->from('admin');
    
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
    $data = array(
      'username' => $param['username'],
      'password' => $param['password'],
      'active' => $param['active'],
      'cretime' => date('Y-m-d H:i:s'),
      'creby' => 'SYSTEM'
    );
    $this->db->insert('admin', $data);
    $insert_id = $this->db->insert_id();
    
    return $insert_id;
  }

  function edit_data($param){
    if($param['password'] == ""){
      $data = array(
        'active' => $param['active'],
        'modtime' => date('Y-m-d H:i:s'),
        'modby' => 'SYSTEM'
      );
    }else{
      $data = array(
        'password' => $param['password'],
        'active' => $param['active'],
        'modtime' => date('Y-m-d H:i:s'),
        'modby' => 'SYSTEM'
      );
    }
    
    $this->db->where('id', $param['id']);
    $this->db->update('admin', $data);
  }
  
  function remove_data($param){
    $data = array(
      'deleted' => 1,
      'modtime' => date('Y-m-d H:i:s'),
      'modby' => 'SYSTEM'
    );
    
    $this->db->where('id', $param['id']);
    $this->db->update('admin', $data);
  }
}
