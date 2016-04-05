<?php

class Model_customer_return extends CI_Model {
  function __construct() {
    parent::__construct();
  }

  function get_data($param, $limit = 0, $size = 0) {
    //Set Param
    $id = (isset($param['id'])) ? $param['id'] : 0;
    $purchase_code = (isset($param['purchase_code'])) ? $param['purchase_code'] : "";
    $customer_email = (isset($param['customer_email'])) ? $param['customer_email'] : "";
    $SKU = (isset($param['SKU'])) ? $param['SKU'] : "";
    $status = (isset($param['status'])) ? $param['status'] : -1;
    $order = (isset($param['order'])) ? $param['order'] : -1;
    //End Set Param
    
    $this->db->select('customer_return.*, products.name AS products_name, customer.customer_email, products.id AS product_id');
    $this->db->from('customer_return');
    $this->db->join('customer', 'customer.customer_id = customer_return.customer_id');
    $this->db->join('products_variant', 'products_variant.sku = customer_return.sku');
    $this->db->join('products', 'products.id = products_variant.id_products');
    
    //Validation
    if($id > 0){$this->db->where('customer_return.id', $id);}
    if($purchase_code != ""){$this->db->like('customer_return.purchase_code', $purchase_code);}
    if($customer_email != ""){$this->db->like('customer.customer_email', $customer_email);}
    if($SKU != ""){$this->db->where('customer_return.SKU', $SKU);}
    if($status > -1){$this->db->where('customer_return.status', $status);}
    //End Validation
    
    $this->db->where('customer_return.deleted', 0);
    if($order == 1){
      $this->db->order_by("customer_return.cretime", "asc");
    }else if($order == 2){
      $this->db->order_by("customer.customer_email", "asc");
    }else if($order == 3){
      $this->db->order_by("customer.customer_email", "desc");
    }else if($order == 4){
      $this->db->order_by("customer_return.purchase_code", "asc");
    }else if($order == 5){
      $this->db->order_by("customer_return.purchase_code", "desc");
    }else{
      $this->db->order_by("customer_return.cretime", "desc");
    }
    
    if($size >= 0){$this->db->limit($size, $limit);}
    $query = $this->db->get();
    
    return $query;
  }
  
  function add_data($param){
    //Set Param
    $purchase_code = (isset($param['purchase_code'])) ? $param['purchase_code'] : "";
    $customer_id = (isset($param['customer_id'])) ? $param['customer_id'] : 0;
    $order_item_id = (isset($param['order_item_id'])) ? $param['order_item_id'] : 0;
    $SKU = (isset($param['SKU'])) ? $param['SKU'] : "";
    $qty = (isset($param['qty'])) ? $param['qty'] : 0;
    $reason = (isset($param['reason'])) ? $param['reason'] : "";
    $status = (isset($param['status'])) ? $param['status'] : 0;
    //End Set Param
    
    $data = array(
      'purchase_code' => $purchase_code,
      'customer_id' => $customer_id,
      'order_item_id' => $order_item_id,
      'SKU' => $SKU,
      'qty' => $qty,
      'reason' => $reason,
      'status' => $status,
      'cretime' => date('Y-m-d H:i:s'),
      'creby' => $this->session->userdata('username')
    );
    $this->db->insert('customer_return', $data);
    $insert_id = $this->db->insert_id();
    
    return $insert_id;
  }

  function edit_data($param){
    //Set Param
    $id = (isset($param['id'])) ? $param['id'] : 0;
    $purchase_code = (isset($param['purchase_code'])) ? $param['purchase_code'] : "";
    $customer_id = (isset($param['customer_id'])) ? $param['customer_id'] : 0;
    $order_item_id = (isset($param['order_item_id'])) ? $param['order_item_id'] : 0;
    $SKU = (isset($param['SKU'])) ? $param['SKU'] : "";
    $qty = (isset($param['qty'])) ? $param['qty'] : 0;
    $reason = (isset($param['reason'])) ? $param['reason'] : "";
    $status = (isset($param['status'])) ? $param['status'] : 0;
    //End Set Param
    
    $data = array(
      'purchase_code' => $purchase_code,
      'customer_id' => $customer_id,
      'order_item_id' => $order_item_id,
      'SKU' => $SKU,
      'qty' => $qty,
      'reason' => $reason,
      'status' => $status,
      'modtime' => date('Y-m-d H:i:s'),
      'modby' => $this->session->userdata('username')
    );
    
    $this->db->where('id', $id);
    $this->db->update('customer_return', $data);
    
    //Additional Update
    $apply_update = (isset($param['apply_update'])) ? $param['apply_update'] : FALSE;
    $updated_qty = (isset($param['updated_qty'])) ? $param['updated_qty'] : 0;
    $updated_qty_warehouse = (isset($param['updated_qty_warehouse'])) ? $param['updated_qty_warehouse'] : 0;
    $product_id = (isset($param['product_id'])) ? $param['product_id'] : 0;
    $history_type = (isset($param['history_type'])) ? $param['history_type'] : 0;
    $shipping_status = (isset($param['shipping_status'])) ? $param['shipping_status'] : 0;
    $purchase_status = (isset($param['purchase_status'])) ? $param['purchase_status'] : 1;
    $updated_cc_credit = (isset($param['updated_cc_credit'])) ? $param['updated_cc_credit'] : 0;
    $cc_amount = (isset($param['cc_amount'])) ? $param['cc_amount'] : 0;
    $cc_type = (isset($param['cc_type'])) ? $param['cc_type'] : 0;
    
    if($apply_update){
      //Update Inventory Logs
      $data_inv_logs = array(
        'user' => $this->session->userdata('id'),
        'product_id' => $product_id,
        'SKU' => $SKU,
        'quantity' => $qty,
        'history_date' => date('Y-m-d H:i:s'),
        'history_type' => $history_type,
        'history_description' => 'Return Purchase',
        'history_category' => 2,
        'purchase_code' => $purchase_code
      );
      
      $this->db->insert('inventory_logs', $data_inv_logs);
      //End Update Inventory Logs
      
      //Update Quantity & Quantity Warehouse
      $data_update_qty = array(
        'quantity' => $updated_qty,
        'quantity_warehouse' => $updated_qty_warehouse
      );
      
      $this->db->where('SKU', $SKU);
      $this->db->update('products_variant', $data_update_qty);
      //End Update Quantity & Quantity Warehouse
      
      //Update Order Item
      $data_update_order_item = array(
        'shipping_status' => $shipping_status,
        'purchase_status' => $purchase_status
      );
      
      $this->db->where('purchase_code', $purchase_code);
      $this->db->where('SKU', $SKU);
      $this->db->update('order_item', $data_update_order_item);
      //End Update Order Item
      
      //Update Customer Credit
      $data_update_cc = array(
        'customer_credit' => $updated_cc_credit
      );
      
      $this->db->where('customer_id', $customer_id);
      $this->db->update('customer', $data_update_cc);
      //End Update Customer Credit
      
      //Customer Credit Log
      $data_credit_log = array(
        'id_customer' => $customer_id,
        'amount' => $cc_amount,
        'type' => $cc_type,
        'description' => 'Return Purchase '.$purchase_code,
        'cretime' => date('Y-m-d H:i:s'),
        'status' => 1
      );
      
      $this->db->insert('credit_log', $data_credit_log);
      //End Customer Credit Log
    }
    //End Additional Update
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
    $this->db->update('customer_return', $data);
  }
  
  function get_order_item($param){
    //Set Param
    $purchase_code = (isset($param['purchase_code'])) ? $param['purchase_code'] : "";
    $SKU = (isset($param['SKU'])) ? $param['SKU'] : "";
    //End Set Param
    
    $this->db->select('order_item.*, products.name AS products_name, customer.customer_email');
    $this->db->from('order_item');
    $this->db->join('products', 'products.id = order_item.product_id');
    $this->db->join('customer', 'customer.customer_id = order_item.customer_id');
    
    //Validation
    if($purchase_code != ""){$this->db->where('order_item.purchase_code', $purchase_code);}
    if($SKU != ""){$this->db->where('order_item.SKU', $SKU);}
    //End Validation
    
    $query = $this->db->get();
    return $query;
  }
}
