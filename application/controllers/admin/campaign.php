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
	
	
	function lists(){
	  require_once 'Pager/Sliding.php';
	 
	if(@$_POST['cid']){ 
		 foreach($_POST['cid'] as $v){
		  switch($_POST['task']){
		   case 'activate': $this->campaign->setStatusCampaign($v,'active'); break;
		   case 'deactivate': $this->campaign->setStatusCampaign($v,'inactive'); break;
		   case 'delete': $this->campaign->removeCampaign($v); break;
		  }
		 }
	 }
	  
	  //echo "test";
	    $sql_filter = "";
        //$config['path'] = APP_ADMIN_URL;
		$config['totalItems'] = $this->db->get_var("SELECT COUNT(*) FROM campaign_group ".$sql_filter);
		$config['perPage'] = 20; 
		$config['urlVar'] = 'pageID';
		$pager = new Pager_Sliding($config);
		$links = $pager->getLinks(@$_GET['pageID']);
		list($from, $to) = $pager->getOffsetByPageId();
		
		$data = $this->campaign->retrieveCampaign(NULL,array('limit_number' => $config['perPage'],'limit_offset' => --$from));
		
		$this->load->view('admin/campaign',array('data'=> $data,'pagination'=>$links));
		
		
	}
	
	function exportlist($gid){
	 
	 $this->export->exportUploadedFiles($gid);
	}
	
	function exportfile($gid){
	 $this->export->exportUploadedLists($gid);
	}
	
	
}