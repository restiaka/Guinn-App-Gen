<?php echo $this->load->view('mobile/mobile_header',$campaign,true); //Begin HTML ?>
<ul class="breadcrumb">
  	<li><a href="<?=mobile_menu_url()?>">Home</a></li>
</ul>
<div class="main" id="success">

<div class="box box-m">
  <div class="inner">
  <h2 class="title"><?php echo $message_title?></h2>
  <p class="center" id="success-text">
  <?php echo $message_text?>
  </p>
  
  <div class="center">
  	<a href="<?php echo mobile_menu_url('gallery')?>" class="button gold big" >View Gallery</a>
  </div>
  
  </div>
</div>

</div>
<?php //echo isset($facebook_share_dialog) ? $facebook_share_dialog : "" ;?> 
<?php echo $this->load->view('mobile/mobile_footer',$campaign,true); //Begin HTML ?>