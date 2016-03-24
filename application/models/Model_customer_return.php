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
    
    $this->db->select('customer_return.*, products.name AS products_name, customer.customer_email');
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
