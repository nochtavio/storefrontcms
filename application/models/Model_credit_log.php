<?php

class Model_credit_log extends CI_Model {
  function __construct() {
    parent::__construct();
  }

  function get_data($param, $limit = 0, $size = 0) {
    //Set Param
    $id = (isset($param['id'])) ? $param['id'] : 0;
    $customer_email = (isset($param['customer_email'])) ? $param['customer_email'] : "";
    $type = (isset($param['type'])) ? $param['type'] : -1;
    $status = (isset($param['status'])) ? $param['status'] : -1;
    $order = (isset($param['order'])) ? $param['order'] : -1;
    //End Set Param
    
    $this->db->select('credit_log.*, customer.customer_email');
    $this->db->from('credit_log');
    $this->db->join('customer', 'customer.customer_id = credit_log.id_customer');
    
    //Validation
    if($id > 0){$this->db->where('credit_log.id', $id);}
    if($customer_email != ""){$this->db->like('customer.customer_email', $customer_email);}
    if($type > -1){$this->db->where('credit_log.type', $type);}
    if($status > -1){$this->db->where('credit_log.status', $status);}
    //End Validation
    
    if($order == 1){
      $this->db->order_by("credit_log.id", "asc");
    }else{
      $this->db->order_by("credit_log.id", "desc");
    }
    
    if($size >= 0){$this->db->limit($size, $limit);}
    $query = $this->db->get();
    
    return $query;
  }
  
  function edit_data($param){
    //Set Param
    $id = (isset($param['id'])) ? $param['id'] : 0;
    $id_customer = (isset($param['id_customer'])) ? $param['id_customer'] : 0;
    $status = (isset($param['status'])) ? $param['status'] : 0;
    $updated_credit = (isset($param['updated_credit'])) ? $param['updated_credit'] : NULL;
    //End Set Param
    
    $data = array(
      'status' => $status,
      'modtime' => date('Y-m-d H:i:s'),
      'modby' => $this->session->userdata('username')
    );
    
    $this->db->where('id', $id);
    $this->db->update('credit_log', $data);
    
    //Update Customer Credit
    if($updated_credit !== NULL){
      $data_update_order_item = array(
        'customer_credit' => $updated_credit
      );

      $this->db->where('customer_id', $id_customer);
      $this->db->update('customer', $data_update_order_item);
    }
    //End Update Customer Credit
  }
}
