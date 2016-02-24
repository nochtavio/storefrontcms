<?php

class Model_category extends CI_Model {
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
    
    $this->db->select('category.*');
    $this->db->from('category');
    
    //Validation
    if($id > 0){$this->db->where('category.id', $id);}
    if($name != ""){$this->db->like('category.name', $name);}
    if($active > -1){$this->db->where('category.active', $active);}
    //End Validation
    
    $this->db->where('category.deleted', 0);
    if($order == 1){
      $this->db->order_by("category.name", "desc");
    }else if($order == 2){
      $this->db->order_by("category.cretime", "desc");
    }else if($order == 3){
      $this->db->order_by("category.cretime", "asc");
    }else{
      $this->db->order_by("category.name", "asc");
    }
    
    if($size >= 0){$this->db->limit($size, $limit);}
    $query = $this->db->get();
    
    return $query;
  }
  
  function add_data($param){
    //Set Param
    $name = (isset($param['name'])) ? $param['name'] : "";
    $active = (isset($param['active'])) ? $param['active'] : 0;
    //End Set Param
    
    $data = array(
      'name' => $name,
      'active' => $active,
      'cretime' => date('Y-m-d H:i:s'),
      'creby' => 'SYSTEM'
    );
    $this->db->insert('category', $data);
    $insert_id = $this->db->insert_id();
    
    return $insert_id;
  }

  function edit_data($param){
    //Set Param
    $id = (isset($param['id'])) ? $param['id'] : 0;
    $name = (isset($param['name'])) ? $param['name'] : "";
    $active = (isset($param['active'])) ? $param['active'] : 0;
    //End Set Param
    
    $data = array(
      'name' => $name,
      'active' => $active,
      'modtime' => date('Y-m-d H:i:s'),
      'modby' => 'SYSTEM'
    );
    
    $this->db->where('id', $id);
    $this->db->update('category', $data);
    
    //Unset Child
    if($active == 0){
      $data = array(
        'active' => 0,
        'modtime' => date('Y-m-d H:i:s'),
        'modby' => 'SYSTEM'
      );

      $this->db->where('id_category', $id);
      $this->db->update('category_child', $data);
      
      $this->db->select('id');
      $this->db->from('category_child');
      $this->db->where('id_category', $id);
      $result_category_child = $this->db->get();
      if ($result_category_child->num_rows() > 0) {
        foreach ($result_category_child->result() as $row) {
          $this->db->where('id_category_child', $row->id);
          $this->db->delete('category_detail');
        }
      }
      
      $this->db->where('id_category', $id);
      $this->db->delete('category_brand');
    }
    //End Unset Child
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
    $this->db->update('category', $data);
    
    //Delete Child
    $this->db->where('id_category', $id);
    $this->db->update('category_child', $data);

    $this->db->select('id');
    $this->db->from('category_child');
    $this->db->where('id_category', $id);
    $result_category_child = $this->db->get();
    if ($result_category_child->num_rows() > 0) {
      foreach ($result_category_child->result() as $row) {
        $this->db->where('id_category_child', $row->id);
        $this->db->delete('category_detail');
      }
    }
    
    $this->db->where('id_category', $id);
    $this->db->delete('category_brand');
    //End Delete Child
  }
}
