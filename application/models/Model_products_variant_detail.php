<?php

class Model_products_variant_detail extends CI_Model {
  function __construct() {
    parent::__construct();
  }

  function get_data($param, $limit = 0, $size = 0) {
    //Set Param
    $id = (isset($param['id'])) ? $param['id'] : 0;
    $id_products = (isset($param['id_products'])) ? $param['id_products'] : 0;
    $id_color = (isset($param['id_color'])) ? $param['id_color'] : 0;
    $variant_size = (isset($param['size'])) ? $param['size'] : "";
    $size_url = (isset($param['size_url'])) ? $param['size_url'] : "";
    $sku = (isset($param['sku'])) ? $param['sku'] : "";
    $active = (isset($param['active'])) ? $param['active'] : -1;
    $order = (isset($param['order'])) ? $param['order'] : -1;
    //End Set Param
    
    $this->db->select('products_variant.*, color.id AS color_id, color.name AS color_name');
    $this->db->from('products_variant');
    $this->db->join('color', 'color.id = products_variant.id_color');
    
    //Validation
    if($id > 0){$this->db->where('products_variant.id', $id);}
    if($id_products > 0){$this->db->where('products_variant.id_products', $id_products);}
    if($id_color > 0){$this->db->where('products_variant.id_color', $id_color);}
    if($variant_size != ""){$this->db->where('products_variant.size', $variant_size);}
    if($size_url != ""){$this->db->where('products_variant.size_url', $size_url);}
    if($sku != ""){$this->db->like('products_variant.SKU', $sku);}
    if($active > -1){$this->db->where('products_variant.active', $active);}
    //End Validation
    
    $this->db->where('products_variant.deleted', 0);
    if($order == 1){
      $this->db->order_by("products_variant.cretime", "asc");
    }else if($order == 2){
      $this->db->order_by("products_variant.quantity", "desc");
    }else if($order == 3){
      $this->db->order_by("products_variant.quantity", "asc");
    }else if($order == 4){
      $this->db->order_by("products_variant.show_order", "asc");
    }else if($order == 5){
      $this->db->order_by("products_variant.show_order", "desc");
    }else{
      $this->db->order_by("products_variant.cretime", "asc");
    }
    
    if($size >= 0){$this->db->limit($size, $limit);}
    $query = $this->db->get();
    
    return $query;
  }
  
  function add_data($param){
    //Set Param
    $id_products = (isset($param['id_products'])) ? $param['id_products'] : 0;
    $id_color = (isset($param['id_color'])) ? $param['id_color'] : 0;
    $sku = (isset($param['sku'])) ? $param['sku'] : '';
    $size = (isset($param['size'])) ? $param['size'] : "";
    $size_url = url_title($param['color_name'], 'dash', true);
    $quantity = (isset($param['quantity'])) ? $param['quantity'] : 0;
    $max_quantity_order = (isset($param['max_quantity_order'])) ? $param['max_quantity_order'] : 0;
    $show_order = (isset($param['show_order'])) ? $param['show_order'] : 0;
    $active = (isset($param['active'])) ? $param['active'] : 0;
    //End Set Param
    
    $data = array(
      'id_products' => $id_products,
      'id_color' => $id_color,
      'SKU' => $sku,
      'size' => $size,
      'size_url' => $size_url,
      'quantity' => $quantity,
      'quantity_warehouse' => $quantity,
      'max_quantity_order' => $max_quantity_order,
      'show_order' => $show_order,
      'active' => $active,
      'cretime' => date('Y-m-d H:i:s'),
      'creby' => $this->session->userdata('username')
    );
    $this->db->insert('products_variant', $data);
    $insert_id = $this->db->insert_id();
    
    //Check Duplicate Size URL
    $param_check['size_url'] = $size_url;
    $result_check = $this->get_data($param_check);
    if($result_check->num_rows() > 1){
      $size_url = url_title($param['color_name'].$insert_id, 'dash', true);
      $data = array(
        'size_url' => $size_url
      );

      $this->db->where('id', $insert_id);
      $this->db->update('products_variant', $data);
    }
    //End Check Duplicate Size URL
    
    //Insert Inventory Logs
    $data_inventory_logs = array(
      'user' => $this->session->userdata('id'),
      'product_id' => $id_products,
      'SKU' => $sku,
      'quantity' => $quantity,
      'history_date' => date('Y-m-d H:i:s'),
      'history_type' => 1,
      'history_description' => 'Backend',
      'history_category' => 2
    );
    $this->db->insert('inventory_logs', $data_inventory_logs);
    //End Insert Inventory Logs
    
    return $insert_id;
  }

  function edit_data($param){
    //Set Param
    $id = (isset($param['id'])) ? $param['id'] : 0;
    $size = (isset($param['size'])) ? $param['size'] : "";
    $quantity = (isset($param['quantity'])) ? $param['quantity'] : 0;
    $max_quantity_order = (isset($param['max_quantity_order'])) ? $param['max_quantity_order'] : 0;
    $show_order = (isset($param['show_order'])) ? $param['show_order'] : 0;
    $active = (isset($param['active'])) ? $param['active'] : 0;
    //End Set Param
    
    $data = array(
      'size' => $size,
      'quantity' => $quantity,
      'quantity_warehouse' => $quantity,
      'max_quantity_order' => $max_quantity_order,
      'show_order' => $show_order,
      'active' => $active,
      'modtime' => date('Y-m-d H:i:s'),
      'modby' => $this->session->userdata('username')
    );
    
    $this->db->where('id', $id);
    $this->db->update('products_variant', $data);
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
    $this->db->update('products_variant', $data);
  }
  
  function set_active($param){
    //Set Param
    $id_color = (isset($param['id_color'])) ? $param['id_color'] : 0;
    $id_products = (isset($param['id_products'])) ? $param['id_products'] : 0;
    $active = (isset($param['active'])) ? $param['active'] : 0;
    //End Set Param
    
    $data = array(
      'active' => $active,
      'modtime' => date('Y-m-d H:i:s'),
      'modby' => $this->session->userdata('username')
    );
    
    $this->db->where('id_color', $id_color);
    $this->db->where('id_products', $id_products);
    $this->db->update('products_variant', $data);
  }
}
