<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
Class App extends CI_Controller {

  protected $db;


	function __construct()
	{   
	    parent::__construct();
		$this->load->model('form_m','form');
		$this->load->library('ezsql_mysql');
		$this->db = $this->ezsql_mysql;
		
		$this->load->model('app_m','app');
	}
	
	function add($gid = 0){
		$this->load->view('admin/app_add',array('content'=>$this->form->app_add($gid)));		
	}
	
	
	function lists(){
	  require_once 'Pager/Sliding.php';
	 
	if($this->input->post('cid')){ 
		 foreach($this->input->post('cid') as $v){
		  switch($this->input->post('task')){
		   case 'delete': $this->app->remove($v); break;
		   case 'dispatch': $this->app->dispatch($v); break;
		  }
		 }
	 }
	  
	  //echo "test";
	    $sql_filter = "";
        //$config['path'] = APP_ADMIN_URL;
		$config['totalItems'] = $this->db->get_var("SELECT COUNT(*) FROM campaign_app ".$sql_filter);
		$config['perPage'] = 20; 
		$config['urlVar'] = 'pageID';
		$pager = new Pager_Sliding($config);
		$links = $pager->getLinks($this->input->get('pageID'));
		list($from, $to) = $pager->getOffsetByPageId();
		
		$data = $this->app->retrieve(NULL,array('limit_number' => $config['perPage'],'limit_offset' => --$from));
		
		$this->load->view('admin/app',array('data'=> $data,'pagination'=>$links));	
	}
	
	
}