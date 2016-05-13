<?php

class Model_order_item extends CI_Model {
  function __construct() {
    parent::__construct();
  }

  function get_data($param, $limit = 0, $size = 0) {
    //Set Param
    $id = (isset($param['id'])) ? $param['id'] : 0;
    $products_name = (isset($param['products_name'])) ? $param['products_name'] : "";
    $SKU = (isset($param['SKU'])) ? $param['SKU'] : "";
    $reseller_email = (isset($param['reseller_email'])) ? $param['reseller_email'] : "";
    $reseller_name = (isset($param['reseller_name'])) ? $param['reseller_name'] : "";
    $order = (isset($param['order'])) ? $param['order'] : -1;
    //End Set Param
    
    $this->db->select(' prod.name AS products_name, 
                        oi.SKU,  
                        color.name AS color_name,
                        reseller.email AS reseller_email,
                        reseller.name AS reseller_name,
                        oi.quantity');
    $this->db->from('order_item oi');
    $this->db->join('products_variant var', 'var.SKU = oi.SKU');
    $this->db->join('products prod', 'prod.id = var.id_products');
    $this->db->join('color', 'color.id = var.id_color');
    $this->db->join('reseller', 'reseller.id = oi.id_reseller');
    
    //Validation
    if($id > 0){$this->db->where('oi.order_item_id', $id);}
    if($products_name != ""){$this->db->like('products_name', $products_name);}
    if($SKU != ""){$this->db->like('SKU', $SKU);}
    if($reseller_email != ""){$this->db->like('reseller_email', $reseller_email);}
    if($reseller_name != ""){$this->db->like('reseller_name', $reseller_name);}
    //End Validation
    
    $this->db->where('oi.id_reseller !=', 0);
    $this->db->where('oi.titip_stok', 1);
    
    if($order == 1){
      $this->db->order_by("oi.order_item_id", "asc");
    }else if($order == 2){
      $this->db->order_by("products_name", "asc");
    }else if($order == 3){
      $this->db->order_by("products_name", "desc");
    }else if($order == 4){
      $this->db->order_by("reseller_email", "asc");
    }else if($order == 5){
      $this->db->order_by("reseller_email", "desc");
    }else if($order == 6){
      $this->db->order_by("reseller_name", "asc");
    }else if($order == 7){
      $this->db->order_by("reseller_name", "desc");
    }else if($order == 8){
      $this->db->order_by("oi.quantity", "asc");
    }else if($order == 9){
      $this->db->order_by("oi.quantity", "desc");
    }else{
      $this->db->order_by("oi.order_item_id", "desc");
    }
    
    if($size >= 0){$this->db->limit($size, $limit);}
    $query = $this->db->get();
    
    return $query;
  }
}
