<?php echo $this->load->view('site/header',$campaign,true); ?>
<div class="main" id="success">

<div class="box box-m">
  <div class="inner">
  <h2 class="title"><?php echo $message_title?></h2>
  <p class="center" id="success-text">
  <?php echo $message_text?>
  </p>
  
  <div class="center">
    <?php if(isset($anchor)):?>
  	<a href="<?php echo $anchor['url']?>" class="button gold big"><?php echo $anchor['text']?></a>
    <?php endif;?>
  </div>
  
  </div>
</div>

</div>
<?php echo $this->load->view('site/footer',$campaign,true); ?>