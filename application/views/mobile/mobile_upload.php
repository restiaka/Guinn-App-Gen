<?php $this->load->view('mobile/mobile_header'); //Begin HTML ?>
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
<?php $this->load->view('mobile/mobile_footer'); //End HTML ?>