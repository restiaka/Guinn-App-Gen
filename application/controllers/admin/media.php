<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
Class Media extends CI_Controller {

  protected $db;
 	
	function __construct()
	{
		 parent::__construct();
		$this->load->model('form_m','form');
		$this->load->library('ezsql_mysql');
		$this->db = $this->ezsql_mysql;
		$this->load->model('media_m','media');
		$this->load->model('campaign_m','campaign');
	}
	
	function add($gid = 0){
		$this->load('admin/media_edit',array('content'=> $this->form->media_add($gid)));
	}
	
	
	function lists(){
	  require_once 'Pager/Sliding.php';
	 
		if($_POST['cid']){ 
			 foreach($_POST['cid'] as $v){
			  switch($_POST['task']){
			   case 'activate': $this->media->setStatusMedia($v,'active'); break;
			   case 'deactivate': $this->media->setStatusMedia($v,'inactive'); break;
			   case 'delete': $this->media->removeMedia($v); break;
			  }
			 }
		 }
	 
	    $clauses = array();
		if($_REQUEST['bycampaign']){
			$clauses['campaign_media.GID'] = $_REQUEST['bycampaign'];
		}	
		if($_REQUEST['byuid']){
			$clauses['campaign_media_owner.uid'] = $_REQUEST['byuid'];	
		}			
		if($_REQUEST['bystatus']){
			$clauses['campaign_media.media_status'] = $_REQUEST['bystatus'];
		}	
			

        //$config['path'] = APP_ADMIN_URL;
		$total = $this->media->retrieveMedia($clauses,array('fields'=>'count(*) as total'));
		$config['totalItems'] = $total[0]['total'];
		$config['perPage'] = 15; 
		$config['urlVar'] = 'pageID';
		$config['urlVar'] = ($_POST['bycampaign'] ? 'bycampaign='.$_POST['bycampaign'].'&' : '').
							($_POST['byuid'] != '' ? 'byuid='.$_POST['byuid'].'&' : '').
							($_POST['bystatus'] ? 'bystatus='.$_POST['bystatus'].'&' : '').
							'pageID';
		
		$pager = new Pager_Sliding($config);
		$links = $pager->getLinks($_GET['pageID']);
		list($from, $to) = $pager->getOffsetByPageId();
		
		
		$campaigns = $this->campaign->retrieveCampaign(null,array('fields'=>'campaign_group.GID,campaign_group.title'));
		
			
		$data = $this->media->retrieveMedia($clauses,array('limit_number' => $config['perPage'],'limit_offset' => --$from));
		
		
		$this->load->view('admin/media',array('data'=> $data,'pagination'=>$links,'campaigns'=>$campaigns));
		
		
	}
	
	
}