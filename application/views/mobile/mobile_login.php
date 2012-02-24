<?php $this->load->view('mobile/mobile_header'); //Begin HTML ?>
<div id="main">
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
			Thank You, You have successfully registered			
			<ul>
		  		<li>
          	  <a href="<?=mobile_menu_url('upload')?>">Contest Upload</a>
          	</li>
          	<li>
          	  <a href="<?=mobile_menu_url('gallery')?>">Gallery</a>
          	</li>
		    	<li>
          	  <a href="<?=mobile_menu_url('rules')?>">Rules/FAQ</a>
          	</li>
        	</ul>
			</div>
		</div>
		<!-- /CONTAINER FORM AUTHORIZED -->
	<?php endif;?>

</div>
<?php $this->load->view('mobile/mobile_footer'); //End HTML ?>
