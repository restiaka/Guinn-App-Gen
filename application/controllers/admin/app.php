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
	 
	if($this->input->post('cid')){ 
		 foreach($this->input->post('cid') as $v){
		  switch($this->input->post('task')){
		   case 'delete': if($this->app->remove($v)){
		   $this->notify->set_message('success', $this->getMsg('delete_true'));
		   }else{
		   $this->notify->set_message('error', $this->getMsg('delete_false'));
		   }
		   break;
		   case 'dispatch': if($this->app->dispatch($v)){
		   $this->notify->set_message('success', $this->getMsg('update_true'));
		   }else{
		   $this->notify->set_message('error', $this->getMsg('update_false'));
		   } break;
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
		$pageID = $this->input->get('pageID') ? $this->input->get('pageID') : 1;
		$links = $pager->getLinks($pageID);
		list($from, $to) = $pager->getOffsetByPageId();
		
		$data = $this->app->retrieve(NULL,array('limit_number' => $config['perPage'],'limit_offset' => --$from));
		
		$this->load->view('admin/app',array('data'=> $data,'pagination'=>$links));	
	}
	
    function exportcustomer($appid){
	 $this->load->model('export_m','export');
	 $this->export->exportCustomerByAppID($appid);
	}
	
	
}