<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migrate extends CI_Controller {

  function __construct() {
    date_default_timezone_set('Asia/Jakarta');
    parent::__construct();
  }
  
  public function readExcel($excel_file = NULL){
    $values = array();
    
    if($excel_file == NULL){
      return $values;
    }
    
    $objPHPExcel = PHPExcel_IOFactory::load($excel_file);

    //get only the Cell Collection
    $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
    
    //extract to a PHP readable array format
    foreach ($cell_collection as $key => $cell) {
      //$column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
      $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
      $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();
      
      //header will/should be in row 1 only. of course this can be modified to suit your need.
      if ($row != 1) {
        $values[$row][] = (isset($data_value) && $data_value != '.') ? $data_value : '' ;
      }
    }
    
    return $values;
  }

  public function migrateProducts() {
    $this->load->library('excel');
    $this->load->model('Model_products');

    $excel_file = './files/migration_product.csv';
    $excel_data = $this->readExcel($excel_file);
    
    if(empty($excel_data)){
      echo 'Excel file is not existed or empty';
      die();
    }
    
    foreach($excel_data as $data){
      //param
      $param = array();
      $param['id_brand']          = $data[0];
      $param['name']              = $data[1];
      $param['price']             = $data[2];
      $param['sale_price']        = $data[3];
      $param['modal_price']       = $data[4];
      $param['potongan_gold']     = $data[5];
      $param['potongan_silver']   = $data[6];
      $param['potongan_bronze']   = $data[7];
      $param['weight']            = $data[8];
      $param['description']       = $data[9];
      $param['short_description'] = $data[10];
      //end param

      $this->Model_products->add_data($param);
    }

    echo count($excel_data).' products is migrated successfully.';
  }
  
  public function migrateProductImages($list_url, $id_products, $id_color){
    $this->load->model('Model_products_image');
    
    if(empty($list_url)){
      return false;
    }
    
    foreach($list_url as $url){
      $param['id_products'] = $id_products;
      $param['id_color']    = $id_color;
      $param['exact_url']   = '/images/products/migration/'.$url;
      $check_duplicate = $this->Model_products_image->get_data($param);
      if($check_duplicate->num_rows() <= 0){
        $param_images = array();
        $param_images['id_products']  = $id_products;
        $param_images['id_color']     = $id_color;
        $param_images['url']          = '/images/products/migration/'.$url;
        $this->Model_products_image->add_data($param_images);
      }
    }
    
    return true;
  }
  
  public function migrateProductVariants(){
    $this->load->library('excel');
    $this->load->model('Model_products_variant_detail');

    $excel_file = './files/migration_variant.csv';
    $excel_data = $this->readExcel($excel_file);
    
    if(empty($excel_data)){
      echo 'Excel file is not existed or empty';
      die();
    }
    
    foreach($excel_data as $data){
      //Insert to products_variant
      //param
      $param = array();
      $param['id_products']         = $data[0];
      $param['id_color']            = $data[1];
      $param['size']                = $data[2];
      $param['sku']                 = $data[3];
      $param['quantity']            = $data[4];
      $param['max_quantity_order']  = $data[5];
      //end param

      $this->Model_products_variant_detail->add_data($param);
      //End Insert to products_variant
      
      //Insert to product_images
      $list_url = array();
      if($data[6] != ''){
        $list_url [] = $data[6];
      }
      if($data[7] != ''){
        $list_url [] = $data[7];
      }
      if($data[8] != ''){
        $list_url [] = $data[8];
      }
      if($data[9] != ''){
        $list_url [] = $data[9];
      }
      
      $this->migrateProductImages($list_url, $data[0], $data[1]);
      //End Insert to product_images
    }

    echo count($excel_data).' products variant is migrated successfully.';
  }
}
