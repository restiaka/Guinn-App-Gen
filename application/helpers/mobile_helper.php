<?php

	function mobile_menu_url($filename = NULL,$path_only = false)
	{
	 $CI = &get_instance();
	 $CI->load->model('setting_m');
      if($appid = $CI->setting_m->get('APP_APPLICATION_ID')){
	    if(!$path_only){
	     return $filename ? site_url("mobile/$appid/$filename") : site_url("mobile/$appid");
		}else{
		 return $filename ? "mobile/$appid/$filename" : "mobile/$appid";
		}
	  }else{
		return "#";
	  }
	}
	
	function mobile_prefetch()
	{
		$CI = &get_instance();
			if($prefetch = $CI->config->item('MOBILE_PREFETCH')){
				return 'data-prefetch';
			}else{
				return NULL;
			}
	}
	
	function themeUrl($theme_dir_name = NULL,$relative_path = TRUE) {
		if(!defined('THEME_DIR') && !$theme_dir_name) return null;
		
		if($theme_dir_name){
		 $basepath = str_replace(ROOT_DIR,'',VIEW_DIR);
		  return ($relative_path ? "" : SITE_URL).$basepath.$theme_dir_name."/";
		}else{
		  $basepath = str_replace(ROOT_DIR,'',THEME_DIR);
		  return ($relative_path ? "" : SITE_URL).$basepath;
		}
	}

?>
