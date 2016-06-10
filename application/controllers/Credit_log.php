<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Credit_log extends CI_Controller {

  function __construct() {
    date_default_timezone_set('Asia/Jakarta');
    parent::__construct();
    check_address();
    check_login();
    if(!check_menu()){
      redirect(base_url().'dashboard/');
    }
    $this->load->model('Model_credit_log');
    $this->load->model('Model_customer');
    $this->load->model('Model_reseller');
  }

  public function index() {
    $page = 'Credit_log';
    $sidebar['page'] = $page;
    $content['js'] = array();
    array_push($content['js'], 'credit_log/function.js');
    array_push($content['js'], 'credit_log/init.js');
    array_push($content['js'], 'credit_log/action.js');

    $data['header'] = $this->load->view('header', '', TRUE);
    $data['sidebar'] = $this->load->view('sidebar', $sidebar, TRUE);
    $data['content'] = $this->load->view('credit_log/index', $content, TRUE);
    $this->load->view('template_index', $data);
  }

  public function get_data(){
    //param
    $param['email'] = ($this->input->post('email', TRUE)) ? $this->input->post('email', TRUE) : "";
    $param['type'] = ($this->input->post('type', TRUE)) ? $this->input->post('type', TRUE) : 0;
    $param['credit_log_type'] = ($this->input->post('credit_log_type', TRUE)) ? $this->input->post('credit_log_type', TRUE) : 0;
    $param['status'] = ($this->input->post('status', TRUE)) ? $this->input->post('status', TRUE) : 0;
    $param['order'] = ($this->input->post('order', TRUE)) ? $this->input->post('order', TRUE) : -1;
    //end param

    //paging
    $get_data = $this->Model_credit_log->get_data($param);
    $page = ($this->input->post('page', TRUE)) ? $this->input->post('page', TRUE) : 1 ;
    $size = ($this->input->post('size', TRUE)) ? $this->input->post('size', TRUE) : 20 ;
    $limit = ($page - 1) * $size;
    //End Set totalpaging

    if ($get_data->num_rows() > 0) {
      $get_data_paging = $this->Model_credit_log->get_data($param, $limit, $size);
      $temp = 0;
      foreach ($get_data_paging->result() as $row) {
        $data['result'] = "r1";
        $data['id'][$temp] = $row->id;
        $data['email'][$temp] = ($row->customer_email != NULL) ? $row->customer_email : $row->email ;
        $data['credit_log_type'][$temp] = ($row->id_customer == 1) ? 'Customer' : 'Reseller' ;
        $data['amount'][$temp] = number_format($row->amount);
        $data['type'][$temp] = $row->type;
        $data['description'][$temp] = $row->description;
        $data['payment_method'][$temp] = $row->payment_method;
        $data['status'][$temp] = $row->status;
        $data['cretime'][$temp] = date_format(date_create($row->cretime), 'd F Y H:i:s');
        $data['modtime'][$temp] = ($row->modtime == NULL) ? NULL : date_format(date_create($row->modtime), 'd F Y H:i:s');
        $data['modby'][$temp] = $row->modby;
        $temp++;
      }
      $data['allowed_edit'] = check_menu("", 2);
      $data['total'] = $temp;
      $data['size'] = $size;
      $data['totalpage'] = ceil($get_data->num_rows() / $size);
    } else {
      $data['result'] = "r2";
      $data['message'] = "No Credit_log";
    }

    echo json_encode($data);
  }

  public function get_specific_data(){
    //param
    $param['id'] = ($this->input->post('id', TRUE)) ? $this->input->post('id', TRUE) : "";
    //end param

    $result_data = $this->Model_credit_log->get_data($param);
    if($result_data->num_rows() > 0){
      $data['result'] = "r1";
      $data['id'] = $result_data->row()->id;
      $data['id_customer'] = $result_data->row()->id_customer;
      $data['id_reseller'] = $result_data->row()->id_reseller;
      $data['email'] = ($result_data->row()->customer_email != NULL) ? $result_data->row()->customer_email : $result_data->row()->email ;
      $data['amount'] = $result_data->row()->amount;
      $data['type'] = $result_data->row()->type;
      $data['type'] = $result_data->row()->type;
      $data['description'] = $result_data->row()->description;
      $data['status'] = $result_data->row()->status;
    }else{
      $data['result'] = "r2";
      $data['message'] = "No Data";
    }

    echo json_encode($data);
  }

  public function validate_post($param){
    //param
    $id_customer = (isset($param['id_customer'])) ? $param['id_customer'] : NULL;
    $id_reseller = (isset($param['id_reseller'])) ? $param['id_reseller'] : NULL;
    $amount = (isset($param['amount'])) ? $param['amount'] : 0;
    //end param

    $data['result'] = "r1";
    $data['result_message'] = "";

    if($id_customer == NULL && $id_reseller == NULL){
      $data['result'] = "r2";
      $data['result_message'] .= "<strong>Email</strong> is invalid !<br/>";
    }

    if($amount == ''){
      $data['result'] = "r2";
      $data['result_message'] .= "<strong>Amount</strong> must be filled !<br/>";
    }else if(!is_numeric($amount) || $amount <= 0){
      $data['result'] = "r2";
      $data['result_message'] .= "<strong>Amount</strong> is incorrect !<br/>";
    }

    return $data;
  }

  public function add_data(){
    //param
    $param['email'] = ($this->input->post('email', TRUE)) ? $this->input->post('email', TRUE) : NULL ;
    $param['type'] = ($this->input->post('type', TRUE)) ? $this->input->post('type', TRUE) : 1 ;
    $param['amount'] = ($this->input->post('amount', TRUE)) ? $this->input->post('amount', TRUE) : 0 ;

    //Get ID
    if($param['type'] == 1 && $param['email'] != NULL){
      //Customer
      $param_customer['exact_customer_email'] = $param['email'];
      $get_customer = $this->Model_customer->get_data($param_customer);
      if($get_customer->num_rows() > 0){
        $param['id_customer'] = $get_customer->row()->customer_id;
        $customer_credit = $get_customer->row()->customer_credit;
      }
    }else if($param['type'] == 2 && $param['email'] != NULL){
      //Reseller
      $param_reseller['exact_email'] = $param['email'];
      $get_reseller = $this->Model_reseller->get_data($param_reseller);
      if($get_reseller->num_rows() > 0){
        $param['id_reseller'] = $get_reseller->row()->id;
        $reseller_wallet = $get_reseller->row()->wallet;
      }
    }
    //End Get ID
    //end param

    $validate_post = $this->validate_post($param);
    if($validate_post['result'] == "r1"){
      $param['updated_credit'] = (isset($customer_credit)) ? $customer_credit + $param['amount'] : NULL ;
      $param['updated_wallet'] = (isset($reseller_wallet)) ? $reseller_wallet + $param['amount'] : NULL ;

      $this->Model_credit_log->add_data($param);
    }

    echo json_encode($validate_post);
  }

  public function edit_data(){
    //param
    $param['id'] = ($this->input->post('id', TRUE)) ? $this->input->post('id', TRUE) : "" ;
    $param['id_customer'] = ($this->input->post('id_customer', TRUE)) ? $this->input->post('id_customer', TRUE) : "" ;
    $param['id_reseller'] = ($this->input->post('id_reseller', TRUE)) ? $this->input->post('id_reseller', TRUE) : "" ;
    $param['amount'] = ($this->input->post('amount', TRUE)) ? $this->input->post('amount', TRUE) : 0 ;
    $param['status'] = ($this->input->post('status', TRUE)) ? $this->input->post('status', TRUE) : "0" ;
    //end param

    if($param['id'] != ""){
      $validate_post = $this->validate_post($param);
      if($validate_post['result'] == "r1"){
        //Get Credit Log
        $param_credit_log['id'] = $param['id'];
        $get_credit_log = $this->Model_credit_log->get_data($param_credit_log);
        if($get_credit_log->num_rows() > 0){
          $status = $get_credit_log->row()->status;
        }
        //End Get Credit log

        //Update Customer Credit
        if($param['id_customer'] != ""){
          $param_customer_credit['customer_id'] = $param['id_customer'];
          $get_customer_credit = $this->Model_customer->get_data($param_customer_credit);
          if($get_customer_credit->num_rows() > 0){
            $customer_credit = $get_customer_credit->row()->customer_credit;
          }

          if($param['status'] != $status && $param['status'] != ""){
            if($param['status'] == 1){
              $param['updated_credit'] = $customer_credit + $param['amount'];
            }else{
              $param['updated_credit'] = $customer_credit - $param['amount'];
            }
          }
        }
        //End Update Customer Credit

        //Update Reseller Wallet
        if($param['id_reseller'] != ""){
          $param_reseller_wallet['id'] = $param['id_reseller'];
          $get_reseller_wallet = $this->Model_reseller->get_data($param_reseller_wallet);
          if($get_reseller_wallet->num_rows() > 0){
            $reseller_wallet = $get_reseller_wallet->row()->wallet;
          }

          if($param['status'] != $status && $param['status'] != ""){
            if($param['status'] == 1){
              $param['updated_wallet'] = $reseller_wallet + $param['amount'];
            }else{
              $param['updated_wallet'] = $reseller_wallet - $param['amount'];
            }
          }
        }
        //End Update Reseller Wallet

        $this->Model_credit_log->edit_data($param);
      }
    }else{
      $validate_post['result'] = "r2";
      $validate_post['result_message'] = "<strong>Data ID</strong> is not found, please refresh your browser!<br/>";
    }

    echo json_encode($validate_post);
  }
}
