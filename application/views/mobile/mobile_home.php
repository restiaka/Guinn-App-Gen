<?php echo $this->load->view('mobile/mobile_header',$campaign,true); //Begin HTML ?>
<div class="main">

	<div class="main-banner">
		<?php $banner_main = isset($campaign['asset_mobile']['banner_main']['url']) ? $campaign['asset_mobile']['banner_main']['url'] : null?>
		<?php if(!$is_authorized):?>
			<?php echo authorizeBanner($banner_main,true,$redirectURL);?>
		<?php else:?>
			<?php echo authorizeBanner($banner_main,false,$redirectURL);?>
		<?php endif;?>
	</div>
	
	<ul data-role="listview" data-inset="true">
		<li><a href="<?=mobile_menu_url('gallery')?>" <?php echo mobile_prefetch(); ?>>Gallery</a></li>
		<?php if($campaign['on_judging'] && $campaign['winner_announced']):?>
		<li><a href="<?php echo mobile_menu_url('winner')?>">The Winner</a></li>
		<?php endif;?>
		<?php if(isset($campaign['pages'])):foreach($campaign['pages'] as $page):if($page['mobile']):?>
		<li><a href="<?php echo mobile_menu_url("page/{$page['id']}")?>" <?php echo mobile_prefetch(); ?>><?php echo $page['name']?></a></li>
		<?php endif;endforeach;endif;?>
	</ul>

</div> <!--end main-->
<?php echo $this->load->view('mobile/mobile_footer',$campaign,true); //End HTML ?>