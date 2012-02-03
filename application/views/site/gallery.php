<?php $CI = &get_instance(); $CI->load->model('media_m');?>
<?php $this->load->view('site/header'); //Begin HTML ?>
<?php $this->load->view('site/menu'); //menu HTML ?>
  <div class="topimage">
	<img src="<?=base_url()?>assets/site/images/logo.jpg" width="400" height="204" alt="Guinness World Series of Pool 2011" />
  </div>
    	<div class="boxvideoshared">	
        	<table style="width:100%" border="0" cellspacing="0" cellpadding="0">
  				<tr>
    				<td class="padform_left">
                    	<div class="boxtext_lft"><img src="<?=base_url()?>assets/site/images/tks_videogallery.gif" width="110" height="12" /></div>
                    </td>
  				</tr>
  				<tr>
  				  <td class="padform_left">
				  <?=$this->media_m->gallery($media,$pagination);?>
                  </td>
			  </tr>
  				<tr>
  				  <td class="padform_left">
                  		
                  
                  
                  
                  </td>
			  </tr>
			</table>
      </div>

<?php $this->load->view('site/footer');//End HTML ?>