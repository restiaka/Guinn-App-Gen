<?php $this->load->view('site/header'); //Begin HTML ?>
<?php $this->load->view('site/header_no_navigation'); //Begin HTML ?>
<style>.big-img img{width:480px;}</style>	
<div id="main">

<div class="main-header">
	<h2>Contest Gallery</h2>
</div>

<div class="gallery detail">

	<div class="big-img">
		<?php echo $media['media_container'];?>
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
	<tr><td colspan="2" align="left"></td></tr>
	</table>
  </div>
 
  <div class="comments clearfix">

  </div>
</div>
</div>	
	
	
<?php $this->load->view('site/footer');//End HTML ?>