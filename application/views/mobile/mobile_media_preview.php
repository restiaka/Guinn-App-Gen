<?php echo $this->load->view('mobile/mobile_header',$campaign,true); //Begin HTML 
$graph = mobile_getGraph($media['uid']);  ?>
<ul class="breadcrumb">
  	<li><a href="<?php echo mobile_menu_url('gallery');?>">Back</a></li>
</ul>

<div class="main" id="detail">
	<div class="media-wrapper clearfix">
   	<div class="thumbnail">
    	<?php echo $media['media_container']?>
    </div>
	</div><!--media-wrapper-->

  <ul class="info">    
    <li>
    	<div class="uploader">
        <a href="#"><img src="https://graph.facebook.com/<?php echo $media['uid'] ?>/picture"></a>
        <div class="owner"><a href="http://www.facebook.com/profile.php?id=<?php echo $media['uid']; ?>"><?php echo $graph['name']; ?></a></div>
	    </div>
   	</li>

    <li>
    	<div class="media-info">
        <p class="about-media">About photo:</p>
        <p><?php echo isset($media['media_description']) ? nl2br($media['media_description']) : '';?></p>
	    </div>
    </li>
  </ul>

</div>
<?php echo $this->load->view('mobile/mobile_footer',$campaign,true); //Begin HTML ?>

