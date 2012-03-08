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
   
   function vote_form($media_id,$media_vote_total,$template = null) {
	 
      $form = new HTMLQuickForm2('votemedia','POST');
	  $form->setAttribute('action', '');
	   $form->addDataSource(new HTML_QuickForm2_DataSource_Array(array('id'=>$media_id)));
	 $form->addElement('hidden','id');
	 
	if ($form->validate()) {
	  $media_vote_total = $this->media_m->setVote(addslashes($media_id));
	}
	
	$template = '<div class="media-info">'.
                '<div><input type="submit" id="vote" name="vote" value="Vote!" /></div>'.
				'<div class="vote"> '.$media_vote_total.' vote(s)</div>'.
				'</div>';
	
	 $form->addElement('static','boxcount','',array('content'=>$template));		

	 $renderer = HTML_QuickForm2_Renderer::factory('default');
	 $form_layout = $form->render($renderer);
	 return $form_layout;
   }
   
   function upload_media($campaign)
   { 
   $this->load->model('setting_m');
     $this->load->library('facebook');
     $uid = $this->facebook->getUser();
	
	 
     $form = new HTMLQuickForm2('uploadmedia','POST');
    $form->setAttribute('action', menu_url('upload'));
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
		   //$form->addElement('static','','',array('content'=>$label));	
		   $r = $form->addElement('text',$domid,'');
		   $r->setLabel($label);
									$r->addRule('required', $label.' is required', null,HTML_QuickForm2_Rule::SERVER);
									break;
		   case 'media_description' : 
		   //$form->addElement('static','','',array('content'=>$label));	
		   $r = $form->addElement('textarea',$domid,'');
		   $r->setLabel($label);
									$r->addRule('required', $label.' is required', null,HTML_QuickForm2_Rule::SERVER);
									break;
		   case 'media_source' : 
		   //$form->addElement('static','','',array('content'=>$label));	
		   $src = explode(',',$allowed_media_source); 
								 if(in_array('facebook',$src) || in_array('youtube',$src) || in_array('twitpic',$src) || in_array('yfrog',$src) || in_array('plixi',$src))
								 {
									//$form->addElement('static','','',array('content'=>'Copy and Paste YouTube URL here'));	
								    $r->setLabel('YouTube URL');
									$r = $form->addElement('text',$domid,'');
									$r->addRule('required', $label.' is required', null,HTML_QuickForm2_Rule::SERVER);
									break;
								 } 
								 elseif(in_array('file',$src))
								 {
									//$form->addElement('static','','',array('content'=>'Upload Photo from your computer here'));	
									
									$r_file = $form->addElement('file',$domid,'');
									$r_file->setLabel('Upload Photo');
									$r_file->addRule('required', $label.' is required', null,HTML_QuickForm2_Rule::SERVER);
									$r_file->addRule('mimetype', $label.' is not valid file type', explode(',',$allowed_mimetype),HTML_QuickForm2_Rule::SERVER);
									$r_file->addRule('maxfilesize', $label.' filesize is exceeded ', $allowed_maxfilesize,HTML_QuickForm2_Rule::SERVER);
									
									break;
								 }				
		  }
		}
		$button = $form->addElement('submit','submit','value="Submit" class="input-submit button big"');
		$button->setLabel('&nbsp;');
		
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
							
						$image = resizeImage( $tmp_name, CUSTOMER_IMAGE_DIR.$active_campaign_gid."/".$uid."_".$time.".jpg", 440 , 'width' );
						$image_medium = resizeImage( $tmp_name, CUSTOMER_IMAGE_DIR.$active_campaign_gid."/medium_".$uid."_".$time.".jpg", 300 , 'width' );
						
						$thumb = resizeImage( $tmp_name, CUSTOMER_IMAGE_DIR.$active_campaign_gid."/thumb_".$uid."_".$time.".jpg", 100 , null,true );
						$data['media_source'] = 'file';
						$data['media_url'] = base_url()."image?gid=".$active_campaign_gid."&src=".$uid."_".$time.".jpg";
						$data['media_medium_url'] = base_url()."image?gid=".$active_campaign_gid."&src=medium_".$uid."_".$time.".jpg";
						$data['media_thumb_url'] = base_url()."image?gid=".$active_campaign_gid."&src=thumb_".$uid."_".$time.".jpg";
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
			$data['media_status'] = $campaign['media_has_approval'] ? 'pending' : 'active';
			$data['media_uploaded_date'] = date('Y-m-d H:i:s',$time);
			$data['media_uploaded_timestamp'] = $time;
			$data['GID'] = $active_campaign_gid;
			
			if($media_ok = $this->media_m->addMedia($data)){
				return "success";
			}else{
			    return "error";
			}
			
/* 			$html_done = '<table width="100%">
							<tr>
								<td width="23%">'.$this->media_m->showMedia($data).'</td>
								<td style="vertical-align:top">
								Your media has been successfully uploaded!<br>
								'.($campaign['media_has_approval'] ? 'Your media will be moderate by Administrator before published!<br>' : '').'
								Thank You.<br>
								</td>
							</tr>
						  </table>'; */
				//$form->addElement('static','','',array('content'=>$html_done));	
			
			$form->removeChild($button);
			if($r_file)	
			 $form->removeChild($r_file);
		}
		
		$renderer = HTML_QuickForm2_Renderer::factory('default');
		$form_layout = $form->render($renderer);
		
		return isset($html_done) ? $html_done : $form_layout;
   }
  
   
  
   
   function customer_register()
   {
    $campaign = $this->campaign_m->getActiveCampaign();
	$this->load->library('facebook');
	
	$form = new HTMLQuickForm2('customer_register','POST');
    $form->setAttribute('action', menu_url('register'));
	$user = getAuthorizedUser();
	$form->addDataSource(new HTML_QuickForm2_DataSource_Array(array(
																	'FIRSTNAME'=>isset($user['first_name']) ? $user['first_name'] : "" ,
																	'LASTNAME'=>isset($user['last_name']) ? $user['last_name'] : "",
																	'EMAIL'=>isset($user['email']) ?  $user['email'] : ""
																	)));
		
		 //$form->addElement('static','','',array('content'=>'Your Firstname :'));	
		 $firstname = $form->addElement('text','FIRSTNAME','');
		 $firstname->setLabel('First Name');
		 $firstname->addRule('required', 'Firstname is required', null,HTML_QuickForm2_Rule::SERVER);
		 
		 //$form->addElement('static','','',array('content'=>'Your Lastname :'));	
		 $lastname = $form->addElement('text','LASTNAME','');
		 $lastname->setLabel('Last Name');
		 $lastname->addRule('required', 'Lastname is required', null,HTML_QuickForm2_Rule::SERVER);
		 
		 //$form->addElement('static','','',array('content'=>'Email :'));	
		 $email = $form->addElement('text','EMAIL','');
		 $email->setLabel('Email');
		 $email->addRule('required', 'Email is required', null,HTML_QuickForm2_Rule::SERVER);
		 
		 //$form->addElement('static','','',array('content'=>'Phone no :'));	
		 $mobile = $form->addElement('text','MOBILE','');
		 $mobile->setLabel('Phone');
		 $mobile->addRule('required', 'Phone no. is required', null,HTML_QuickForm2_Rule::SERVER);
		 
		 //$form->addElement('static','','',array('content'=>'Phone no :'));	
		 $address = $form->addElement('textarea','ADDRESS','');
		 $address->setLabel('Address');
		 $address->addRule('required', 'Address is required', null,HTML_QuickForm2_Rule::SERVER);
		 
		 //$form->addElement('static','','',array('content'=>'Phone no :'));	
		 $terms = $form->addElement('checkbox','TERMS','',array('content'=>'I accept Terms & Conditions'));
		 $terms->setLabel('Regulation');
		 $terms->addRule('required', 'Terms Agreement Required', null,HTML_QuickForm2_Rule::SERVER);
		 
		 //$form->addElement('static','','',array('content'=>'Phone no :'));	
		 $SUBSCRIPTION = $form->addElement('checkbox','SUBSCRIPTIONID1','value = "'.$this->config->item('APP_APPLICATION_ID').'|S"',array('content'=>'Please send me news & updates'));
		 $SUBSCRIPTION->setLabel('Email Subscribe');
		 
		
		$button = $form->addElement('submit','submit','value="Register"');
		$button->setLabel('&nbsp;');
		
		if ($form->validate()) {
			$form->toggleFrozen(true);
			$data = $form->getValue();
			unset($data['submit'],$data['_qf__customer_register'],$data['TERMS']);
			
			$data['GID'] = $campaign['GID']."_".$this->config->item('APP_APPLICATION_ID');
			
			if($registered = $this->customer_m->add($data)){
			 return "success";	
			}else{
			 return "error";
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
		if(!$default_data) return '';
		$default_data['task'] = 'edit';
		$form->addDataSource(new HTML_QuickForm2_DataSource_Array($default_data));
		
	}
	
	$form->addElement('hidden','task');
	
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
	 
	$APP_RESTRICTION = $form->addElement('checkbox','APP_AGE_RESTRICTION','style="width:10px;float:left;"',array('content'=>'Age 21+ Restriction'));
	
	$button = $form->addElement('submit','submit','style="margin-top:20px;" value="Submit Application"');
	
	
	if ($form->validate()) 
	{
		$this->is_validated = true;
		$form->removeChild($button);
		$form->toggleFrozen(true);
		
		$data = $form->getValue();
		unset($data['submit'],$data['_qf__appaddform'],$data['task']);
		
		if(!$data['APP_AGE_RESTRICTION']) $data['APP_AGE_RESTRICTION'] = '0';
		
		if($url = parse_url($data['APP_FANPAGE'])){
		  $new_url = $url['scheme']."://".$url['host'].$url['path'];//."?sk=app_".$rows['APP_APPLICATION_ID'];
		  $data['APP_FANPAGE'] = $new_url;
		}
		
		if(!$gid){
			if($this->app_m->add($data)){
			 $this->notify->set_message('success', 'Data has been successfuly submitted.');
			}else{
			 $this->notify->set_message('error', 'Data has failed to submit.');
			}
		}else{
			if($this->app_m->update($data)){
			 $this->notify->set_message('success', 'Data has been successfuly updated.');
			}else{
			 $this->notify->set_message('error', 'Data has been failed to be updated.');
			}
		}
	}
		
		$renderer = HTML_QuickForm2_Renderer::factory('default');
		
		$form_layout = $form->render($renderer);
		return $form_layout;	
   }
   
   function user_add($userID=0)
   {
    $this->load->model('user_m');
     $form = new HTMLQuickForm2('userform','POST','action="'.site_url('admin/user/add/'.$userID).'"');
	
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
	  if(!$user) return '';
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
			//->and_($repPassword->createRule('nonempty', 'Repeat password', $newPassword))
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
		   unset($data['submit'],$data['_qf__userform'],$data['PasswordRepeat']);
		  if($this->user_m->add($data)){
			if(!isset($data['user_ID'])){
			 $fslogcred = $form->addElement('fieldset')->setLabel('User Login Credential (Please Write this note)');
			 $fslogcred->addElement('static','','',array('content'=>"<div>
															<b>Email : {$data['user_email']}</b><br>
															<b>Password : {$data['user_password']}</b>
															</div>"));	
			 $form->addElement('static','','',array('content'=>"<Br><br><a href='".site_url('admin/user/add')."'>Add another User</a>"));													
			}
			$this->notify->set_message('success', 'Data has been successfuly submitted.');
		  }else{
		    $this->notify->set_message('error', 'Data has failed to be submitted.');
		  }
			$form->removeChild($button);
			$form->toggleFrozen(true);
			
			
		}
		
		$renderer = HTML_QuickForm2_Renderer::factory('default');
		
		$form_layout = $form->render($renderer);
		return $form_layout;
   }
   
   function page_add($page_id = 0){
    $this->load->model('page_m');
   
	$form = new HTMLQuickForm2('page','POST','action="'.site_url('admin/page/add/'.$page_id).'"');
	if($page_id){
		 $page = $this->page_m->detailPage($page_id) ;
		 $form->addDataSource(new HTML_QuickForm2_DataSource_Array(array(
                           'title' => $page['page_title'],
							'page_id' => $page['page_id'],
							'GID' => $page['GID'],	
							'page_title' => $page['page_title'],		
							'page_short_name' => $page['page_short_name'],		
							'page_body' => $page['page_body'],	
							'page_status' => $page['page_status'],		
							'page_publish_date' => $page['page_publish_date'] 
                        )));
		 if(!$page) return '';				
	}else{
		 $form->addDataSource(new HTML_QuickForm2_DataSource_Array(array(
							'page_publish_date' => date('Y-m-d H:i:s')
							)));
	}
	
	$form->addElement('hidden','page_id');
	$cmp_options[''] = "Choose Campaign";
	if($campaignRow = $this->db->get_results("SELECT GID,title FROM campaign_group",'ARRAY_A'))
	{
		foreach($campaignRow as $conf){
		  $cmp_options[$conf['GID']] = $conf['title'];
		}
	}
	
	$form->addElement('static','','',array('content'=>'<b>Please choose Campaign for your Custom Page</b>'));
	$GID = $form->addElement('select','GID','',array('options'=>$cmp_options));
	$GID->addRule('required', 'Campaign is required', null,HTML_QuickForm2_Rule::SERVER);
	
	$form->addElement('static','','',array('content'=>'<b>Publish Date</b>'));
	$date_set = $page_id ? array('format'=>'dFY His','maxYear'=>date('Y')) : array('format'=>'dFY His','minYear'=>date('Y'),'maxYear'=>date('Y')+1);
		
	$page_publish_date = $form->addElement('group');	 
	$page_publish_date->addElement('date','page_publish_date','',$date_set,'style="width:100px;"');
			
	$form->addElement('static','','',array('content'=>'<b>Status</b>'));
	$GID = $form->addElement('select','page_status','',array('options'=>array('publish'=>'Publish','draft'=>'Draft')));

	
	$form->addElement('static','','',array('content'=>'<b>Page Title</b>'));
	$page_title = $form->addElement('text','page_title');
	$page_title->addRule('required', 'Title is required', null,HTML_QuickForm2_Rule::SERVER);
	$page_title->addRule('regex', 'Should contain only letters, digits and underscores','/^[a-zA-Z0-9_ ]+$/');
	
	$form->addElement('static','','',array('content'=>'<b>Page Anchor Link Name</b>'));
	$page_short_name = $form->addElement('text','page_short_name',array('maxlength'=>'30'));
	$page_short_name->addRule('required', 'Anchor Link Name is required', null,HTML_QuickForm2_Rule::SERVER);
	$page_short_name->addRule('regex', 'Should contain only letters, digits and underscores','/^[a-zA-Z0-9_ ]+$/');
   
	   
	$form->addElement('static','','',array('content'=>'<b>Page Body</b>'));
	$page_body = $form->addElement('textarea','page_body',array('style'=>'height:768px;'));
	$page_body->addRule('required', 'Body is required', null,HTML_QuickForm2_Rule::SERVER);
	
   
    $button = $form->addElement('submit','','value="Submit Page"');
		$html = array();
		if ($form->validate()) {
			$data = $form->getValue();
		   unset($data['submit'],$data['_qf__page']);

		    extract($data['page_publish_date']);
			$data['page_publish_date'] = $Y.'-'.$F.'-'.$d.' '.$H.':'.$i.':'.$s;			

	

		   if($page_id){
			   if(!$this->page_m->updatePage($data)){
				 $this->notify->set_message('error', 'Data has failed to be updated.');
			   }else{
			    $this->notify->set_message('success', 'Data has been successfuly updated.');
			   }
		   }else{
		       unset($data['page_id']);
			   if(!$this->page_m->addPage($data)){
				 $this->notify->set_message('error', 'Data has failed to be submitted.');
			   }else{
				$this->notify->set_message('success', 'Data has been successfuly submitted.');
				}			   
		   }
		   
			$form->removeChild($button);
			$form->toggleFrozen(true);
		}
		
		$renderer = HTML_QuickForm2_Renderer::factory('default');		
		$form_layout = $form->render($renderer);
		return $form_layout;
   
   }
   
   function asset_add($asset_id){
    $this->load->model('assets_m');
		$form = new HTMLQuickForm2('assets','POST','action="'.site_url('admin/assets/add/'.$asset_id).'"');
		$form->setAttribute('enctype', 'multipart/form-data');	
		if($asset_id){
		  $assets = $this->assets_m->detailAsset($asset_id);
		  		 $form->addDataSource(new HTML_QuickForm2_DataSource_Array(array(
							'asset_id' => $assets['asset_id'],
                            'asset_name' => $assets['asset_name'],
							'asset_type' => $assets['asset_type'],
							'asset_platform' => $assets['asset_platform'],
							'asset_bgcolor' => $assets['asset_bgcolor']
                        )));
		  if(!$assets) return '';				
		}
		$form->addElement('hidden','asset_id');
		$form->addElement('static','','',array('content'=>'<b>Asset Name ?</b>'));
		$asset_name = $form->addElement('text','asset_name',array('style'=>''));
		$asset_name->addRule('required', 'Required', null,HTML_QuickForm2_Rule::SERVER);
		
		$form->addElement('static','','',array('content'=>'<b>Asset Platform ?</b>'));
		$asset_platform = $form->addElement('select','asset_platform','',array('options'=>array('facebook'=>'On Facebook',
																						'mobile'=>'On Mobile Web')));
		$asset_platform->addRule('required', 'Required', null,HTML_QuickForm2_Rule::SERVER);
		
		$form->addElement('static','','',array('content'=>'<b>Asset Type ?</b>'));
		$asset_type = $form->addElement('select','asset_type','',array('options'=>array('banner_header'=>'Banner Header',
																						'banner_main'=>'Banner Main',
																						'banner_footer'=>'Banner Footer',
																						'background_norepeat'=>'Background No Repeat',
																						'background_repeat'=>'Background Repeat')));
		$asset_type->addRule('required', 'Required', null,HTML_QuickForm2_Rule::SERVER);
		
		$form->addElement('static','','',array('content'=>'<b>Default Background Color ? ex: #090909 </b>'));
		$asset_bgcolor = $form->addElement('text','asset_bgcolor',array('style'=>'width:100px;font-size:20;','size'=>7,'maxlength'=>7));
		$asset_bgcolor->addRule('regex', 'Wrong Color Format','/^#[a-zA-Z0-9]{6}$/');
		
		if($asset_id){
		$form->addElement('static','','',array('content'=>'<img src="'.$assets['asset_url'].'" />'));
		}else{
		$asset_uploadedtext = $form->addElement('static','','',array('content'=>'<b>Upload Assets ? (GIF,JPG,PNG)</b>'));
		$asset_uploadedfile = $form->addElement('file','uploadedfile','size="80"');
		$asset_uploadedfile->addRule('mimetype', 'Asset is not a valid file type', array('image/gif','image/jpeg','image/pjpeg','image/png'),HTML_QuickForm2_Rule::SERVER);
		//$asset_file->addRule('maxfilesize', 'Asset filesize is exceeded ', $allowed_maxfilesize,HTML_QuickForm2_Rule::SERVER);

		$form->addElement('static','','',array('content'=>'<b>Set Width Size? (Height resized by width Ratio)</b>'));
		$asset_resizeto = $form->addElement('select','asset_resizeto','',array('options'=>array(''=>'No Resizing','90'=>'90px','100'=>'100px','120'=>'120px','200'=>'220px','320'=>'320px','400'=>'400px','520'=>'520px','760'=>'760px','810'=>'810px')));
		}
		$button = $form->addElement('submit','','value="Submit Asset"');
		
		if ($form->validate()) {
			$data = $form->getValue();
		    unset($data['submit'],$data['_qf__assets']);

			if (isset($data['uploadedfile']['error']) && $data['uploadedfile']['error'] == UPLOAD_ERR_OK) {
				
				$asset_resizeto = $data['asset_resizeto'];
				unset($data['asset_resizeto']);
				
				$tmp_name = $data['uploadedfile']["tmp_name"];
				$time = md5(uniqid(rand(), true).time());
				$img = resizeImage( $tmp_name, CAMPAIGN_IMAGE_DIR."/".$time.".jpg", $asset_resizeto , 'width' );	
				$info_img = getimagesize($img); 
				//Thumb Creation
				$thm = resizeImage( $tmp_name, CAMPAIGN_IMAGE_DIR."/thumb_".$time.".jpg", 100 , null,true );
				$data['asset_basename'] = $time.".jpg";
				$data['asset_width'] = isset($info_img[0]) ? $info_img[0] : '';
				$data['asset_height'] = isset($info_img[1]) ? $info_img[1] : '';
				$data['asset_mimetype'] = isset($info_img['mime']) ? $info_img['mime'] : '';
				$data['asset_url'] = site_url('image/campaign').'?src='.$data['asset_basename'];
				$data['asset_thumb_url'] = site_url('image/campaign').'?src=thumb_'.$data['asset_basename'];
				unset($data['uploadedfile']);
				$form->addElement('static','','',array('content'=>'<div style="margin-top:10px;">Uploaded Asset :</div><div><img src="'.$data['asset_url'].'" /></div>'));
			}
			if(isset($data['uploadedfile'])) unset($data['uploadedfile']);
			
		   if($asset_id){
		  
			   if($this->assets_m->updateAssets($data)){
				 $this->notify->set_message('success', 'Data has been successfuly updated.');
			   }else{
			    $this->notify->set_message('error', 'Data has failed to be updated.');
			   }
		   }else{
		       unset($data['asset_id']);
			   if($this->assets_m->addAssets($data)){
				 $this->notify->set_message('success', 'Data has been successfuly submitted.');
			   }else{
				$this->notify->set_message('error', 'Data has failed to be submitted.');
			   }			   
		   }
		    if(isset($asset_uploadedtext))
			$form->removeChild($asset_uploadedtext);
			if(isset($asset_uploadedfile))
		    $form->removeChild($asset_uploadedfile);
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
		 if(!$campaign) return '';
		 $form->addDataSource(new HTML_QuickForm2_DataSource_Array(array(
                           'title' => $campaign['title'],
                            'description' => $campaign['description'],
							'startdate' => $campaign['startdate'],
							'upload_enddate' => $campaign['upload_enddate'],
							'winner_selectiondate' => $campaign['winner_selectiondate'],
							'enddate' => $campaign['enddate'],
							'gid' => $campaign['GID'],
							'media_has_approval' => $campaign['media_has_approval'],
							'media_has_fbcomment' => $campaign['media_has_fbcomment'],
							'media_has_fblike' => $campaign['media_has_fblike'],
							'media_has_vote' => $campaign['media_has_vote'],
							'campaign_rules'=>html_entity_decode($campaign['campaign_rules']),
							'APP_APPLICATION_ID'=>$campaign['APP_APPLICATION_ID']
                        )));
		}else{
		 $form->addDataSource(new HTML_QuickForm2_DataSource_Array(array(
							'startdate' => date('Y-m-d H:i:s'),
							'upload_enddate' => date('Y-m-d H:i:s'),
							'enddate' => date('Y-m-d H:i:s'),
							'winner_selectiondate' => date('Y-m-d H:i:s')
                        )));
		}
		/**/
		
		$form->addElement('hidden','gid');
		
		$form->addElement('static','','',array('content'=>'<b>Your Facebook Application Name/ID ?</b> <a href="'.site_url('admin/app/add').'">Add</a>'));
		$fb_options[''] = 'Select Apps Name';

		if($appIDrow = $this->db->get_results("SELECT APP_APPLICATION_ID, APP_APPLICATION_NAME 
											   FROM campaign_app",'ARRAY_A'))
		{
		 
			foreach($appIDrow as $conf){
			  $fb_options[$conf['APP_APPLICATION_ID']] = $conf['APP_APPLICATION_NAME'];
			}
		}
		
		$APP_APPLICATION_ID = $form->addElement('select','APP_APPLICATION_ID','',array('options'=>$fb_options));
		if(count($fb_options) <= 1) 
		$form->addElement('static','','',array('content'=>'<b style="color:red;">*All APPLICATION ID has been registered please create new one! Please go to <a href="'.site_url('admin/app/add').'">App</a> panel.</b>'));

		$APP_APPLICATION_ID->addRule('required', 'Facebook App Name is required', null,HTML_QuickForm2_Rule::SERVER);				
		$APP_APPLICATION_ID->addRule('callback','Can\'t change this, someone already registered','callback_validateAppID_availability');
				
		
		
		$form->addElement('static','','',array('content'=>'<b>Title for your campaign ?</b>'));
		$stitle = $form->addElement('text','title',array('style'=>''));
		
		$form->addElement('static','','',array('content'=>'<b>What is your campaign all about ?</b>'));
		$sdescription = $form->addElement('textarea','description',array('class'=>'mceNoEditor'));
		
/* 		$allowed_maxfilesize = 1024*1024;
		$allowed_mimetype = 'image/gif,image/jpeg,image/pjpeg,image/png';

		$form->addElement('static','','',array('content'=>'<b>Upload Header Image for your Campaign ? (image width will be resize to 400px)</b>'));
		$r_file = $form->addElement('file','image_header_uploadfile','size="80"');
		$r_file->addRule('mimetype', 'Image is not valid file type', explode(',',$allowed_mimetype),HTML_QuickForm2_Rule::SERVER);
		$r_file->addRule('maxfilesize', 'Image filesize is exceeded ', $allowed_maxfilesize,HTML_QuickForm2_Rule::SERVER);
		
		if($gid){
			$form->addElement('static','','',array('content'=>'<img src="'.site_url('image/campaign')."?src=".$campaign['image_header'].'">'));
		} */
			
		$date_set = $gid ? array('format'=>'dFY His','maxYear'=>date('Y')) : array('format'=>'dFY His','minYear'=>date('Y'),'maxYear'=>date('Y')+1);
		
		$form->addElement('static','','',array('content'=>'<b>When will your campaign will start ?</b>'));
		$startdate_group = $form->addElement('group');	 
		$startdate_group->addElement('date','startdate','',$date_set,'style="width:100px;"');
		$startdate_group->addRule('callback','Start Date with this Facebook App Name/ID cannot be Overlap with other Campaigns','callback_validateStartDate');
		
		$form->addElement('static','','',array('content'=>'<b>When will Upload end ?</b>'));
		$upload_enddate_group = $form->addElement('group');	 
		$upload_enddate_group->addElement('date','upload_enddate','',$date_set,'style="width:100px;"');
		
		$upload_enddate_group->addRule('callback','Date must be longer than start date','callback_validateUploadEndDate');
		
		$form->addElement('static','','',array('content'=>'<b>Judging and Announcement before campaign end ?</b>'));
		$winner_selectiondate_group = $form->addElement('group');	 
		$winner_selectiondate_group->addElement('date','winner_selectiondate','',$date_set,'style="width:100px;"');		
		$winner_selectiondate_group->addRule('callback','Date must be shorter or the same as end date','callback_validateWinnerDate');

		
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
		
		$allowed_media_fields = $form->addElement('hidden','allowed_media_fields',array('style'=>''))->setValue('media_source=Upload Content Here&media_description=It\'s About');
				
		$allowed_mimetype = $form->addElement('hidden','allowed_mimetype',array('style'=>''))->setValue('image/gif,image/jpeg,image/pjpeg,image/png');

		$form->addElement('static','','',array('content'=>'<b>Does User can only upload once ?</b>'));
		$media_has_uploadonce = $form->addElement('select','media_has_uploadonce','',array('options'=>array('1'=>'Yes','0'=>'No')));
		
		$form->addElement('static','','',array('content'=>'<b>Does the uploaded media has vote system ?</b>'));
		$media_has_approval = $form->addElement('select','media_has_vote','',array('options'=>array('1'=>'Yes','0'=>'No')));
			
		$form->addElement('static','','',array('content'=>'<b>Does the uploaded media need approval by admin before published ?</b>'));
		$media_has_approval = $form->addElement('select','media_has_approval','',array('options'=>array('1'=>'Yes','0'=>'No')));
		
		$form->addElement('static','','',array('content'=>'<b>Does media use Facebook Comments (Social Plugin) ?</b>'));
		$media_has_fbcomment = $form->addElement('select','media_has_fbcomment','',array('options'=>array('1'=>'Yes','0'=>'No')));
		
		$form->addElement('static','','',array('content'=>'<b>Does media use Facebook Like (Social Plugin) ?</b>'));
		$media_has_fblike = $form->addElement('select','media_has_fblike','',array('options'=>array('1'=>'Yes','0'=>'No')));
		
		$form->addElement('static','','',array('content'=>'<b>Define your Campaign Rules/FAQ ?</b>'));
		$campaign_rules = $form->addElement('textarea','campaign_rules',array('style'=>'height:400px','id'=>'campaign_rules'));
		
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
			extract($data['winner_selectiondate']);
			$data['winner_selectiondate'] = $Y.'-'.$F.'-'.$d.' '.$H.':'.$i.':'.$s;				

			
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
			
/* 			if ($data['image_header_uploadfile']['error'] == UPLOAD_ERR_OK) {
				$tmp_name = $data['image_header_uploadfile']["tmp_name"];
				$time = md5(uniqid(rand(), true).time());
				$image = resizeImage( $tmp_name, CAMPAIGN_IMAGE_DIR."/".$time.".jpg", 400 , 'width' );					
				$data['image_header'] = $time.".jpg";
				unset($data['image_header_uploadfile']);
			}
			
			unset($data['image_header_uploadfile']); */
		   if($gid){
			   if($this->campaign_m->updateCampaign($data)){
				 $this->notify->set_message('success', 'Data has been successfuly updated.');
			   }else{
			    $this->notify->set_message('error', 'Data has failed to be updated.');
			   }
		   }else{
		       unset($data['gid']);
			   if($this->campaign_m->addCampaign($data)){
				 $this->notify->set_message('success', 'Data has been successfuly submitted.');
			   }else{
				 $this->notify->set_message('error', 'Data has failed to be submitted.');
			   }			   
		   }
		    //$form->removeChild($r_file);
			$form->removeChild($button);
			$form->toggleFrozen(true);
		}
		
			
		$renderer = HTML_QuickForm2_Renderer::factory('default');		
		$form_layout = $form->render($renderer);
		return $form_layout;
   }
   
 }