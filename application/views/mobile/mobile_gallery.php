<?php $this->load->view('mobile/mobile_header'); //Begin HTML ?>
<?php $CI = &get_instance(); $CI->load->model('media_m');?>

	<div class="main-header">
		<h2>Contest Gallery</h2>
	</div>

	<div class="gallery">
	<?=$this->media_m->gallery($media,$pagination);?>
	</div>

<?php $this->load->view('mobile/mobile_footer'); //End HTML ?>