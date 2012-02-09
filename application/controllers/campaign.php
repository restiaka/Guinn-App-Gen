<?php
Class Campaign extends CI_Controller {

	function __construct()
	{
	 parent::__construct();
	 $this->load->library('ezsql_mysql');
	 $this->load->model('campaign_m','campaign');
	 $this->load->model('form_m','form');
	 $this->load->model('media_m','media');
	}
	
	public function _remap($app_env, $params = array())
	{
	 $appID = $params[0];
	  $method = $params[1];
	  unset($params[0],$params[1]);
	  
	  if(!$method){
	     $method = 'home';
	  }
	  
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
		
		/*debug
		$this->load->model('setting_m');
		dg($this->setting_m->get('APP_FANPAGE'));
		dg($this->config);*/
		
		$isAuthorized = (!$this->facebook->getUser() || !isExtPermsAllowed()) ? false : true;
	 
	    $campaign = $this->campaign->getActiveCampaign();
	    $form = $campaign ? $this->form->upload_media($campaign) : 'Sorry No Contest Available Yet!'; 	
		$this->load->view('site/tab',array('campaign_info'=>$campaign,
											'html_form_upload' => $form,
										   'html_form_register' => $this->form->customer_register(),
										   'customer_registered' => ($this->customer->isRegistered() ? true : false),
										   'is_authorized' => $isAuthorized,
										   'notification' => $this->notify,
										   'error' => $this->error));										
	 }
	 
	 public function winner()
	 {
		$campaign = $this->campaign->getActiveCampaign();
		$now = date('Y-m-d h:i:s');
		$data = array();
		if($campaign['upload_enddate'] >= $now){
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
		$campaign = $this->campaign->getActiveCampaign();
		$rowMedia = $this->media->detailMedia($media_id); 
		$fblike_href = $this->setting_m->get('APP_CANVAS_PAGE').menu_url('media',true).'/?m='.$rowMedia['media_id'];
		$meta = $this->media->setOpenGraphMeta(array(
													 'title' => 'Photo Contest Beta',
													 'type' => 'activity',
													 'image' => $rowMedia['media_thumb_url'],
													 'url' => $fblike_href,
													 'site_name' => 'Photo Contest'
													));
		registerMetaTags($meta);
		$this->load->view('site/media',array('campaign_info'=>$campaign,'media' => $rowMedia,'notification' => $this->notify,'error' => $this->error));										
	  }
	  
	  public function gallery()
	  { 
	   require_once 'Pager/Sliding.php';
	   $active_campaign = $this->campaign->getActiveCampaign();
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
		$this->load->view('site/gallery',array('campaign_info'=>$active_campaign,'media' => $rowsMedia,'pagination'=>$links,'notification' => $this->notify,'error' => $this->error));	
	  } 
  
	  public function rules()
	  {
	    $campaign = $this->campaign->getActiveCampaign();
		$this->load->view('site/rules',array('campaign_info'=>$campaign,'rules' => $campaign['campaign_rules'],'notification' => $this->notify,'error' => $this->error));	
	  }
	  
	  public function mechanism()
	  {
	   $campaign = $this->campaign->getActiveCampaign();
	   $this->load->view('site/mechanism',array('campaign_info'=>$campaign,'mechanism' => $campaign['campaign_mechanism'],'notification' => $this->notify,'error' => $this->error));	
	  }
	  
	  public function policy()
	  {
	   $campaign = $this->campaign->getActiveCampaign();
	   $this->load->view('site/policy',array('campaign_info'=>$campaign,'policy' => $campaign['campaign_policy'],'notification' => $this->notify,'error' => $this->error));	
	  }

}