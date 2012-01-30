<?php
 Class Home_c extends Controller
 {
   protected $campaign;
   protected $form;
   protected $media;
   public $error = array();
   public $notify = array();
   
	  public function __construct()
	  {
	    $this->load->model('campaign_m','campaign');
		$this->load->model('form_m','form');
		$this->load->model('media_m','media');
		

		
		if($activeCampaign = $this->campaign->active_campaign){
			switch(@strtolower(load::l('router')->segments[2])){
			  case 'media' : redirectToFanPage(); //redirect if not on Fan Page
							 $this->detail($_GET['m']); break;
			  case 'gallery' : redirectToFanPage(); //redirect if not on Fan Page
								$this->gallery();  break;
			  case 'rules' : $this->rules(); break;
			  case 'mechanism' : $this->mechanism(); break;
			  case 'policy' : $this->policy(); break;
			  case 'register' : $this->register(); break;
			  default : redirectToFanPage(); //redirect if not on Fan Page
						
						load::m('customer_m')->registerRequire();
						
						$this->home();
						break;
			}
		}else{
			$this->notify[] = "Sorry, there is no active campaign for now!";
		}
		
		$this->setOutput(array('notification' => $this->notify,'error' => $this->error));

	  }
	   
	  public function home()
	  {
	    $form = $this->form->upload_media($this->campaign->active_campaign); 	
		$this->setOutput(array('html_form_upload' => $form,'notification' => $this->notify,'error' => $this->error));										
	  }
	  
	  public function register()
	  {
	  	$this->setOutput(array('html_form_register' => $form,'notification' => $this->notify,'error' => $this->error));										
	
	  }
	  
	  public function detail($media_id)
	  { 
		$rowMedia = $this->media->detailMedia($media_id); 
		$fblike_href = load::m('setting_m')->get('APP_CANVAS_PAGE').menu_url('media',true).'/?m='.$rowMedia['media_id'];
		$meta = $this->media->setOpenGraphMeta(array(
													 'title' => 'Photo Contest Beta',
													 'type' => 'activity',
													 'image' => $rowMedia['media_thumb_url'],
													 'url' => $fblike_href,
													 'site_name' => 'Photo Contest'
													));
		registerMetaTags($meta);
		$this->setOutput(array('media' => $rowMedia,'notification' => $this->notify,'error' => $this->error));										
	  }
	  
	  public function gallery()
	  {
	   require_once 'Pager/Sliding.php';
	   
	   $sql_filter = "WHERE campaign_media.media_status = 'active' AND campaign_media.GID = ".$this->campaign->active_campaign['GID'];
	   $sumPerCampaign = load::l('ezsql/ezsql_mysql')->get_var("SELECT COUNT(*) FROM campaign_media ".$sql_filter);
        //$config['path'] = APP_ADMIN_URL;
		$config['totalItems'] = $sumPerCampaign;
		$config['perPage'] = 3; 
		$config['urlVar'] = 'pageID';
		$pager = new Pager_Sliding($config);
		$links = $pager->getLinks($_GET['pageID']);
		list($from, $to) = $pager->getOffsetByPageId();
		
		$rowsMedia = $this->media->retrieveMedia(array('campaign_media.media_status'=>'active','campaign_media.GID'=>$this->campaign->active_campaign['GID']),array('limit_number' => $config['perPage'],'limit_offset' => --$from));
		$this->setOutput(array('media' => $rowsMedia,'pagination'=>$links,'notification' => $this->notify,'error' => $this->error));	
	  } 
  
	  public function rules()
	  {
		$rules = $this->campaign->active_campaign['campaign_rules'];
		$this->setOutput(array('rules' => $rules,'notification' => $this->notify,'error' => $this->error));	
	  }
	  
	  public function mechanism()
	  {
	   $mechanism = $this->campaign->active_campaign['campaign_mechanism'];
	   $this->setOutput(array('mechanism' => $mechanism,'notification' => $this->notify,'error' => $this->error));	
	  }
	  
	  public function policy()
	  {
	   $policy = $this->campaign->active_campaign['campaign_policy'];
	   $this->setOutput(array('policy' => $policy,'notification' => $this->notify,'error' => $this->error));	
	  }
	  
 } 
