<?php

class Model_products_image extends CI_Model {
  function __construct() {
    parent::__construct();
  }

  function get_data($param, $limit = 0, $size = 0) {
    //Set Param
    $id = (isset($param['id'])) ? $param['id'] : 0;
    $id_products = (isset($param['id_products'])) ? $param['id_products'] : 0;
    $id_color = (isset($param['id_color'])) ? $param['id_color'] : 0;
    $url = (isset($param['url'])) ? $param['url'] : "";
    $active = (isset($param['active'])) ? $param['active'] : -1;
    $order = (isset($param['order'])) ? $param['order'] : -1;
    //End Set Param
    
    $this->db->select('products_image.*');
    $this->db->from('products_image');
    $this->db->join('color', 'color.id = products_image.id_color');
    
    //Validation
    if($id > 0){$this->db->where('products_image.id', $id);}
    if($id_products > 0){$this->db->where('products_image.id_products', $id_products);}
    if($id_color > 0){$this->db->where('products_image.id_color', $id_color);}
    if($url != ""){$this->db->like('products_image.url', $url);}
    if($active > -1){$this->db->where('products_image.active', $active);}
    //End Validation
    
    $this->db->where('products_image.deleted', 0);
    if($order == 1){
      $this->db->order_by("products_image.cretime", "asc");
    }else{
      $this->db->order_by("products_image.cretime", "desc");
    }
    
    if($size >= 0){$this->db->limit($size, $limit);}
    $query = $this->db->get();
    
    return $query;
  }
  
  function add_data($param){
    //Set Param
    $id_products = (isset($param['id_products'])) ? $param['id_products'] : 0;
    $id_color = (isset($param['id_color'])) ? $param['id_color'] : 0;
    $url = (isset($param['url'])) ? $param['url'] : "";
    $default = (isset($param['default'])) ? $param['default'] : 0;
    $show_order = (isset($param['show_order'])) ? $param['show_order'] : 0;
    $active = (isset($param['active'])) ? $param['active'] : 0;
    //End Set Param
    
    $data = array(
      'id_products' => $id_products,
      'id_color' => $id_color,
      'url' => $url,
      'default' => $default,
      'show_order' => $show_order,
      'active' => $active,
      'cretime' => date('Y-m-d H:i:s'),
      'creby' => $this->session->userdata('username')
    );
    $this->db->insert('products_image', $data);
    $insert_id = $this->db->insert_id();
    
    return $insert_id;
  }

  function edit_data($param){
    //Set Param
    $id = (isset($param['id'])) ? $param['id'] : 0;
    $url = (isset($param['url'])) ? $param['url'] : "";
    $default = (isset($param['default'])) ? $param['default'] : 0;
    $show_order = (isset($param['show_order'])) ? $param['show_order'] : 0;
    $active = (isset($param['active'])) ? $param['active'] : 0;
    //End Set Param
    
    if($url != ""){
      $data = array(
        'url' => $url,
        'default' => $default,
        'show_order' => $show_order,
        'active' => $active,
        'modtime' => date('Y-m-d H:i:s'),
        'modby' => $this->session->userdata('username')
      );
    }else{
      $data = array(
        'default' => $default,
        'show_order' => $show_order,
        'active' => $active,
        'modtime' => date('Y-m-d H:i:s'),
        'modby' => $this->session->userdata('username')
      );
    }
    
    $this->db->where('id', $id);
    $this->db->update('products_image', $data);
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
    $this->db->update('products_image', $data);
  }
}
