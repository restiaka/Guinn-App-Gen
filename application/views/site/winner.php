<?php echo $this->load->view('site/header',$campaign,true); //Begin HTML ?>
<?php $CI = & get_instance(); $CI->load->model('media_m');?>
<div class="main">
<div class="box box-l">
	<div class="inner">
	<h2>THE WINNERS</h2>
  <?php if(isset($media) && !empty($media)):?>
  <ul class="gallery-list center">
   <?php foreach($media as $m):?>
	<li>
      <div class="thumbnail">
        <a href="<?php echo menu_url('media').'/?m='.$m['media_id']?>" title="See detail">
          <?php echo $CI->media_m->showMedia($m);?>
          <span class="see-more"><i class="button">See detail</i></span>
        </a>
      </div>
      <div class="owner"><fb:name uid="<?php echo $m['uid']?>" firstnameonly="true" /></div>
    </li>
	<?php endforeach;?>
  </ul>
  <?php endif;?>

</div>
</div>
</div>
	
<?php echo $this->load->view('site/footer',$campaign,true);//End HTML ?>