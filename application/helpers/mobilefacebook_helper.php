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

 function mobile_getGraph($uid){
	$CI = &get_instance();
	$CI->load->library('facebook');
  
	$facebook = $CI->facebook;
	$graph = $facebook->api($uid);

	return $graph;
 }
 
 function update_status($campaign){
	$CI = &get_instance();
	$CI->load->library('facebook');
	$CI->load->model('media_m');
//	$_REQUEST['status'] = 'I just Upload a Photo for The Contest ';
	$facebook = $CI->facebook;
	
	$user = $facebook->getUser();
	
	$feed = array('name'=>ucfirst($campaign['title']),
									  'caption'=>'{*actor*} just upload a photo for The Contest',
									  'message'=>'Check this out ',
									  'description'=>$campaign['description'],
									  'picture'=>$user_media['media_thumb_url'],
									  'link'=>$CI->config->item('APP_CANVAS_PAGE'));
	//dg($feed);
	if ($user){
        //update user's status using graph api
        if (TRUE) {
            try {
                //$status = htmlentities($_REQUEST['status'], ENT_QUOTES) . $CI->config->item('APP_CANVAS_PAGE');
                $statusUpdate = $facebook->api('/me/feed', 'post',$feed);
            } catch (FacebookApiException $e) {
                d($e);
            }
            echo "Status Update Successfull. ";
            exit;
        }
    }
 }
 

 
 

function create_pagination($segment, $total, $limit, $uri_segment) {
    $CI = & get_instance();
    $CI->load->library('pagination');

    $config['base_url'] = site_url($segment);
    $config['total_rows'] = $total;
    $config['per_page'] = $limit;
    $config['uri_segment'] = $uri_segment;
	$config['cur_tag_open'] = '<span class="current">';
	$config['cur_tag_close'] = '</span>';
	$config['anchor_class'] = ' class="page larger" ';

    $CI->pagination->initialize($config);

    return $CI->pagination->create_links();
}
 ?>