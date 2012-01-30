<?php
echo "<pre>";
/*
$url_meta = parse_url("http://www.youtube.com/watch?v=B37wW9CGWyY");
$url_meta['host'] = str_replace("www.","",$url_meta['host']);
print_r($url_meta);
parse_str($url_meta['query'],$o);
print_r($o);
*/
/*
$ok = load::l('facebook')->api("/1048572455137/picture");
print_r($ok);
*/

echo load::m('media_m')->showMedia(2,false);



  
 
?>
