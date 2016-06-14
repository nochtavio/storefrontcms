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
    $read = (isset($param['read'])) ? $param['read'] : -1;
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
    if($read > -1){$this->db->where('oh.read', $read);}
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
    $customer_id = (isset($param['customer_id'])) ? $param['customer_id'] : 0;
    $paycode = (isset($param['paycode'])) ? $param['paycode'] : 0;
    $cc_type = (isset($param['cc_type'])) ? $param['cc_type'] : 0;
    $updated_credit = (isset($param['updated_credit'])) ? $param['updated_credit'] : NULL;
    //End Set Param
    
    $data = array(
      'status' => $status,
      'updated_by' => $this->session->userdata('username')
    );
    
    $this->db->where('purchase_code', $purchase_code);
    $this->db->update('order_payment', $data);
    
    //Update Order Item Status
    $purchase_status = ($status == 1) ? 3 : 1 ;
    
    $data_update_order_item = array(
      'purchase_status' => $purchase_status,
      'approval_date' => date('Y-m-d H:i:s'),
      'updated_by' => $this->session->userdata('id')
    );
    
    $this->db->where('purchase_code', $purchase_code);
    $this->db->update('order_item', $data_update_order_item);
    //End Update Order Item Status
    
    //Update Customer Credit
    if($updated_credit !== NULL){
      $data_update_order_item = array(
        'customer_credit' => $updated_credit
      );

      $this->db->where('customer_id', $customer_id);
      $this->db->update('customer', $data_update_order_item);
    }
    //End Update Customer Credit
    
    //Customer Credit Log
    $data_credit_log = array(
      'id_customer' => $customer_id,
      'amount' => $paycode,
      'type' => $cc_type,
      'description' => 'Purchase '.$purchase_code,
      'cretime' => date('Y-m-d H:i:s'),
      'status' => 1
    );

    $this->db->insert('credit_log', $data_credit_log);
    //End Customer Credit Log
  }
  
  function get_order_item($param) {
    //Set Param
    $id = (isset($param['id'])) ? $param['id'] : 0;
    $purchase_code = (isset($param['purchase_code'])) ? $param['purchase_code'] : "";
    //End Set Param
    
    $this->db->select('order_item.*, order_header.shipping_cost, order_header.paycode, order_header.discount, order_header.credit_use, products.name AS product_name, order_payment.status AS payment_status');
    $this->db->from('order_item');
    $this->db->join('order_header', 'order_header.purchase_code = order_item.purchase_code');
    $this->db->join('products', 'products.id = order_item.product_id');
    $this->db->join('order_payment', 'order_payment.purchase_code = order_header.purchase_code');
    
    //Validation
    if($id > 0){$this->db->where('order_item.order_item_id', $id);}
    if($purchase_code != ""){$this->db->like('order_item.purchase_code', $purchase_code);}
    //End Validation
    
    $this->db->order_by("order_item.order_item_id", "asc");
    
    $query = $this->db->get();
    
    return $query;
  }
  
  function update_shipping($param){
    //Set Param
    $id = (isset($param['id'])) ? $param['id'] : 0;
    $shipping_status = (isset($param['shipping_status'])) ? $param['shipping_status'] : 0;
    $resi = (isset($param['resi'])) ? $param['resi'] : NULL;
    //End Set Param
    
    if($shipping_status == 0){
      $data = array(
        'shipping_status' => $shipping_status,
        'resi' => NULL,
        'shipping_date' => NULL,
        'delivery_date' => NULL,
        'updated_by' => $this->session->userdata('id')
      );
    }else if($shipping_status == 1){
      $data = array(
        'shipping_status' => $shipping_status,
        'resi' => $resi,
        'shipping_date' => date('Y-m-d H:i:s'),
        'delivery_date' => NULL,
        'updated_by' => $this->session->userdata('id')
      );
    }else if($shipping_status == 2){
      $data = array(
        'shipping_status' => $shipping_status,
        'resi' => $resi,
        'delivery_date' => date('Y-m-d H:i:s'),
        'updated_by' => $this->session->userdata('id')
      );
    }
    
    $this->db->where('order_item_id', $id);
    $this->db->update('order_item', $data);
    
    //Inventory Logs
    $purchase_code = (isset($param['purchase_code'])) ? $param['purchase_code'] : '';
    $product_id = (isset($param['product_id'])) ? $param['product_id'] : 0;
    $SKU = (isset($param['SKU'])) ? $param['SKU'] : '';
    $quantity = (isset($param['quantity'])) ? $param['quantity'] : 0;
    $add_log = (isset($param['add_log'])) ? $param['add_log'] : FALSE;
    $history_type = (isset($param['history_type'])) ? $param['history_type'] : 0;
    
    if($add_log){
      $data_inv_logs = array(
        'user' => $this->session->userdata('id'),
        'product_id' => $product_id,
        'SKU' => $SKU,
        'quantity' => $quantity,
        'history_date' => date('Y-m-d H:i:s'),
        'history_type' => $history_type,
        'history_description' => 'Shipping Purchase',
        'history_category' => 2,
        'purchase_code' => $purchase_code
      );
      $this->db->insert('inventory_logs', $data_inv_logs);
    }
    //End Inventory Logs
  }
  
  function get_unconfirmed_order(){
    $date_before = date('Y-m-d', strtotime("-2 days"));
    
    $this->db->select('order_payment.*');
    $this->db->from('order_payment');
    $this->db->where('order_payment.status', 0);
    $this->db->where('order_payment.transactionDate <=', $date_before);
    
    $query = $this->db->get();
    
    return $query;
  }
  
  function cancel_order($param){
    $purchase_code = (isset($param['purchase_code'])) ? $param['purchase_code'] : "";
    
    //Update Order Payment Status
    $data_update_order_payment = array(
      'status' => 2
    );
    
    $this->db->where('purchase_code', $purchase_code);
    $this->db->update('order_payment', $data_update_order_payment);
    //End Update Order Payment Status
    
    //Update Order Item Status
    $data_update_order_item = array(
      'purchase_status' => 4
    );
    
    $this->db->where('purchase_code', $purchase_code);
    $this->db->update('order_item', $data_update_order_item);
    //End Update Order Item Status
  }
  
  function update_quantity($param){
    $purchase_code = (isset($param['purchase_code'])) ? $param['purchase_code'] : "";
    $product_id = (isset($param['product_id'])) ? $param['product_id'] : 0;
    $SKU = (isset($param['SKU'])) ? $param['SKU'] : "";
    $updated_quantity = (isset($param['updated_quantity'])) ? $param['updated_quantity'] : 0;
    
    $data_update_quantity = array(
      'quantity' => $updated_quantity
    );
    
    $this->db->where('SKU', $SKU);
    $this->db->update('products_variant', $data_update_quantity);
    
    $data_inv_logs = array(
      'user' => 'CRON',
      'product_id' => $product_id,
      'SKU' => $SKU,
      'quantity' => $updated_quantity,
      'history_date' => date('Y-m-d H:i:s'),
      'history_type' => 1,
      'history_description' => 'Cancel Purchase',
      'history_category' => 3,
      'purchase_code' => $purchase_code
    );
    $this->db->insert('inventory_logs', $data_inv_logs);
  }
  
  function set_read(){
    $data_update_read = array(
      'read' => 1
    );
    
    $this->db->update('order_header', $data_update_read);
  }
}
