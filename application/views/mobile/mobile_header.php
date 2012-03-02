<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://ogp.me/ns#" xmlns:fb="http://www.facebook.com/2008/fbml">
  <head>
    
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>
      Guinness Contests
    </title>

	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.1.0-rc.1/jquery.mobile-1.1.0-rc.1.min.css" />
	<script src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
	<script src="http://code.jquery.com/mobile/1.1.0-rc.1/jquery.mobile-1.1.0-rc.1.min.js"></script>

<?php echo setMetaTags(); //Set Additional Meta Tag from registerMetaTags() | REQUIRED!!! ?>
<?php $this->load->view('fbjs'); //Set Facebook JS SDK | REQUIRED!!! ?>
  </head>
  <body>
    <?php $this->load->view('fbjs_async_load'); //Async Facebook js sdk Load (Always put after <body> tag!) | REQUIRED!!! ?>

    <div data-role="page" data-theme="e">
	<div data-role="header"></div>
	<div data-role="content">
	
	<div data-role="controlgroup" data-type="horizontal">
	<a href="<?=mobile_menu_url()?>" data-inline="true" data-role="button" data-icon="home" data-theme="b">Home</a>
	<?=mobile_logoutUrl()?>
	</div>
