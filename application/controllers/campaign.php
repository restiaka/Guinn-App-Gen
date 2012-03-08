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
	  $method = isset($params[0]) ? $params[0] : 'home';	  
	  unset($params[0]);
	  
		if (method_exists($this, $method))
		{
			return call_user_func_array(array($this, $method), $params);
		}
		show_404();
	}

	
	public function home()
	{
		$user = getAuthorizedUser(true);
		$this->load->library('facebook');

		$isAuthorized = $user ? true : false;
	 
	    if(!$campaign = $this->campaign->getActiveCampaign()){
			show_404();
		}
		
		
		$sr = $this->facebook->getSignedRequest();
		if($isAuthorized){
		 $redirect_url = menu_url('upload');
		}else{
		 $redirect_url = isset($sr['page']) ? $this->config->item('APP_FANPAGE')."&app_data=redirect|".menu_url('upload') : "http://apps.facebook.com/".$this->config->item('APP_APPLICATION_ID')."/upload";
		}
		$this->load->view('site/home',array('campaign'=>$campaign,
										   'is_authorized' => $isAuthorized,
										   'redirectURL' => $redirect_url,
										   'custom_page_url' => ($campaign ? $this->page_m->getPageURL($campaign['GID']) : null),
										   ));	
	}
	
	public function upload()
	{
	 $this->load->model('customer_m','customer');
	 
	 $sr = $this->facebook->getSignedRequest();
	 $redirect_url = isset($sr['page']) ? $this->config->item('APP_FANPAGE')."&app_data=redirect|".current_url() : "http://apps.facebook.com/".$this->config->item('APP_APPLICATION_ID')."/upload";
		
	 
	 if(!$campaign = $this->campaign->getActiveCampaign()){
			show_404();
		}
	 if(!$user = getAuthorizedUser(true)){
		redirect(menu_url('authorize').'?ref='.$redirect_url);
	 }
	 if(!$isFan = user_isFan()){
	   redirect(menu_url('likepage').'?ref='.$redirect_url);
	 }	 
	 if(!$this->customer->isRegistered()){
	   redirect(menu_url('register'));	   
	 }
	 
	 $form = $this->media->showUploadForm($campaign);
	 if($form == "success"){
		//redirect(menu_url('upload'));
		$media_type = $campaign['allowed_media_source'] == "file" ? "photo" : "video";
		if($campaign['media_has_approval']){
			$data['message_text'] = "Enjoy Your Guinness while we're moderating your $media_type Check your email for further notification, it's just a bottle away.";
	    }else{
			$data['message_text'] = "Thanks for participating, Your $media_type is now listed on the gallery.";
		}
			$data['message_title'] = "Successful";
		$this->load->view('site/upload_notification',$data);
	 }elseif($form == "error"){
		$this->notify->set_message( 'error', 'Sorry. Please Try Again.' );
		redirect(menu_url('upload'));
	 }else{
	 
		$this->load->model('customer_m','customer');

		$this->load->library('facebook');

		$isAuthorized = $user ? true : false;

		
		$this->load->view('site/upload',array('campaign'=>$campaign,
											'html_form_upload' => $form
										   ));	
	 }									   
	}
	
	public function authorize()
	{
	  if(!$campaign = $this->campaign->getActiveCampaign()){
			show_404();
		}
	  $redirectURL = urldecode($this->input->get_post('ref'));
		
		$this->load->view('site/authorize',array(
											   'campaign'=>$campaign,
											   'custom_page_url' => ($campaign ? $this->page_m->getPageURL($campaign['GID']) : null),
											   'fbpage_url' => $this->config->item('APP_FANPAGE'),
											   'redirectURL' => $redirectURL
											   ));	
	}
	
	public function likepage()
	{
	  if(!$campaign = $this->campaign->getActiveCampaign()){
			show_404();
		}
		
	$redirectURL = $this->input->get_post('ref');
		
	 if(!$user = getAuthorizedUser(true)){
		redirect(menu_url('authorize').'?ref='.$redirectURL);
	 }	
	 
	 
		
		$this->load->view('site/likepage',array('campaign'=>$campaign,
											   'custom_page_url' => ($campaign ? $this->page_m->getPageURL($campaign['GID']) : null),
												'fbpage' => getFacebookPage(),
												'redirectURL' => $redirectURL
											   ));		
	}
	
	public function register()
	{
	 $this->load->model('customer_m','customer');
	 
	 if(!$campaign = $this->campaign->getActiveCampaign()){
			show_404();
	 }
	 
	 $sr = $this->facebook->getSignedRequest();
	 $redirect_url = isset($sr['page']) ? $this->config->item('APP_FANPAGE')."&app_data=redirect|".current_url() : "http://apps.facebook.com/".$this->config->item('APP_APPLICATION_ID')."/register";
	
	 
	 if(!$user = getAuthorizedUser(true)){
		redirect(menu_url('authorize').'?ref='.$redirect_url);
	 }
	 
	 if(!$isFan = user_isFan()){
	   redirect(menu_url('likepage').'?ref='.$redirect_url);
	 }
	 
	 if($this->customer->isRegistered()){
	   redirect(menu_url('home'));	   
	 }
	 $form = $this->form->customer_register();
	
 	 if($form == "success"){
		redirect(menu_url('upload'));
	 }elseif($form == "error"){
		$this->notify->set_message( 'error', 'Sorry. Please Try Again.' );
		redirect(menu_url('register'));
	 }
	 
	 $this->load->view('site/register',array('campaign'=>$campaign,
										   'html_form_register' => $form,
										   'custom_page_url' => ($campaign ? $this->page_m->getPageURL($campaign['GID']) : null)
										   ));										
	}
	
	public function page($pageID)
	{

	 	if(!$campaign = $this->campaign->getActiveCampaign()){
			show_404();
		}

		$sr = $this->facebook->getSignedRequest();
		$redirect_url = isset($sr['page']) ? $this->config->item('APP_FANPAGE')."&app_data=redirect|".current_url() : "http://apps.facebook.com/".$this->config->item('APP_APPLICATION_ID')."/register";
		
		
		if(!$user = getAuthorizedUser(true)){
		 redirect(menu_url('authorize').'?ref='.$redirect_url);
	    }
		$this->load->model('page_m');
		$page = $this->page_m->detailPage($pageID);
		if(date('Y-m-d H:i:s') < $page['page_publish_date'] || $page['page_status'] == 'draft'){
			show_404();
		}else{
		  $page['campaign'] = $campaign;
		  $this->load->view('site/page',$page);	
		}
		
	}
	 
	 public function winner()
	 {
	  	 $sr = $this->facebook->getSignedRequest();
	 $redirect_url = isset($sr['page']) ? $this->config->item('APP_FANPAGE')."&app_data=redirect|".current_url() : "http://apps.facebook.com/".$this->config->item('APP_APPLICATION_ID')."/winner";

		 if(!$user = getAuthorizedUser(true)){
			redirect(menu_url('authorize').'?ref='.$redirect_url);
		 }	 
	   	if(!$campaign = $this->campaign->getActiveCampaign()){
			show_404();
		}
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
	  

	  
	  public function media($media_id = null)
	  { 
		if(!$media_id){
			if(!$media_id = addslashes($this->input->get('m', TRUE))){
			  show_404();
			}
		}
	   $sr = $this->facebook->getSignedRequest();
	 $redirect_url = isset($sr['page']) ? $this->config->item('APP_FANPAGE')."&app_data=redirect|".current_url() : "http://apps.facebook.com/".$this->config->item('APP_APPLICATION_ID')."/media?m=$media_id";

	   	if(!$user = getAuthorizedUser(true)){
		  redirect(menu_url('authorize').'?ref='.$redirect_url);
	    }
	    

	
	    $this->load->model('setting_m');
		
		if($rowMedia = $this->media->detailMedia($media_id)){
			$campaign = $this->campaign->detailCampaign($rowMedia['GID']);
			//if campaign out of date
			$campaign_status = $this->campaign->getStatus($campaign);
			if($campaign_status['is_off'] || $rowMedia['media_status'] == 'pending' || $rowMedia['media_status'] == 'banned'){
				$rowMedia['media_container'] = $this->media->showMedia($rowMedia,false);
				$campaign['media_preview'] = true;
				$this->load->view('site/media_preview',array('campaign'=>$campaign,'media' => $rowMedia,'notification' => $this->notify,'error' => $this->error));	
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
															 'site_name' => 'Photo Contest'
															));
				registerMetaTags($meta);
				$this->load->view('site/media',array('campaign'=>$campaign,'plugin'=>$plugin,'media' => $rowMedia));										
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
	   
	   $sr = $this->facebook->getSignedRequest();
	   $redirect_url = isset($sr['page']) ? $this->config->item('APP_FANPAGE')."&app_data=redirect|".current_url() : "http://apps.facebook.com/".$this->config->item('APP_APPLICATION_ID')."/gallery";
	   
	   if(!$user = getAuthorizedUser(true)){
		redirect(menu_url('authorize').'?ref='.$redirect_url);
	   }
	 
    	 $userMedia = $this->media->mediaByUID($user['id'],$active_campaign['GID'],'active');
		 $randMedia = $this->media->mediaByRandom($active_campaign['GID'],'active');
	   
	   $sql_filter = "WHERE campaign_media.media_status = 'active' AND campaign_media.GID = ".$active_campaign['GID'];
	   $sumPerCampaign = $this->ezsql_mysql->get_var("SELECT COUNT(*) FROM campaign_media ".$sql_filter);
       
	   $orderby = 'campaign_media.media_id';
		$order = 'DESC';
		if($byorder = $this->input->get_post('orderby', TRUE)){		
					switch($byorder){
						case "mostvoted" : 	$orderby = "campaign_media.media_vote_total"; 
											$order = "DESC"; break;
						case "latest" :  	   $orderby = 'campaign_media.media_id';
											$order = 'DESC'; break;
					}
		}			
	   

	   //$config['path'] = APP_ADMIN_URL;
		$config['totalItems'] = $sumPerCampaign;
		$config['perPage'] = 8; 
		$config['urlVar'] = ($this->input->post('orderby') ? 'orderby='.$this->input->post('orderby').'&' : '').
							'pageID';
		$pager = new Pager_Sliding($config);
		$pageID = $this->input->get_post('pageID') ? $this->input->get_post('pageID') : 1;
		$links = $pager->getLinks($pageID);
		list($from, $to) = $pager->getOffsetByPageId();
		
		$rowsMedia = $this->media->retrieveMedia(array('campaign_media.media_status'=>'active','campaign_media.GID'=>$active_campaign['GID']),array('orderby'=>$orderby,'order'=>$order,'limit_number' => $config['perPage'],'limit_offset' => --$from));
		$this->load->view('site/gallery',array('campaign'=>$active_campaign,
												'media' => $rowsMedia,
												'user_media' => $userMedia ? $userMedia : null,
												'random_media' => $randMedia ? $randMedia : null,
												'pagination'=>$links));	
	  } 
  
	  public function rules()
	  {
	    if(!$campaign = $this->campaign->getActiveCampaign()){
			show_404();
		}
		$sr = $this->facebook->getSignedRequest();
		$redirect_url = isset($sr['page']) ? $this->config->item('APP_FANPAGE')."&app_data=redirect|".current_url() : "http://apps.facebook.com/".$this->config->item('APP_APPLICATION_ID')."/rules";

	    if(!$user = getAuthorizedUser(true)){
			redirect(menu_url('authorize').'?ref='.$redirect_url);
	    }
		
		$this->load->view('site/rules',array('campaign'=>$campaign,
										'rules' => $campaign['campaign_rules']
		));	
	  }
	  
	  

}