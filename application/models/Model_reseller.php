<?php

class Model_reseller extends CI_Model {
  function __construct() {
    parent::__construct();
  }

  function get_data($param, $limit = 0, $size = 0) {
    //Set Param
    $id = (isset($param['id'])) ? $param['id'] : 0;
    $name = (isset($param['name'])) ? $param['name'] : "";
    $store_name = (isset($param['store_name'])) ? $param['store_name'] : "";
    $email = (isset($param['email'])) ? $param['email'] : "";
    $phone = (isset($param['phone'])) ? $param['phone'] : "";
    $status = (isset($param['status'])) ? $param['status'] : -1;
    $minimum_wallet = (isset($param['minimum_wallet'])) ? $param['minimum_wallet'] : -1;
    $order = (isset($param['order'])) ? $param['order'] : -1;
    //End Set Param
    
    $this->db->select('reseller.*');
    $this->db->from('reseller');
    
    //Validation
    if($id > 0){$this->db->where('reseller.id', $id);}
    if($name != ""){$this->db->like('reseller.name', $name);}
    if($store_name != ""){$this->db->like('reseller.store_name', $store_name);}
    if($email != ""){$this->db->like('reseller.email', $email);}
    if($phone != ""){$this->db->like('reseller.phone', $phone);}
    if($status > -1){$this->db->where('reseller.status', $status);}
    if($minimum_wallet == 1){
      $this->db->where('reseller.wallet >= (SELECT content FROM static_content WHERE TYPE = 7)');
    }
    //End Validation
    
    if($order == 1){
      $this->db->order_by("reseller.name", "desc");
    }else if($order == 2){
      $this->db->order_by("reseller.store_name", "asc");
    }else if($order == 3){
      $this->db->order_by("reseller.store_name", "desc");
    }else if($order == 4){
      $this->db->order_by("reseller.cretime", "desc");
    }else if($order == 5){
      $this->db->order_by("reseller.cretime", "asc");
    }else{
      $this->db->order_by("reseller.name", "asc");
    }
    
    if($size >= 0){$this->db->limit($size, $limit);}
    $query = $this->db->get();
    
    return $query;
  }
  
  function set_status($param){
    //Post Data
    $id = (isset($param['id'])) ? $param['id'] : 0;
    $status = (isset($param['status'])) ? $param['status'] : 0;
    //End Post Data
    
    $data = array(
      'status' => $status,
      'modtime' => date('Y-m-d H:i:s')
    );
    
    $this->db->where('id', $id);
    $this->db->update('reseller', $data);
  }
}
