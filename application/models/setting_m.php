<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
Class Setting_m extends CI_Model {
  
  
  function __construct()
  {
    parent::__construct();
	$this->load->library('ezsql_mysql');
    $this->load_setting();
	
		
  }
  
  public function load_setting()
  {
	$rows = $this->ezsql_mysql->get_results('SELECT * FROM campaign_setting','ARRAY_A');
		foreach ($rows as $row){
		   $this->set($row['name'], $row['value']);
		}
  }
  
  public function get($key)
  {
/* 	if(isset($this->setting[$key]))
	return $this->setting[$key]; */
	
	$this->config->item($key);
  }
  
  public function set($key,$value,$overwrite = TRUE)
  {
	/* if($overwrite){
	  $this->setting[$key] = $value;
	}else{
	  if(!isset($this->setting[$key]))
	  $this->setting[$key] = $value;
	} */
	
	$this->config->set_item($key, $value);
  }

}