<?php echo $this->load->view('mobile/mobile_header',null,true); //Begin HTML ?>

<div class="main">

	<div class="main-banner">
		<a href="<?=mobile_menu_url('login')?>"><img src="<?php echo base_url()?>assets/mobile/img/banner/main-banner.jpg" alt="Join now!" title="Join now!"></a>
	</div>
	<!--
	<ul id="main-nav">
	  <li><a href="<?=mobile_menu_url('gallery')?>" class="button black">Gallery<i></i></a></li>
	  <li><a href="<?=mobile_menu_url('rules')?>" class="button black">Terms & Conditions<i></i></a></li>
	  <li><a href="<?=mobile_menu_url('about')?>" class="button black">Winner Gallery<i></i></a></li>
	</ul>
	-->
	
	<ul data-role="listview" data-inset="true">
		<li><a href="<?=mobile_menu_url('gallery')?>" >Gallery</a></li>
		<li><a href="<?=mobile_menu_url('rules')?>" >Terms & Conditions</a></li>
		<li><a href="<?=mobile_menu_url('about')?>" >Winner Gallery</a></li>
	</ul>

</div> <!--end main-->

<?php echo $this->load->view('mobile/mobile_footer',null,true); //End HTML ?>
