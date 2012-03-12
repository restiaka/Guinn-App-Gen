<?php echo $this->load->view('site/header',null,true); //Begin HTML ?>
<?php $CI = & get_instance(); $CI->load->model('media_m');?>
<div class="main">

<div class="box box-l">
	<div class="inner">
  
  <ul class="breadcrumb">
  	<li><a href="#">Home</a></li>
    <li>Gallery</li>
  </ul>
  <?php if(isset($media) && isset($user_media)):?>
  <div class="user-panel">
    <div class="main-wrapper">
    <div id="myphoto_toggle" class="inner clearfix">
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
    <a href="#" class="hide" onclick="$('.user-panel').toggle();">hide this -</a>
   </div>
  </div>
  <?php endif;?>

  
  <?php if(isset($media)):?>
    <form id="gform" name="gform" method="POST">
	<table align="right" style="margin-bottom:20px;"><tr>
	<Td>
	<?php if(isset($random_media)):?>
	<a href="<?php echo menu_url('media').'/?m='.$random_media['media_id']?>" class="button" style="font-weight:bold;color:#000000">Random <?php echo $campaign['allowed_media_type'] == "image" ? "Photo" : "Video"?></a>
   <?php endif;?>
	</td>
	<td>&nbsp;&nbsp;</td>
	<th style="font-size:11px">Find Friends: </th><td><input type="text" style="width:111px;border:1px #000000 solid;height:20px;" name="searchby" onenter="document.getElementById('gform').submit();"/></td>
	<td>&nbsp;&nbsp;</td>
	<th style="font-size:11px">Sort by: </th><td>	<select name="orderby" onchange="document.getElementById('gform').submit();">
      <option value="latest" <?php echo ($this->input->get_post('orderby', TRUE) == 'latest' ? "selected='selected'" : "")?> >Latest Upload</option>
	  <option value="mostvote" <?php echo ($this->input->get_post('orderby', TRUE) == 'mostvote' ? "selected='selected'" : "")?> >Most Voted</option>
    </select> </td>
	</tr></table>
	</form>
   <?php endif;?>
   
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
      <div class="vote"><?php echo $m['media_vote_total']?> votes</div>
      <div class="owner"><fb:name uid="<?php echo $m['uid']?>" firstnameonly="true" /></div>
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
<?php echo $this->load->view('site/footer',null,true);//End HTML ?>