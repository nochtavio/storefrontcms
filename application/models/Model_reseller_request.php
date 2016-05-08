<?php

class Model_reseller_request extends CI_Model {
  function __construct() {
    parent::__construct();
  }

  function get_data($param, $limit = 0, $size = 0) {
    //Set Param
    $id = (isset($param['id'])) ? $param['id'] : 0;
    $name = (isset($param['name'])) ? $param['name'] : "";
    $email = (isset($param['email'])) ? $param['email'] : "";
    $phone = (isset($param['phone'])) ? $param['phone'] : "";
    $barang = (isset($param['barang'])) ? $param['barang'] : "";
    $promosi = (isset($param['promosi'])) ? $param['promosi'] : "";
    $domain = (isset($param['domain'])) ? $param['domain'] : "";
    $order = (isset($param['order'])) ? $param['order'] : -1;
    //End Set Param
    
    $this->db->select('reseller_request.*');
    $this->db->from('reseller_request');
    
    //Validation
    if($id > 0){$this->db->where('reseller_request.id', $id);}
    if($name != ""){$this->db->like('reseller_request.name', $name);}
    if($email != ""){$this->db->like('reseller_request.email', $email);}
    if($phone != ""){$this->db->like('reseller_request.phone', $phone);}
    if($barang != ""){$this->db->like('reseller_request.barang', $barang);}
    if($promosi != ""){$this->db->like('reseller_request.promosi', $promosi);}
    if($domain != ""){$this->db->like('reseller_request.domain', $domain);}
    //End Validation
    
    if($order == 1){
      $this->db->order_by("reseller_request.name", "desc");
    }else if($order == 2){
      $this->db->order_by("reseller_request.cretime", "desc");
    }else if($order == 3){
      $this->db->order_by("reseller_request.cretime", "asc");
    }else{
      $this->db->order_by("reseller_request.name", "asc");
    }
    
    if($size >= 0){$this->db->limit($size, $limit);}
    $query = $this->db->get();
    
    return $query;
  }
  
  function approval($param){
    //Remove from reseller_request
    $id = (isset($param['id'])) ? $param['id'] : 0;
    $this->db->where('id', $id);
    $this->db->delete('reseller_request');
    
    //Insert to reseller
    $name = (isset($param['name'])) ? $param['name'] : "";
    $email = (isset($param['email'])) ? $param['email'] : "";
    $phone = (isset($param['phone'])) ? $param['phone'] : "";
    $upline_email = (isset($param['upline_email'])) ? $param['upline_email'] : NULL;
    $password = random_string('alnum', 4);
    
    $data = array(
      'email' => $email,
      'name' => $name,
      'phone' => $phone,
      'password' => sha1(md5($password)),
      'status' => 1,
      'cretime' => date('Y-m-d H:i:s')
    );
    $this->db->insert('reseller', $data);
    
    $data['insert_id'] = $this->db->insert_id();
    $data['password'] = $password;
    
    //Insert Reseller Level
    if($upline_email !== NULL){
      $this->db->select('id');
      $this->db->from('reseller');
      $this->db->where('email', $upline_email);
      $fetch_id_reseller = $this->db->get();
      
      $data_reseller_level = array(
        'upline_id' => $fetch_id_reseller->row()->id,
        'downline_id' => $data['insert_id']
      );
      $this->db->insert('reseller_level', $data_reseller_level);
    }
    //End Insert Reseller Level
    
    return $data;
  }
}
