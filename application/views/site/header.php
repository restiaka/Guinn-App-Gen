<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://ogp.me/ns#" xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
<link href="<?php echo base_url()?>assets/site/style/css.css" type="text/css" rel="stylesheet" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/site/style/quickform.css" />   
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Guinness Apps</title>
<script src="<?php echo base_url()?>assets/site/js/jquery-1.5.1.min.js" type="text/javascript"></script>
<script src="<?php echo base_url()?>assets/site/js/script.js" type="text/javascript"></script>
<?php echo setMetaTags(); //Set Additional Meta Tag from registerMetaTags() | REQUIRED!!! ?>
<?php $this->load->view('fbjs'); //Set Facebook JS SDK | REQUIRED!!! ?>
</head><body>
<?php $this->load->view('fbjs_async_load'); //Async Facebook js sdk Load (Always put after <body> tag!) | REQUIRED!!! ?>

<?php if(isset($campaign['asset_facebook']['background_repeat'])):?>
  <?php $background = "background:url(".$campaign['asset_facebook']['background_repeat']['url'].") repeat ".@$campaign['asset_facebook']['background_repeat']['bgcolor']?>
<?php elseif(isset($campaign['asset_facebook']['background_norepeat'])):?>
 <?php $background = "background:url(".$campaign['asset_facebook']['background_norepeat']['url'].") no-repeat ".@$campaign['asset_facebook']['background_norepeat']['bgcolor']?>
<?php else:?>
 <?php $background = "background:#000000"?>
<?php endif;?>

<div id="container" style="<?php echo $background?>">
<div id="top-banner">
<?php if(isset($campaign['asset_facebook']['banner_header']['url'])):?>
<img src="<?php echo $campaign['asset_facebook']['banner_header']['url']?>"/>
<?php endif;?>
</div>
<?php if(!isset($campaign['media_preview'])):?>
<div id="header">
<ul id="main-nav">
  <li><a href="<?php echo menu_url()?>">Home</a></li>
  <li><a href="<?php echo menu_url('gallery')?>">Gallery</a></li>
  <?php if($campaign['on_judging'] && $campaign['winner_announced']):?>
  <li><a href="<?php echo menu_url('winner')?>">The Winner</a></li>
  <?php endif;?>
  <?php if(isset($campaign['pages'])):foreach($campaign['pages'] as $page):if($page['facebook']):?>
	<li><a href="<?php echo $page['url']?>"><?php echo $page['name']?></a></li>
	<?php endif;endforeach;endif;?>
</ul>
</div>	
<?php endif;?>
