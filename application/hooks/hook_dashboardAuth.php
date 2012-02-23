<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 function dashboardAuth()
 {
	  $CI = &get_instance(); 
	  if($CI->uri->segment(1) == 'admin'){
		  $CI->auth->start();
		  if(!$CI->auth->getAuth() && $CI->uri->segment(2) != 'login'){
				redirect("admin/login");
			}
	  }
 }
 
 function appAuth()
 { 
   $CI = &get_instance(); 
   $CI->load->model('app_m');
   $CI->load->model('setting_m','settings');
   
   if($CI->uri->segment(1) == 'campaign' || $CI->uri->segment(1) == 'mobile'){
		if($APP_APPLICATION_ID =  $CI->uri->segment(2)){
			if($rows = $CI->app_m->detailApp($APP_APPLICATION_ID)){
				foreach($rows as $k => $v) $CI->settings->set($k,$v);
				
				if($url = parse_url($rows['APP_FANPAGE'])){
				  $new_url = $url['scheme']."://".$url['host'].$url['path']."?sk=app_".$rows['APP_APPLICATION_ID'];
				 $CI->settings->set('APP_FANPAGE',$new_url);
				}
				$app_accesstoken = getAppAccessToken(array(
														'app_id'=> $rows['APP_APPLICATION_ID'],
														'app_secret'=> $rows['APP_SECRET_KEY']
													));
				if($app_accesstoken){
					$approw = getAppDetail($APP_APPLICATION_ID,$app_accesstoken);
				}
				if($approw){	
					$CI->settings->set('APP_CANVAS_PAGE','https://apps.facebook.com/'.$approw['namespace']);
					$CI->settings->set('APP_CANVAS_URL',$approw['canvas_url']);
					$CI->settings->set('APP_SECURE_CANVAS_URL',$approw['secure_canvas_url']);
					$CI->settings->set('APP_PAGE_TAB_URL',$approw['page_tab_url']);
					$CI->settings->set('APP_SECURE_PAGE_TAB_URL',$approw['secure_page_tab_url']);
					$CI->settings->set('APP_LINK',$approw['link']);
					$CI->settings->set('APP_LOGO_URL',$approw['logo_url']);
				}	
				
			}else{
			  die('UNREGISTERED APPLICATION');
			}
			
			/* echo "<pre>";
			print_r($CI->settings);
			exit; */
   
			
			 $CI->load->library('facebook', array('appId'=> $rows['APP_APPLICATION_ID'],
												  'secret'=> $rows['APP_SECRET_KEY']));		 
		  
			//GET FACEBOOK PAGE SIGNED REQUEST
			 $signed_request = $CI->facebook->getSignedRequest();
			 
			 
			//EXTRACT APP_DATA QUERY STRING AND DO ACTION
			 if(isset($signed_request['app_data']) && $signed_request['app_data']){   
			   list($mode,$value) = explode("|",$signed_request['app_data']);
			   switch($mode){
				case 'redirect' : redirect(site_url($value));exit;
								  break;
			   }
			 }
		}
   }
 }