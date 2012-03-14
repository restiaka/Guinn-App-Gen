<?php echo $this->load->view('mobile/mobile_header',$campaign,true); //Begin HTML ?>
<?php $CI = & get_instance(); $CI->load->model('media_m');?>
<ul class="breadcrumb">
  	<li><a href="<?=mobile_menu_url()?>">Home</a></li>
	<li>The Winner</li>
</ul>
<div class="main" id="gallery">

<div>
	<div class="inner">
	  <?php if(isset($media) && !empty($media)):?>
	  <ul class="gallery-list center">
	   <?php foreach($media as $m):?>
	   <li>
			<a href="<?php echo mobile_menu_url('media').'/?m='.$m['media_id']?>" title="See detail">
			  <div class="thumbnail"><?php echo $CI->media_m->showMedia($m);?></div>
			<div class="vote"><?php echo $m['media_vote_total']?> votes</div>
			<div class="owner"><?php $graph = mobile_getGraph($m['uid']); echo $graph['first_name']; ?></div>
			<p><?php echo truncateText($m['media_description'], 5); ?></p>
			</a>
		</li>
		<?php endforeach;?>
	  </ul>
	  <div id="pagination" data-role="controlgroup">
		<?php if(isset($pagination['linkTagsRaw']['next']['url'])): ?>
			<li><a href="<?php echo $pagination['linkTagsRaw']['next']['url']; ?>" data-role="button" data-icon="arrow-r" data-iconpos="right">Next</a></li>
		<?php endif; ?>
		<?php if(isset($pagination['linkTagsRaw']['prev']['url'])): ?>
			<li><a href="<?php echo $pagination['linkTagsRaw']['prev']['url']; ?>" data-role="button" data-icon="arrow-l">Prev</a></li>
		<?php endif; ?>
	  </div>
	  <?php else:?>
	  <div style="text-align:center;">SORRY NO SUBMITTED YET</div>
	  <?php endif;?>

</div>
</div>
	
<?php echo $this->load->view('mobile/mobile_footer',$campaign,true); //End HTML ?>