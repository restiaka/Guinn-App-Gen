<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
Class Assets extends CI_Controller {

  protected $db;
 	
	function __construct()
	{
		 parent::__construct();
		$this->load->model('form_m','form');
		$this->load->library('ezsql_mysql');
		$this->db = $this->ezsql_mysql;
		$this->load->model('assets_m','asset');
		$this->load->model('campaign_m','campaign');
	}
	
	function add($asset_id = 0){
		$this->load->view('admin/asset_add',array('content'=> $this->form->asset_add($asset_id)));
	}
	
	function lists(){
	  require_once 'Pager/Sliding.php';
	 
		if($this->input->post('cid')){ 
			 foreach($this->input->post('cid') as $v){
			  switch($this->input->post('task')){
			   case 'delete': $this->asset->removeAssets($v); break;
			   case 'connect':  if($gid = $this->input->post('bycampaign')){
									$this->asset->setConnectionCampaign($v,$gid); 
								}
								break;
			  }
			 }
		 }
	 
	    $clauses = array();
		$orderby = 'campaign_media.media_id';
		$order = 'DESC';
		if($this->input->get_post('bycampaign', TRUE)){
			$clauses['campaign_group_assets.GID'] = $this->input->get_post('bycampaign', TRUE);
		}	
		if($bysearch = $this->input->get_post('bysearch', TRUE)){
			$clauses['campaign_assets.asset_name'] = " LIKE '%".$this->input->get_post('bysearch', TRUE)."%' ";	
		}
		
			

        //$config['path'] = APP_ADMIN_URL;
		$total = $this->asset->retrieveAssets($clauses,array('fields'=>'count(*) as total'));
		$config['totalItems'] = $total[0]['total'];
		$config['perPage'] = 15;
		//$config['urlVar'] = 'pageID';
		$config['urlVar'] = ($this->input->post('bycampaign') ? 'bycampaign='.$this->input->post('bycampaign').'&' : '').
							($this->input->post('bysearch') ? 'bysearch='.$this->input->post('bysearch').'&' : '').
							'pageID';
		
		$pager = new Pager_Sliding($config);
		$pageID = $this->input->get_post('pageID') ? $this->input->get_post('pageID') : 1;
		$links = $pager->getLinks($pageID);
		list($from, $to) = $pager->getOffsetByPageId();
		
		
		$campaigns = $this->campaign->retrieveCampaign(null,array('fields'=>'campaign_group.GID,campaign_group.title'));
		
			
		$data = $this->asset->retrieveAssets($clauses,array('limit_number' => $config['perPage'],'limit_offset' => --$from));
		
		
		$this->load->view('admin/asset',array('data'=> $data,'offset'=>$from,'pagination'=>$links,'campaigns'=>$campaigns));
		
		
	}
	
	
}