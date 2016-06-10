<?php

class Model_credit_log extends CI_Model {
  function __construct() {
    parent::__construct();
  }

  function get_data($param, $limit = 0, $size = 0) {
    //Set Param
    $id = (isset($param['id'])) ? $param['id'] : 0;
    $email = (isset($param['email'])) ? $param['email'] : "";
    $type = (isset($param['type'])) ? $param['type'] : -1;
    $credit_log_type = (isset($param['credit_log_type'])) ? $param['credit_log_type'] : -1; //1: Customer || 2: Reseller
    $status = (isset($param['status'])) ? $param['status'] : -1;
    $order = (isset($param['order'])) ? $param['order'] : -1;
    //End Set Param

    $this->db->select('credit_log.*, customer.customer_email, reseller.email');
    $this->db->from('credit_log');
    $this->db->join('customer', 'customer.customer_id = credit_log.id_customer', 'left');
    $this->db->join('reseller', 'reseller.id = credit_log.id_reseller', 'left');

    //Validation
    if($id > 0){$this->db->where('credit_log.id', $id);}
    if($email != ""){
      $this->db->like('customer.customer_email', $email);
      $this->db->or_where('reseller.email >', $email);
    }
    if($type > -1){$this->db->where('credit_log.type', $type);}
    if($credit_log_type > -1){
      if($credit_log_type == 1){
        $this->db->where('credit_log.id_customer', 1);
      }else if($credit_log_type == 2){
        $this->db->where('credit_log.id_reseller ', 1);
      }
    }
    if($status > -1){$this->db->where('credit_log.status', $status);}
    //End Validation

    $this->db->where('credit_log.store_id', 0);
    if($order == 1){
      $this->db->order_by("credit_log.id", "asc");
    }else{
      $this->db->order_by("credit_log.id", "desc");
    }

    if($size >= 0){$this->db->limit($size, $limit);}
    $query = $this->db->get();

    return $query;
  }

  function add_data($param){
    //Set Param
    $id_customer = (isset($param['id_customer'])) ? $param['id_customer'] : NULL;
    $id_reseller = (isset($param['id_reseller'])) ? $param['id_reseller'] : NULL;
    $amount = (isset($param['amount'])) ? $param['amount'] : 0;
    $updated_credit = (isset($param['updated_credit'])) ? $param['updated_credit'] : NULL;
    $updated_wallet = (isset($param['updated_wallet'])) ? $param['updated_wallet'] : NULL;
    //End Set Param

    $data = array(
      'id_customer' => $id_customer,
      'id_reseller' => $id_reseller,
      'amount' => $amount,
      'type' => 1, //Add
      'description' => 'Admin Backend',
      'is_reseller' => ($id_customer == NULL) ? 1 : 0 ,
      'cretime' => date('Y-m-d H:i:s'),
      'status' => 1 //Paid
    );
    $this->db->insert('credit_log', $data);
    $insert_id = $this->db->insert_id();

    //Update Customer Credit
    if($id_customer !== NULL){
      $data_update_order_item = array(
        'customer_credit' => $updated_credit
      );

      $this->db->where('customer_id', $id_customer);
      $this->db->update('customer', $data_update_order_item);
    }
    //End Update Customer Credit

    //Update Reseller Wallet
    if($id_reseller !== NULL){
      $data_update_reseller = array(
        'wallet' => $updated_wallet
      );

      $this->db->where('id', $id_reseller);
      $this->db->update('reseller', $data_update_reseller);
    }
    //End Update Reseller Wallet

    return $insert_id;
  }

  function edit_data($param){
    //Set Param
    $id = (isset($param['id'])) ? $param['id'] : 0;
    $id_customer = (isset($param['id_customer'])) ? $param['id_customer'] : 0;
    $id_reseller = (isset($param['id_reseller'])) ? $param['id_reseller'] : 0;
    $status = (isset($param['status'])) ? $param['status'] : 0;
    $updated_credit = (isset($param['updated_credit'])) ? $param['updated_credit'] : NULL;
    $updated_wallet = (isset($param['updated_wallet'])) ? $param['updated_wallet'] : NULL;
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

    //Update Reseller Wallet
    if($updated_wallet !== NULL){
      $data_update_reseller = array(
        'wallet' => $updated_wallet
      );

      $this->db->where('id', $id_reseller);
      $this->db->update('reseller', $data_update_reseller);
    }
    //End Update Reseller Wallet
  }
}
