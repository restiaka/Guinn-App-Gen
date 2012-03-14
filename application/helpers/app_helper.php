<?php
	function redirectTohttps()
	{
		if($_SERVER['HTTPS']!="on")
			{
				$redirect= "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
				header("Location:$redirect");
			}
	}
	
	
  	function printNotification(){
	  $CI = &get_instance();
	  $CI->load->library('notify');
	  return $CI->notify->output(true);
	}

	
	function registerMetaTags($meta)
	{
	    $CI = &get_instance();
        $CI->load->library('session');
  
	    $CI->session->set_userdata(array('APP_META'=>$meta));
	}
	
	function setMetaTags()
	{
	  $CI = &get_instance();
	  $CI->load->library('session');

		 $meta = $CI->session->userdata('APP_META');
		 //clear
		 $CI->session->unset_userdata('APP_META');
		 return $meta ? $meta : '';
	}
	
	function og_meta_set(array $meta){
   //title,type,url,image,site_name,admins,description
	$og_meta = '';
	if(count($meta)<1) return null;
	foreach($meta as $key => $value){
	   $og_meta .= '<meta property="og:'.$key.'" content="'.$value.'"/>'; 
	}
	getSession()->set('og_meta',$og_meta);
	}
 
 function og_meta_get(){
  if($og_meta = getSession()->get('og_meta')){
	getSession()->set('og_meta',null);
	return $og_meta;
  }
 }

	function menu_url($filename = NULL,$path_only = false)
	{
	 $CI = &get_instance();
	 $CI->load->model('setting_m');
      if($appid = $CI->setting_m->get('APP_APPLICATION_ID')){
	    if(!$path_only){
	     return $filename ? site_url("campaign/$appid/$filename") : site_url("campaign/$appid");
		}else{
		 return $filename ? "campaign/$appid/$filename" : "campaign/$appid";
		}
	  }else{
		return "#";
	  }
	}

	 function callback_validateAppID(){
	 $CI = &get_instance();
	   $appid = $CI->input->post('APP_APPLICATION_ID');
	   $secret = $CI->input->post('APP_SECRET_KEY');
		$token = getAppAccessToken(array('app_id'=>$appid,'app_secret'=>$secret));
		if($app_detail = getAppDetail($appid,$token)){
			if(!$app_detail['canvas_url'])return false;
			return true;
		}else{
			return false;
		}
	 }
 
	 function callback_isAppIDregistered(){
	   $CI = &get_instance();
	   $CI->load->library('ezsql_mysql');
	   $filter = '';
	   if($_POST['task'] == 'edit'){
		 $filter = " AND APP_APPLICATION_ID <> ".addslashes($CI->input->post('APP_APPLICATION_ID'));
	   }
	   $var = $CI->ezsql_mysql->get_var('SELECT APP_APPLICATION_ID FROM campaign_app WHERE APP_APPLICATION_ID = '.addslashes($CI->input->post('APP_APPLICATION_ID')).$filter);
	   return $var ? false : true;
	 } 
	 
	function callback_validateAppID_availability(){
		$CI = &get_instance();
	    $CI->load->library('ezsql_mysql');
		if($gid = $CI->input->post('gid')){
		 
		 $appid = $CI->ezsql_mysql->get_var("SELECT campaign_group.APP_APPLICATION_ID FROM campaign_group WHERE campaign_group.GID = ".addslashes($gid));
		 if(!$appid) return true;
		 if($appid == $CI->input->post('APP_APPLICATION_ID')) return true;
		 /*Alternate query*
		 $sql = "SELECT count(campaign_customer_fbauthorization.uid)
				   FROM campaign_customer_fbauthorization
				   WHERE campaign_customer_fbauthorization.APP_APPLICATION_ID = $appid";
		 **/
		 
		 $sql = "SELECT
					count(campaign_media_owner.uid)
					FROM
					campaign_media
					Inner Join campaign_media_owner ON campaign_media.media_id = campaign_media_owner.media_id
					Inner Join campaign_group ON campaign_media.GID = campaign_group.GID
					WHERE
					APP_APPLICATION_ID = $appid 
					AND
					campaign_group.GID = $gid";
		   
		  $total_registered = $CI->ezsql_mysql->get_var($sql);
		  return $total_registered > 0 ? false : true;
		}
		return true;
	}

	function callback_validateAppIDRangeDate(){
	 $CI = &get_instance();
	 $CI->load->library('ezsql_mysql');
	 $exclude_sql = $CI->input->post('gid') ? " AND campaign_group.GID <> ".addslashes($CI->input->post('gid'))." " : "";
	 
	 $sql = "SELECT enddate FROM campaign_group WHERE campaign_group.APP_APPLICATION_ID = ".addslashes($CI->input->post('APP_APPLICATION_ID')).$exclude_sql." ORDER BY campaign_group.enddate DESC LIMIT 1";
	 if($latest_enddate = $CI->ezsql_mysql->get_var($sql)){
	 
	  extract($CI->input->post('startdate'));
	  $o_startdate = new DateTime($Y.'-'.$F.'-'.$d.' '.$H.':'.$i.':'.$s); //mktime($H, $i, $s, $d, $F, $Y);
	  $startdate = $o_startdate->getTimestamp();
	  
	  $o_latest_enddate = new DateTime($latest_enddate); //mktime($H, $i, $s, $d, $F, $Y);
	  $xlatest_enddate = $o_latest_enddate->getTimestamp();
	 
	  if($startdate <= $xlatest_enddate) return false;
	  }
	  return true;
	}
	
	function callback_validateStartDate(){
	 $CI = &get_instance();
	 $CI->load->library('ezsql_mysql');
	 $exclude_sql = $CI->input->post('gid') ? " AND campaign_group.GID <> ".addslashes($CI->input->post('gid'))." " : "";
	 
		  extract($CI->input->post('startdate'));
		  $post_startdate = $Y.'-'.$F.'-'.$d.' '.$H.':'.$i.':'.$s;
		  $o_newdate = new DateTime($post_startdate); 
		  $newdate = $o_newdate->getTimestamp();
	 
	 $sql = "SELECT startdate,enddate 
			 FROM campaign_group 
			 WHERE campaign_group.APP_APPLICATION_ID = ".addslashes($CI->input->post('APP_APPLICATION_ID')).
					" AND campaign_group.startdate <= '".$post_startdate."' ".
					$exclude_sql.
			 " ORDER BY campaign_group.startdate DESC LIMIT 1";
	 if($date = $CI->ezsql_mysql->get_row($sql,'ARRAY_A')){
	 
		  
		  
		  $o_startdate = new DateTime($date['startdate']); 
		  $startdate = $o_startdate->getTimestamp();
		  
		  $o_enddate = new DateTime($date['enddate']); 
		  $enddate = $o_enddate->getTimestamp();
		 
		  if($startdate == $newdate){ 
			return false;
		  }elseif($startdate < $newdate){
			if($newdate <= $enddate)return false;
		  }
	  
	  }
	  return true;
	}
 
	function callback_validateUploadEndDate(){
		$CI = &get_instance();
	  extract($CI->input->post('startdate'));
	  $o_startdate = new DateTime($Y.'-'.$F.'-'.$d.' '.$H.':'.$i.':'.$s); //mktime($H, $i, $s, $d, $F, $Y);
	  $startdate = $o_startdate->getTimestamp();
	 
	  extract($CI->input->post('upload_enddate'));
	  $o_uploadenddate = new DateTime($Y.'-'.$F.'-'.$d.' '.$H.':'.$i.':'.$s); //mktime($H, $i, $s, $d, $F, $Y);
	  $uploadenddate = $o_uploadenddate->getTimestamp();
	  
	  
	  if($startdate == $uploadenddate) return false;
	  if($uploadenddate < $startdate) return false;
	  
	  return true;
	}
	
	

	function callback_validateEndDate_deprecated(){
	  $CI = &get_instance();
	  extract($CI->input->post('enddate'));
	  $o_enddate = new DateTime($Y.'-'.$F.'-'.$d.' '.$H.':'.$i.':'.$s); //mktime($H, $i, $s, $d, $F, $Y);
	  $enddate = $o_enddate->getTimestamp();
	  
	  extract($CI->input->post('upload_enddate'));
	  $o_uploadenddate = new DateTime($Y.'-'.$F.'-'.$d.' '.$H.':'.$i.':'.$s); //mktime($H, $i, $s, $d, $F, $Y);
	  $uploadenddate = $o_uploadenddate->getTimestamp();

	 if($enddate == $uploadenddate) return false;
	  if($uploadenddate > $enddate) return false;
	  
	  return true;
	}
	
	function callback_validateEndDate(){
	 $CI = &get_instance();
	 $CI->load->library('ezsql_mysql');
	 $exclude_sql = $CI->input->post('gid') ? " AND campaign_group.GID <> ".addslashes($CI->input->post('gid'))." " : "";
	 
	 		  extract($CI->input->post('startdate'));
		  $post_startdate = $Y.'-'.$F.'-'.$d.' '.$H.':'.$i.':'.$s;;
	 
	 $sql = "SELECT startdate,enddate 
			 FROM campaign_group 
			 WHERE campaign_group.APP_APPLICATION_ID = ".addslashes($CI->input->post('APP_APPLICATION_ID')).
					" AND campaign_group.startdate > '".$post_startdate."' ".
					$exclude_sql.
			 " ORDER BY campaign_group.startdate ASC LIMIT 1";
	 if($date = $CI->ezsql_mysql->get_row($sql,'ARRAY_A')){
	 
		  extract($CI->input->post('enddate'));
		  $o_newdate = new DateTime($Y.'-'.$F.'-'.$d.' '.$H.':'.$i.':'.$s); 
		  $newdate = $o_newdate->getTimestamp();
		  
		  $o_startdate = new DateTime($date['startdate']); 
		  $startdate = $o_startdate->getTimestamp();
		  
		  $o_enddate = new DateTime($date['enddate']); 
		  $enddate = $o_enddate->getTimestamp();
		  
	      extract($CI->input->post('winner_selectiondate'));
		  $o_winner_selectiondate = new DateTime($Y.'-'.$F.'-'.$d.' '.$H.':'.$i.':'.$s); //mktime($H, $i, $s, $d, $F, $Y);
		  $winner_selectiondate = $o_winner_selectiondate->getTimestamp();
		 
		  if($startdate == $newdate){ 
			return false;
		  }elseif($newdate >= $startdate){
			return false;
		  }elseif($newdate <= $winner_selectiondate){
		    return false;
		  }
	  
	  }
	  return true;
	}
	
	function callback_validateWinnerDate(){
	 $CI = &get_instance();
	  extract($CI->input->post('enddate'));
	  $o_enddate = new DateTime($Y.'-'.$F.'-'.$d.' '.$H.':'.$i.':'.$s); //mktime($H, $i, $s, $d, $F, $Y);
	  $enddate = $o_enddate->getTimestamp();
	  
	  extract($CI->input->post('upload_enddate'));
	  $o_uploadenddate = new DateTime($Y.'-'.$F.'-'.$d.' '.$H.':'.$i.':'.$s); //mktime($H, $i, $s, $d, $F, $Y);
	  $uploadenddate = $o_uploadenddate->getTimestamp();
	  
 	  extract($CI->input->post('winner_selectiondate'));
	  $o_winner_selectiondate = new DateTime($Y.'-'.$F.'-'.$d.' '.$H.':'.$i.':'.$s); //mktime($H, $i, $s, $d, $F, $Y);
	  $winner_selectiondate = $o_winner_selectiondate->getTimestamp(); 
		
	  if($winner_selectiondate < $uploadenddate) return false;	
	  if($winner_selectiondate > $enddate) return false;
	  
	  return true;
	}
 
	 function get_image_from_url($url) {
	 $CI = &get_instance();
        $CI->load->library('facebook');
		$url_meta = parse_url($url);
		$url_meta['host'] = str_replace("www.","",$url_meta['host']);
		switch ($url_meta['host'])
		{
			case "twitpic.com":
				return array(
							 'image' => "http://twitpic.com/show/large" . $url_meta['path'],
							 'thumb' => "http://twitpic.com/show/thumb" . $url_meta['path'],
							 'from' => "twitpic" 
							);		
				break;
			case "plixi.com":
				return array(
							 'image' => "http://api.plixi.com/api/tpapi.svc/imagefromurl?size=medium&url=" . $url,
							 'thumb' => "http://api.plixi.com/api/tpapi.svc/json/imagefromurl?size=thumbnail&url=" . $url,
							 'from' => "plixi"
							);		
				break;
			case "yfrog.com":
				 return array(
							 'image' => $url . ":iphone",
							 'thumb' => $url . ":small",
							 'from' => "yfrog"
							);			
				break;
			case "facebook.com":
				parse_str($url_meta['query'],$o);
				if(!$o['fbid']) return null;
				 $pic = $CI->facebook->api("/".$o['fbid']);
				 if(!$pic) return null;
				 return array(
								'image' => $pic['source'],
								'thumb' => $pic['picture'],
								'from' => "facebook"
							 );
				 break;
		}
	  }
  
   function get_video_from_url($url) {
		$url_meta = parse_url($url);
		$url_meta['host'] = str_replace("www.","",$url_meta['host']);
		switch ($url_meta['host'])
		{
			case "youtube.com" :
				parse_str($url_meta['query'],$o);
				if($url_meta['path'] != "/watch" || !$o['v']) return null;
					
				return array(
							 'video' => "http://www.youtube.com/v/".$o['v'],
							 'thumb' => "http://img.youtube.com/vi/".$o['v']."/2.jpg",
							 'from' => 'youtube'
							);	
				break;
			case "facebook.com" :
				parse_str($url_meta['query'],$o);
				if($url_meta['path'] != "/video/video.php" || !$o['v']) return null;
				
				return array(
							 'video' => "http://www.facebook.com/v/".$o['v'],
							 'thumb' => "https://graph.facebook.com/".$o['v']."/picture",
							 'from' => 'facebook'
							);		
				break;
		}
	}

	function truncateText($text, $limit){
	   $array = explode(" ", $text, $limit+1);
	   if (count($array) > $limit) unset($array[$limit]);
	   return implode(" ", $array)." ...";
	}

	function setDate($date, $datestring,$format = 'ISO')
    {

        if (preg_match('/^(\d{4})-?(\d{2})-?(\d{2})([T\s]?(\d{2}):?(\d{2}):?(\d{2})(Z|[\+\-]\d{2}:?\d{2})?)?$/i', $date, $regs)
            && $format != 'UNIX') {
            // DATE_FORMAT_ISO, ISO_BASIC, ISO_EXTENDED, and TIMESTAMP
            // These formats are extremely close to each other.  This regex
            // is very loose and accepts almost any butchered format you could
            // throw at it.  e.g. 2003-10-07 19:45:15 and 2003-10071945:15
            // are the same thing in the eyes of this regex, even though the
            // latter is not a valid ISO 8601 date.
            $year   = $regs[1];
            $month  = $regs[2];
            $day    = $regs[3];
            $thour   = isset($regs[5])?$regs[5]:0;
            $minute = isset($regs[6])?$regs[6]:0;
            $second = isset($regs[7])?$regs[7]:0;
            
            return mdate($datestring,mktime(0, 0, 0, $month  , $day, $year));
            
           
        } elseif (is_numeric($date)) {
            // UNIXTIME
            setDate(date("Y-m-d H:i:s", $date));
        } else {
            // unknown format
            $year   = 0;
            $month  = 1;
            $day    = 1;
            $hour   = 0;
            $minute = 0;
            $second = 0;
             return mdate($datestring,mktime(0, 0, 0, $month  , $day, $year));
        }
    }
    
    function format_date($value,$type="date"){
		 $fd = array(
		  '01' => 'Jan','02' => 'Feb','03' => 'Mar','04' => 'Apr','05' => 'May','06' => 'Jun',
		  '07' => 'Jul','08' => 'Aug','09' => 'Sep','10' => 'Okt','11' => 'Nov','12' => 'Dec'
		 );
		/*
		 $dd = array(
		  0 => 'Minggu',1 => 'Senin',2 => 'Selasa',3 => 'Rabu',
		  4 => 'Kamis',5 => 'Jumat',6 => 'Sabtu'
		 );*/
		 $dd = array(
		  0 => 'Sun',1 => 'Mon',2 => 'Tue',3 => 'Wed',
		  4 => 'Thu',5 => 'Fri',6 => 'Sat'
		 );
		
		 $st = explode(" ",$value);
		 $date = explode("-",$st[0]);
		 $time = explode(":",$st[1]);
		
				 if($type == 'date'){
				   $jd = cal_to_jd(CAL_GREGORIAN,intval($date[1]),intval($date[2]),intval($date[0]));
			   $day = jddayofweek($jd,0);
		
			  if($date[0] == '0000' && $date[1] == '00' && $date[2] == '00')
			  $d = "Date Undefined";
			  else
			  $d = $date[2]." ".$fd[$date[1]]." ".$date[0];
				 }
		
			  if($type == 'time'){
				if($time[0] == '00' && $time[1] == '00')
				$d = "";
				else
				$d = " ".$time[0]." : ".$time[1];
			  }
		
		  return $d;
	 }

	function GetAge($Birthdate)
	{
			// Explode the date into meaningful variables
			list($BirthYear,$BirthMonth,$BirthDay) = explode("-", $Birthdate);
			// Find the differences
			$YearDiff = date("Y") - $BirthYear;
			$MonthDiff = date("m") - $BirthMonth;
			$DayDiff = date("d") - $BirthDay;
			// If the birthday has not occured this year
			if ($MonthDiff < 0 && $DayDiff < 0)
			  $YearDiff--;
			  
			return $YearDiff;
	}

 								
	function quote_smart(& $value)
	{ global $db;
		// Stripslashes
		if (get_magic_quotes_gpc()) {
			$value = stripslashes($value);
		}
		// Quote if not a number or a numeric string
		//if (!is_numeric($value)) {
			$value = "'" . mysql_escape_string($value) . "'";
		//}
		return $value;
	}

	function dg(){ 
	  echo "<pre style='background-color:yellow;border:1px solid black;'>";	
	  $numargs = func_num_args();
		if( $numargs > 0){
			$arg_list = func_get_args();
			for ($i = 0; $i < $numargs; $i++) {
				print_r($arg_list[$i]);
				echo "</br>";
			}		
		}else{
			print_r('empty');
		}
	  echo "</pre>";
	}

	function resizeImage( $file, $thumbpath, $max_side = NULL , $fixfor = NULL, $cropped = false ) {

			// 1 = GIF, 2 = JPEG, 3 = PNG

		if ( file_exists( $file ) ) {
			$type = getimagesize( $file );

			if (!function_exists( 'imagegif' ) && $type[2] == 1 ) {
				$error = __( 'Filetype not supported. Thumbnail not created.' );
			}
			elseif (!function_exists( 'imagejpeg' ) && $type[2] == 2 ) {
				$error = __( 'Filetype not supported. Thumbnail not created.' );
			}
			elseif (!function_exists( 'imagepng' ) && $type[2] == 3 ) {
				$error = __( 'Filetype not supported. Thumbnail not created.' );
			} else {

				// create the initial copy from the original file
				if ( $type[2] == 1 ) {
					$image = imagecreatefromgif( $file );
				}
				elseif ( $type[2] == 2 ) {
					$image = imagecreatefromjpeg( $file );
				}
				elseif ( $type[2] == 3 ) {
					$image = imagecreatefrompng( $file );
				}

				if ( function_exists( 'imageantialias' ))
					imageantialias( $image, TRUE );

				$image_attr = getimagesize( $file );
				$max_side = $max_side ? $max_side : $image_attr[0]; 
				// figure out the longest side
			if($fixfor){
					if($fixfor == 'width'){
						$image_width = $image_attr[0];
					$image_height = $image_attr[1];
					$image_new_width = $max_side;

					$image_ratio = $image_width / $image_new_width;
					$image_new_height = $image_height / $image_ratio;
					}elseif($fixfor == 'height'){
					 $image_width = $image_attr[0];
					$image_height = $image_attr[1];
					$image_new_height = $max_side;

					$image_ratio = $image_height / $image_new_height;
					$image_new_width = $image_width / $image_ratio;	
					}
			}elseif($cropped){
			  $image_new_width = $max_side*2;
			  $image_ratio = $image_attr[0] / $image_new_width;
			  $image_new_height = $image_attr[1] / $image_ratio;
			  
			  $image_resized_crop = imagecreatetruecolor( $image_new_width, $image_new_height);
			  @imagecopyresampled( $image_resized_crop, $image, 0, 0, 0, 0, $image_new_width, $image_new_height, $image_attr[0], $image_attr[1] );

			  $cropX = intval((imagesx($image_resized_crop) - $max_side) / 2);
			  $cropY = intval((imagesy($image_resized_crop) - $max_side) / 2);
			 
			}else{
				if ( $image_attr[0] > $image_attr[1] ) {
					$image_width = $image_attr[0];
					$image_height = $image_attr[1];
					$image_new_width = $max_side;

					$image_ratio = $image_width / $image_new_width;
					$image_new_height = $image_height / $image_ratio;
					//width is > height
				} else {
					$image_width = $image_attr[0];
					$image_height = $image_attr[1];
					$image_new_height = $max_side;

					$image_ratio = $image_height / $image_new_height;
					$image_new_width = $image_width / $image_ratio;
					//height > width
				}
			}	

				
				if(!$cropped){
					$thumbnail = imagecreatetruecolor( $image_new_width, $image_new_height);
					@imagecopyresampled( $thumbnail, $image, 0, 0, 0, 0, $image_new_width, $image_new_height, $image_attr[0], $image_attr[1] );
				}elseif($cropped){
					$thumbnail = imagecreatetruecolor( $max_side, $max_side);
					@imagecopyresampled( $thumbnail, $image_resized_crop, 0, 0, $cropX, $cropY, $max_side, $max_side, $max_side, $max_side);				
				}
				
				if (!imagejpeg( $thumbnail, $thumbpath ) ) {
						$error = 0;
					}
			}
		} else {
			$error = 0;
		}

		if (!empty ( $error ) ) {
			return $error;
		} else {
			return $thumbpath;
		}
	}

	 
	 function recursive_listdir( $base ,$exclude='') {
		 $filelist = array();
		 $dirlist = array();

	   if(is_dir($base)) {
	   
			$dh = opendir($base);
			while (false !== ($dir = readdir($dh))) {
			
				if ($dir !== $exclude && $dir !== '.' && $dir !== '..' && is_dir($base .'/'. $dir) && strtolower($dir) !== 'cvs' && strtolower($dir) !== '.svn') {
					$subbase = $base .'/'. $dir;
					$dirlist[] = $subbase;
					$subdirlist = recursive_listdir($subbase,$exclude);
				}
			}
			closedir($dh);
		}

		return $dirlist;
	 }
	 
	 
	 function rm_all_dir($dir) {
		if(is_dir($dir)) {
			$d = @dir($dir);

			while ( false !== ( $entry = $d->read() ) ) {
				if($entry != '.' && $entry != '..') {
					$node = $dir.'/'.$entry;
					if(is_file($node)) {
						unlink($node);
					} else if(is_dir($node)) {
						rm_all_dir($node);
					}
				}
			}
			$d->close();

			rmdir($dir);
		}
		return true;
	}
	
	function list_files_dir($dir) {
	$files = array();
		if(is_dir($dir)) {
			$d = @dir($dir);

			while ( false !== ( $entry = $d->read() ) ) {
				if($entry != '.' && $entry != '..') {
					$node = $dir.'/'.$entry;
					if(is_file($node)) {
						$files[] = $node;
					}
				}
			}
			$d->close();
		}
		return $files;
	}

	function MakePath($base, $path='', $mode = NULL) {
		global $mosConfig_dirperms;

		// convert windows paths
		$path = str_replace( '\\', '/', $path );
		$path = str_replace( '//', '/', $path );
		// ensure a clean join with a single slash
		$path = ltrim( $path, '/' );
		$base = rtrim( $base, '/' ).'/';

		// check if dir exists
		if (file_exists( $base . $path )) return true;

		// set mode
		$origmask = NULL;
		if (isset($mode)) {
			$origmask = @umask(0);
		} else {
			if ($mosConfig_dirperms=='') {
				// rely on umask
				$mode = 0777;
			} else {
				$origmask = @umask(0);
				$mode = octdec($mosConfig_dirperms);
			} // if
		} // if

		$parts = explode( '/', $path );
		$n = count( $parts );
		$ret = true;
		if ($n < 1) {
			if (substr( $base, -1, 1 ) == '/') {
				$base = substr( $base, 0, -1 );
			}
			$ret = @mkdir($base, $mode);
		} else {
			$path = $base;
			for ($i = 0; $i < $n; $i++) {
				// don't add if part is empty
				if ($parts[$i]) {
					$path .= $parts[$i] . '/';
				}
				if (!file_exists( $path )) {
					if (!@mkdir(substr($path,0,-1),$mode)) {
						$ret = false;
						break;
					}
				}
			}
		}
		if (isset($origmask)) {
			@umask($origmask);
		}

		return $ret;
	}

	function get_filenames_basename($source_dir, $fext,$output = 'ARRAY_A',$check_dir = FALSE, $include_path=FALSE,$_recursion = FALSE)
	{
		static $_filedata = array();
				
		if ($fp = @opendir($source_dir))
		{
			// reset the array and make sure $source_dir has a trailing slash on the initial call
			if ($_recursion === FALSE)
			{
				$_filedata = array();
				$source_dir = rtrim(realpath($source_dir), DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
			}
			
			while (FALSE !== ($file = readdir($fp)))
			{ // echo $file;
				
				if (@is_dir($source_dir.$file) && strncmp($file, '.', 1) !== 0 && $check_dir === TRUE)
				{
					 get_filenames_basename($source_dir.$file.DIRECTORY_SEPARATOR, $output,$include_path, TRUE);
				}
				elseif (strncmp($file, '.', 1) !== 0)
				{  
				   preg_match("/(.*)\.".$fext."/i",$file,$match);
				   if($match[1])$file = $match[1];else continue;
				   	
				   if($output == 'ARRAY_A')
					$_filedata[$file] = ($include_path == TRUE) ? $source_dir.$file : $file;
				   elseif($output == 'ARRAY_N')
					$_filedata[] = ($include_path == TRUE) ? $source_dir.$file : $file;	
				   
				}
			}
			return $_filedata;
		}
		else
		{
			return FALSE;
		}
	}

   function file_get_contents_curl($url) {
	$ch = curl_init();
	
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Set curl to return the data instead of printing it to the browser.
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);

	$data = curl_exec($ch);
	curl_close($ch);
	
	return $data;
   }	