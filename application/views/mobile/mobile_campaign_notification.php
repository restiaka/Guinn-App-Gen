<?php echo $this->load->view('mobile/mobile_header',$campaign,true); //Begin HTML ?>
<ul class="breadcrumb">
  	<li><a href="<?=mobile_menu_url()?>">Home</a></li>
</ul>
<div class="main" id="success">

<div class="box box-m">
  <div class="inner">
  <h2 class="title"><?php echo $message_title; ?></h2>
  <p class="center" id="success-text">
  <?php echo $message_description; ?>
  </p>
  
  <div class="center">
    <?php if(isset($anchor)):?>
  	<a href="<?php echo $anchor['url']?>" class="button gold big" ><?php echo $anchor['text']?></a>
    <?php endif;?>
  </div>
  
  </div>
</div>

</div>
<?php echo $this->load->view('mobile/mobile_footer',$campaign,true); //Begin HTML ?>