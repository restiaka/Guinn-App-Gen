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
   <table width="100%"><tr><td width="30%">
    <fb:profile-pic uid="<?php echo $media['uid']?>"></fb:profile-pic>
    <div class="posted-by">Posted by</div>
    <div class="owner"><fb:name firstnameonly="true" uid="<?php echo $media['uid']?>"></fb:name></div>
	</td><Td width="70%">
	<div style="margin-left:10px;">
	<?php echo @nl2br($media['media_description']);?>
	</div>
	</td>
	</tr>
	<tr><td colspan="2" align="left"><?php echo @$votebutton?></td></tr>
	</table>
  </div>
 
  <div class="comments clearfix">
	<?php echo @$fblike?>
	<?php echo @$fbcomment?>
  </div>
</div>
</div>	
	
	
<?php $this->load->view('site/footer');//End HTML ?>