<?php echo $this->load->view('mobile/mobile_header',null,true); //Begin HTML ?>
<ul class="breadcrumb">
  <li><a href="<?=mobile_menu_url()?>">Home</a></li>
  <li>Login</li>
</ul>
    <?php if(!$is_authorized):?>	
		<!-- CONTAINER FORM UNAUTHORIZED -->
		<div class="box brown">
			<div class="authorize btn">
			<?php mobile_loginUrl('https://apps.facebook.com/guinnidgentestone/')?>
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

<?php echo $this->load->view('mobile/mobile_footer',null,true); //End HTML ?>
