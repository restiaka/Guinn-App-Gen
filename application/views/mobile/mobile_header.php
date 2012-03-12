<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://ogp.me/ns#" xmlns:fb="http://www.facebook.com/2008/fbml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>
		Guinness Contests
    </title>
	<link rel="stylesheet" href="<?php echo base_url()?>assets/mobile/css/jquery.mobile-1.1.0-rc.1.min.css" />
	<link rel="stylesheet" href="<?php echo base_url()?>assets/mobile/css/guin.css" />
	<link href="<?php echo base_url()?>assets/mobile/css/mobile.css" type="text/css" rel="stylesheet" media="screen" />
	<script src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
	<script src="http://code.jquery.com/mobile/1.1.0-rc.1/jquery.mobile-1.1.0-rc.1.min.js"></script>
	<?php echo setMetaTags(); //Set Additional Meta Tag from registerMetaTags() | REQUIRED!!! ?>
	<?php $this->load->view('fbjs'); //Set Facebook JS SDK | REQUIRED!!! ?>
  </head>


	<?php if(isset($campaign['asset_mobile']['background_repeat'])):?>
		<?php $background = "background:url(".$campaign['asset_mobile']['background_repeat']['url'].") repeat ".@$campaign['asset_mobile']['background_repeat']['bgcolor']?>
	<?php elseif(isset($campaign['asset_mobile']['background_norepeat'])):?>
		<?php $background = "background:url(".$campaign['asset_mobile']['background_norepeat']['url'].") no-repeat ".@$campaign['asset_mobile']['background_norepeat']['bgcolor']?>
	<?php else:?>
		<?php $background = "background:#000000"?>
	<?php endif;?>
  
  <body style="<?php echo $background?>" >
    <?php $this->load->view('fbjs_async_load'); //Async Facebook js sdk Load (Always put after <body> tag!) | REQUIRED!!! ?>

	<div data-role="page" data-theme="a">
	<div id="container" data-role="content">
	<div id="top-banner">
		<?php if(isset($campaign['asset_mobile']['banner_header'])):?>
			<img src="<?php echo $campaign['asset_mobile']['banner_header']['url']?>"/>
		<?php endif;?>
	</div>