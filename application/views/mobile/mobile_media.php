<?php echo $this->load->view('mobile/mobile_header',$campaign,true); //Begin HTML ?>
<style>
.quickform  input[type=submit] {
    background: url("<?php echo base_url()?>assets/site/img/gr2.png") repeat-x scroll 0 0 #919191;
    border: 1px solid #D5A658;
    color: #000000;
	font-size: 14px;
    font-weight: bold;
    padding: 10px 23px;
}

.quickform div.element {
    display: inline;
    float: right;
    padding: 0;
}
</style>

<div class="main">
<div class="box box-l">

	<div class="inner">
  
  <ul class="breadcrumb">
  	<li><a href="<?php echo menu_url();?>">Home</a></li>
    <li><a href="<?php echo menu_url('gallery');?>">Gallery</a></li>
    <li>Detail</li>
  </ul>
	
	<?php echo isset($plugin['votebutton']) ? $plugin['votebutton'] : "";?>	
  
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

  <div class="comments clearfix">
	<?php echo isset($plugin['fblike']) ? $plugin['fblike'] : ""?>
	<?php echo isset($plugin['fbcomment']) ? $plugin['fbcomment'] : ""?>
  </div>
</div>

</div>

</div>
<?php echo $this->load->view('mobile/mobile_footer',$campaign,true); //End HTML ?>