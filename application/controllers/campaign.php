<?php
Class Campaign extends CI_Controller {

	function __construct()
	{
	 parent::__construct();
	 $this->load->library('ezsql_mysql');
	 $this->load->model('campaign_m','campaign');
	 $this->load->model('setting_m');
	 $this->load->model('form_m','form');
	 $this->load->model('media_m','media');
	 
	 $activeCampaign = $this->campaign->active_campaign;
	 
	}
	
	public function _remap($app_env, $params = array())
	{
	  dg($params);

	$appID = $params[0];
	  $method = $params[1];
	  unset($params[0],$params[1]);
	  dg($app_env);
	  dg($params);
	  
	  if(!$params[2]){
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
	    $form = $this->form->upload_media($this->campaign->active_campaign); 	
		$this->load->view('site/tab',array('html_form_upload' => $form,'notification' => $this->notify,'error' => $this->error));										
	  }
	  
	  public function register()
	  {
	  	$this->load->view('site/register',array('html_form_register' => $form,'notification' => $this->notify,'error' => $this->error));										
	
	  }
	  
	  public function detail($media_id)
	  { 
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
		$this->load->view('site/media',array('media' => $rowMedia,'notification' => $this->notify,'error' => $this->error));										
	  }
	  
	  public function gallery()
	  {
	   require_once 'Pager/Sliding.php';
	   
	   $sql_filter = "WHERE campaign_media.media_status = 'active' AND campaign_media.GID = ".$this->campaign->active_campaign['GID'];
	   $sumPerCampaign = $this->ezsql_mysql->get_var("SELECT COUNT(*) FROM campaign_media ".$sql_filter);
        //$config['path'] = APP_ADMIN_URL;
		$config['totalItems'] = $sumPerCampaign;
		$config['perPage'] = 3; 
		$config['urlVar'] = 'pageID';
		$pager = new Pager_Sliding($config);
		$links = $pager->getLinks($_GET['pageID']);
		list($from, $to) = $pager->getOffsetByPageId();
		
		$rowsMedia = $this->media->retrieveMedia(array('campaign_media.media_status'=>'active','campaign_media.GID'=>$this->campaign->active_campaign['GID']),array('limit_number' => $config['perPage'],'limit_offset' => --$from));
		$this->load->view('site/gallery',array('media' => $rowsMedia,'pagination'=>$links,'notification' => $this->notify,'error' => $this->error));	
	  } 
  
	  public function rules()
	  {
		$rules = $this->campaign->active_campaign['campaign_rules'];
		$this->load->view('site/rules',array('rules' => $rules,'notification' => $this->notify,'error' => $this->error));	
	  }
	  
	  public function mechanism()
	  {
	   $mechanism = $this->campaign->active_campaign['campaign_mechanism'];
	   $this->load->view('site/mechanism',array('mechanism' => $mechanism,'notification' => $this->notify,'error' => $this->error));	
	  }
	  
	  public function policy()
	  {
	   $policy = $this->campaign->active_campaign['campaign_policy'];
	   $this->load->view('site/policy',array('policy' => $policy,'notification' => $this->notify,'error' => $this->error));	
	  }

}