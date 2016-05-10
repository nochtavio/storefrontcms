<?php

class Model_admin extends CI_Model {
  function __construct() {
    parent::__construct();
  }

  function get_data($param, $limit = 0, $size = 0) {
    //Set Param
    $id = (isset($param['id'])) ? $param['id'] : 0;
    $id_role = (isset($param['id_role'])) ? $param['id_role'] : 0;
    $username = (isset($param['username'])) ? $param['username'] : "";
    $active = (isset($param['active'])) ? $param['active'] : -1;
    $order = (isset($param['order'])) ? $param['order'] : -1;
    //End Set Param
    
    $this->db->select('admin.*, role.name AS role_name');
    $this->db->from('admin');
    $this->db->join('role', 'role.id = admin.id_role');
    
    //Validation
    if($id > 0){$this->db->where('admin.id', $id);}
    if($id_role > 0){$this->db->where('admin.id_role', $id_role);}
    if($username != ""){$this->db->like('admin.username', $username);}
    if($active > -1){$this->db->where('admin.active', $active);}
    //End Validation
    
    $this->db->where('admin.deleted', 0);
    //$this->db->where('admin.id_role !=', 1);
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
    $id_role = (isset($param['id_role'])) ? $param['id_role'] : 0;
    $username = (isset($param['username'])) ? $param['username'] : "";
    $password = (isset($param['password'])) ? $param['password'] : "";
    $active = (isset($param['active'])) ? $param['active'] : 0;
    //End Set Param
    
    $data = array(
      'id_role' => $id_role,
      'username' => $username,
      'password' => hash('sha1', $password.get_salt()),
      'active' => $active,
      'cretime' => date('Y-m-d H:i:s'),
      'creby' => $this->session->userdata('username')
    );
    $this->db->insert('admin', $data);
    $insert_id = $this->db->insert_id();
    
    return $insert_id;
  }

  function edit_data($param){
    //Set Param
    $id = (isset($param['id'])) ? $param['id'] : 0;
    $id_role = (isset($param['id_role'])) ? $param['id_role'] : 0;
    $password = (isset($param['password'])) ? $param['password'] : "";
    $active = (isset($param['active'])) ? $param['active'] : 0;
    //End Set Param
    
    if($password == ""){
      $data = array(
        'id_role' => $id_role,
        'active' => $active,
        'modtime' => date('Y-m-d H:i:s'),
        'modby' => $this->session->userdata('username')
      );
    }else{
      $data = array(
        'id_role' => $id_role,
        'password' => hash('sha1', $password.get_salt()),
        'active' => $active,
        'modtime' => date('Y-m-d H:i:s'),
        'modby' => $this->session->userdata('username')
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
      'modby' => $this->session->userdata('username')
    );
    
    $this->db->where('id', $id);
    $this->db->update('admin', $data);
  }
  
  function get_menu($param){
    //Set Param
    $menu = (isset($param['menu'])) ? $param['menu'] : "";
    $type = (isset($param['type'])) ? $param['type'] : 0;
    //End Set Param
    
    if($type == 0){
      $this->db->select('menu.index AS allowed_id');
    }else if($type == 1){
      $this->db->select('menu.add AS allowed_id');
    }else if($type == 2){
      $this->db->select('menu.edit AS allowed_id');
    }else if($type == 3){
      $this->db->select('menu.delete AS allowed_id');
    }
    
    $this->db->from('menu');
    $this->db->where('menu.name', $menu);
    
    $query = $this->db->get();
    return $query;
  }
}
