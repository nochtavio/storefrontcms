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
  $CI->load->model('Model_menu');
  
  $param_get['name'] = ($menu != "") ? $menu : $CI->uri->segment(1);
  $param_get['type'] = $type;
  $get_id_menu = $CI->Model_menu->get_data($param_get);
  if ($get_id_menu->num_rows() > 0) {
    $id_menu = $get_id_menu->row()->id;
    $param_role['id_menu'] = $id_menu;
    $param_role['id_role'] = $CI->session->userdata('id_role');
    $get_role_menu = $CI->Model_menu->get_role_menu($param_role);
    if ($get_role_menu->num_rows() > 0) {
      return true;
    }else{
      return false;
    }
  }else{
    return false;
  }
}
