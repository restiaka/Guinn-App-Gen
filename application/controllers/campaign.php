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
	  /** BEGIN REQUIRED VALIDATION **/
		$this->load->library('facebook');
	    $user = getAuthorizedUser(true);

		$isAuthorized = $user ? true : false;

	    if(!$campaign = $this->campaign->getActiveCampaign()){
			show_404();
		}
		/** END REQUIRED VALIDATION **/
		
		if($isAuthorized){
		 $redirect_url = menu_url('upload');
		}else{
		 $sr = $this->facebook->getSignedRequest();
		 $redirect_url = isset($sr['page']) ? $this->config->item('APP_FANPAGE')."&app_data=redirect|".menu_url('upload') : "http://apps.facebook.com/".$this->config->item('APP_APPLICATION_ID')."/upload";
		}
		
		$this->load->view('site/home',array('campaign'=>$campaign,
										   'is_authorized' => $isAuthorized,
										   'redirectURL' => $redirect_url
										   ));	
	}
	
	public function addtopage()
	{
	  $this->notify->set_message('success', 'You\'re Fan Page successfuly setup. Please check your Fan Page Admin Panel');
	  redirect(site_url('admin/app/lists'));
	}
	
	public function upload()
	{
	 $this->load->library('facebook');
	 $this->load->model('customer_m','customer');

	 /** BEGIN REQUIRED VALIDATION **/
	 if(!$campaign = $this->campaign->getActiveCampaign()){
			show_404();
		}
	 
     if($campaign['on_judging']){
	    $data['campaign'] = $campaign;
		$data['message_title'] = "The Winner Announce Soon";
		$data['message_text'] = "Sorry! We are on Judging Time for The Campaign.";
		$this->load->view('site/campaign_notification',$data);
	    return;
	 }
	 
     if(!$campaign['on_upload']){
	    $data['campaign'] = $campaign;
		$data['message_title'] = "Campaign Upload End";
		$data['message_text'] = "Sorry! Upload submission for the campaign has just ended";
		$this->load->view('site/campaign_notification',$data);
		return;
	 }
	 

	 

	
     $sr = $this->facebook->getSignedRequest();
	 $redirect_url = isset($sr['page']) ? $this->config->item('APP_FANPAGE')."&app_data=redirect|".current_url() : "http://apps.facebook.com/".$this->config->item('APP_APPLICATION_ID')."/upload";


	 if(!$user = getAuthorizedUser(true)){
		redirect(menu_url('authorize').'?ref='.$redirect_url);
	 }
	 

	 
	 if(!$isFan = user_isFan()){
	   redirect(menu_url('likepage').'?ref='.$redirect_url);
	 }	 
	 if(!$this->customer->isRegistered($campaign)){
	   redirect(menu_url('register'));	   
	 }
	 /** END REQUIRED VALIDATION **/
	 
	 $form = $this->media->showUploadForm($campaign);
	 if($form == "success"){
		//redirect(menu_url('upload'));
		$media_type = $campaign['allowed_media_source'] == "file" ? "photo" : "video";
		if($campaign['media_has_approval']){
			$data['message_text'] = "Enjoy Your Guinness while we're moderating your $media_type Check your email for further notification.";
	    }else{
			$data['message_text'] = "Thanks for participating, Your $media_type is now listed on the gallery.";
		}
			$data['message_title'] = "Successful";
			$data['campaign'] = $campaign;
			
			$feed = array(
			   "name" => $campaign['title'],
			   "caption" => "{*actor*} has just Upload a Photo for The Contest.",
			   "link" => $this->config->item('APP_CANVAS_PAGE')
			   );
			if(isset($campaign['asset_facebook']['logo_main'])){
				$feed['picture'] = $campaign['asset_facebook']['logo_main']['url'];
			}			
			
			$data['facebook_share_dialog'] = '<script>'.
											 'fbDialogFeed('.json_encode($feed).')'.
											 '</script>';
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
	  $this->load->library('facebook');
	  /** BEGIN REQUIRED VALIDATION **/
	  if(!$campaign = $this->campaign->getActiveCampaign()){
			show_404();
		}
	  /** END REQUIRED VALIDATION **/
	  
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
	 $this->load->library('facebook');
	  /** BEGIN REQUIRED VALIDATION **/
	  if(!$campaign = $this->campaign->getActiveCampaign()){
			show_404();
		}
		
		$redirectURL = $this->input->get_post('ref');
		
	 if(!$user = getAuthorizedUser(true)){
		redirect(menu_url('authorize').'?ref='.$redirectURL);
	 }	
	 /** END REQUIRED VALIDATION **/
	 
	 
		
		$this->load->view('site/likepage',array('campaign'=>$campaign,
											   'custom_page_url' => ($campaign ? $this->page_m->getPageURL($campaign['GID']) : null),
												'fbpage' => getFacebookPage(),
												'redirectURL' => $redirectURL
											   ));		
	}
	
	public function register()
	{
	 $this->load->library('facebook');
	 $this->load->model('customer_m','customer');
	 
	 /** BEGIN REQUIRED VALIDATION **/
	 if(!$campaign = $this->campaign->getActiveCampaign()){
			show_404();
	 }
	 
	 if($campaign['on_judging']){
	    $data['campaign'] = $campaign;
		$data['message_title'] = "The Winner Announce Soon";
		$data['message_description'] = "Sorry! We are on Judging Time for The Campaign.";
		$this->load->view('site/campaign_notification',$data);
		return;
	 }
	 
	 $sr = $this->facebook->getSignedRequest();
	 $redirect_url = isset($sr['page']) ? $this->config->item('APP_FANPAGE')."&app_data=redirect|".current_url() : "http://apps.facebook.com/".$this->config->item('APP_APPLICATION_ID')."/register";
	
	 
	 if(!$user = getAuthorizedUser(true)){
		redirect(menu_url('authorize').'?ref='.$redirect_url);
	 }
	 
	 if(!$isFan = user_isFan()){
	   redirect(menu_url('likepage').'?ref='.$redirect_url);
	 }
	 
	 if($this->customer->isRegistered($campaign)){
	   redirect(menu_url('home'));	   
	 }
	 /** END REQUIRED VALIDATION **/
	 
	 
	 $form = $this->form->customer_register();
	
 	 if($form == "success"){
		redirect(menu_url('upload'));
	 }elseif($form == "error"){
		$this->notify->set_message( 'error', 'Sorry. Please Try Again.' );
		redirect(menu_url('register'));
	 }
	 
	 $this->load->view('site/register',array('campaign'=>$campaign,
										   'html_form_register' => $form
										   ));										
	}
	
	public function page($pageID)
	{
	 $this->load->library('facebook');
		/** BEGIN REQUIRED VALIDATION **/
	 	if(!$campaign = $this->campaign->getActiveCampaign()){
			show_404();
		}

		$sr = $this->facebook->getSignedRequest();
		$redirect_url = isset($sr['page']) ? $this->config->item('APP_FANPAGE')."&app_data=redirect|".current_url() : "http://apps.facebook.com/".$this->config->item('APP_APPLICATION_ID')."/register";		
		
		if(!$user = getAuthorizedUser(true)){
		 redirect(menu_url('authorize').'?ref='.$redirect_url);
	    }
		/** END REQUIRED VALIDATION **/
		
		
		$this->load->model('page_m');
		if($page = $this->page_m->detailPage($pageID)){
		    if(!$page['page_facebook']) show_404();
			if(date('Y-m-d H:i:s') < $page['page_publish_date'] || $page['page_status'] == 'draft'){
				show_404();
			}else{
			  $page['campaign'] = $campaign;
			  $this->load->view('site/page',$page);	
			}
		}else{
			show_404();
		}
		
	}
	 
	 public function winner()
	 {
	  $this->load->library('facebook');
	  /** BEGIN REQUIRED VALIDATION **/
	  	 $sr = $this->facebook->getSignedRequest();
		$redirect_url = isset($sr['page']) ? $this->config->item('APP_FANPAGE')."&app_data=redirect|".current_url() : "http://apps.facebook.com/".$this->config->item('APP_APPLICATION_ID')."/winner";
	   	if(!$campaign = $this->campaign->getActiveCampaign()){
			show_404();
		}
		
		if(!$user = getAuthorizedUser(true)){
			redirect(menu_url('authorize').'?ref='.$redirect_url);
		}	 
		/** END REQUIRED VALIDATION **/

		$data = array();
		if($campaign['on_judging'] && $campaign['winner_announced']){
		  //Get Winner
		  if($media = $this->media->retrieveMedia(array('campaign_media.GID'=>$campaign['GID'],'campaign_media.media_winner' => 1))){
			$data['media'] = $media;
		  }
		}else{
			show_404();
		}
		$data['campaign'] = $campaign;
		
		$this->load->view('site/winner',$data);
	 }
	  

	  
	  public function media($media_id = null)
	  { 
	   $this->load->library('facebook');
	  
	  /** BEGIN REQUIRED VALIDATION **/
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
		/** END REQUIRED VALIDATION **/
		
	    $this->load->model('setting_m');
		
		if($rowMedia = $this->media->detailMedia($media_id)){
			$campaign = $this->campaign->detailCampaign($rowMedia['GID']);
			//if campaign out of date
			$campaign_status = $this->campaign->getStatus($campaign);
			if($campaign_status['is_off'] || $rowMedia['media_status'] == 'pending' || $rowMedia['media_status'] == 'banned'){
				$rowMedia['media_container'] = $this->media->showMedia($rowMedia,false);
				$campaign['media_preview'] = true;
				$this->load->view('site/media_preview',array('campaign'=>$campaign,'media' => $rowMedia));	
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
	  $this->load->library('facebook');
	  
	   require_once 'Pager/Sliding.php';
	   /** BEGIN REQUIRED VALIDATION **/
	   if(!$active_campaign = $this->campaign->getActiveCampaign()){
			show_404();
	   }
	   
	   $sr = $this->facebook->getSignedRequest();
	   $redirect_url = isset($sr['page']) ? $this->config->item('APP_FANPAGE')."&app_data=redirect|".current_url() : "http://apps.facebook.com/".$this->config->item('APP_APPLICATION_ID')."/gallery";

	   
	   if(!$user = getAuthorizedUser(true)){
		redirect(menu_url('authorize').'?ref='.$redirect_url);
	   }
	   /** END REQUIRED VALIDATION **/
	 
    	 $userMedia = $this->media->mediaByUID($user['id'],$active_campaign['GID'],'active');
		 $randMedia = $this->media->mediaByRandom($active_campaign['GID'],'active');
		 

	   
	   $sql_filter = "WHERE campaign_media.media_status = 'active' AND campaign_media.GID = ".$active_campaign['GID'];
	   
	   	if($bysearch = $this->input->get_post('searchby',true)){
		  $media_ids = $this->media->getMediaIdsByCustomer($active_campaign,$bysearch);
		  $clauses['campaign_media.media_id'] = $media_ids;
		  $sql_filter .= ' AND campaign_media.media_id IN ('.implode( ",", $media_ids).') ';
		} 
	   
	   
	   $sumPerCampaign = $this->ezsql_mysql->get_var("SELECT COUNT(*) FROM campaign_media ".$sql_filter);
       
	   $orderby = 'campaign_media.media_id';
		$order = 'DESC';
		
		if($byorder = $this->input->get_post('orderby', TRUE)){		
					switch($byorder){
						case "mostvote" : 	$orderby = "campaign_media.media_vote_total"; 
											$order = "DESC"; break;
						case "latest" :  	   $orderby = 'campaign_media.media_id';
											$order = 'DESC'; break;
					}
		}	


	   

	   //$config['path'] = APP_ADMIN_URL;
		$config['totalItems'] = $sumPerCampaign;
		$config['perPage'] = 8; 
		$config['urlVar'] = ($this->input->post('orderby') ? 'orderby='.$this->input->post('orderby').'&' : '').
							($this->input->post('searchby') ? 'searchby='.$this->input->post('searchby').'&' : '').
							'pageID';
		$pager = new Pager_Sliding($config);
		$pageID = $this->input->get_post('pageID') ? $this->input->get_post('pageID') : 1;
		$links = $pager->getLinks($pageID);
		list($from, $to) = $pager->getOffsetByPageId();
		
		$clauses['campaign_media.media_status'] = 'active';
		$clauses['campaign_media.GID'] = $active_campaign['GID'];
		$args = array('orderby'=>$orderby,'order'=>$order,'limit_number' => $config['perPage'],'limit_offset' => --$from);

		$rowsMedia = $this->media->retrieveMedia($clauses,$args);
		$this->load->view('site/gallery',array('campaign'=>$active_campaign,
												'media' => $rowsMedia,
												'user_media' => $userMedia ? $userMedia : null,
												'random_media' => $randMedia ? $randMedia : null,
												'pagination'=>$links));	
	  }
  
	  public function rules()
	  {
	    $this->load->library('facebook');
	   
	   /** BEGIN REQUIRED VALIDATION **/
	    if(!$campaign = $this->campaign->getActiveCampaign()){
			show_404();
		}
		$sr = $this->facebook->getSignedRequest();
		$redirect_url = isset($sr['page']) ? $this->config->item('APP_FANPAGE')."&app_data=redirect|".current_url() : "http://apps.facebook.com/".$this->config->item('APP_APPLICATION_ID')."/rules";

	    if(!$user = getAuthorizedUser(true)){
			redirect(menu_url('authorize').'?ref='.$redirect_url);
	    }
		/** END REQUIRED VALIDATION **/
		
		$this->load->view('site/rules',array('campaign'=>$campaign,
										'rules' => $campaign['campaign_rules']
		));	
	  }
	  
	  

}