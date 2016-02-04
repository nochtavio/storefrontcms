<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

  public function index() {
    $page = 'Dashboard';
    $sidebar['page'] = $page;
    
    $data['header'] = $this->load->view('header', '', TRUE);
    $data['sidebar'] = $this->load->view('sidebar', $sidebar, TRUE);
    $data['content'] = $this->load->view('dashboard/index', '', TRUE);
    $this->load->view('template_index', $data);
  }
}
