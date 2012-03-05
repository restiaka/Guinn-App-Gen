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
	
		
	public function getMsg($type){
		$template = array("submission_true"=>"Your data has been successfuly submitted.",
					 "submission_false"=>"Submission has Failed, Please Try Again.",
					 "update_true"=>"Item(s) has been successfuly updated.",
					 "update_false"=>"Item(s) has failed to be updated. Please Try Again.",
					 "delete_true"=>"Item(s) has been successfuly deleted",
					 "delete_false"=>"Item(s) has failed to be deleted. Please Try Again.");
					 
	    return isset($template[$type]) ? $template[$type] : '';	   
	}	
		
		
	function lists(){
	  require_once 'Pager/Sliding.php';
	 
	if(@$this->input->post('cid')){ 
		 foreach($this->input->post('cid') as $v){
		  switch($this->input->post('task')){
		   case 'publish': if($this->page->setStatusPage($v,'publish')){
			   $this->notify->set_message('success', $this->getMsg('update_true'));
			   }else{
			   $this->notify->set_message('error', $this->getMsg('update_false'));
			   } break;
		   case 'draft': if($this->page->setStatusPage($v,'draft')){
			   $this->notify->set_message('success', $this->getMsg('update_true'));
			   }else{
			   $this->notify->set_message('error', $this->getMsg('update_false'));
			   } break;
		   case 'delete': if($this->page->removePage($v)){
			   $this->notify->set_message('success', $this->getMsg('delete_true'));
			   }else{
			   $this->notify->set_message('error', $this->getMsg('delete_false'));
			   } break;
		  }
		 }
	 }
	  
	  //echo "test";
	    $sql_filter = "";
        //$config['path'] = APP_ADMIN_URL;
		$config['totalItems'] = $this->db->get_var("SELECT COUNT(*) FROM campaign_page ".$sql_filter);
		$config['perPage'] = 20; 
		$config['urlVar'] = 'pageID';
		$pageID = $this->input->get('pageID') ? $this->input->get('pageID') : 1;
		$pager = new Pager_Sliding($config);
		$links = $pager->getLinks($pageID);
		list($from, $to) = $pager->getOffsetByPageId();
		
		$data = $this->page->retrievePage(NULL,array('limit_number' => $config['perPage'],'limit_offset' => --$from));
		
		$this->load->view('admin/page',array('data'=> $data,'pagination'=>$links));
		
		
	}	
	
}