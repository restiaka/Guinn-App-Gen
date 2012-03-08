<?php echo $this->load->view('site/header',$campaign,true); //Begin HTML ?>
<div class="main">
<div class="box box-l">

	<div class="inner">
  
	<div class="media-wrapper">
   	<div class="thumbnail">
    	<?php echo $media['media_container']?>
    </div>
  </div>
  
  <div class="info clearfix">
  	<div class="left-content">
     <fb:profile-pic uid="<?php echo $media['uid']?>" size="square"></fb:profile-pic>
      <div class="posted-by">Posted by</div>
      <div class="owner"><fb:name uid="<?php echo $media['uid']?>" firstnameonly="true"></div>
    </div>
    <div class="right-content">
	<?php echo isset($media['media_description']) ? nl2br($media['media_description']) : '';?>
	</div>
	</div>


</div>

</div>

</div>
	
<?php echo $this->load->view('site/footer',$campaign,true);//End HTML ?>

