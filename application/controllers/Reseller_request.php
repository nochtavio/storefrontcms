<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Reseller_request extends CI_Controller {
  
  function __construct() {
    date_default_timezone_set('Asia/Jakarta');
    parent::__construct();
    check_address();
    check_login();
    if(!check_menu()){
      redirect(base_url().'dashboard/');
    }
    $this->load->model('Model_reseller_request');
  }
  
  public function index() {
    $page = 'Reseller_request';
    $sidebar['page'] = $page;
    $content['js'] = array();
    array_push($content['js'], 'reseller_request/function.js');
    array_push($content['js'], 'reseller_request/init.js');
    array_push($content['js'], 'reseller_request/action.js');
    
    $data['header'] = $this->load->view('header', '', TRUE);
    $data['sidebar'] = $this->load->view('sidebar', $sidebar, TRUE);
    $data['content'] = $this->load->view('reseller_request/index', $content, TRUE);
    $this->load->view('template_index', $data);
  }
  
  public function get_data(){
    //param
    $param['name'] = ($this->input->post('name', TRUE)) ? $this->input->post('name', TRUE) : "";
    $param['store_name'] = ($this->input->post('store_name', TRUE)) ? $this->input->post('store_name', TRUE) : "";
    $param['email'] = ($this->input->post('email', TRUE)) ? $this->input->post('email', TRUE) : "";
    $param['phone'] = ($this->input->post('phone', TRUE)) ? $this->input->post('phone', TRUE) : "";
    $param['barang'] = ($this->input->post('barang', TRUE)) ? $this->input->post('barang', TRUE) : "";
    $param['promosi'] = ($this->input->post('promosi', TRUE)) ? $this->input->post('promosi', TRUE) : "";
    $param['domain'] = ($this->input->post('domain', TRUE)) ? $this->input->post('domain', TRUE) : "";
    $param['status'] = ($this->input->post('status', TRUE)) ? $this->input->post('status', TRUE) : 0;
    $param['order'] = ($this->input->post('order', TRUE)) ? $this->input->post('order', TRUE) : -1;
    //end param
    
    //paging
    $get_data = $this->Model_reseller_request->get_data($param);
    $page = ($this->input->post('page', TRUE)) ? $this->input->post('page', TRUE) : 1 ;
    $size = ($this->input->post('size', TRUE)) ? $this->input->post('size', TRUE) : 10 ;
    $limit = ($page - 1) * $size;
    //End Set totalpaging

    if ($get_data->num_rows() > 0) {
      $get_data_paging = $this->Model_reseller_request->get_data($param, $limit, $size);
      $temp = 0;
      foreach ($get_data_paging->result() as $row) {
        $data['result'] = "r1";
        $data['id'][$temp] = $row->id;
        $data['name'][$temp] = $row->name;
        $data['store_name'][$temp] = $row->store_name;
        $data['email'][$temp] = $row->email;
        $data['phone'][$temp] = $row->phone;
        $data['status'][$temp] = $row->status;
        $data['cretime'][$temp] = date_format(date_create($row->cretime), 'd F Y H:i:s');
        $temp++;
      }
      $data['allowed_edit'] = check_menu("", 2);
      $data['allowed_delete'] = check_menu("", 3);
      $data['total'] = $temp;
      $data['size'] = $size;
      $data['totalpage'] = ceil($get_data->num_rows() / $size);
    } else {
      $data['result'] = "r2";
      $data['message'] = "No Reseller Request";
    }
    
    echo json_encode($data);
  }
  
  public function get_specific_data(){
    //param
    $param['id'] = ($this->input->post('id', TRUE)) ? $this->input->post('id', TRUE) : "";
    //end param
    
    $result_data = $this->Model_reseller_request->get_data($param);
    if($result_data->num_rows() > 0){
      $data['result'] = "r1";
      $data['id'] = $result_data->row()->id;
      $data['name'] = $result_data->row()->name;
      $data['store_name'] = $result_data->row()->store_name;
      $data['email'] = $result_data->row()->email;
      $data['phone'] = $result_data->row()->phone;
      $data['barang'] = $result_data->row()->barang;
      $data['promosi'] = $result_data->row()->promosi;
      $data['domain'] = $result_data->row()->domain;
      $data['keterangan'] = $result_data->row()->keterangan;
    }else{
      $data['result'] = "r2";
      $data['message'] = "No Data";
    }
    
    echo json_encode($data);
  }
  
  public function approval(){
    //post
    $param['id'] = ($this->input->post('id', TRUE)) ? $this->input->post('id', TRUE) : "" ;
    //end post
    
    $result_data = $this->Model_reseller_request->get_data($param);
    if($result_data->num_rows() > 0){
      $param['name'] = $result_data->row()->name;
      $param['store_name'] = $result_data->row()->store_name;
      $param['email'] = $result_data->row()->email;
      $param['phone'] = $result_data->row()->phone;
      $param['upline_email'] = $result_data->row()->upline_email;
      
      $approval = $this->Model_reseller_request->approval($param);
      
      //Send Email
      $config = Array(
        'protocol' => 'smtp',
        'smtp_host' => 'mail.storefrontindo.com',
        'smtp_port' => 25,
        'smtp_user' => 'do-not-reply@storefrontindo.com', // change it to yours
        'smtp_pass' => 'v0AOsm[viHJB', // change it to yours
        'mailtype' => 'html',
        'charset' => 'iso-8859-1',
        'wordwrap' => TRUE
      );

      $this->load->library('email', $config);
      $this->email->set_newline("\r\n");
      $this->email->set_mailtype("html");
      $this->email->from('do-not-reply@storefrontindo.com', 'Storefront Indonesia'); // change it to yours
      $this->email->to($param['email']); // change it to yours
      $this->email->subject("Reseller Approval");
      $this->email->message(""
        . "Dear <strong>".$param['name']."</strong><br/> <br/>"
        . "Selamat karena permintaan anda untuk menjadi reseller FFStore sudah kami setujui. Kami akan memproses dan membuatkan toko online anda dalam kurun waktu 5 - 10 hari kerja. Berikut adalah detail untuk masuk ke dalam admin panel reseller. <br/> <br/>"
        . "<strong>Email: </strong> ".$param['email']."<br/> <br/>"
        . "<strong>Password: </strong> ".$approval['password']."<br/> <br/>"
        . "Silahkan login ke admin panel <a href='http://www.storefrontindo.com/front/reseller/login/' target='_blank'>http://www.storefrontindo.com/front/reseller/login/</a>  dengan menggunakan email dan password diatas. <br/> <br/>"
        . "Salam <br/> <br/>"
        . "Owner FFStore"
        . "");
      //End Send Email
      
      if ($this->email->send()) {
        $data['result'] = "r1";
      } else {
        $data['result'] = "r2";
        $data['result_message'] = show_error($this->email->print_debugger());
      }
    }else{
      $data['result'] = "r2";
      $data['result_message'] = "<strong>Data ID</strong> is not found, please refresh your browser!<br/>";
    }
    
    echo json_encode($data);
  }
}
