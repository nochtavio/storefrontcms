<?php

class Model_category_child extends CI_Model {
  function __construct() {
    parent::__construct();
  }

  function get_data($param, $limit = 0, $size = 0) {
    //Set Param
    $id = (isset($param['id'])) ? $param['id'] : 0;
    $id_category = (isset($param['id_category'])) ? $param['id_category'] : 0;
    $name = (isset($param['name'])) ? $param['name'] : "";
    $url = (isset($param['url'])) ? $param['url'] : "";
    $active = (isset($param['active'])) ? $param['active'] : -1;
    $order = (isset($param['order'])) ? $param['order'] : -1;
    
    $now = (isset($param['now'])) ? $param['now'] : 0;
    $now_updated = (isset($param['now_updated'])) ? $param['now_updated'] : 0;
    //End Set Param
    
    $this->db->select('category_child.*');
    $this->db->from('category_child');
    $this->db->join('category', 'category.id = category_child.id_category');
    
    //Validation
    if($id > 0){$this->db->where('category_child.id', $id);}
    if($id_category > 0){$this->db->where('category.id', $id_category);}
    if($name != ""){$this->db->like('category_child.name', $name);}
    if($url != ""){$this->db->where('category_child.url', $url);}
    if($active > -1){$this->db->where('category_child.active', $active);}
    
    if($now > 0){
      $this->db->where('category_child.cretime >=', date('Y-m-d'));
      $this->db->where('category_child.cretime <=', date('Y-m-d')." 23:59:59");
    }
    
    if($now_updated > 0){
      $this->db->where('category_child.modtime >=', date('Y-m-d'));
      $this->db->where('category_child.modtime <=', date('Y-m-d')." 23:59:59");
    }
    //End Validation
    
    $this->db->where('category_child.parent', 0);
    $this->db->where('category_child.deleted', 0);
    if($order == 1){
      $this->db->order_by("category_child.name", "desc");
    }else if($order == 2){
      $this->db->order_by("category_child.cretime", "desc");
    }else if($order == 3){
      $this->db->order_by("category_child.cretime", "asc");
    }else{
      $this->db->order_by("category_child.name", "asc");
    }
    
    if($size >= 0){$this->db->limit($size, $limit);}
    $query = $this->db->get();
    
    return $query;
  }
  
  function add_data($param){
    //Set Param
    $id_category = (isset($param['id_category'])) ? $param['id_category'] : 0;
    $name = (isset($param['name'])) ? $param['name'] : "";
    $url = url_title($name, 'dash', true);
    $active = (isset($param['active'])) ? $param['active'] : 0;
    //End Set Param
    
    $data = array(
      'id_category' => $id_category,
      'name' => $name,
      'url' => $url,
      'active' => $active,
      'cretime' => date('Y-m-d H:i:s'),
      'creby' => $this->session->userdata('username')
    );
    $this->db->insert('category_child', $data);
    $insert_id = $this->db->insert_id();
    
    //Check Duplicate URL
    $param_check['url'] = $url;
    $result_check = $this->get_data($param_check);
    if($result_check->num_rows() > 1){
      $url = url_title($name.$insert_id, 'dash', true);
      $data = array(
        'url' => $url
      );

      $this->db->where('id', $insert_id);
      $this->db->update('category_child', $data);
    }
    //End Check Duplicate URL
    
    return $insert_id;
  }

  function edit_data($param){
    //Set Param
    $id = (isset($param['id'])) ? $param['id'] : 0;
    $name = (isset($param['name'])) ? $param['name'] : "";
    $active = (isset($param['active'])) ? $param['active'] : 0;
    //End Set Param
    
    $data = array(
      'name' => $name,
      'active' => $active,
      'modtime' => date('Y-m-d H:i:s'),
      'modby' => $this->session->userdata('username')
    );
    
    $this->db->where('id', $id);
    $this->db->update('category_child', $data);
    
    //Deactive child if parent deactivated
    if($active == 0){
      $data_child = array(
        'active' => $active,
        'modtime' => date('Y-m-d H:i:s'),
        'modby' => $this->session->userdata('username')
      );

      $this->db->where('parent', $id);
      $this->db->update('category_child', $data_child);
    }
    //End Deactive child
    
    //Remove Child
    $this->db->where('id_category_child', $id);
    $this->db->delete('category_detail');
    //End Remove Child
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
    $this->db->update('category_child', $data);
    
    //Remove Child
    $this->db->where('id_category_child', $id);
    $this->db->delete('category_detail');
    //End Remove Child
  }
}
