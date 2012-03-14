<?php echo $this->load->view('mobile/mobile_header',$campaign,true); //Begin HTML ?>
<ul class="breadcrumb">
  <li><a href="<?=mobile_menu_url()?>">Home</a></li>
  <li>Registration</li>
</ul>

<div class="main">
<div class="box box-m">
	<div class="inner" data-role="none">
		<h2 class="title">Quick Registration</h2>
		<p>You are just 60 seconds away from chance to win 100 tablet.</p>
		<div><?php echo printNotification()?></div>
			<?php echo $html_form_register?>
	</div>
</div>

</div>
<?php echo $this->load->view('mobile/mobile_footer',$campaign,true); //End HTML ?>