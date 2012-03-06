<?php echo $this->load->view('site/header',null,true); ?>
<div class="main">
	<div class="main-banner">
		<?php if(!$is_authorized):?>
			<?php echo authorizeBanner(base_url()."assets/site/img/banner/main-banner.png",true,$redirectURL);?>
		<?php else:?>
			<?php echo authorizeBanner(base_url()."assets/site/img/banner/main-banner.png",false,$redirectURL);?>
		<?php endif;?>	
	</div>
</div>
<?php echo $this->load->view('site/footer',null,true);//End HTML ?>