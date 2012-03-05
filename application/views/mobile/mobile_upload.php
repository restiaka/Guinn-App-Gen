<?php echo $this->load->view('mobile/mobile_header',null,true); //Begin HTML ?>
<ul class="breadcrumb">
  <li><a href="<?=mobile_menu_url()?>">Home</a></li>
  <li>Upload</li>
</ul>
<div id="main">

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

</div>
<?php echo $this->load->view('mobile/mobile_footer',null,true); //End HTML ?>