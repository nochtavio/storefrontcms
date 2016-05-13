<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Order_item extends CI_Controller {
  
  function __construct() {
    date_default_timezone_set('Asia/Jakarta');
    parent::__construct();
    check_address();
    check_login();
    if(!check_menu()){
      redirect(base_url().'dashboard/');
    }
    $this->load->model('Model_order_item');
  }
  
  public function index() {
    $page = 'Order_item';
    $sidebar['page'] = $page;
    $content['js'] = array();
    array_push($content['js'], 'order_item/function.js');
    array_push($content['js'], 'order_item/init.js');
    array_push($content['js'], 'order_item/action.js');
    
    $data['header'] = $this->load->view('header', '', TRUE);
    $data['sidebar'] = $this->load->view('sidebar', $sidebar, TRUE);
    $data['content'] = $this->load->view('order_item/index', $content, TRUE);
    $this->load->view('template_index', $data);
  }
  
  public function get_data(){
    //param
    $param['products_name'] = ($this->input->post('products_name', TRUE)) ? $this->input->post('products_name', TRUE) : "";
    $param['SKU'] = ($this->input->post('SKU', TRUE)) ? $this->input->post('SKU', TRUE) : "";
    $param['reseller_email'] = ($this->input->post('reseller_email', TRUE)) ? $this->input->post('reseller_email', TRUE) : "";
    $param['reseller_name'] = ($this->input->post('reseller_name', TRUE)) ? $this->input->post('reseller_name', TRUE) : "";
    $param['order'] = ($this->input->post('order', TRUE)) ? $this->input->post('order', TRUE) : -1;
    //end param
    
    //paging
    $get_data = $this->Model_order_item->get_data($param);
    $page = ($this->input->post('page', TRUE)) ? $this->input->post('page', TRUE) : 1 ;
    $size = ($this->input->post('size', TRUE)) ? $this->input->post('size', TRUE) : 10 ;
    $limit = ($page - 1) * $size;
    //End Set totalpaging

    if ($get_data->num_rows() > 0) {
      $get_data_paging = $this->Model_order_item->get_data($param, $limit, $size);
      $temp = 0;
      foreach ($get_data_paging->result() as $row) {
        $data['result'] = "r1";
        $data['id'][$temp] = $row->id;
        $data['products_name'][$temp] = $row->products_name;
        $data['SKU'][$temp] = $row->SKU;
        $data['color_name'][$temp] = $row->color_name;
        $data['reseller_email'][$temp] = $row->reseller_email;
        $data['reseller_name'][$temp] = $row->reseller_name;
        $data['quantity'][$temp] = $row->quantity;
        $temp++;
      }
      $data['allowed_edit'] = check_menu("", 2);
      $data['allowed_delete'] = check_menu("", 3);
      $data['total'] = $temp;
      $data['size'] = $size;
      $data['totalpage'] = ceil($get_data->num_rows() / $size);
    } else {
      $data['result'] = "r2";
      $data['message'] = "No Data";
    }
    
    echo json_encode($data);
  }
}
