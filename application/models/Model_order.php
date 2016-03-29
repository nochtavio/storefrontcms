<?php

class Model_order extends CI_Model {
  function __construct() {
    parent::__construct();
  }

  function get_data($param, $limit = 0, $size = 0) {
    //Set Param
    $id = (isset($param['id'])) ? $param['id'] : 0;
    $purchase_code = (isset($param['purchase_code'])) ? $param['purchase_code'] : "";
    $customer_email = (isset($param['customer_email'])) ? $param['customer_email'] : "";
    $status_payment = (isset($param['status_payment'])) ? $param['status_payment'] : -1;
    $status = (isset($param['status'])) ? $param['status'] : -1;
    $order = (isset($param['order'])) ? $param['order'] : -1;
    //End Set Param
    
    $this->db->select('oh.*, op.status, payment.name AS payment_name, op.updated_by, op.confirm_transfer_by, op.confirm_transfer_bank, op.confirm_transfer_amount');
    $this->db->from('order_header oh');
    $this->db->join('order_payment op', 'op.purchase_code = oh.purchase_code');
    $this->db->join('payment', 'payment.id = op.master_payment_id');
    
    //Validation
    if($id > 0){$this->db->where('oh.id', $id);}
    if($purchase_code != ""){$this->db->like('oh.purchase_code', $purchase_code);}
    if($customer_email != ""){$this->db->like('oh.customer_email', $customer_email);}
    if($status_payment == 1){$this->db->where('op.confirm_transfer_by is NOT NULL', NULL, FALSE);}
    if($status_payment == 2){$this->db->where('op.confirm_transfer_by is NULL', NULL, FALSE);}
    if($status > -1){$this->db->where('op.status', $status);}
    //End Validation
    
    if($order == 1){
      $this->db->order_by("oh.purchase_date", "asc");
    }else{
      $this->db->order_by("oh.purchase_date", "desc");
    }
    
    if($size >= 0){$this->db->limit($size, $limit);}
    $query = $this->db->get();
    
    return $query;
  }

  function edit_data($param){
    //Set Param
    $purchase_code = (isset($param['purchase_code'])) ? $param['purchase_code'] : 0;
    $status = (isset($param['status'])) ? $param['status'] : 0;
    //End Set Param
    
    $data = array(
      'status' => $status,
      'updated_by' => $this->session->userdata('username')
    );
    
    $this->db->where('purchase_code', $purchase_code);
    $this->db->update('order_payment', $data);
  }
  
  function get_order_item($param) {
    //Set Param
    $id = (isset($param['id'])) ? $param['id'] : 0;
    $purchase_code = (isset($param['purchase_code'])) ? $param['purchase_code'] : "";
    //End Set Param
    
    $this->db->select('order_item.*, order_header.shipping_cost, order_header.paycode, order_header.discount, order_header.credit_use, products.name AS product_name');
    $this->db->from('order_item');
    $this->db->join('order_header', 'order_header.purchase_code = order_item.purchase_code');
    $this->db->join('products', 'products.id = order_item.product_id');
    
    //Validation
    if($id > 0){$this->db->where('order_item.id', $id);}
    if($purchase_code != ""){$this->db->like('order_item.purchase_code', $purchase_code);}
    //End Validation
    
    $this->db->order_by("order_item.order_item_id", "asc");
    
    $query = $this->db->get();
    
    return $query;
  }
}
