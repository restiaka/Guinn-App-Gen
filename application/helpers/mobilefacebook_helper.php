<?php
 function mobile_loginUrl($redirect_uri=''){
   $CI = &get_instance();
   $CI->load->library('facebook');
   $CI->load->model('setting_m');
  
  $facebook = $CI->facebook;
  //Get Login Url for redirection if user not yet authorized your apps
   $loginUrl = $facebook->getLoginUrl(array(
											'scope' => $CI->setting_m->get('APP_EXT_PERMISSIONS'),
											 'redirect_uri' => $redirect_uri,
												'display' => 'popup'
											));

  //Check for facebook session , redirect to Login Url for unauthorized user
	if (!$facebook->getUser() || !mobile_isExtPermsAllowed()) {
	   //echo '<a href="'.$loginUrl.'" style="font-weight:bold;font-size:15px;">Click here to Authorize</a>';
	   echo '<a href="#" onclick="FB.login(null, {scope: \''.$CI->setting_m->get('APP_EXT_PERMISSIONS').'\'});">log js</a>';
	    
	   exit;
	}
 }			

 function mobile_logoutUrl(){
   $CI = &get_instance();
   $CI->load->library('facebook');
   $CI->load->model('setting_m');
  
   $facebook = $CI->facebook;
   //Get Logout Url for redirection if user not yet authorized your apps
   $logoutUrl = $facebook->getLogoutUrl();

   //Check for facebook session , redirect to Login Url for unauthorized user
   if ($facebook->getUser()) {
	   echo '<a href="'.$logoutUrl.'" data-inline="true" data-role="button" data-icon="delete" data-theme="a">Logout</a>';
	}
 }	

 function mobile_isExtPermsAllowed(){
  $CI = &get_instance();
   $CI->load->library('facebook');
   $CI->load->model('setting_m');
  
  $facebook = $CI->facebook;
  
  	  //Checking Permission needed
	  try{
		 list($permissions) = $facebook->api(array('method'=>'fql.query',
												   'query'=>'SELECT '.$CI->setting_m->get('APP_EXT_PERMISSIONS').' 
															 FROM permissions 
															 WHERE uid = '.$facebook->getUser()
									));	
		} catch(Exception $e){
			return false;
		}
		if(!$permissions) return false;
		foreach($permissions as $value){
		  if(!$value) { 
		   return false;
		  }
		}
		return true;
 }

 ?>