<?php echo $this->load->view('site/header',null,true); //Begin HTML ?>
<?php $CI = & get_instance(); $CI->load->model('media_m');?>
<div class="main">

<div class="box box-l">
	<div class="inner">
  
  <ul class="breadcrumb">
  	<li><a href="#">Home</a></li>
    <li>Gallery</li>
  </ul>
  
  <div class="user-panel">
    <div class="main-wrapper">
    <div class="inner clearfix">
    	<h3>My photo</h3>
      <div class="thumbnail">
        <a href="gallery-detail.php" title="See detail">
          <img src="img/upload/thumb.png" width="100" height="100" alt="img">
          <span class="see-more"><i class="button">See detail</i></span>
        </a>
      </div>
      <div class="vote">99 votes</div>
      <div class="share"><a href="#" class="button big">Share to Friends</a></div>
    </div>
    <a href="#" class="hide">hide this -</a>
  </div>
  </div>
  
  <div class="sort-list clearfix">
  	<label>Sort by:</label>
  	<select>
      <option value="volvo">Most Voted</option>
      <option value="saab">Latest Upload</option>
    </select> 
  </div>
  
  <?php if(isset($media) && !empty($media)):?>
  <ul class="gallery-list center">
   <?php foreach($media as $m):?>
	<li>
      <div class="thumbnail">
        <a href="<?php echo menu_url('media').'/?m='.$m['media_id']?>" title="See detail">
          <?php $this->media_m->showMedia($row)?>
          <span class="see-more"><i class="button">See detail</i></span>
        </a>
      </div>
      <div class="vote"><?php echo $m['media_vote_total']?> votes</div>
      <div class="owner"><fb:name uid="<?php echo $m['uid']?></div>
    </li>
	<?php endforeach;?>
  </ul>
  
  <?php else:?>
  <div style="text-align:center;">SORRY NO SUBMITTED YET</div>
  <?php endif;?>

<!--<ul class="pagination">
<li>&laquo; Previous</li>
<li class="active">1</li>
<li><a href="#">2</a></li>
<li><a href="#">3</a></li>
<li><a href="#">4</a></li>
<li><a href="#">Next &raquo;</a></li>
</ul>-->
<div id="pagination"><?php echo $pagination['all']?></div>
</div>
</div>
</div>
<?php echo $this->load->view('site/footer',null,true);//End HTML ?>