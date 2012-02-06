<?php
 function requireLogin($redirect_uri=''){
   $CI = &get_instance();
   $CI->load->library('facebook');
   $CI->load->model('setting_m');
  
  $facebook = $CI->facebook;
  //Get Login Url for redirection if user not yet authorized your apps
   $loginUrl = $facebook->getLoginUrl(array(
											'scope' => $CI->setting_m->get('APP_EXT_PERMISSIONS'),
											'fbconnect' => 0,
											'canvas' => 1,
											 'redirect_uri' => $redirect_uri
											));
  //Check for facebook session , redirect to Login Url for unauthorized user
	if (!$facebook->getUser()) {
	   echo "<script>window.top.location.href = '$loginUrl';</script>";
	   echo "<a href='$loginUrl' style='font-weight:bold;font-size:15px;'>Click here if you're not redirected</a>";
	   exit;
	}
 }			
 
 function getAppAccessToken($app_config = array()){
    $parameter = array('client_id'   => $app_config['app_id'],
						'client_secret' => $app_config['app_secret'],
						'grant_type'    => 'client_credentials');
	try{
			$request = graph_request('/oauth/access_token', 'GET',$parameter,true,false);
			parse_str($request);
		} catch (Exception $e){ die($e); }
		
	$request = $request ? $access_token : NULL;	
	return $request;						 
 }
 
 
 /*
 *
 *	http://developers.facebook.com/docs/reference/api/application/
 *
 *
 */
 function getAppDetail($appid,$app_accesstoken,$fields = array()){
 
  if(!$fields){
	$fields = array('id','name','link','canvas_name','namespace','logo_url','restrictions',
			   'app_domains','canvas_url','contact_email','creator_uid','page_tab_default_name',
			   'page_tab_url','privacy_policy_url','secure_canvas_url','secure_page_tab_url',
			   'website_url');
  }
 
   $parameter = array( 'fields' 	  => implode(',',$fields),
					   'access_token' => $app_accesstoken );
 	$request = graph_request('/'.$appid, 'GET',$parameter,true,false);
	return json_decode($request,true);
}

function appToPage_dialog(){
// http://www.facebook.com/dialog/pagetab?app_id=YOUR_APP_ID&next=YOUR_URL
}
 
 function graph_request($path,$method = "POST",$args = array(),$ssl = true,$json_decode = true){
   $ch = curl_init();
   $domain = "graph.facebook.com";
   $method = strtoupper($method);
   $url = $ssl ? "https://".$domain.$path : "http://".$domain.$path;
   
    if($method == 'POST'){ 
		curl_setopt($ch, CURLOPT_POST, true); 
	}elseif($method == 'GET'){
		curl_setopt($ch, CURLOPT_HTTPGET, true);
	}elseif($method == 'DELETE'){
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_HTTPGET, true);
	}
	 
    if($args && $method == 'POST')
	 curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($args, null, '&'));
	elseif($args && $method == 'GET')
     $url .= '?'.http_build_query($args, null, '&'); 	

	curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
   	
	curl_setopt($ch, CURLOPT_URL, $url);
	
	$result = curl_exec($ch);
	if ($result === false) {
      curl_close($ch);
	  return curl_error($ch); 
    }
	curl_close($ch);
	
	return $json_decode ? json_decode($result,true) : $result;
   }
 
 
 function isAppUser($uid)
 {
     $CI = &get_instance();
   $CI->load->library('facebook');
  
  $facebook = $CI->facebook;
      try{
			return $facebook->api(array('method'=>'users.isAppUser','uid'=>$uid));
		 } catch (Exception $e){
		   return false;//Got an exception of invalid OAUTH 2.0 token    
		 }		 									
 }
 
 
 /*
  * 
  * Commonly used for mobile authentication 
  *
  */
 function graphRequireLogin($redirect_uri,$display = 'popup') 
 {
     $CI = &get_instance();
   $CI->load->library('facebook');
   $CI->load->model('setting_m');
  
  $facebook = $CI->facebook;
  
    $code = $_REQUEST["code"];
	$dialog_options = array ( 
	                   'client_id' => $CI->setting_m->get('APP_APPLICATION_ID'),
					   'redirect_uri' => $redirect_uri,
					   'display' => $display,
					   'scope' => $CI->setting_m->get('APP_EXT_PERMISSIONS')
					 );
	$token_options	= array (
							    'client_id' => $CI->setting_m->get('APP_APPLICATION_ID'),
								'redirect_uri' => $redirect_uri,
								'client_secret' => $CI->setting_m->get('APP_SECRET_KEY'),
								'code' => $code
							  );
					 
	$dialog_url = "http://www.facebook.com/dialog/oauth?".http_build_query($dialog_options);		
	$token_url = "https://graph.facebook.com/oauth/access_token?".http_build_query($token_options);
   
   if(!$facebook->getUser()){
    if(empty($code)){ 
	  header("Location: ".$dialog_url); exit();  
	}elseif(isset($code) && !empty($code)){
	   $access_token = file_get_contents_curl($token_url);
	   parse_str($access_token);
	   if($access_token){
			$params = generateSessionVars($access_token);
			//SET COOKIE DIRECTLY TO BE USED BY PHP SDK
			//TO DO SET PERSISTENT DATA FOR PHPSDK 3
	   }
	}   
   }

 }
 
 function getFacebookUser($uid){
	$content = file_get_contents('https://graph.facebook.com/'.$uid);
	return json_decode($content);
 }
 


 function authorizeButton($text = 'Click here to Authorize'){
 $CI = &get_instance();
        $CI->load->library('facebook');
		$CI->load->model('setting_m');
    if(!$CI->facebook->getUser()){
	  return "<a onclick=\"fbDialogLogin('fb_login','".$CI->setting_m->get('APP_CANVAS_URL')."'); return false;\" class=\"fb_button fb_button_medium\"><span class=\"fb_button_text\">".$text."</span></a>";
	}
	return null;
 }
 
 function isExtPermsAllowed(){
  $CI = &get_instance();
   $CI->load->library('facebook');
   $CI->load->model('setting_m');
  
  $facebook = $CI->facebook;
  
  	  //Checking Permission needed
		 list($permissions) = $facebook->api(array('method'=>'fql.query',
												   'query'=>'SELECT '.$CI->setting_m->get('APP_EXT_PERMISSIONS').' 
															 FROM permissions 
															 WHERE uid = '.$facebook->getUser()
									));						
		foreach($permissions as $value){
		  if(!$value) { 
		   return false;
		  }
		}
		return true;
 }
 
 function generateSessionVars($accessToken)
 {
 $e = explode('|',$accessToken);
   $s = explode('-',$e[1]);
  
  
  $params = array(
					'uid'=>trim($s[1]),
					'access_token' => trim($accessToken)
				 ); 
  $params['sig'] = generateSignature($params,APP_SECRET);	

  return $params;				 
 }
 
 function setCookieFromSession($session){
 $CI = &get_instance();
   $CI->load->model('setting_m');
  $value = '"' . http_build_query($session, null, '&') . '"';
  setcookie('fbs_' . $CI->setting_m->get('APP_APPLICATION_ID'), $value, '/');
 }
 

  function generateSignature($params,$secret) {
 
  // work with sorted data
    ksort($params);

    // generate the base string
    $base_string = '';
    foreach($params as $key => $value) {
      $base_string .= $key . '=' . $value;
    }
    $base_string .= $secret;

    return md5($base_string);
  }
  

	function base64_url_decode($input) {
	  return base64_decode(strtr($input, '-_', '+/'));
	} 