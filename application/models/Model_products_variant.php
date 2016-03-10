<?php

class Model_products_variant extends CI_Model {
  function __construct() {
    parent::__construct();
  }

  function get_data($param, $limit = 0, $size = 0) {
    //Set Param
    $id_products = (isset($param['id_products'])) ? $param['id_products'] : 0;
    $id_color = (isset($param['id_color'])) ? $param['id_color'] : 0;
    $order = (isset($param['order'])) ? $param['order'] : -1;
    //End Set Param
    
    $this->db->select('pv.id_products, pv.id_color, color.name AS color_name,  COUNT(pv.size) as total_size, SUM(pv.quantity) as total_quantity,  (SELECT COUNT(id) FROM products_image WHERE products_image.id_products = pv.id_products AND products_image.id_color = pv.id_color) AS total_images');
    $this->db->from('products_variant pv');
    $this->db->join('color', 'color.id = pv.id_color');
    
    //Validation
    if($id_products > 0){$this->db->where('pv.id_products', $id_products);}
    if($id_color > 0){$this->db->where('pv.id_color', $id_color);}
    //End Validation
    
    $this->db->where('pv.deleted', 0);
    $this->db->group_by('pv.id_products, pv.id_color'); 
    
    if($order == 1){
      $this->db->order_by("pv.cretime", "asc");
    }else if($order == 2){
      $this->db->order_by("total_size", "asc");
    }else if($order == 3){
      $this->db->order_by("total_size", "desc");
    }else if($order == 4){
      $this->db->order_by("total_quantity", "asc");
    }else if($order == 5){
      $this->db->order_by("total_quantity", "desc");
    }else if($order == 6){
      $this->db->order_by("total_images", "asc");
    }else if($order == 7){
      $this->db->order_by("total_images", "desc");
    }else{
      $this->db->order_by("pv.cretime", "asc");
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
    $quantity = (isset($param['quantity'])) ? $param['quantity'] : 0;
    $show_order = (isset($param['show_order'])) ? $param['show_order'] : 0;
    $active = (isset($param['active'])) ? $param['active'] : 0;
    //End Set Param
    
    $data = array(
      'id_products' => $id_products,
      'id_color' => $id_color,
      'SKU' => $sku,
      'size' => $size,
      'quantity' => $quantity,
      'quantity_warehouse' => $quantity,
      'show_order' => $show_order,
      'active' => $active,
      'cretime' => date('Y-m-d H:i:s'),
      'creby' => $this->session->userdata('username')
    );
    $this->db->insert('products_variant', $data);
    $insert_id = $this->db->insert_id();
    
    return $insert_id;
  }

  function edit_data($param){
    //Set Param
    $id = (isset($param['id'])) ? $param['id'] : 0;
    $size = (isset($param['size'])) ? $param['size'] : "";
    $quantity = (isset($param['quantity'])) ? $param['quantity'] : 0;
    $show_order = (isset($param['show_order'])) ? $param['show_order'] : 0;
    $active = (isset($param['active'])) ? $param['active'] : 0;
    //End Set Param
    
    $data = array(
      'size' => $size,
      'quantity' => $quantity,
      'quantity_warehouse' => $quantity,
      'show_order' => $show_order,
      'active' => $active,
      'modtime' => date('Y-m-d H:i:s'),
      'modby' => $this->session->userdata('username')
    );
    
    $this->db->where('id', $id);
    $this->db->update('products_variant', $data);
  }
}
