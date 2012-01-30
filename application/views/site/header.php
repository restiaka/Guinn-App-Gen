<html xmlns="http://www.w3.org/1999/xhtml"
      xmlns:og="http://ogp.me/ns#"
      xmlns:fb="http://www.facebook.com/2008/fbml">
 <head>
  <title>Facebook Apps Guinness Indonesia Photo Contest</title>
  <!-- BEGIN Theme Style -->
  <link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/site/style/style.css" />
  <!-- END Theme Style -->
  <?php echo setMetaTags(); //Set Additional Meta Tag from registerMetaTags() | REQUIRED!!! ?> 
  <?php $this->load->view('fbjs'); //Set Facebook JS SDK | REQUIRED!!! ?>
 </head>
 <body>
 <?php $this->load->view('fbjs_async_load'); //Async Facebook js sdk Load (Always put after <body> tag!) | REQUIRED!!! ?>
 
  <!-- CONTAINER ALL -->
<div class="containerall">