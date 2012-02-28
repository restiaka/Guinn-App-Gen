<?php
Class Mobile extends CI_Controller {

	function __construct()
	{
	 parent::__construct();
	 $this->load->library('ezsql_mysql');
	 $this->load->model('campaign_m','campaign');
	 $this->load->model('form_m','form');
	 $this->load->model('media_m','media');
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
										   'is_authorized' => $isAuthorized,
										   'notification' => $this->notify,
										   'error' => $this->error));
	}

	function register(){
	    $campaign = $this->campaign->getActiveCampaign();
						 
	  	$this->load->view('mobile/mobile_register',array('campaign_info'=>$campaign,
											'html_form_register' => $form,
											'notification' => $this->notify,
											'error' => $this->error));										
	
	}

	function about(){
		$campaign = $this->campaign->getActiveCampaign();
		//dg($campaign);
		$this->load->view('mobile/mobile_about',$campaign);
	}

	function gallery(){
		requireLogin('https://guinnessapp.dev/mobile/282088055180043','popup');
		//graphRequireLogin('https://guinnessapp.dev/mobile/282088055180043','wap');
		$this->load->view('mobile/mobile_gallery');
	}

	function rules(){
		$this->load->view('mobile/mobile_rules');
	}

}