<?php

class Model_customer extends CI_Model {
  function __construct() {
    parent::__construct();
  }

  function get_data($param, $limit = 0, $size = 0) {
    //Set Param
    $customer_id = (isset($param['customer_id'])) ? $param['customer_id'] : 0;
    $customer_email = (isset($param['customer_email'])) ? $param['customer_email'] : "";
    $name = (isset($param['name'])) ? $param['name'] : "";
    $customer_gender = (isset($param['customer_gender'])) ? $param['customer_gender'] : -1;
    $customer_province = (isset($param['customer_province'])) ? $param['customer_province'] : "";
    $customer_city = (isset($param['customer_city'])) ? $param['customer_city'] : "";
    $customer_status = (isset($param['customer_status'])) ? $param['customer_status'] : -1;
    $order = (isset($param['order'])) ? $param['order'] : -1;
    //End Set Param
    
    $this->db->select('customer.*');
    $this->db->from('customer');
    
    //Validation
    if($customer_id > 0){$this->db->where('customer.customer_id', $customer_id);}
    if($customer_email != ""){$this->db->like('customer.customer_email', $customer_email);}
    if($name != ""){$this->db->where('customer.customer_fname', $name);$this->db->or_where('customer.customer_lname', $name); }
    if($customer_gender > -1){$this->db->where('customer.customer_gender', $customer_gender);}
    if($customer_province != ""){$this->db->like('customer.customer_email', $customer_email);}
    if($customer_city != ""){$this->db->like('customer.customer_email', $customer_email);}
    if($active > -1){$this->db->where('customer.active', $active);}
    //End Validation
    
    $this->db->where('customer.deleted', 0);
    if($order == 1){
      $this->db->order_by("customer.name", "desc");
    }else if($order == 2){
      $this->db->order_by("customer.cretime", "desc");
    }else if($order == 3){
      $this->db->order_by("customer.cretime", "asc");
    }else{
      $this->db->order_by("customer.name", "asc");
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
      'creby' => $this->session->userdata('username')
    );
    $this->db->insert('customer', $data);
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
      'modby' => $this->session->userdata('username')
    );
    
    $this->db->where('id', $id);
    $this->db->update('customer', $data);
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
    $this->db->update('customer', $data);
  }
}
