<?php $CI = &get_instance(); $CI->load->model('media_m'); extract($CI->media_m->getPlugin($media));?>
<?php $this->load->view('site/header'); //Begin HTML ?>
<style>.big-img img{width:480px;}</style>	
<div id="main">

<div class="main-header">
	<h2>Contest Gallery</h2>
</div>

<div class="gallery detail">

	<div class="big-img">
		<?php echo $CI->media_m->showMedia($media,false);?>
		<div class="img-nav">
			<a href="#" id="prev-img">Previous Image</a>
			<a href="#" id="next-img">Next Image</a>
		</div>
	</div>
  
  <div class="info clearfix">
    <fb:name uid="<?php echo $media['media_owner']?>"></fb:name>
    <div class="posted-by">Posted by</div>
    <div class="owner"><fb:profile-pic uid="<?php echo $media['media_owner']?>"></fb:profile-pic></div>
  </div>
  <?php echo @$votebutton?>
  <div class="comments clearfix">
	<?php echo @$fblike?>
	<?php echo @$fbcomment?>
  </div>
</div>
</div>	
	
	
<?php $this->load->view('site/footer');//End HTML ?>