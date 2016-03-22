<?php

class Model_payment extends CI_Model {
  function __construct() {
    parent::__construct();
  }

  function get_data($param, $limit = 0, $size = 0) {
    //Set Param
    $id = (isset($param['id'])) ? $param['id'] : 0;
    $name = (isset($param['name'])) ? $param['name'] : "";
    $type = (isset($param['type'])) ? $param['type'] : -1;
    $active = (isset($param['active'])) ? $param['active'] : -1;
    $order = (isset($param['order'])) ? $param['order'] : -1;
    //End Set Param
    
    $this->db->select('payment.*');
    $this->db->from('payment');
    
    //Validation
    if($id > 0){$this->db->where('payment.id', $id);}
    if($name != ""){$this->db->like('payment.name', $name);}
    if($type > -1){$this->db->where('payment.type', $type);}
    if($active > -1){$this->db->where('payment.active', $active);}
    //End Validation
    
    $this->db->where('payment.deleted', 0);
    if($order == 1){
      $this->db->order_by("payment.name", "desc");
    }else if($order == 2){
      $this->db->order_by("payment.cretime", "desc");
    }else if($order == 3){
      $this->db->order_by("payment.cretime", "asc");
    }else if($order == 4){
      $this->db->order_by("payment.show_order", "desc");
    }else if($order == 5){
      $this->db->order_by("payment.show_order", "asc");
    }else{
      $this->db->order_by("payment.name", "asc");
    }
    
    if($size >= 0){$this->db->limit($size, $limit);}
    $query = $this->db->get();
    
    return $query;
  }
  
  function add_data($param){
    //Set Param
    $name = (isset($param['name'])) ? $param['name'] : "";
    $description = (isset($param['description'])) ? $param['description'] : "";
    $logo = (isset($param['logo'])) ? $param['logo'] : "";
    $type = (isset($param['type'])) ? $param['type'] : 0;
    $minimum_grand_total = (isset($param['minimum_grand_total']) && $param['minimum_grand_total'] != "") ? $param['minimum_grand_total'] : NULL;
    $show_order = (isset($param['show_order'])) ? $param['show_order'] : 0;
    $active = (isset($param['active'])) ? $param['active'] : 0;
    //End Set Param
    
    $data = array(
      'name' => $name,
      'description' => $description,
      'logo' => $logo,
      'type' => $type,
      'minimum_grand_total' => $minimum_grand_total,
      'show_order' => $show_order,
      'active' => $active,
      'cretime' => date('Y-m-d H:i:s'),
      'creby' => $this->session->userdata('username')
    );
    $this->db->insert('payment', $data);
    $insert_id = $this->db->insert_id();
    
    return $insert_id;
  }

  function edit_data($param){
    //Set Param
    $id = (isset($param['id'])) ? $param['id'] : 0;
    $name = (isset($param['name'])) ? $param['name'] : "";
    $description = (isset($param['description'])) ? $param['description'] : "";
    $logo = (isset($param['logo'])) ? $param['logo'] : "";
    $type = (isset($param['type'])) ? $param['type'] : 0;
    $minimum_grand_total = (isset($param['minimum_grand_total']) && $param['minimum_grand_total'] != "") ? $param['minimum_grand_total'] : NULL;
    $show_order = (isset($param['show_order'])) ? $param['show_order'] : 0;
    $active = (isset($param['active'])) ? $param['active'] : 0;
    //End Set Param
    
    if($logo != ""){
      $data = array(
        'name' => $name,
        'description' => $description,
        'logo' => $logo,
        'type' => $type,
        'minimum_grand_total' => $minimum_grand_total,
        'show_order' => $show_order,
        'active' => $active,
        'modtime' => date('Y-m-d H:i:s'),
        'modby' => $this->session->userdata('username')
      );
    }else{
      $data = array(
        'name' => $name,
        'description' => $description,
        'type' => $type,
        'minimum_grand_total' => $minimum_grand_total,
        'show_order' => $show_order,
        'active' => $active,
        'modtime' => date('Y-m-d H:i:s'),
        'modby' => $this->session->userdata('username')
      );
    }
    
    
    $this->db->where('id', $id);
    $this->db->update('payment', $data);
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
    $this->db->update('payment', $data);
  }
}
