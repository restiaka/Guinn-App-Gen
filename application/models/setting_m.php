<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
Class Setting_m extends CI_Model {
  
  function __construct()
  {
    parent::__construct();
	$this->load->library('ezsql_mysql');	
  }
  
  public function get($key)
  {
	return $this->config->item($key);
  }
  
  public function set($key,$value,$overwrite = TRUE)
  {
	return $this->config->set_item($key, $value);
  }

}