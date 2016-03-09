<?php

function get_salt(){
  return 'storefrontindo2016';
}

function check_login() {
  $CI =& get_instance();
  if(!$CI->session->userdata('logged_in')){
    redirect(base_url().'login/');
  }
}

function check_menu($menu = "", $type = 0){
  $CI =& get_instance();
  $CI->load->model('Model_admin');
  
  //Set Param
  $param['menu'] = ($menu != "") ? $menu : $CI->uri->segment(1);
  $param['type'] = $type; //0: Index | 1: Add | 2: Edit | 3: Delete
  //End Set Param
  
  $get_data = $CI->Model_admin->get_menu($param);
  if ($get_data->num_rows() > 0) {
    $allowed_id = array_filter(explode(';', $get_data->row()->allowed_id));
    if(in_array($CI->session->userdata('id_role'), $allowed_id)){
      return true;
    }else{
      return false;
    }
  }else{
    return false;
  }
}
