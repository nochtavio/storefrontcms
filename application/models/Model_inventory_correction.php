<?php

class Model_inventory_correction extends CI_Model {
  function __construct() {
    parent::__construct();
  }
  
  function add_data($param){
    //Set Param
    $SKU = (isset($param['SKU'])) ? $param['SKU'] : "";
    $product_id = (isset($param['product_id'])) ? $param['product_id'] : "";
    $quantity = (isset($param['quantity'])) ? $param['quantity'] : 0;
    $quantity_warehouse = (isset($param['quantity_warehouse'])) ? $param['quantity_warehouse'] : 0;
    $updated_quantity = (isset($param['updated_quantity'])) ? $param['updated_quantity'] : 0;
    $history_type = (isset($param['history_type'])) ? $param['history_type'] : 0;
    //End Set Param
    
    $data = array(
      'quantity' => $quantity,
      'quantity_warehouse' => $quantity_warehouse
    );
    $this->db->update('products_variant', $data);
    
    //Update Inventory Logs
    $data_inv_logs = array(
      'user' => $this->session->userdata('id'),
      'product_id' => $product_id,
      'SKU' => $SKU,
      'quantity' => $updated_quantity,
      'history_date' => date('Y-m-d H:i:s'),
      'history_type' => $history_type,
      'history_description' => 'Inventory Correction',
      'history_category' => 2
    );

    $this->db->insert('inventory_logs', $data_inv_logs);
    //End Update Inventory Logs
  }
}
