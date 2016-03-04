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
