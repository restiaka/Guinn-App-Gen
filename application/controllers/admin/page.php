<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
Class Page extends CI_Controller {

  protected $db;
 	
	function __construct()
	{
		parent::__construct();
		$this->load->model('form_m','form');
		$this->load->library('ezsql_mysql');
		$this->db = $this->ezsql_mysql;
		
		$this->load->model('page_m','page');		
		$this->load->model('app_m','app');
	}
	
	function add($gid = 0){
		$this->load->view('admin/page_add',array('content'=> $this->form->page_add($gid)));
	}
	
	
	function lists(){
	  require_once 'Pager/Sliding.php';
	 
	if(@$this->input->post('cid')){ 
		 foreach($this->input->post('cid') as $v){
		  switch($this->input->post('task')){
		   case 'publish': $this->page->setStatusPage($v,'publish'); break;
		   case 'draft': $this->page->setStatusPage($v,'draft'); break;
		   case 'delete': $this->page->removePage($v); break;
		  }
		 }
	 }
	  
	  //echo "test";
	    $sql_filter = "";
        //$config['path'] = APP_ADMIN_URL;
		$config['totalItems'] = $this->db->get_var("SELECT COUNT(*) FROM campaign_page ".$sql_filter);
		$config['perPage'] = 20; 
		$config['urlVar'] = 'pageID';
		$pager = new Pager_Sliding($config);
		$links = $pager->getLinks(@$this->input->get('pageID'));
		list($from, $to) = $pager->getOffsetByPageId();
		
		$data = $this->page->retrievePage(NULL,array('limit_number' => $config['perPage'],'limit_offset' => --$from));
		
		$this->load->view('admin/page',array('data'=> $data,'pagination'=>$links));
		
		
	}	
	
}