<?php
Class Campaign extends CI_Controller {

	function __construct()
	{
	 parent::__construct();
	 $this->load->library('ezsql_mysql');
	 $this->load->model('campaign_m','campaign');
	 $this->load->model('form_m','form');
	 $this->load->model('media_m','media');
	 $this->load->model('page_m');
	}
	
	public function _remap($appID, $params = array())
	{
	  $method = $params[0] ? $params[0] : 'home';	  
	  unset($params[0]);
	  
		if (method_exists($this, $method))
		{
			return call_user_func_array(array($this, $method), $params);
		}
		show_404();
	}

	
	public function home()
	{
		$this->load->model('customer_m','customer');

		$this->load->library('facebook');

		$isAuthorized = (!$this->facebook->getUser() || !isExtPermsAllowed()) ? false : true;
		$isFan = user_isFan();
	 
	    if($campaign = $this->campaign->getActiveCampaign()){
			$form = !$campaign['on_upload'] ? "Sorry! Upload Time has ended. <Br/> Thank you." : $this->form->upload_media($campaign);
		}else{
			show_404();
		}
		

		
		$this->load->view('site/tab',array('campaign_info'=>$campaign,
											'html_form_upload' => $form,
										   'html_form_register' => $this->form->customer_register(),
										   'customer_registered' => ($this->customer->isRegistered() ? true : false),
										   'is_authorized' => $isAuthorized,
										   'is_fan' => $isFan,
										   'custom_page_url' => ($campaign ? $this->page_m->getPageURL($campaign['GID']) : null),
										   'notification' => $this->notify,
										   'error' => $this->error));	
	}
	
	public function page($pageID)
	{
		$this->load->model('page_m');
		$page = $this->page_m->detailPage($pageID);
		if(date('Y-m-d H:i:s') >= $page['page_publish_date'] || $page['status'] == 'draft'){
			show_404();
		}else{
		  $page['custom_page_url'] = ($page['GID'] ? $this->page_m->getPageURL($page['GID']) : null);
			$this->load->view('site/page',$page);	
		}
		
	}
	 
	 public function winner()
	 {
		$campaign = $this->campaign->getActiveCampaign();
		$now = date('Y-m-d h:i:s');
		$data = array();
		if($campaign['on_judging']){
		  //Get Winner
		  if($media = $this->media->retrieveMedia(array('GID'=>$campaign['GID'],'media_winner' => 1))){
			$data['media'] = $media;
		  }
		}
		
		$this->load->view('site/winner',$data);
	 }
	  
	  public function register()
	  {
	     $campaign = $this->campaign->getActiveCampaign();
		 
	  	$this->load->view('site/register',array('campaign_info'=>$campaign,'html_form_register' => $form,'notification' => $this->notify,'error' => $this->error));										
	
	  }
	  
	  public function media($media_id = null)
	  { 
	    if(!$media_id){
			$media_id = addslashes($_GET['m']);
		}
	    $this->load->model('setting_m');
		
		if($rowMedia = $this->media->detailMedia($media_id)){
			$campaign = $this->campaign->detailCampaign($rowMedia['GID']);
			//if campaign out of date
			$campaign_status = $this->campaign->getStatus($campaign);
			if($campaign_status['is_off'] || $rowMedia['media_status'] == 'pending' || $rowMedia['media_status'] == 'banned'){
				$rowMedia['media_container'] = $this->media->showMedia($rowMedia,false);
				$this->load->view('site/media_preview',array('campaign_info'=>$campaign,'media' => $rowMedia,'notification' => $this->notify,'error' => $this->error));	
			}else{
			   $fblike_href = $this->setting_m->get('APP_CANVAS_PAGE').menu_url('media',true).'/?m='.$rowMedia['media_id'];
				
				$plugin_switch = array();
				$plugin_switch[] = $campaign['media_has_vote'] && $campaign_status['on_vote'] ? 'vote' : null;
				$plugin_switch[] = $campaign['media_has_fblike'] ? 'fblike' : null;
				$plugin_switch[] = $campaign['media_has_fbcomment'] ? 'fbcomment' : null;
				
				$plugin = $this->media->getPlugin($rowMedia,$plugin_switch);
				
				$rowMedia['media_container'] = $this->media->showMedia($rowMedia,false);
				$meta = $this->media->setOpenGraphMeta(array(
															 'title' => 'Photo Contest Beta',
															 'type' => 'activity',
															 'image' => $rowMedia['media_thumb_url'],
															 'url' => $fblike_href,
															 'site_name' => 'Photo Contest',
															  'custom_page_url' => ($campaign ? $this->page_m->getPageURL($campaign['GID']) : null),
															));
				registerMetaTags($meta);
				$this->load->view('site/media',array('campaign_info'=>$campaign,'plugin'=>$plugin,'media' => $rowMedia,'notification' => $this->notify,'error' => $this->error));										
			}
		}else{
		 show_404(); 
		}
	  }
	  
	  public function gallery()
	  { 
	   require_once 'Pager/Sliding.php';
	   if(!$active_campaign = $this->campaign->getActiveCampaign()){
			show_404();
	   }
	   
	   
	   $sql_filter = "WHERE campaign_media.media_status = 'active' AND campaign_media.GID = ".$active_campaign['GID'];
	   $sumPerCampaign = $this->ezsql_mysql->get_var("SELECT COUNT(*) FROM campaign_media ".$sql_filter);
        //$config['path'] = APP_ADMIN_URL;
		$config['totalItems'] = $sumPerCampaign;
		$config['perPage'] = 8; 
		$config['urlVar'] = 'pageID';
		$pager = new Pager_Sliding($config);
		$links = $pager->getLinks($_GET['pageID']);
		list($from, $to) = $pager->getOffsetByPageId();
		
		$rowsMedia = $this->media->retrieveMedia(array('campaign_media.media_status'=>'active','campaign_media.GID'=>$active_campaign['GID']),array('limit_number' => $config['perPage'],'limit_offset' => --$from));
		$this->load->view('site/gallery',array('campaign_info'=>$active_campaign,
												'media' => $rowsMedia,
												'pagination'=>$links,
												'notification' => $this->notify,
												'error' => $this->error,
												 'custom_page_url' => ($campaign ? $this->page_m->getPageURL($campaign['GID']) : null)));	
	  } 
  
	  public function rules()
	  {
	    if(!$campaign = $this->campaign->getActiveCampaign()){
			show_404();
		}
		
		$this->load->view('site/rules',array('campaign_info'=>$campaign,
		'rules' => $campaign['campaign_rules'],
		'notification' => $this->notify,'error' => $this->error,
		'custom_page_url' => ($campaign ? $this->page_m->getPageURL($campaign['GID']) : null)
		));	
	  }
	  
	  

}