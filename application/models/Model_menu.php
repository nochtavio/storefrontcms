<?php

class Model_menu extends CI_Model {
  function __construct() {
    parent::__construct();
  }

  function get_data($param, $limit = 0, $size = 0) {
    //Set Param
    $id = (isset($param['id'])) ? $param['id'] : 0;
    $name = (isset($param['name'])) ? $param['name'] : "";
    $type = (isset($param['type'])) ? $param['type'] : 0;
    //End Set Param
    
    $this->db->select('menu.*');
    $this->db->from('menu');
    
    //Validation
    if($id > 0){$this->db->where('menu.id', $id);}
    if($name != ""){$this->db->like('menu.name', $name);}
    if($type > 0){$this->db->where('menu.type', $type);}
    //End Validation
    
    $this->db->order_by("menu.name", "asc");
    $this->db->order_by("menu.type", "asc");
    
    if($size >= 0){$this->db->limit($size, $limit);}
    $query = $this->db->get();
    
    return $query;
  }
  
  function get_role_menu($param){
    //Set Param
    $id = (isset($param['id'])) ? $param['id'] : 0;
    $id_menu = (isset($param['id_menu'])) ? $param['id_menu'] : 0;
    $id_role = (isset($param['id_role'])) ? $param['id_role'] : 0;
    //End Set Param
    
    $this->db->select('role_menu.*');
    $this->db->from('role_menu');
    
    //Validation
    if($id > 0){$this->db->where('role_menu.id', $id);}
    if($id_menu > 0){$this->db->where('role_menu.id_menu', $id_menu);}
    if($id_role > 0){$this->db->where('role_menu.id_role', $id_role);}
    //End Validation
    
    $query = $this->db->get();
    return $query;
  }
}
