<?php echo $this->load->view('mobile/mobile_header',null,true); //Begin HTML ?>
<?php $CI = &get_instance(); $CI->load->model('media_m');?>

<ul class="breadcrumb">
  <li><a href="<?=mobile_menu_url()?>">Home</a></li>
  <li>Gallery</li>
</ul>
<div class="top-panel clearfix">
  	<div class="user-panel-link">
    	<a href="#" class="button gold">View my photo</a>
   	</div>

    <div class="sort-list clearfix">
      <label><strong>Sort by:</strong></label>
      <select>
        <option value="volvo">Most Voted</option>
        <option value="saab">Latest Upload</option>
      </select> 
    </div>

  </div>
  
<div class="main" id="gallery">

  <?=$this->media_m->mobile_gallery($media,$pagination);?>
<!--
  <ul class="gallery-list">
    <li>
    	<a href="gallery-detail.php" title="See detail">
	      <div class="thumbnail">
          <img src="img/upload/thumb.png" width="100" height="100" alt="img">
  	    </div>
        <div class="vote">99 votes</div>
        <div class="owner">David Haselhof</div>
        <p>The old man takes the poison now the widow makes the ...</p>
      </a>
    </li>
  </ul>

  <ul class="pager">
	  <li class="next"><a href="#">Next<i></i></a></li>
    <li class="prev"><a href="#">Previous<i></i></a></li>
  </ul>
	
-->
</div>


<?php echo $this->load->view('mobile/mobile_footer',null,true); //End HTML ?>