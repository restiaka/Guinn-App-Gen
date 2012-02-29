<?php $this->load->view('site/header'); //Begin HTML ?>
<?php $this->load->view('site/header_main_navigation'); //Begin HTML 
dg($custom_page_url);
?>
<div id="main">
	<div class="bigtext">
	<p><?=nl2br($campaign_info['description'])?></p>
	</div>
  
    <?php if(!$is_authorized):?>	
		<!-- CONTAINER FORM UNAUTHORIZED -->
		<div class="box brown">
			<div class="inner">
			<p>Only users that have permission Authorized for this application may join</p>
			<div class="gold"><p>Please "Allow Pop Up" from this application to authorize the application</p></div>

			<div class="authorize btn">
			<?php echo authorizeButton();?>
			</div>
			</div>
		</div>
		<!-- /CONTAINER FORM UNAUTHORIZED -->
	<?php elseif(!$customer_registered): ?>
		<!-- REGISTRATION FORM -->
		<div class="box brown">
		<div class="inner">
		<div align="center"><h1>Register to Join Contest</h1></div>
					  <?php if($html_form_register):?>
					   <?=$html_form_register?>
					  <?php else:?>
					   <?=implode("<br/>",$notification)?>
					  <?php endif;?>
		</div>
		</div>
		<!-- /REGISTRATION FORM -->
	<?php else: ?>
		<!-- CONTAINER FORM AUTHORIZED -->
		<div class="box brown">
			<div class="inner">
						  <?php if($html_form_upload):?>
						   <?=$html_form_upload?>
						  <?php else:?>
						   <?=implode("<br/>",$notification)?>
						  <?php endif;?>
			</div>
		</div>
		<!-- /CONTAINER FORM AUTHORIZED -->
	<?php endif;?>

</div>
<?php $this->load->view('site/footer');//End HTML ?>