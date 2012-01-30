<?php
$src = addslashes(strip_tags($_GET['src']));
$GID = (int) addslashes(strip_tags($_GET['gid']));
if(!$src || !$GID) 
	header( "HTTP/1.0 404 Not Found" );

 // Where did you actually put your images?
  // Make sure that the path you put below ends with
  // a directory slash ("/"). The script below assumes it.
  $imagedir = CUSTOMER_IMAGE_DIR.$GID.'/';

  // What are the websites (hostnames) that can use this
  // image?
  // If your site can be accessed with or without the
  // "www" prefix, make sure you put both here. Do not put
  // any trailing slashes ("/") nor any "http://" prefixes.
  // Follow the example below.
  $url_meta = parse_url(load::m('setting_m')->get('SITE_URL'));
  $url_meta_no_www = str_replace("www.","",$url_meta['host']);

  $validprefixes = array (
    $url_meta['host'],
    $url_meta_no_www
  );

  // What is the main page of your website? Visitors will
  // be directed here if they type
  //   "http://www.example.com/chimage.php"
  // in their browser.
  $homepage = load::m('setting_m')->get('APP_FANPAGE');


  function isreferrerokay ( $referrer, $validprefixes )
  {
    $validreferrer = 0 ;
    $authreferrer  = current( $validprefixes );
    while ($authreferrer) {
      if (eregi( "^https?://$authreferrer/", $referrer )) {
        $validreferrer = 1 ;
        break ;
      }
      $authreferrer = next( $validprefixes );
    }
    return $validreferrer ;
  }

  //----------------------- main program -----------------------

  $image = $src;
  $referrer = getenv( "HTTP_REFERER" );

  if (isset($src)) {

    if (empty($referrer) ||
      isreferrerokay( $referrer, $validprefixes )) {

      $imagepath = $imagedir . $image ;

      $imageinfo = getimagesize( $imagepath );
      if ($imageinfo[2] == 1) {
        $imagetype = "gif" ;
      }
      elseif ($imageinfo[2] == 2) {
        $imagetype = "jpeg" ;
      }
      elseif ($imageinfo[2] == 3) {
        $imagetype = "png" ;
      }
      else {
        header( "HTTP/1.0 404 Not Found" );
        exit ;
      }

      header( "Content-type: image/$imagetype" );
      @readfile( $imagepath );

    }
    else {
      header( "HTTP/1.0 404 Not Found" );
    }
  }
  else {
    header( "Location: $homepage" );
  }
