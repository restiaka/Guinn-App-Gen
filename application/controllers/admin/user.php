<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
Class User extends CI_Controller {

  protected $db;
 	
	function __construct()
	{
		parent::__construct();
		$this->load->model('form_m','form');
		$this->load->library('ezsql_mysql');
		$this->db = $this->ezsql_mysql;
		
		$this->load->model('user_m','user');
		
	
	}
	
	function add($uid=0){
		$this->load->view('admin/user_add',array('content'=> $this->form->user_add($uid)));
	}
	
	function lists(){
	  require_once 'Pager/Sliding.php';
	 
	if(@$this->input->post('cid')){ 
		 foreach($this->input->post('cid') as $v){
		  switch($this->input->post('task')){
		   case 'activate': $this->user->setStatus($v,'active'); break;
		   case 'deactivate': $this->user->setStatus($v,'inactive'); break;
		   case 'delete': $this->user->remove($v); break;
		  }
		 }
	 }
	  
	  //echo "test";
	    $sql_filter = "";
        //$config['path'] = APP_ADMIN_URL;
		$config['totalItems'] = $this->db->get_var("SELECT COUNT(*) FROM campaign_user ".$sql_filter);
		$config['perPage'] = 20; 
		$config['urlVar'] = 'pageID';
		$pageID = $this->input->get('pageID') ? $this->input->get('pageID') : 1;
		$pager = new Pager_Sliding($config);
		$links = $pager->getLinks($pageID);
		list($from, $to) = $pager->getOffsetByPageId();
		
		$data = $this->user->retrieve(NULL,array('limit_number' => $config['perPage'],'limit_offset' => --$from));
		
		$this->load->view('admin/user',array('data'=> $data,'pagination'=>$links));
		
		
	}
	
	
}