<?php echo $this->load->view('mobile/mobile_header',$campaign,true); //Begin HTML ?>
<?php $CI = & get_instance(); $CI->load->model('media_m');?>
<div class="main">

<div class="box box-l">
	<div class="inner">
  
  <ul class="breadcrumb">
  	<li><a href="<?=mobile_menu_url()?>">Home</a></li>
    <li>Gallery</li>
  </ul>
  <?php if(isset($user_media)):?>
  <div class="user-panel">
    <div class="main-wrapper">
    <div class="inner clearfix">
    	<h3>My photo</h3>
      <div class="thumbnail">
        <a href="<?php echo menu_url('media').'/?m='.$user_media['media_id']?>" title="See detail">
           <?php echo $CI->media_m->showMedia($user_media);?>
          <span class="see-more"><i class="button">See detail</i></span>
        </a>
      </div>
      <div class="vote"><?php echo $user_media['media_vote_total']?> vote(s)</div>
      <div class="share"><a href="#" class="button big">Share to Friends</a></div>
    </div>
    <a href="#" class="hide">hide this -</a>
   </div>
  </div>
  <?php endif;?>
  <div class="sort-list clearfix">
  <form id="sortform" name="sortform" method="POST"></form>
  	<label>Sort by:</label>
  	<select name="orderby" onchange="document.getElementById('sortform').submit();">
      <option value="mostvote">Most Voted</option>
      <option value="latest">Latest Upload</option>
    </select> 
  </div>
  
  <?php if(isset($media) && !empty($media)):?>
  <ul class="gallery-list center">
   <?php foreach($media as $m):?>
   <li>
    	<a href="<?php echo menu_url('media').'/?m='.$m['media_id']?>" title="See detail">
	      <div class="thumbnail"><?php echo $CI->media_m->showMedia($m);?></div>
        <div class="vote"><?php echo $m['media_vote_total']?> votes</div>
        <div class="owner">Name</div>
        <p>The old man takes the poison now the widow makes the ...</p>
		</a>
    </li>
   	<?php endforeach;?>
  </ul>
  <div id="pagination"><?php echo $pagination['all']?></div>
  <?php else:?>
  <div style="text-align:center;">SORRY NO SUBMITTED YET</div>
  <?php endif;?>

</div>
</div>
</div>
<?php echo $this->load->view('mobile/mobile_footer',$campaign,true); //End HTML ?>