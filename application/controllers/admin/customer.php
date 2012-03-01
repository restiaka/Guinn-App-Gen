<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
Class Customer extends CI_Controller {

  protected $db;
	
	function __construct()
	{
	    parent::__construct();
		$this->load->model('form_m','form');
		$this->load->library('ezsql_mysql');
		$this->db = $this->ezsql_mysql;
		$this->load->model('customer_m','customer');
		
		
	}
/*	
	function add($uid = 0){
		$this->load->view('admin/customer',array('content'=> $this->form->customer_add($uid)));
	}
*/	
	function lists(){
	  require_once 'Pager/Sliding.php';
	 
	if($this->input->post('cid')){ 
		 foreach($this->input->post('cid') as $v){
		  switch($this->input->post('task')){
		   case 'activate': $this->customer->setStatus($v,'active'); break;
		   case 'deactivate': $this->customer->setStatus($v,'inactive'); break;
		   case 'delete': $this->customer->remove($v); break;
		  }
		 }
	 }
	  
	  //echo "test";
	    $sql_filter = "";
        //$config['path'] = APP_ADMIN_URL;
		$config['totalItems'] = $this->db->get_var("SELECT COUNT(*) FROM campaign_customer ".$sql_filter);
		$config['perPage'] = 20; 
		$config['urlVar'] = 'pageID';
		$pager = new Pager_Sliding($config);
		$links = $pager->getLinks($this->input->get('pageID'));
		list($from, $to) = $pager->getOffsetByPageId();
		
		$data = $this->customer->retrieve(NULL,array('limit_number' => $config['perPage'],'limit_offset' => --$from));
		
		$this->load->view('admin/customer',array('data'=> $data,'pagination'=>$links));
		
		
	}
	
	
}