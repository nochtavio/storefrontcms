<?php

class Model_static_content extends CI_Model {
  function __construct() {
    parent::__construct();
  }

  function get_data($param, $limit = 0, $size = 0) {
    //Set Param
    $id = (isset($param['id'])) ? $param['id'] : 0;
    $type = (isset($param['type'])) ? $param['type'] : -1; //0: About Us |1: Contact Us |2: Terms and Condition |3: Privacy Policy
    //End Set Param
    
    $this->db->select('static_content.*');
    $this->db->from('static_content');
    
    //Validation
    if($id > 0){$this->db->where('static_content.id', $id);}
    if($type > -1){$this->db->where('static_content.type', $type);}
    //End Validation
    
    $this->db->order_by("static_content.id", "asc");
    
    if($size >= 0){$this->db->limit($size, $limit);}
    $query = $this->db->get();
    
    return $query;
  }

  function edit_data($param){
    //Set Param
    $id = (isset($param['id'])) ? $param['id'] : 0;
    $content = (isset($param['content'])) ? $param['content'] : "";
    //End Set Param
    
    $data = array(
      'content' => $content,
      'modtime' => date('Y-m-d H:i:s'),
      'modby' => $this->session->userdata('username')
    );
    
    $this->db->where('id', $id);
    $this->db->update('static_content', $data);
  }
}
