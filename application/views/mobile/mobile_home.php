<?php echo $this->load->view('mobile/mobile_header',$campaign,true); //Begin HTML ?>

<div class="main">

	<div class="main-banner">
		<?php $banner_main = isset($campaign['asset_mobile']['banner_main']) ? $campaign['asset_mobile']['banner_main'] : null?>
		<?php if(!$is_authorized):?>
			<?php echo authorizeBanner($banner_main,true,$redirectURL);?>
		<?php else:?>
			<?php echo authorizeBanner($banner_main,false,$redirectURL);?>
		<?php endif;?>
	</div>
	
	<ul data-role="listview" data-inset="true">
		<li><a href="<?=mobile_menu_url('gallery')?>" >Gallery</a></li>
		<li><a href="<?=mobile_menu_url('rules')?>" >Terms & Conditions</a></li>
		<li><a href="<?=mobile_menu_url('about')?>" >Winner Gallery</a></li>
	</ul>

</div> <!--end main-->

<?php echo $this->load->view('mobile/mobile_footer',$campaign,true); //End HTML ?>