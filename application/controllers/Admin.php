<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {
  
  function __construct() {
    date_default_timezone_set('Asia/Jakarta');
    parent::__construct();
    $this->load->model('Model_admin');
  }
  
  public function index() {
    $page = 'Admin';
    $sidebar['page'] = $page;
    $content['js'] = array();
    array_push($content['js'], 'admin/function.js');
    array_push($content['js'], 'admin/init.js');
    array_push($content['js'], 'admin/action.js');
    
    $data['header'] = $this->load->view('header', '', TRUE);
    $data['sidebar'] = $this->load->view('sidebar', $sidebar, TRUE);
    $data['content'] = $this->load->view('admin/index', $content, TRUE);
    $this->load->view('template_index', $data);
  }
  
  public function get_data(){
    //filter
    $username = ($this->input->post('username', TRUE)) ? $this->input->post('username', TRUE) : "" ;
    $active = ($this->input->post('active', TRUE)) ? $this->input->post('active', TRUE) : 0 ;
    $order = ($this->input->post('order', TRUE)) ? $this->input->post('order', TRUE) : -1 ;
    //end filter
    
    //paging
    $page = ($this->input->post('page', TRUE)) ? $this->input->post('page', TRUE) : 1 ;
    $size = ($this->input->post('size', TRUE)) ? $this->input->post('size', TRUE) : 10 ;
    $limit = ($page - 1) * $size;
    //end paging
    
    //Set totalpaging
    $totalrow = $this->Model_admin->get_data(0, $username, $active, $order)->num_rows();
    $totalpage = ceil($totalrow / $size);
    $data['totalpage'] = $totalpage;
    //End Set totalpaging

    if ($totalrow > 0) {
      $query = $this->Model_admin->get_data(0, $username, $active, $order, $limit, $size)->result();
      $temp = 0;
      foreach ($query as $row) {
        $data['result'] = "r1";
        $data['id'][$temp] = $row->id;
        $data['username'][$temp] = $row->username;
        $data['active'][$temp] = $row->active;
        $data['cretime'][$temp] = date_format(date_create($row->cretime), 'd F Y H:i:s');
        $data['creby'][$temp] = $row->creby;
        $data['modtime'][$temp] = ($row->modtime == NULL) ? NULL : date_format(date_create($row->modtime), 'd F Y H:i:s');
        $data['modby'][$temp] = $row->modby;
        $temp++;
      }
      $data['total'] = $temp;
      $data['size'] = $size;
    } else {
      $data['result'] = "r2";
      $data['message'] = "No Admin";
    }
    
    echo json_encode($data);
  }
  
  public function get_specific_data(){
    //filter
    $id = ($this->input->post('id', TRUE)) ? $this->input->post('id', TRUE) : "";
    //end filter
    
    $result_data = $this->Model_admin->get_data($id);
    if($result_data->num_rows() > 0){
      $data['result'] = "r1";
      $data['id'] = $result_data->row()->id;
      $data['username'] = $result_data->row()->username;
      $data['active'] = $result_data->row()->active;
    }else{
      $data['result'] = "r2";
      $data['message'] = "No Data";
    }
    
    echo json_encode($data);
  }
  
  public function validate_post($username, $password, $conf_password, $state = "add", $edit_password = TRUE){
    $data['result'] = "r1";
    $data['result_message'] = "";
    
    if($state == "add"){
      if($username == ""){
        $data['result'] = "r2";
        $data['result_message'] .= "<strong>Username</strong> must be filled !<br/>";
      }
    }
    
    if($edit_password){
      if($password == ""){
        $data['result'] = "r2";
        $data['result_message'] .= "<strong>Password</strong> must be filled !<br/>";
      }elseif($password != $conf_password){
        $data['result'] = "r2";
        $data['result_message'] .= "<strong>Password</strong> and <strong>Confirmation Password</strong> must match !<br/>";
      }
    }
    
    return $data;
  }
  
  public function add_data(){
    //post
    $username = ($this->input->post('username', TRUE)) ? $this->input->post('username', TRUE) : "" ;
    $password = ($this->input->post('password', TRUE)) ? $this->input->post('password', TRUE) : "" ;
    $conf_password = ($this->input->post('conf_password', TRUE)) ? $this->input->post('conf_password', TRUE) : "" ;
    $active = ($this->input->post('active', TRUE)) ? $this->input->post('active', TRUE) : "" ;
    //end post
    
    $validate_post = $this->validate_post($username, $password, $conf_password, "add", TRUE);
    if($validate_post['result'] == "r1"){
      $this->Model_admin->add_data($username, $password, $active);
    }
    
    echo json_encode($validate_post);
  }
  
  public function edit_data(){
    //post
    $id = ($this->input->post('id', TRUE)) ? $this->input->post('id', TRUE) : "" ;
    $password = ($this->input->post('password', TRUE)) ? $this->input->post('password', TRUE) : "" ;
    $conf_password = ($this->input->post('conf_password', TRUE)) ? $this->input->post('conf_password', TRUE) : "" ;
    $active = ($this->input->post('active', TRUE)) ? $this->input->post('active', TRUE) : "" ;
    //end post
    
    //check password is edited or not
    $edit_password = TRUE;
    if($password == ""){
      $edit_password = FALSE;
    }
    //end check
    
    if($id != ""){
      $validate_post = $this->validate_post("", $password, $conf_password, "edit", $edit_password);
      if($validate_post['result'] == "r1"){
        $this->Model_admin->edit_data($id, $password, $active);
      }
    }else{
      $validate_post['result'] = "r2";
      $validate_post['result_message'] = "<strong>Data ID</strong> is not found, please refresh your browser!<br/>";
    }
    
    echo json_encode($validate_post);
  }
  
  public function remove_data(){
    //post
    $id = ($this->input->post('id', TRUE)) ? $this->input->post('id', TRUE) : "" ;
    //end post
    
    if($id != ""){
      $data['result'] = "r1";
      $this->Model_admin->remove_data($id);
    }else{
      $data['result'] = "r2";
      $data['result_message'] = "<strong>Data ID</strong> is not found, please refresh your browser!<br/>";
    }
    
    echo json_encode($data);
  }
}
