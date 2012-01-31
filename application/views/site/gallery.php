<?php $CI = &get_instance(); $CI->load->model('media_m');?>
<?php $this->load->view('site/header'); //Begin HTML ?>
<?php $this->load->view('site/menu'); //menu HTML ?>
<pre>
<?php
//print_r($media);
?>
    	<div class="boxvideoshared">	
        	<table style="width:100%" border="0" cellspacing="0" cellpadding="0">
  				<tr>
    				<td class="padform_left">
                    	<div class="boxtext_lft"><img src="<?=base_url()?>assets/site/images/tks_videogallery.gif" width="110" height="12" /></div>
                      <div class="boxtext_rgt"><a href="<?=menu_url()?>"><img src="<?=base_url()?>assets/site/images/button_ikutanshare.gif" /></a></div>
                    </td>
  				</tr>
  				<tr>
  				  <td class="padform_left">
				  <?=$this->media_m->gallery($media,$pagination);?>
                  </td>
			  </tr>
  				<tr>
  				  <td class="padform_left">
                  		
                        <div class="boxtext_rgt"><a href="<?=menu_url()?>"><img src="<?=base_url()?>assets/site/images/button_ikutanshare.gif" /></a></div>
                  
                  
                  
                  </td>
			  </tr>
			</table>
      </div>

<?php $this->load->view('site/footer');//End HTML ?>