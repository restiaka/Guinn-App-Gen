<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 Class Form_m extends CI_Model{
   protected $db;
 
   function __construct()
   {
    	parent::__construct();
		require_once 'HTML/QuickForm2.php';
		require_once 'HTML/QuickForm2/Renderer.php';
		
		$this->load->library('ezsql_mysql');
		$this->db = $this->ezsql_mysql;
		
		
		$this->load->library('auth');
		
	
		$this->load->model('media_m');
		$this->load->model('customer_m');
		$this->load->model('app_m');
		$this->load->model('campaign_m');
   }
   
   function upload_media($campaign)
   { 
   $this->load->model('setting_m');
     $this->load->library('facebook');
     $uid = $this->facebook->getUser();
	 if(date('Y-m-d H:i:s') > $campaign['upload_enddate']){
	  return "Sorry! Your time for Uploading Media has ended. <Br/> Thank you.";
	 }
	 
     $form = new HTMLQuickForm2('uploadmedia','POST');
	 $form->setAttribute('action', '');
	 /**Setup default value*
	 $form->addDataSource(new HTML_QuickForm2_DataSource_Array(array(
	 )));
	 /**/
	 $active_campaign_gid = $campaign['GID'];
	 
	 $allowed_media_fields = $campaign['allowed_media_fields'];
	 parse_str($allowed_media_fields,$fields_to_add);
	 
	 $allowed_media_source = $campaign['allowed_media_source'];
	 $allowed_media_type = $campaign['allowed_media_type'];
	 
	 $allowed_maxfilesize = 5*1024*1024;
	 $allowed_mimetype = @$campaign['allowed_mimetype'];
	 
	 if($allowed_media_source == "file"){
		$form->setAttribute('enctype', 'multipart/form-data');
		//$form->addElement('hidden','MAX_FILE_SIZE')->setValue($allowed_maxfilesize);
	 }
 
		foreach($fields_to_add as $domid => $label){
		 $label = ucfirst($label).' &nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;';
		  switch ($domid){ 
		   case 'media_title': 
		   $form->addElement('static','','',array('content'=>$label));	
		   $r = $form->addElement('text',$domid,'style="width:395px;"');
									$r->addRule('required', $label.' is required', null,HTML_QuickForm2_Rule::SERVER);
									break;
		   case 'media_description' : 
		   $form->addElement('static','','',array('content'=>$label));	
		   $r = $form->addElement('textarea',$domid,'style="width:395px;"');
									$r->addRule('required', $label.' is required', null,HTML_QuickForm2_Rule::SERVER);
									break;
		   case 'media_source' : 
		   //$form->addElement('static','','',array('content'=>$label));	
		   $src = explode(',',$allowed_media_source); 
								 if(in_array('facebook',$src) || in_array('youtube',$src) || in_array('twitpic',$src) || in_array('yfrog',$src) || in_array('plixi',$src))
								 {
									$form->addElement('static','','',array('content'=>'Copy and Paste YouTube URL here'));	
								    $r = $form->addElement('text',$domid,'style="width:395px;"');
									$r->addRule('required', $label.' is required', null,HTML_QuickForm2_Rule::SERVER);
									break;
								 } 
								 elseif(in_array('file',$src))
								 {
									$form->addElement('static','','',array('content'=>'Upload Photo from your computer here'));	
									$r_file = $form->addElement('file',$domid,'size="49"');
									$r_file->addRule('required', $label.' is required', null,HTML_QuickForm2_Rule::SERVER);
									$r_file->addRule('mimetype', $label.' is not valid file type', explode(',',$allowed_mimetype),HTML_QuickForm2_Rule::SERVER);
									$r_file->addRule('maxfilesize', $label.' filesize is exceeded ', $allowed_maxfilesize,HTML_QuickForm2_Rule::SERVER);
									
									break;
								 }				
		  }
		}
		$button = $form->addElement('submit','submit','value="Submit" style="border:solid 1px #D9BB75; background-color:#000;color:#D9BB75;padding:5px;margin-left:350px;"');
		
		if ($form->validate()) {
			$form->toggleFrozen(true);
			$data = $form->getValue();
			unset($data['submit'],$data['_qf__uploadmedia']);
		    /* Array ( [media_title] => sdfsdfs [media_description] => sdfsdfsdf [media_source] => Array ( [name] => Billboard6.png [type] => image/png [tmp_name] => /tmp/php1kDGMJ [error] => 0 [size] => 274034 ) ) */
			switch(strtolower($allowed_media_type)){
			    case 'image' : 
				 if(strtolower($allowed_media_source) == "file"){
					if ($data['media_source']['error'] == UPLOAD_ERR_OK) {
						$tmp_name = $data['media_source']["tmp_name"];
						$time = md5(uniqid(rand(), true).time());
						if(!is_dir(CUSTOMER_IMAGE_DIR.$gid)){
							mkdir(CUSTOMER_IMAGE_DIR.$gid, 0700);
						}
							
						$image = resizeImage( $tmp_name, CUSTOMER_IMAGE_DIR.$active_campaign_gid."/".$uid."_".$time.".jpg", 500 , 'width' );
						$thumb = resizeImage( $tmp_name, CUSTOMER_IMAGE_DIR.$active_campaign_gid."/thumb_".$uid."_".$time.".jpg", 90 , 'width' );
						$data['media_source'] = 'file';
						$data['media_url'] = base_url()."image?gid=".$active_campaign_gid."&src=".$uid."_".$time.".jpg";
						$data['media_thumb_url'] = base_url."image?gid=".$active_campaign_gid."&src=thumb_".$uid."_".$time.".jpg";
						$data['media_type'] = $allowed_media_type;
						$data['media_basename'] = $uid."_".$time.".jpg";
					}else{
					  $data = array();
					}
				 }else{
				   $meta = get_image_from_url($data['media_source']);
				   $data['media_source'] = $meta['from'];
				   $data['media_url'] = $meta['image'];
				   $data['media_thumb_url'] = $meta['thumb'];
				   $data['media_type'] = $allowed_media_type;
				 }				 
					break;
				case 'video' :  
				  if(strtolower($allowed_media_source) == "file"){
					if ($data['media_source']['error'] == UPLOAD_ERR_OK) {
						$tmp_name = $data['media_source']["tmp_name"];
						$basename = explode('.',$data['media_source']["name"]);
						$time = md5(uniqid(rand(), true).time());;
						if(!move_uploaded_file($tmp_name, CUSTOMER_VIDEO_DIR.$active_campaign_gid."/".$uid."_".$time.'.'.$basename[1])){
						 return false;	 
						}else{
							$data['media_source'] = 'file';
							$data['media_url'] = $uid."_".$time.'.'.$basename[1];
							$data['media_thumb_url'] = '';
							$data['media_type'] = $allowed_media_type;
							$data['media_basename'] = $uid."_".$time.'.'.$basename[1];
						}		
					}else{
					  $data = array();
					}
				   }else{
				     $meta = get_video_from_url($data['media_source']);
					  $data['media_source'] = $meta['from'];
					  $data['media_url'] = $meta['video'];
					  $data['media_thumb_url'] = $meta['thumb'];
					  $data['media_type'] = $allowed_media_type;
				   }
					break;
			  }			  
				
			$time = time();
			$data['media_status'] = $campaign['media_has_approval'] ? 'inactive' : 'active';
			$data['media_uploaded_date'] = date('Y-m-d H:i:s',$time);
			$data['media_uploaded_timestamp'] = $time;
			$data['GID'] = $active_campaign_gid;
			
			$media_ok = $this->media_m->addMedia($data);
			
			$html_done = '<table width="100%">
							<tr>
								<td width="23%">'.$this->media_m->showMedia($data).'</td>
								<td style="vertical-align:top">
								Your media has been successfully uploaded!<br>
								'.($campaign['media_has_approval'] ? 'Your media will be moderate by Administrator before published!<br>' : '').'
								Thank You.<br>
								</td>
							</tr>
						  </table>';
			
			$form->removeChild($button);
				$form->addElement('static','','',array('content'=>$html_done));	
			if($r_file)	
			 $form->removeChild($r_file);
		}
		
		$renderer = HTML_QuickForm2_Renderer::factory('default');
		$form_layout = $form->render($renderer);
		
		return $form_layout;
   }
  
   
  
   
   function customer_register()
   {
    $campaign = $this->campaign_m->getActiveCampaign();
	$this->load->library('facebook');
	
	$form = new HTMLQuickForm2('customer_register','POST','action=""  ');
		
		 $form->addElement('static','','',array('content'=>'Your Firstname :'));	
		 $firstname = $form->addElement('text','FIRSTNAME','style="width:395px;"');
		 $firstname->addRule('required', 'Firstname is required', null,HTML_QuickForm2_Rule::SERVER);
		 
		 $form->addElement('static','','',array('content'=>'Your Lastname :'));	
		 $lastname = $form->addElement('text','LASTNAME','style="width:395px;"');
		 $lastname->addRule('required', 'Lastname is required', null,HTML_QuickForm2_Rule::SERVER);
		 
		 $form->addElement('static','','',array('content'=>'Email :'));	
		 $email = $form->addElement('text','EMAIL','style="width:395px;"');
		 $email->addRule('required', 'Email is required', null,HTML_QuickForm2_Rule::SERVER);
		 
		 $form->addElement('static','','',array('content'=>'Phone no :'));	
		 $mobile = $form->addElement('text','MOBILE','style="width:395px;"');
		 $mobile->addRule('required', 'Phone no. is required', null,HTML_QuickForm2_Rule::SERVER);
		
		$button = $form->addElement('submit','submit','value="Submit Registration" style="border:solid 1px #D9BB75; background-color:#000;color:#D9BB75;padding:5px;margin-left:280px;"');
		
		if ($form->validate()) {
			$form->toggleFrozen(true);
			$data = $form->getValue();
			unset($data['submit'],$data['_qf__customer_register']);
			
			//$data['3012669'] = $this->facebook->getUser();//TRAC_ATTR_FBUID
			$data['3014098'] = $campaign['GID'];//TRAC_ATTR_GID
			$data['3031180'] = $data['MOBILE'];//TRAC_ATTR_MOBILE2
			
			unset($data['MOBILE']);
			
			
			
			if($registered = $this->customer_m->add($data)){
				$form->addElement('static','','',array('content'=>'<div>Done</div>'));	
			}else{
				$form->addElement('static','','',array('content'=>'<div>'.implode('<br/>',$this->customer_m->error).'</div>'));	
			}
			
			unset($data['submit'],$data['_qf__customer_register']);
			$form->removeChild($button);
		}
		
		$renderer = HTML_QuickForm2_Renderer::factory('default');
		
		
		$form_layout = $form->render($renderer);
		return $form_layout;
   }
    
   
   function setting_form()
   {
		$form = new HTMLQuickForm2('settingform','POST','action="'.site_url('/admin/setting/').'"');
		$rows = $this->db->get_results("SELECT * FROM campaign_setting ORDER BY name");

		foreach($rows as $row)
		{
		   $default_data[$row->name] = $row->value;
		   $r = $form->addElement('text',$row->name,'style="width:500px;"')->setLabel($row->description.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
			$r->addRule('required', $row->description.' is required', null,HTML_QuickForm2_Rule::SERVER);						
		}
		
		$form->addDataSource(new HTML_QuickForm2_DataSource_Array($default_data));
		
		$button = $form->addElement('submit','submit','value="Submit Data"');
		
		if ($form->validate()) 
		{
		   $this->is_validated = true;
			$form->removeChild($button);
			$form->toggleFrozen(true);
			
			$data = $form->getValue();
			unset($data['submit'],$data['_qf__settingform']);
			
			
			//dg(__FILE__,$data);
			
			foreach($data as $k => $v)
			{
			 $this->db->update(array("value" =>$v),array("name"=>$k)); 
			}
		}
		
		$renderer = HTML_QuickForm2_Renderer::factory('default');
		
		$form_layout = $form->render($renderer);
		return $form_layout;
   }
   
   
   function generate_form_login()
   {
     $form = new HTMLQuickForm2('loginform','POST','action="'.site_url('admin/login').'"');
		$form->addDataSource(new HTML_QuickForm2_DataSource_Array(array('username'=>'admin@demo.com','password'=>'passwd')));
		
		  $r = $form->addElement('text','username','style="width:200px;"')->setLabel('Email &nbsp;:&nbsp;&nbsp;');
		  $r->addRule('required', 'Email is required', null,HTML_QuickForm2_Rule::SERVER);						
		
		$r = $form->addElement('password','password','style="width:200px;"')->setLabel('Password &nbsp;:&nbsp;&nbsp;');
		  $r->addRule('required', 'Password is required', null,HTML_QuickForm2_Rule::SERVER);	
		
		$button = $form->addElement('submit','submit','value="Login"');
		
		if ($form->validate()) 
		{
		    $this->is_validated = true;
			$form->removeChild($button);
			$form->toggleFrozen(true);
			
			$data = $form->getValue();
			unset($data['submit'],$data['_qf__loginform']);
			if($this->auth->getAuth())
			{
			 redirect('admin/dashboard');
			}else{
			 die("YOU ARE NOT AUTHORIZED, CONTACT WEB ADMINISTRATOR");
			}
		}
		
		$renderer = HTML_QuickForm2_Renderer::factory('default');
		
		$form_layout = $form->render($renderer);
		return $form_layout;
   }

   function app_add($gid = 0)
   {
	$this->load->model('setting_m');
    $form = new HTMLQuickForm2('appaddform','POST','action="'.site_url('admin/app/add/'.$gid).'"');
	
	if($gid){
		$default_data = $this->app_m->detailApp($gid) ;
		$form->addDataSource(new HTML_QuickForm2_DataSource_Array($default_data));
	}
	
	$APP_APPLICATION_NAME = $form->addElement('text','APP_APPLICATION_NAME','style=""')->setLabel('APPLICATION NAME &nbsp;:&nbsp;&nbsp;');
	$APP_APPLICATION_NAME->addRule('required', 'APP_APPLICATION_NAME is required', null,HTML_QuickForm2_Rule::SERVER);		
	
	$APP_APPLICATION_ID = $form->addElement('text','APP_APPLICATION_ID','style=""')->setLabel('APP ID &nbsp;:&nbsp;&nbsp;');
	$APP_APPLICATION_ID->addRule('required', 'APP_APPLICATION_ID is required', null,HTML_QuickForm2_Rule::SERVER)
					   ->and_($APP_APPLICATION_ID->createRule('regex', 'App ID should contain only digits','/^[0-9]+$/'))
					   ->and_($APP_APPLICATION_ID->createRule('callback','Already Registered','callback_isAppIDregistered'))
					   ->and_($APP_APPLICATION_ID->createRule('callback','Invalid App ID','callback_validateAppID'));
	
	
	/* $APP_API_KEY = $form->addElement('text','APP_API_KEY','style=""')->setLabel('APP_API_KEY &nbsp;:&nbsp;&nbsp;');
	$APP_API_KEY->addRule('required', 'APP_API_KEY is required', null,HTML_QuickForm2_Rule::SERVER);		
	*/
	$APP_SECRET_KEY = $form->addElement('text','APP_SECRET_KEY','style=""')->setLabel('APP SECRET &nbsp;:&nbsp;&nbsp;');
	$APP_SECRET_KEY->addRule('required', 'APP_SECRET_KEY is required', null,HTML_QuickForm2_Rule::SERVER)
				   ->and_($APP_SECRET_KEY->createRule('regex', 'Secret Key should contain only letters and digits','/^[a-zA-Z0-9]+$/'));
	
	/*$APP_CANVAS_PAGE = $form->addElement('text','APP_CANVAS_PAGE','style=""')->setLabel('APP_CANVAS_PAGE &nbsp;:&nbsp;&nbsp;');
	$APP_CANVAS_PAGE->addRule('required', 'APP_CANVAS_PAGE is required', null,HTML_QuickForm2_Rule::SERVER);		
	
	$APP_CANVAS_URL = $form->addElement('hidden','APP_CANVAS_URL','style=""')->setValue($this->setting_m->get('SITE_URL'));
	$APP_CANVAS_URL->addRule('required', 'Couldnt load setting!', null,HTML_QuickForm2_Rule::SERVER);			
	 */
	//$APP_EXT_PERMISSIONS = $form->addElement('text','APP_EXT_PERMISSIONS','style=""')->setLabel('APP_EXT_PERMISSIONS &nbsp;:&nbsp;&nbsp;');
	
	$APP_EXT_PERMISSIONS = $form->addElement('hidden','APP_EXT_PERMISSIONS')->setValue('publish_stream,email,user_birthday,user_hometown,user_interests,user_likes');
	
	$APP_EXT_PERMISSIONS->addRule('required', 'APP_EXT_PERMISSIONS is required', null,HTML_QuickForm2_Rule::SERVER);		
	
	$APP_FANPAGE = $form->addElement('text','APP_FANPAGE','style=""')->setLabel('FACEBOOK PAGE URL &nbsp;:&nbsp;&nbsp;');
	$APP_FANPAGE->addRule('required', 'Facebook Page URL is required', null,HTML_QuickForm2_Rule::SERVER);		
	 
		
	$button = $form->addElement('submit','submit','value="Submit Application"');
	
	
	if ($form->validate()) 
	{
		$this->is_validated = true;
		$form->removeChild($button);
		$form->toggleFrozen(true);
		
		$data = $form->getValue();
		unset($data['submit'],$data['_qf__appaddform']);
		
		if($url = parse_url($data['APP_FANPAGE'])){
		  $new_url = $url['scheme']."://".$url['host'].$url['path']."?sk=app_".$rows['APP_APPLICATION_ID'];
		  $data['APP_FANPAGE'] = $new_url;
		}
		
		if(!$gid){
			$this->app_m->add($data);
		}else{
			$this->app_m->update($data);
		}
	}
		
		$renderer = HTML_QuickForm2_Renderer::factory('default');
		
		$form_layout = $form->render($renderer);
		return $form_layout;	
   }
   
   function user_add($userID=0)
   {
     $form = new HTMLQuickForm2('userform','POST','action="'.site_url('admin/user/add/').$userID.'"');
	
      $fsCredential = $form->addElement('fieldset')
							  ->setLabel('User Detail');	
		  $r = $fsCredential->addElement('text','user_name','style="width:200px;"')->setLabel('Name &nbsp;:&nbsp;&nbsp;');
		  $r->addRule('required', 'Name is required', null,HTML_QuickForm2_Rule::SERVER);						

		  $r = $fsCredential->addElement('text','user_email','style="width:200px;"')->setLabel('Email (for login) &nbsp;:&nbsp;&nbsp;');
		  $r->addRule('required', 'Email is required', null,HTML_QuickForm2_Rule::SERVER);		

		 // $acl = $fsCredential->addElement('select','user_access_level','',array('options'=>array('administrator'=>'Administrator','super_administrator'=>'Super Administrator','viewer'=>'Viewer')))->setLabel('Access Level &nbsp;:&nbsp;&nbsp;'); 			  
	      //$acl->addRule('required', 'Access Level is required', null,HTML_QuickForm2_Rule::SERVER);
		  
	if($userID){
	  $user = $this->db->query("SELECT * FROM campaign_user WHERE user_ID = ".$userID)->row();
	  $form->addDataSource(new HTML_QuickForm2_DataSource_Array(array('user_ID'=>$userID,
																	  'user_name'=>$user->user_name,
																	  'user_email'=>$user->user_email
																	  //'user_access_level'=>$user->user_access_level
																	  )));
	  
		$fsPasswords = $form->addElement('fieldset')
							  ->setLabel('Supply password only if you want to change it');

		$oldPassword = $fsPasswords->addElement('password', 'oldPassword', array('style' => 'width: 200px;'))
								   ->setLabel('Old password :');
		$newPassword = $fsPasswords->addElement('password', 'user_password', array('style' => 'width: 200px;'))
								   ->setLabel('New password (min 6 chars):');
		$repPassword = $fsPasswords->addElement('password', 'PasswordRepeat', array('style' => 'width: 200px;'))
								   ->setLabel('Repeat new password:');

		$oldPassword->addRule('empty', '', null, HTML_QuickForm2_Rule::SERVER)
					->and_($newPassword->createRule('empty'))
					->or_($oldPassword->createRule('callback', 'Wrong password / Old password needed', 'check_password'));
		
	    $newPassword->addRule('empty', '', null, HTML_QuickForm2_Rule::SERVER)
					->and_($repPassword->createRule('empty'))
					->and_($oldPassword->createRule('empty'))
					->or_($repPassword->createRule('eq', 'The passwords do not match', $newPassword))
					->and_($newPassword->createRule('minlength', 'The password is too short', 6))
					->and_($oldPassword->createRule('nonempty', 'Supply old password if you want to change it'));	
		
	
		
		
	    $form->addElement('hidden','user_ID');
		$button = $form->addElement('submit','submit','value="Update User"');
	}else{
	$form->addDataSource(new HTML_QuickForm2_DataSource_Array(array('user_registered_date'=>date('Y-m-d h:i:s'),
																	  'user_registered_by'=>$this->auth->getAuthData('user_ID'))));
	
	    $fsPasswords = $form->addElement('fieldset')
							->setLabel('Set User Password');
		$newPassword = $fsPasswords->addElement('password', 'user_password', array('style' => 'width: 200px;'))
								   ->setLabel('New password (min 6 chars):');
		$repPassword = $fsPasswords->addElement('password', 'PasswordRepeat', array('style' => 'width: 200px;'))
								   ->setLabel('Repeat new password:');
		$newPassword->addRule('nonempty', 'User Password', null, HTML_QuickForm2_Rule::SERVER)
			->and_($repPassword->createRule('nonempty', 'Repeat password', $newPassword))
			->and_($repPassword->createRule('eq', 'The passwords do not match', $newPassword))
			->and_($newPassword->createRule('minlength', 'The password is too short', 6));		
		
		$form->addElement('hidden','user_registered_date');	
		$form->addElement('hidden','user_registered_by');	
		
		$button = $form->addElement('submit','submit','value="Add User"');
	}
	

		
		
		
		if ($form->validate()) 
		{  
		   $this->is_validated = true;
		   $data = $form->getValue();
		   unset($data['submit'],$data['_qf__userform']);
		  if($this->user_model->add($data)){
			if(!$data['user_ID']){
			 $fslogcred = $form->addElement('fieldset')->setLabel('User Login Credential (Please Write this note)');
			 $fslogcred->addElement('static','','',array('content'=>"<div>
															<b>Email : {$data['user_email']}</b><br>
															<b>Password : {$data['user_password']}</b>
															</div>"));	
			 $form->addElement('static','','',array('content'=>"<Br><br><a href='".site_url('admin/user/add')."'>Add another User</a>"));													
			}
		  }
			$form->removeChild($button);
			$form->toggleFrozen(true);
			
			
		}
		
		$renderer = HTML_QuickForm2_Renderer::factory('default');
		
		$form_layout = $form->render($renderer);
		return $form_layout;
   }
  
   
    function campaign_add($gid = 0){
   
		$form = new HTMLQuickForm2('campaign','POST','action="'.site_url('admin/campaign/add/'.$gid).'"');
		$form->setAttribute('enctype', 'multipart/form-data');
		/**/
		if($gid){
		 $campaign = $this->campaign_m->detailCampaign($gid) ;
		 $form->addDataSource(new HTML_QuickForm2_DataSource_Array(array(
                           'title' => $campaign['title'],
                            'description' => $campaign['description'],
							'startdate' => $campaign['startdate'],
							'upload_enddate' => $campaign['upload_enddate'],
							'enddate' => $campaign['enddate'],
							'gid' => $campaign['GID'],
							'media_has_approval' => $campaign['media_has_approval'],
							'media_has_fbcomment' => $campaign['media_has_fbcomment'],
							'media_has_fblike' => $campaign['media_has_fblike'],
							//'allowed_media_source'=> $campaign['allowed_media_source'],
							//'allowed_media_type'=>$campaign['allowed_media_type'],
							//'allowed_media_fields'=>$campaign['allowed_media_fields'],
							//'allowed_mimetype'=>$campaign['allowed_mimetype'],
							'campaign_rules'=>html_entity_decode($campaign['campaign_rules']),
							//'campaign_policy'=>html_entity_decode($campaign['campaign_policy']),
							//'campaign_mechanism'=>html_entity_decode($campaign['campaign_mechanism']),
							//'campaign_fbshare_media'=>$campaign['campaign_fbshare_media'],
							//'campaign_twshare_media'=>$campaign['campaign_twshare_media'],
							'APP_APPLICATION_ID'=>$campaign['APP_APPLICATION_ID']
                        )));
		}else{
		 $form->addDataSource(new HTML_QuickForm2_DataSource_Array(array(
							'startdate' => date('Y-m-d H:i:s'),
							'upload_enddate' => date('Y-m-d H:i:s'),
							'enddate' => date('Y-m-d H:i:s')
                        )));
		}
		/**/
		
		$form->addElement('hidden','gid');
		
		$form->addElement('static','','',array('content'=>'<b>Your Facebook Application Name that you have created ?</b> <a href="'.site_url('admin/app/add').'">Add</a>'));
		$fb_options[''] = 'Select Apps Name';
		if($appIDrow = $this->db->get_results("SELECT APP_APPLICATION_ID, APP_APPLICATION_NAME 
											   FROM campaign_app  
											   WHERE campaign_app.APP_APPLICATION_ID NOT IN 
													 (SELECT APP_APPLICATION_ID FROM campaign_group 
													  WHERE APP_APPLICATION_ID <> '' ".($gid ? "AND campaign_group.GID <> $gid" : "").") ",'ARRAY_A'))
		{
		 
			foreach($appIDrow as $conf){
			  $fb_options[$conf['APP_APPLICATION_ID']] = $conf['APP_APPLICATION_NAME'];
			}
		}
		$APP_APPLICATION_ID = $form->addElement('select','APP_APPLICATION_ID','',array('options'=>$fb_options));
		if(count($fb_options) <= 1) 
		$form->addElement('static','','',array('content'=>'<b style="color:red;">*All APPLICATION ID has been registered please create new one! Please go to <a href="'.site_url('admin/app/add').'">App</a> panel.</b>'));

		$APP_APPLICATION_ID->addRule('required', 'Facebook App Name is required', null,HTML_QuickForm2_Rule::SERVER);
		
		$form->addElement('static','','',array('content'=>'<b>Title for your campaign ?</b>'));
		$stitle = $form->addElement('text','title',array('style'=>''));
		
		$form->addElement('static','','',array('content'=>'<b>What is your campaign all about ?</b>'));
		$sdescription = $form->addElement('textarea','description',array('style'=>''));
		
		$allowed_maxfilesize = 1024*1024;
		$allowed_mimetype = 'image/gif,image/jpeg,image/pjpeg,image/png';

		$form->addElement('static','','',array('content'=>'<b>Upload Header Image for your Campaign ? (image width will be resize to 400px)</b>'));
		$r_file = $form->addElement('file','image_header_uploadfile','');
		$r_file->addRule('mimetype', $label.' is not valid file type', explode(',',$allowed_mimetype),HTML_QuickForm2_Rule::SERVER);
		$r_file->addRule('maxfilesize', $label.' filesize is exceeded ', $allowed_maxfilesize,HTML_QuickForm2_Rule::SERVER);
		
		if($gid){
			$form->addElement('static','','',array('content'=>'<img src="'.site_url('image/campaign')."?src=".$campaign['image_header'].'">'));
		}
			
		$date_set = $gid ? array('format'=>'dFY His','maxYear'=>date('Y')) : array('format'=>'dFY His','minYear'=>date('Y'),'maxYear'=>date('Y')+1);
		
		$form->addElement('static','','',array('content'=>'<b>When will your campaign will start ?</b>'));
		$startdate_group = $form->addElement('group');	 
		$startdate_group->addElement('date','startdate','',$date_set,'style="width:100px;"');
		
		$form->addElement('static','','',array('content'=>'<b>When will Upload Task end ?</b>'));
		$upload_enddate_group = $form->addElement('group');	 
		$upload_enddate_group->addElement('date','upload_enddate','',$date_set,'style="width:100px;"');
		
		$upload_enddate_group->addRule('callback','Date must be longer than start date','callback_validateUploadEndDate');
		
		
		$form->addElement('static','','',array('content'=>'<b>When will your campaign end ?</b>'));
		$enddate_group = $form->addElement('group');	 
		$enddate_group->addElement('date','enddate','',$date_set,'style="width:100px;"');

		$enddate_group->addRule('callback','Date must be longer than upload end date','callback_validateEndDate');
		
		
		$form->addElement('static','','',array('content'=>'<b>Set allowed media to be use for the contest ?</b>'));
		
		$fsAddFields = $form->addElement('group');
		$data_media_source	=  @$campaign['allowed_media_source'] ? explode(',',$campaign['allowed_media_source']) : array();
		foreach (array('youtube'=>'Youtube Only (Video)','file'=>'File Upload (Image Only)') as $k => $v){
			$checked = count(array_intersect($data_media_source,explode(',',$k))) == count($data_media_source) ? 'checked = "checked"' : '';
			$fsAddFields->addElement('radio','allowed_media_source',$checked."  value='".$k."' style='width:0px;'");
			$fsAddFields->addElement('static','','',array('content'=>$v.'<br/>'));
		}
		
		//$allowed_media_source = $form->addElement('text','allowed_media_source',array('style'=>''));
		
		
		//$form->addElement('static','','',array('content'=>'<b>Allowed Media Type : Video | Image'));
		//$allowed_media_type = $form->addElement('text','allowed_media_type',array('style'=>''));
		
		//$form->addElement('static','','',array('content'=>'<b>Allowed Media Fields : <Br/>ex: media_title=Judul&media_description=Deskripsi&media_source=Upload your media'));
		$allowed_media_fields = $form->addElement('hidden','allowed_media_fields',array('style'=>''))->setValue('media_source=Upload Content Here&media_description=It\'s About');
		
		
		//$form->addElement('static','','',array('content'=>'<br/><b>You may fill one of this field below if allowed media source is a file</b> <br/><hr/>'));
		
		//$form->addElement('static','','',array('content'=>'<b>Allowed mimetype for File Upload: <Br/>ex: image/gif,image/jpeg,image/pjpeg,image/png'));
		$allowed_mimetype = $form->addElement('hidden','allowed_mimetype',array('style'=>''))->setValue('image/gif,image/jpeg,image/pjpeg,image/png');

		//$form->addElement('static','','',array('content'=>'<br/><b>You may fill in Campaign rules,policy,mechanism,share feed</b> <br/><hr/>'));
		
		$form->addElement('static','','',array('content'=>'<b>Does the uploaded media need approval by admin before published ?</b>'));
		$media_has_approval = $form->addElement('select','media_has_approval','',array('options'=>array('0'=>'No','1'=>'Yes')));
		
		$form->addElement('static','','',array('content'=>'<b>Does media use Facebook Comments (Social Plugin) ?</b>'));
		$media_has_fbcomment = $form->addElement('select','media_has_fbcomment','',array('options'=>array('0'=>'No','1'=>'Yes')));
		
		$form->addElement('static','','',array('content'=>'<b>Does media use Facebook Like (Social Plugin) ?</b>'));
		$media_has_fblike = $form->addElement('select','media_has_fblike','',array('options'=>array('0'=>'No','1'=>'Yes')));
		
		$form->addElement('static','','',array('content'=>'<b>Define your Campaign Rules/FAQ ?</b>'));
		$campaign_rules = $form->addElement('textarea','campaign_rules',array('style'=>'height:400px','id'=>'campaign_rules'));
		/*
		$form->addElement('static','','',array('content'=>'<b>Define your Campaign Policy ?</b>'));
		$campaign_policy = $form->addElement('textarea','campaign_policy',array('style'=>'height:200px','id'=>'campaign_policy'));
		
		
		$form->addElement('static','','',array('content'=>'<b>Define your Campaign Mechanism ?</b>'));
		$campaign_mechanism = $form->addElement('textarea','campaign_mechanism',array('style'=>'height:200px','id'=>'campaign_mechanism'));	
		*/
		
		/*
		$form->addElement('static','','',array('content'=>'<b>Any Facebook Feed Description for sharing media ?</b>'));
		$campaign_fbshare_media = $form->addElement('textarea','campaign_fbshare_media',array('style'=>'height:60px'));
			*/
		/*	
		$form->addElement('static','','',array('content'=>'<b>Any Twitter status for sharing media ?</b>'));
		$campaign_twshare_media = $form->addElement('textarea','campaign_twshare_media',array('style'=>'height:60px'));	
		*/
	

		
		
		$stitle->addRule('required', 'Title is required', null,HTML_QuickForm2_Rule::SERVER);
		$sdescription->addRule('required', 'Description is required', null,HTML_QuickForm2_Rule::SERVER);		
		
		 $button = $form->addElement('submit','','value="Submit Campaign"');
		$html = array();
		if ($form->validate()) {
			$data = $form->getValue();
		   unset($data['submit'],$data['_qf__campaign']);

		   extract($data['startdate']);
			$data['startdate'] = $Y.'-'.$F.'-'.$d.' '.$H.':'.$i.':'.$s;			
			extract($data['enddate']);
			$data['enddate'] = $Y.'-'.$F.'-'.$d.' '.$H.':'.$i.':'.$s;	
			extract($data['upload_enddate']);
			$data['upload_enddate'] = $Y.'-'.$F.'-'.$d.' '.$H.':'.$i.':'.$s;	
			

			
			$data['allowed_media_fields'] = html_entity_decode($data['allowed_media_fields']);
			
			$allowed_media_source = explode(',',$data['allowed_media_source']);
			
			if($media = array_intersect(array('plixi','twitpic','yfrog'),$allowed_media_source)){
			 $data['allowed_media_source'] = implode(',',$media);
			 $data['allowed_media_type'] = 'image';
			}elseif($media = array_intersect(array('youtube','facebook'),$allowed_media_source)){
			 $data['allowed_media_source'] = implode(',',$media);
			 $data['allowed_media_type'] = 'video';
			}elseif($media = array_intersect(array('file'),$allowed_media_source)){
			 $data['allowed_media_source'] = implode(',',$media);
			 $data['allowed_media_type'] = 'image';
			}
			
			if ($data['image_header_uploadfile']['error'] == UPLOAD_ERR_OK) {
				$tmp_name = $data['image_header_uploadfile']["tmp_name"];
				$time = md5(uniqid(rand(), true).time());
				$image = resizeImage( $tmp_name, CAMPAIGN_IMAGE_DIR."/".$time.".jpg", 400 , 'width' );					
				$data['image_header'] = $time.".jpg";
				unset($data['image_header_uploadfile']);
			}
			
			unset($data['image_header_uploadfile']);
		   if($gid){
			   if(!$this->campaign_m->updateCampaign($data)){
				 $error = $this->campaign_m->error;
			   }
		   }else{
		       unset($data['gid']);
			   if(!$this->campaign_m->addCampaign($data)){
				 $error = $this->campaign_m->error;
			   }		   
		   }
		   
			$form->removeChild($button);
			$form->toggleFrozen(true);
		}
		
			
		$renderer = HTML_QuickForm2_Renderer::factory('default');		
		$form_layout = $form->render($renderer);
		return $form_layout;
   }
   
 }