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
			   case 'activate': if($this->media->setStatusMedia($v,'active')){
			   $this->notify->set_message('success', $this->getMsg('update_true'));
			   }else{
			   $this->notify->set_message('error', $this->getMsg('update_false'));
			   } break;
			   case 'deactivate': if($this->media->setStatusMedia($v,'banned')){
			   $this->notify->set_message('success', $this->getMsg('update_true'));
			   }else{
			   $this->notify->set_message('error', $this->getMsg('update_false'));
			   } break;
			   case 'delete': if($this->media->removeMedia($v)){
			   $this->notify->set_message('success', $this->getMsg('delete_true'));
			   }else{
			   $this->notify->set_message('error', $this->getMsg('delete_false'));
			   } break;
			   case 'winner': if($this->media->setWinnerMedia($v,1)){
			   $this->notify->set_message('success', $this->getMsg('update_true'));
			   }else{
			   $this->notify->set_message('error', $this->getMsg('update_false'));
			   } break;
			   case 'resetwinner': if($this->media->setWinnerMedia($v,0)){
			   $this->notify->set_message('success', $this->getMsg('update_true'));
			   }else{
			   $this->notify->set_message('error', $this->getMsg('update_false'));
			   } break;
			  }
			  
			  if($this->input->post('notify') && in_array($this->input->post('task'),array('activate','deactivate'))){
			  $data = $this->media->detailMedia($v);
			    switch($this->input->post('task')){
				 case 'activate': $this->media->sendNotificationMail('approved',$data); break;
				 case 'deactivate': $this->media->sendNotificationMail('banned',$data); break;
			    }
			  }
			 }
		 }
	 
	    $clauses = array();
		$orderby = 'campaign_media.media_id';
		$order = 'DESC';
		if($this->input->get_post('bycampaign', TRUE)){
			$clauses['campaign_media.GID'] = $this->input->get_post('bycampaign', TRUE);
		}	
		if($this->input->get_post('byuid', TRUE)){
			$clauses['campaign_media_owner.uid'] = $this->input->get_post('byuid', TRUE);	
		}
		if($this->input->get_post('bystatus', TRUE)){
			$clauses['campaign_media.media_status'] = $this->input->get_post('bystatus', TRUE);
		}
		if($byorder = $this->input->get_post('byorder', TRUE)){		
			$orderby = "campaign_media.media_vote_total";
			switch($byorder){
				case "mostvoted" :  $order = "DESC"; break;
				case "lessvoted" :  $order = "ASC"; break;
			}
		}			
			

        //$config['path'] = APP_ADMIN_URL;
		$total = $this->media->retrieveMedia($clauses,array('fields'=>'count(*) as total'));
		$config['totalItems'] = $total[0]['total'];
		$config['perPage'] = 15; 
		//$config['urlVar'] = 'pageID';
		$config['urlVar'] = ($this->input->post('bycampaign') ? 'bycampaign='.$this->input->post('bycampaign').'&' : '').
							($this->input->post('byuid') != '' ? 'byuid='.$this->input->post('byuid').'&' : '').
							($this->input->post('bystatus') ? 'bystatus='.$this->input->post('bystatus').'&' : '').
							($this->input->post('byorder') ? 'byorder='.$this->input->post('byorder').'&' : '').
							'pageID';
		
		$pager = new Pager_Sliding($config);
		$pageID = $this->input->get_post('pageID') ? $this->input->get_post('pageID') : 1;
		$links = $pager->getLinks($pageID);
		list($from, $to) = $pager->getOffsetByPageId();
		
		
		$campaigns = $this->campaign->retrieveCampaign(null,array('fields'=>'campaign_group.GID,campaign_group.title'));
		
			
		$data = $this->media->retrieveMedia($clauses,array('orderby'=>$orderby,'order'=>$order,'limit_number' => $config['perPage'],'limit_offset' => --$from));
		
		
		$this->load->view('admin/media',array('data'=> $data,'offset'=>$from,'pagination'=>$links,'campaigns'=>$campaigns));
		
		
	}
	
	
}