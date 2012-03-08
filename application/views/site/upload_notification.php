<?php echo $this->load->view('site/header',$campaign,true); ?>
<div class="main" id="success">

<div class="box box-m">
  <div class="inner">
  <h2 class="title"><?php echo $message_title?></h2>
  <p class="center" id="success-text">
  <?php echo $message_text?>
  </p>
  
  <div class="center">
  	<a href="<?php echo menu_url('gallery')?>" class="button gold big">View Gallery</a>
  </div>
  
  </div>
</div>

</div>
<?php echo $this->load->view('site/footer',$campaign,true); ?>