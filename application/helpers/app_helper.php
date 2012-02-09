<?php
	function redirectTohttps()
	{
		if($_SERVER['HTTPS']!="on")
			{
				$redirect= "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
				header("Location:$redirect");
			}
	}
	
	function redirectToFanPage(){
	 $CI = &get_instance();
        $CI->load->model('setting_m');
		
	  $host = parse_url($_SERVER['HTTP_REFERER'],PHP_URL_HOST);
	  //Strip Canvas Page Host URL
	  //ex : from -> http://apps.facebook.com/guinnidphotocontest/tab/209681662395432/media/?m=9
	  //     to -> tab/209681662395432/media/?m=9
	  $path = '/'.str_replace($CI->setting_m->get('APP_CANVAS_PAGE'),'',$_SERVER['HTTP_REFERER']);
	  $redirect_url = $CI->setting_m->get('APP_FANPAGE').'&app_data=redirect|'.$path;
	  if($host == "apps.facebook.com"){
		echo "<script>window.top.location.href = '$redirect_url';</script>";
		echo "<a href='$redirect_url' target='_top' style='font-weight:bold;font-size:15px;'>Click Here if you're not redirected!</a>";	
		exit;	
	  }
	  return false;
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
		 return $meta;
	}
	

	function menu_url($filename = NULL,$path_only = false)
	{
	 $CI = &get_instance();
	 $CI->load->model('setting_m');
      if($appid = $CI->setting_m->get('APP_APPLICATION_ID')){
	    if(!$path_only){
	     return $filename ? site_url("campaign/canvas/$appid/$filename") : site_url("campaign/canvas/$appid");
		}else{
		 return $filename ? "campaign/canvas/$appid/$filename" : "campaign/canvas/$appid";
		}
	  }else{
		return "#";
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
 
	function callback_validateUploadEndDate(){
	
	  extract($_POST['startdate']);
	  $o_startdate = new DateTime($Y.'-'.$F.'-'.$d.' '.$H.':'.$i.':'.$s); //mktime($H, $i, $s, $d, $F, $Y);
	  $startdate = $o_startdate->getTimestamp();
	 
	  extract($_POST['upload_enddate']);
	  $o_uploadenddate = new DateTime($Y.'-'.$F.'-'.$d.' '.$H.':'.$i.':'.$s); //mktime($H, $i, $s, $d, $F, $Y);
	  $uploadenddate = $o_uploadenddate->getTimestamp();
	  
	  
	  if($startdate == $uploadenddate) return false;
	  if($uploadenddate < $startdate) return false;
	  
	  return true;
	}

	function callback_validateEndDate(){
	  extract($_POST['enddate']);
	  $o_enddate = new DateTime($Y.'-'.$F.'-'.$d.' '.$H.':'.$i.':'.$s); //mktime($H, $i, $s, $d, $F, $Y);
	  $enddate = $o_enddate->getTimestamp();
	  
	  extract($_POST['upload_enddate']);
	  $o_uploadenddate = new DateTime($Y.'-'.$F.'-'.$d.' '.$H.':'.$i.':'.$s); //mktime($H, $i, $s, $d, $F, $Y);
	  $uploadenddate = $o_uploadenddate->getTimestamp();

	 if($enddate == $uploadenddate) return false;
	  if($uploadenddate > $enddate) return false;
	  
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

	function resizeImage( $file, $thumbpath, $max_side , $fixfor = NULL ) {

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

				$thumbnail = imagecreatetruecolor( $image_new_width, $image_new_height);
				@ imagecopyresampled( $thumbnail, $image, 0, 0, 0, 0, $image_new_width, $image_new_height, $image_attr[0], $image_attr[1] );

				// move the thumbnail to its final destination
				/*
				if ( $type[2] == 1 ) {
					if (!imagegif( $thumbnail, $thumbpath ) ) {
						$error = 0;
					}
				}
				elseif ( $type[2] == 2 ) {
					if (!imagejpeg( $thumbnail, $thumbpath ) ) {
						$error = 0;
					}
				}
				elseif ( $type[2] == 3 ) {
					if (!imagepng( $thumbnail, $thumbpath ) ) {
						$error = 0;
					}
				}*/
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
	
	$data = curl_exec($ch);
	curl_close($ch);
	
	return $data;
   }	