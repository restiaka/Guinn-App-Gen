<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://ogp.me/ns#" xmlns:fb="http://www.facebook.com/2008/fbml">
  <head>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/site/style/css.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/site/style/quickform.css" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>
      Guinness Contests
    </title>
<script src="<?php echo base_url()?>assets/js/jquery-1.5.1.min.js" type="text/javascript"></script>
<script src="<?php echo base_url()?>assets/js/script.js" type="text/javascript"></script>
<?php echo setMetaTags(); //Set Additional Meta Tag from registerMetaTags() | REQUIRED!!! ?>
<?php $this->load->view('fbjs'); //Set Facebook JS SDK | REQUIRED!!! ?>
  </head>
  <body>
    <?php $this->load->view('fbjs_async_load'); //Async Facebook js sdk Load (Always put after <body> tag!) | REQUIRED!!! ?>
    <div id="container">
      <div id="header">
        <ul id="main-nav">
          <li>
            <a href="<?=menu_url()?>">Home</a>
          </li>
          <li>
            <a href="<?=menu_url('gallery')?>">Gallery</a>
          </li>
          <li>
            <a href="<?=menu_url('rules')?>">Rules/FAQ</a>
          </li>
        </ul>
		<div align="center">
       <img src="<?=site_url('image/campaign')."?src=".$campaign_info['image_header']?>" width="400" height="204" alt="Guinness World Series of Pool 2011" />
		</div>
	  </div>
 
