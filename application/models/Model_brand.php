<?php

class Model_brand extends CI_Model {
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
    
    $this->db->select('brand.*');
    $this->db->from('brand');
    
    //Validation
    if($id > 0){$this->db->where('brand.id', $id);}
    if($name != ""){$this->db->like('brand.name', $name);}
    if($active > -1){$this->db->where('brand.active', $active);}
    //End Validation
    
    $this->db->where('brand.deleted', 0);
    if($order == 1){
      $this->db->order_by("brand.name", "desc");
    }else if($order == 2){
      $this->db->order_by("brand.cretime", "desc");
    }else if($order == 3){
      $this->db->order_by("brand.cretime", "asc");
    }else{
      $this->db->order_by("brand.name", "asc");
    }
    
    if($size >= 0){$this->db->limit($size, $limit);}
    $query = $this->db->get();
    
    return $query;
  }
  
  function add_data($param){
    //Set Param
    $name = (isset($param['name'])) ? $param['name'] : "";
    $img = (isset($param['img'])) ? $param['img'] : "";
    $category = (isset($param['category'])) ? $param['category'] : array();
    $active = (isset($param['active'])) ? $param['active'] : 0;
    //End Set Param
    
    if(!is_array($category)){
      $category = explode(",", $param['category']);
    }
    
    $data = array(
      'name' => $name,
      'img' => $img,
      'active' => $active,
      'cretime' => date('Y-m-d H:i:s'),
      'creby' => $this->session->userdata('username')
    );
    $this->db->insert('brand', $data);
    $insert_id = $this->db->insert_id();
    
    //add new category
    if(!empty($category)){
      foreach ($category as $cat) {
        $data = array(
          'id_category' => $cat,
          'id_brand' => $insert_id,
          'cretime' => date('Y-m-d H:i:s'),
          'creby' => $this->session->userdata('username')
        );
        $this->db->insert('category_brand', $data);
      }
    }
    
    return $insert_id;
  }

  function edit_data($param){
    //Set Param
    $id = (isset($param['id'])) ? $param['id'] : 0;
    $name = (isset($param['name'])) ? $param['name'] : "";
    $img = (isset($param['img'])) ? $param['img'] : "";
    $category = (isset($param['category'])) ? $param['category'] : array();
    $active = (isset($param['active'])) ? $param['active'] : 0;
    //End Set Param
    
    if(!is_array($category)){
      $category = explode(",", $param['category']);
    }
    
    $data = array(
      'name' => $name,
      'img' => $img,
      'active' => $active,
      'modtime' => date('Y-m-d H:i:s'),
      'modby' => $this->session->userdata('username')
    );
    
    $this->db->where('id', $id);
    $this->db->update('brand', $data);
    
    //remove category
    $this->db->where('id_brand', $id);
    $this->db->delete('category_brand');
    
    //add new category
    if(!empty($category)){
      foreach ($category as $cat) {
        $data = array(
          'id_category' => $cat,
          'id_brand' => $id,
          'cretime' => date('Y-m-d H:i:s'),
          'creby' => $this->session->userdata('username')
        );
        $this->db->insert('category_brand', $data);
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
    $this->db->update('brand', $data);
  }
  
  function get_category($param){
    //Set Param
    $id = (isset($param['id'])) ? $param['id'] : 0;
    $id_category = (isset($param['id_category'])) ? $param['id_category'] : 0;
    //End Set Param
    
    $this->db->select('category_brand.*');
    $this->db->from('category_brand');
    
    //Validation
    if($id > 0){$this->db->where('category_brand.id_brand', $id);}
    if($id_category > 0){$this->db->where('category_brand.id_category', $id_category);}
    //End Validation
    
    $query = $this->db->get();
    
    return $query;
  }
}
