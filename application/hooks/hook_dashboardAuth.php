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
   
   if($CI->uri->segment(2) == 'canvas' || $CI->uri->segment(2) == 'tab'){
		if($APP_APPLICATION_ID =  $CI->uri->segment(3)){
			if($rows = $CI->app_m->detailApp($APP_APPLICATION_ID)){
				foreach($rows as $k => $v) $CI->settings->set($k,$v);
			}else{
			  die('UNREGISTERED APPLICATION');
			}
			

			
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