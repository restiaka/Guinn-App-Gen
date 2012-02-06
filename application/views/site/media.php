<?php $this->load->view('site/header'); //Begin HTML ?>
<?php $this->load->view('site/menu'); //menu HTML ?>
<?php $this->load->view('site/header_image'); //header image HTML ?>
  <!-- LATEST VIDEO SHARED -->
  		<div class="clear"></div>

    	<div class="boxvideoshared">
			<?php
			$CI = &get_instance();
			$CI->load->model('media_m');
			echo $CI->media_m->mediaContainer($media);
			?>
		</div>

	<!-- /LATEST VIDEO SHARED -->
	
<?php $this->load->view('site/footer');//End HTML ?>