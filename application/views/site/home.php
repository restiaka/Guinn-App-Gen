<?php echo $this->load->view('site/header',$campaign,true); ?>
<div class="main">
	<div class="main-banner">
	<?php $banner_main = isset($campaign['asset_facebook']['banner_main']['url']) ? $campaign['asset_facebook']['banner_main']['url'] : null?>
		<?php if(!$is_authorized):?>
		
			<?php echo authorizeBanner($banner_main,true,$redirectURL);?>
		<?php else:?>
			<?php echo authorizeBanner($banner_main,false,$redirectURL);?>
		<?php endif;?>	
	</div>
</div>
<?php echo $this->load->view('site/footer',$campaign,true);//End HTML ?>