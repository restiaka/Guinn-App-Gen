<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
Class Campaign extends CI_Controller {

  protected $db;
 	
	function __construct()
	{
		parent::__construct();
		$this->load->model('form_m','form');
		$this->load->library('ezsql_mysql');
		$this->db = $this->ezsql_mysql;
		
		$this->load->model('campaign_m','campaign');		
		$this->load->model('app_m','app');
		$this->load->model('export_m','export');
	}
	
	function add($gid = 0){
		$this->load->view('admin/campaign_add',array('content'=> $this->form->campaign_add($gid)));
	}
	
	function duplicate($gid = 0){
	  $this->load->view('admin/campaign_add',array('content'=> $this->form->campaign_duplicate($gid)));
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
			   case 'activate': if($this->campaign->setStatusCampaign($v,'active')){
			   $this->notify->set_message('success', $this->getMsg('update_true'));
			   }else{
			   $this->notify->set_message('error', $this->getMsg('update_false'));
			   } break;
			   case 'deactivate': if($this->campaign->setStatusCampaign($v,'inactive')){
			   $this->notify->set_message('success', $this->getMsg('update_true'));
			   }else{
			   $this->notify->set_message('error', $this->getMsg('update_false'));
			   } break;
			   case 'delete': if($this->campaign->removeCampaign($v)){
			   $this->notify->set_message('success', $this->getMsg('delete_true'));
			   }else{
			   $this->notify->set_message('error', $this->getMsg('delete_false'));
			   } break;
			   case 'announcewinner' : if($this->campaign->announceWinner($v,'1')){
			   $this->notify->set_message('success', $this->getMsg('update_true'));
			   }else{
			   $this->notify->set_message('error', $this->getMsg('update_false'));
			   } break;
			   case 'haltwinner' : if($this->campaign->announceWinner($v,'0')){
			   $this->notify->set_message('success', $this->getMsg('update_true'));
			   }else{
			   $this->notify->set_message('error', $this->getMsg('update_false'));
			   } break;
			  }
			 }
		 }elseif($this->input->post('task')){
			$this->notify->set_message('error', 'You haven\'t select any items required for the action.');
		 }
	  
	    $sql_filter = "";
        //$config['path'] = APP_ADMIN_URL;
		$config['totalItems'] = $this->db->get_var("SELECT COUNT(*) FROM campaign_group ".$sql_filter);
		$config['perPage'] = 50; 
		$config['urlVar'] = 'pageID';
		$pageID = $this->input->get('pageID') ? $this->input->get('pageID') : 1;
		$pager = new Pager_Sliding($config);
		$links = $pager->getLinks($pageID);
		list($from, $to) = $pager->getOffsetByPageId();
		
		$data = $this->campaign->retrieveCampaign(NULL,array('limit_number' => $config['perPage'],'limit_offset' => --$from));
		
		$this->load->view('admin/campaign',array('data'=> $data,'pagination'=>$links));
	}
	
	function exportlist($gid){
	 $this->export->exportUploadedLists($gid);
	}
	
	function exportfile($gid){
	 $this->export->exportUploadedFiles($gid);
	}
	
	function exportcustomer($gid){
	 $this->export->exportCustomerByCampaign($gid);
	}
	
	
}