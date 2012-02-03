<?php $this->load->view('site/header'); //Begin HTML ?>
<?php $this->load->view('site/menu'); //menu HTML ?>
  <div class="topimage">
	<img src="<?=base_url()?>assets/site/images/logo.jpg" width="400" height="204" alt="Guinness World Series of Pool 2011" />
  </div>
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