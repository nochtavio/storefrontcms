<?php

class Model_slider extends CI_Model {
  function __construct() {
    parent::__construct();
  }

  function get_data($param, $limit = 0, $size = 0) {
    //Set Param
    $id = (isset($param['id'])) ? $param['id'] : 0;
    $url = (isset($param['url'])) ? $param['url'] : "";
    $link = (isset($param['link'])) ? $param['link'] : "";
    $active = (isset($param['active'])) ? $param['active'] : -1;
    $order = (isset($param['order'])) ? $param['order'] : -1;
    //End Set Param
    
    $this->db->select('slider.*');
    $this->db->from('slider');
    
    //Validation
    if($id > 0){$this->db->where('slider.id', $id);}
    if($url != ""){$this->db->like('slider.url', $url);}
    if($link != ""){$this->db->like('slider.link', $link);}
    if($active > -1){$this->db->where('slider.active', $active);}
    //End Validation
    
    $this->db->where('slider.deleted', 0);
    if($order == 1){
      $this->db->order_by("slider.cretime", "asc");
    }else if($order == 2){
      $this->db->order_by("slider.show_order", "asc");
    }else if($order == 3){
      $this->db->order_by("slider.show_order", "desc");
    }else{
      $this->db->order_by("slider.cretime", "desc");
    }
    
    if($size >= 0){$this->db->limit($size, $limit);}
    $query = $this->db->get();
    
    return $query;
  }
  
  function add_data($param){
    //Set Param
    $show_order = (isset($param['show_order'])) ? $param['show_order'] : 0;
    $url = (isset($param['url'])) ? $param['url'] : "";
    $link = (isset($param['link'])) ? $param['link'] : "";
    $target = (isset($param['target'])) ? $param['target'] : 0;
    $title = (isset($param['title'])) ? $param['title'] : "";
    $description = (isset($param['description'])) ? $param['description'] : "";
    $active = (isset($param['active'])) ? $param['active'] : 0;
    //End Set Param
    
    $data = array(
      'show_order' => $show_order,
      'url' => $url,
      'link' => $link,
      'target' => $target,
      'title' => $title,
      'description' => $description,
      'active' => $active,
      'cretime' => date('Y-m-d H:i:s'),
      'creby' => 'SYSTEM'
    );
    $this->db->insert('slider', $data);
    $insert_id = $this->db->insert_id();
    
    return $insert_id;
  }

  function edit_data($param){
    //Set Param
    $id = (isset($param['id'])) ? $param['id'] : 0;
    $show_order = (isset($param['show_order'])) ? $param['show_order'] : 0;
    $url = (isset($param['url'])) ? $param['url'] : "";
    $link = (isset($param['link'])) ? $param['link'] : "";
    $target = (isset($param['target'])) ? $param['target'] : 0;
    $title = (isset($param['title'])) ? $param['title'] : "";
    $description = (isset($param['description'])) ? $param['description'] : "";
    $active = (isset($param['active'])) ? $param['active'] : 0;
    //End Set Param
    
    $data = array(
      'show_order' => $show_order,
      'url' => $url,
      'link' => $link,
      'target' => $target,
      'title' => $title,
      'description' => $description,
      'active' => $active,
      'modtime' => date('Y-m-d H:i:s'),
      'modby' => 'SYSTEM'
    );
    
    $this->db->where('id', $id);
    $this->db->update('slider', $data);
  }
  
  function remove_data($param){
    //Set Param
    $id = (isset($param['id'])) ? $param['id'] : 0;
    //End Set Param
    
    $data = array(
      'deleted' => 1,
      'modtime' => date('Y-m-d H:i:s'),
      'modby' => 'SYSTEM'
    );
    
    $this->db->where('id', $id);
    $this->db->update('slider', $data);
  }
}
