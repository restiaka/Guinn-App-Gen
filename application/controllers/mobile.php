<?php
Class Mobile extends CI_Controller {

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

	function home(){
		$this->load->model('customer_m','customer');
		$this->load->library('facebook');

		
		$data['isAuthorized'] = (!$this->facebook->getUser() || !isExtPermsAllowed()) ? false : true;

		$this->load->view('mobile/mobile_home',$data);
	}
	
	function login(){
		$this->load->model('customer_m','customer');
		$this->load->library('facebook');
		
		$isAuthorized = (!$this->facebook->getUser() || !isExtPermsAllowed()) ? false : true;
	 
	    if($campaign = $this->campaign->getActiveCampaign()){
			$form = (date('Y-m-d H:i:s') > $campaign['upload_enddate']) ? "Sorry! Your time for Uploading Media has ended. <Br/> Thank you." : $this->form->upload_media($campaign);
		}else{
			$form = 'Sorry No Contest Available Yet!'; 
		}

		$this->load->view('mobile/mobile_login',array('campaign_info'=>$campaign,
										   'html_form_upload' => $form,
										   'html_form_register' => $this->form->customer_register(),
										   'customer_registered' => ($this->customer->isRegistered() ? true : false),
										   'is_authorized' => $isAuthorized));
	}
	
	function upload(){
		$this->load->model('customer_m','customer');
		$this->load->library('facebook');
		
		$isAuthorized = (!$this->facebook->getUser() || !isExtPermsAllowed()) ? false : true;
	 
	    if($campaign = $this->campaign->getActiveCampaign()){
			$form = (date('Y-m-d H:i:s') > $campaign['upload_enddate']) ? "Sorry! Your time for Uploading Media has ended. <Br/> Thank you." : $this->form->upload_media($campaign);
		}else{
			$form = 'Sorry No Contest Available Yet!'; 
		}

		$this->load->view('mobile/mobile_upload',array('campaign_info'=>$campaign,
										   'html_form_upload' => $form,
										   'html_form_register' => $this->form->customer_register(),
										   'customer_registered' => ($this->customer->isRegistered() ? true : false),
										   'is_authorized' => $isAuthorized));
	}

	function about(){
		$campaign = $this->campaign->getActiveCampaign();
		//dg($campaign);
		$this->load->view('mobile/mobile_about',$campaign);
	}

	function gallery(){
		//requireLogin('https://guinnessapp.dev/mobile/282088055180043','popup');
		//graphRequireLogin('https://guinnessapp.dev/mobile/282088055180043','wap');
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
		$links = $pager->getLinks($this->input->get('pageID', TRUE));
		list($from, $to) = $pager->getOffsetByPageId();
		
		$rowsMedia = $this->media->retrieveMedia(array('campaign_media.media_status'=>'active','campaign_media.GID'=>$active_campaign['GID']),array('limit_number' => $config['perPage'],'limit_offset' => --$from));
		$this->load->view('mobile//mobile_gallery',array('campaign_info'=>$active_campaign,
												'media' => $rowsMedia,
												'pagination'=>$links,
												'custom_page_url' => ($active_campaign ? $this->page_m->getPageURL($active_campaign['GID']) : null)));
	}

	function rules(){
		$this->load->view('mobile/mobile_rules');
	}

}
