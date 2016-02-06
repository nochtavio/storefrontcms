<?php

class Model_admin extends CI_Model {
  function __construct() {
    parent::__construct();
  }

  function get_data($param, $limit = 0, $size = 0) {
    //Set Param
    $id = (isset($param['id'])) ? $param['id'] : 0;
    $username = (isset($param['username'])) ? $param['username'] : "";
    $active = (isset($param['active'])) ? $param['active'] : -1;
    $order = (isset($param['order'])) ? $param['order'] : -1;
    //End Set Param
    
    $this->db->select('admin.*');
    $this->db->from('admin');
    
    //Validation
    if($id > 0){$this->db->where('admin.id', $id);}
    if($username != ""){$this->db->like('admin.username', $username);}
    if($active > -1){$this->db->where('admin.active', $active);}
    //End Validation
    
    $this->db->where('admin.deleted', 0);
    if($order == 1){
      $this->db->order_by("admin.username", "desc");
    }else if($order == 2){
      $this->db->order_by("admin.cretime", "desc");
    }else if($order == 3){
      $this->db->order_by("admin.cretime", "asc");
    }else{
      $this->db->order_by("admin.username", "asc");
    }
    
    if($size >= 0){$this->db->limit($size, $limit);}
    $query = $this->db->get();
    
    return $query;
  }
  
  function add_data($param){
    //Set Param
    $username = (isset($param['username'])) ? $param['username'] : "";
    $password = (isset($param['password'])) ? $param['password'] : "";
    $active = (isset($param['active'])) ? $param['active'] : 0;
    //End Set Param
    
    $data = array(
      'username' => $username,
      'password' => $password,
      'active' => $active,
      'cretime' => date('Y-m-d H:i:s'),
      'creby' => 'SYSTEM'
    );
    $this->db->insert('admin', $data);
    $insert_id = $this->db->insert_id();
    
    return $insert_id;
  }

  function edit_data($param){
    //Set Param
    $id = (isset($param['id'])) ? $param['id'] : 0;
    $password = (isset($param['password'])) ? $param['password'] : "";
    $active = (isset($param['active'])) ? $param['active'] : 0;
    //End Set Param
    
    if($password == ""){
      $data = array(
        'active' => $param['active'],
        'modtime' => date('Y-m-d H:i:s'),
        'modby' => 'SYSTEM'
      );
    }else{
      $data = array(
        'password' => $password,
        'active' => $active,
        'modtime' => date('Y-m-d H:i:s'),
        'modby' => 'SYSTEM'
      );
    }
    
    $this->db->where('id', $id);
    $this->db->update('admin', $data);
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
    $this->db->update('admin', $data);
  }
}
