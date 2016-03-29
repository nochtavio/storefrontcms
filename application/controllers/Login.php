<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
  
  function __construct() {
    date_default_timezone_set('Asia/Jakarta');
    parent::__construct();
    check_address();
    $this->load->model('Model_admin');
  }
  
  public function index() {
    $this->load->view('login/index');
  }
  
  public function login(){
    //post
    $param['username'] = ($this->input->post('username', TRUE)) ? $this->input->post('username', TRUE) : "" ;
    $param['password'] = ($this->input->post('password', TRUE)) ? hash('sha1', $this->input->post('password', TRUE).get_salt()) : "" ;
    //end post
    
    $data['result'] = "r1";
    $data['result_message'] = "";
    
    if($param['username'] == ""){
      $data['result'] = "r2";
      $data['result_message'] .= "Username must be filled. <br/>";
    }
    if($param['password'] == ""){
      $data['result'] = "r2";
      $data['result_message'] .= "Password must be filled. <br/>";
    }
    if($data['result'] == "r1"){
      $param_check['username'] = $param['username'];
      $result_check = $this->Model_admin->get_data($param_check);
      if($result_check->num_rows() > 0){
        //Get Username Detail
        $id = $result_check->row()->id;
        $active = $result_check->row()->active;
        $password = $result_check->row()->password;
        $id_role = $result_check->row()->id_role;
        $role_name = $result_check->row()->role_name;
        //End Get Username Detail
        
        if($active == 0){
          $data['result'] = "r2";
          $data['result_message'] .= 'Username is not active. <br/>';
        }else{
          if($param['password'] != $password){
            $data['result'] = "r2";
            $data['result_message'] .= 'Username and Password are not match.<br/>';
          }else{
            //Set Session Login
            $sess_login = array(
              'id'        => $id,
              'username'  => $param['username'],
              'id_role'   => $id_role,
              'role'      => $role_name,
              'logged_in' => TRUE
            );

            $this->session->set_userdata($sess_login);
            //End Set Session Login
          }
        }
      }else{
        $data['result'] = "r2";
        $data['result_message'] .= 'Username is not exist. <br/>';
      }
    }
    
    echo json_encode($data);
  }
  
  public function logout(){
    $sess_login = array('username', 'id_role', 'role', 'logged_in');
    $this->session->unset_userdata($sess_login);
    echo json_encode(TRUE);
  }
}
