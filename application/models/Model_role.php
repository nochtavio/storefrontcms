<?php

class Model_role extends CI_Model {
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
    
    $this->db->select('role.*');
    $this->db->from('role');
    
    //Validation
    if($id > 0){$this->db->where('role.id', $id);}
    if($name != ""){$this->db->like('role.name', $name);}
    if($active > -1){$this->db->where('role.active', $active);}
    //End Validation
    
    $this->db->where('role.deleted', 0);
    //$this->db->where('role.id !=', 1);
    if($order == 1){
      $this->db->order_by("role.name", "desc");
    }else if($order == 2){
      $this->db->order_by("role.cretime", "desc");
    }else if($order == 3){
      $this->db->order_by("role.cretime", "asc");
    }else{
      $this->db->order_by("role.name", "asc");
    }
    
    if($size >= 0){$this->db->limit($size, $limit);}
    $query = $this->db->get();
    
    return $query;
  }
  
  function add_data($param){
    //Set Param
    $name = (isset($param['name'])) ? $param['name'] : "";
    $menu = (isset($param['menu'])) ? $param['menu'] : array();
    $active = (isset($param['active'])) ? $param['active'] : 0;
    //End Set Param
    
    $data = array(
      'name' => $name,
      'active' => $active,
      'cretime' => date('Y-m-d H:i:s'),
      'creby' => $this->session->userdata('username')
    );
    $this->db->insert('role', $data);
    $insert_id = $this->db->insert_id();
    
    //add new menu
    if(!empty($menu)){
      foreach ($menu as $m) {
        $data = array(
          'id_menu' => $m,
          'id_role' => $insert_id
        );
        $this->db->insert('role_menu', $data);
      }
    }
    
    return $insert_id;
  }

  function edit_data($param){
    //Set Param
    $id = (isset($param['id'])) ? $param['id'] : 0;
    $name = (isset($param['name'])) ? $param['name'] : "";
    $menu = (isset($param['menu'])) ? $param['menu'] : array();
    $active = (isset($param['active'])) ? $param['active'] : 0;
    //End Set Param
    
    $data = array(
      'name' => $name,
      'active' => $active,
      'modtime' => date('Y-m-d H:i:s'),
      'modby' => $this->session->userdata('username')
    );
    
    $this->db->where('id', $id);
    $this->db->update('role', $data);
    
    //remove menu
    $this->db->where('id_role', $id);
    $this->db->delete('role_menu');
    
    //add new menu
    if(!empty($menu)){
      foreach ($menu as $m) {
        $data = array(
          'id_menu' => $m,
          'id_role' => $id
        );
        $this->db->insert('role_menu', $data);
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
    $this->db->update('role', $data);
  }
}
