<?php

class Model_voucher extends CI_Model {
  function __construct() {
    parent::__construct();
  }

  function get_data($param, $limit = 0, $size = 0) {
    //Set Param
    $id = (isset($param['id'])) ? $param['id'] : 0;
    $name = (isset($param['name'])) ? $param['name'] : "";
    $code = (isset($param['code'])) ? $param['code'] : "";
    $discount_type = (isset($param['discount_type'])) ? $param['discount_type'] : -1;
    $transaction_type = (isset($param['transaction_type'])) ? $param['transaction_type'] : -1;
    $active = (isset($param['active'])) ? $param['active'] : -1;
    $order = (isset($param['order'])) ? $param['order'] : -1;
    //End Set Param
    
    $this->db->select('voucher.*');
    $this->db->from('voucher');
    
    //Validation
    if($id > 0){$this->db->where('voucher.id', $id);}
    if($name != ""){$this->db->like('voucher.name', $name);}
    if($code != ""){$this->db->like('voucher.code', $code);}
    if($discount_type > -1){$this->db->where('voucher.discount_type', $discount_type);}
    if($transaction_type > -1){$this->db->where('voucher.transaction_type', $transaction_type);}
    if($active > -1){$this->db->where('voucher.active', $active);}
    //End Validation
    
    $this->db->where('voucher.deleted', 0);
    if($order == 1){
      $this->db->order_by("voucher.name", "desc");
    }else if($order == 2){
      $this->db->order_by("voucher.cretime", "desc");
    }else if($order == 3){
      $this->db->order_by("voucher.cretime", "asc");
    }else{
      $this->db->order_by("voucher.name", "asc");
    }
    
    if($size >= 0){$this->db->limit($size, $limit);}
    $query = $this->db->get();
    
    return $query;
  }
  
  function add_data($param){
    //Set Param
    $name = (isset($param['name'])) ? $param['name'] : "";
    $code = (isset($param['code'])) ? $param['code'] : "";
    $description = (isset($param['description'])) ? $param['description'] : "";
    $discount_type = (isset($param['discount_type'])) ? $param['discount_type'] : 1;
    $transaction_type = (isset($param['transaction_type'])) ? $param['transaction_type'] : 1;
    $value = (isset($param['value'])) ? $param['value'] : 0;
    $category = (isset($param['category'])) ? implode(",", $param['category']) : NULL;
    $min_price = (isset($param['min_price']) && $param['min_price'] != "") ? $param['min_price'] : NULL;
    $start_date = (isset($param['start_date']) && $param['start_date'] != "") ? $param['start_date'] : NULL;
    $end_date = (isset($param['end_date']) && $param['end_date'] != "") ? $param['end_date'] : NULL;
    $active = (isset($param['active'])) ? $param['active'] : 0;
    //End Set Param
    
    $data = array(
      'name' => $name,
      'code' => $code,
      'description' => $description,
      'discount_type' => $discount_type,
      'transaction_type' => $transaction_type,
      'value' => $value,
      'category' => $category,
      'min_price' => $min_price,
      'start_date' => $start_date,
      'end_date' => date_format(date_create($end_date), 'Y-m-d 23:59:59'),
      'active' => $active,
      'cretime' => date('Y-m-d H:i:s'),
      'creby' => $this->session->userdata('username')
    );
    $this->db->insert('voucher', $data);
    $insert_id = $this->db->insert_id();
    
    return $insert_id;
  }

  function edit_data($param){
    //Set Param
    $id = (isset($param['id'])) ? $param['id'] : 0;
    $name = (isset($param['name'])) ? $param['name'] : "";
    $code = (isset($param['code'])) ? $param['code'] : "";
    $description = (isset($param['description'])) ? $param['description'] : "";
    $discount_type = (isset($param['discount_type'])) ? $param['discount_type'] : 1;
    $transaction_type = (isset($param['transaction_type'])) ? $param['transaction_type'] : 1;
    $value = (isset($param['value'])) ? $param['value'] : 0;
    $category = (isset($param['category'])) ? implode(",", $param['category']) : NULL;
    $min_price = (isset($param['min_price']) && $param['min_price'] != "") ? $param['min_price'] : NULL;
    $start_date = (isset($param['start_date']) && $param['start_date'] != "") ? $param['start_date'] : NULL;
    $end_date = (isset($param['end_date']) && $param['end_date'] != "") ? $param['end_date'] : NULL;
    $active = (isset($param['active'])) ? $param['active'] : 0;
    //End Set Param
    
    $data = array(
      'name' => $name,
      'code' => $code,
      'description' => $description,
      'discount_type' => $discount_type,
      'transaction_type' => $transaction_type,
      'value' => $value,
      'category' => $category,
      'min_price' => $min_price,
      'start_date' => $start_date,
      'end_date' => date_format(date_create($end_date), 'Y-m-d 23:59:59'),
      'active' => $active,
      'modtime' => date('Y-m-d H:i:s'),
      'modby' => $this->session->userdata('username')
    );
    
    $this->db->where('id', $id);
    $this->db->update('voucher', $data);
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
    $this->db->update('voucher', $data);
  }
}
