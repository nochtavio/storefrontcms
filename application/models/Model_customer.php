<?php

class Model_customer extends CI_Model {
  function __construct() {
    parent::__construct();
  }

  function get_data($param, $limit = 0, $size = 0) {
    //Set Param
    $customer_id = (isset($param['customer_id'])) ? $param['customer_id'] : 0;
    $customer_email = (isset($param['customer_email'])) ? $param['customer_email'] : "";
    $exact_customer_email = (isset($param['exact_customer_email'])) ? $param['exact_customer_email'] : "";
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
    if($exact_customer_email != ""){$this->db->where('customer.customer_email', $exact_customer_email);}
    if($name != ""){$this->db->like('customer.customer_fname', $name);$this->db->or_like('customer.customer_lname', $name); }
    if($customer_gender > -1){$this->db->where('customer.customer_gender', $customer_gender);}
    if($customer_province != ""){$this->db->like('customer.customer_email', $customer_email);}
    if($customer_city != ""){$this->db->like('customer.customer_email', $customer_email);}
    if($customer_status > -1){$this->db->where('customer.customer_status', $customer_status);}
    //End Validation

    $this->db->where('customer.store_id', 0);
    if($order == 1){
      $this->db->order_by("customer.customer_registration_date", "asc");
    }else if($order == 2){
      $this->db->order_by("customer.customer_email", "asc");
    }else if($order == 3){
      $this->db->order_by("customer.customer_email", "desc");
    }else if($order == 4){
      $this->db->order_by("customer.customer_fname", "asc");
    }else if($order == 5){
      $this->db->order_by("customer.customer_fname", "desc");
    }else{
      $this->db->order_by("customer.customer_registration_date", "desc");
    }

    if($size >= 0){$this->db->limit($size, $limit);}
    $query = $this->db->get();

    return $query;
  }

  function edit_data($param){
    //Set Param
    $customer_id = (isset($param['customer_id'])) ? $param['customer_id'] : 0;
    $customer_status = (isset($param['customer_status'])) ? $param['customer_status'] : 0;
    //End Set Param

    $data = array(
      'customer_status' => $customer_status,
      'last_modified' => date('Y-m-d H:i:s')
    );

    $this->db->where('customer_id', $customer_id);
    $this->db->update('customer', $data);
  }
}
