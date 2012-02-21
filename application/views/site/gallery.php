<?php $CI = &get_instance(); $CI->load->model('media_m');?>
<?php $this->load->view('site/header'); //Begin HTML ?>
<div id="main">
	<div class="main-header">
		<h2>Contest Gallery</h2>
	</div>

	<div class="gallery">
	<?=$this->media_m->gallery($media,$pagination);?>
	</div>
</div>	  
<?php $this->load->view('site/footer');//End HTML ?>