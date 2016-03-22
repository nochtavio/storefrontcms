<?php

class Model_shipping extends CI_Model {
  function __construct() {
    parent::__construct();
  }

  function get_data($param, $limit = 0, $size = 0) {
    //Set Param
    $id = (isset($param['id'])) ? $param['id'] : 0;
    $name = (isset($param['name'])) ? $param['name'] : "";
    $active = (isset($param['active'])) ? $param['active'] : -1;
    $order = (isset($param['order'])) ? $param['order'] : -1;
    //End Set Param
    
    $this->db->select('shipping.*, kabupaten.nama AS name');
    $this->db->from('shipping');
    $this->db->join('kabupaten', 'kabupaten.id_kabupaten = shipping.id_kabupaten');
    
    //Validation
    if($id > 0){$this->db->where('shipping.id', $id);}
    if($name != ""){$this->db->like('kabupaten.nama', $name);}
    if($active > -1){$this->db->where('shipping.active', $active);}
    //End Validation
    
    if($order == 1){
      $this->db->order_by("kabupaten.nama", "desc");
    }else{
      $this->db->order_by("kabupaten.nama", "asc");
    }
    
    if($size >= 0){$this->db->limit($size, $limit);}
    $query = $this->db->get();
    
    return $query;
  }

  function edit_data($param){
    //Set Param
    $id = (isset($param['id'])) ? $param['id'] : 0;
    $reguler = (isset($param['reguler'])) ? $param['reguler'] : 0;
    $oke = (isset($param['oke'])) ? $param['oke'] : 0;
    $yes = (isset($param['yes'])) ? $param['yes'] : 0;
    $active = (isset($param['active'])) ? $param['active'] : 0;
    //End Set Param
    
    $data = array(
      'reguler' => $reguler,
      'oke' => $oke,
      'yes' => $yes,
      'active' => $active,
      'modtime' => date('Y-m-d H:i:s'),
      'modby' => $this->session->userdata('username')
    );
    
    $this->db->where('id', $id);
    $this->db->update('shipping', $data);
  }
}
