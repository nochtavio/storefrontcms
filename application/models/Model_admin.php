<?php

class Model_admin extends CI_Model {
  function __construct() {
    parent::__construct();
  }

  function get_data($id = 0, $username = "", $active = -1, $order = -1, $limit = 0, $size = 0) {
    $this->db->select('ma.*');
    $this->db->from('ms_admin ma');
    
    //Validation
    if($id > 0){$this->db->where('ma.id', $id);}
    if($username != ""){$this->db->like('ma.username', $username);}
    if($active > -1){$this->db->where('ma.active', $active);}
    //End Validation
    
    $this->db->where('ma.deleted', 0);
    if($order == 1){
      $this->db->order_by("ma.username", "desc");
    }else if($order == 2){
      $this->db->order_by("ma.cretime", "desc");
    }else if($order == 3){
      $this->db->order_by("ma.cretime", "asc");
    }else{
      $this->db->order_by("ma.username", "asc");
    }
    
    if($size >= 0){$this->db->limit($size, $limit);}
    $query = $this->db->get();
    
    return $query;
  }
  
  function add_data($username, $password, $active){
    $data = array(
      'username' => $username,
      'password' => $password,
      'active' => $active,
      'cretime' => date('Y-m-d H:i:s'),
      'creby' => 'SYSTEM'
    );
    $this->db->insert('ms_admin', $data);
    $insert_id = $this->db->insert_id();
    
    return $insert_id;
  }

  function edit_data($id, $password, $active){
    if($password == ""){
      $data = array(
        'active' => $active,
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
    $this->db->update('ms_admin', $data);
  }
  
  function remove_data($id){
    $data = array(
      'deleted' => 1,
      'modtime' => date('Y-m-d H:i:s'),
      'modby' => 'SYSTEM'
    );
    
    $this->db->where('id', $id);
    $this->db->update('ms_admin', $data);
  }
}
