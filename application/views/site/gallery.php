<?php echo $this->load->view('site/header',null,true); //Begin HTML ?>
<?php echo $this->load->view('site/header_main_navigation',null,true); //Begin HTML ?>
<?php $CI = &get_instance(); $CI->load->model('media_m');?>
<div id="main">
	<div class="main-header">
		<h2>Contest Gallery</h2>
	</div>

	<div class="gallery">
	<?=$this->media_m->gallery($media,$pagination);?>
	</div>
</div>	  
<?php echo $this->load->view('site/footer',null,true);//End HTML ?>