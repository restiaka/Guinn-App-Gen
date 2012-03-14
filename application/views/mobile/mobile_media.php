<?php echo $this->load->view('mobile/mobile_header',$campaign,true); //Begin HTML ?>
<ul class="breadcrumb">
  	<li><a href="<?php echo mobile_menu_url();?>">Home</a></li>
    <li><a href="<?php echo mobile_menu_url('gallery');?>" <?php echo mobile_prefetch(); ?>>Gallery</a></li>
    <li>Detail</li>
</ul>

<div class="main" id="detail">

	<div class="media-wrapper clearfix">
   	<div class="thumbnail">
    	<?php echo $media['media_container']?>
    </div>
    <?php echo isset($plugin['votebutton_mobile']) ? $plugin['votebutton_mobile'] : "";?>
	</div><!--media-wrapper-->

	

  <ul class="info">
	<li>
    	<div class="vote"><?php echo $media['media_vote_total']?> votes</div>
    </li>
    
    <li>
    	<div class="uploader">
        <a href="#"><img src="https://graph.facebook.com/<?php echo $media['uid'] ?>/picture"></a>
        <div class="owner"><a href="<?php $graph = mobile_getGraph($media['uid']); echo $graph['link']; ?>"><?php $graph = mobile_getGraph($media['uid']); echo $graph['name']; ?></a></div>
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
<?php echo $this->load->view('mobile/mobile_footer',$campaign,true); //End HTML ?>