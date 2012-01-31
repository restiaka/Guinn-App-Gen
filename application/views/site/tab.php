<?php $CI = &get_instance();?>
<?php $this->load->view('site/header'); //Begin HTML ?>

<?php //$this->load->view('site/menu'); //menu HTML ?>

    

  <div class="topimage"><img src="<?=base_url()?>assets/site/images/logo.jpg" width="400" height="204" alt="Guinness World Series of Pool 2011" /></div>

  

    <!-- CONTAINER VIDEO -->
<div class="cnt_vid_home">

        	<p class="gold">Contoh video Trick</p><br/>
        	<!-- VID LEFT -->
            	<div class="cnt_vidhome_left"><img src="<?=base_url()?>assets/site/images/vid_1.jpg" /></div>
            <!-- /VID LEFT -->
            
            <!-- VID RIGHT -->
            	<div class="cnt_vidhome_rgt"><img src="<?=base_url()?>assets/site/images/vid_2.jpg" /></div>
            <!-- /VID RIGHT -->
		<div class="clear"></div>
        </div>
    <!-- /CONTAINER VIDEO -->
    <?php if(!$CI->facebook->getUser() || !isExtPermsAllowed()):?>
		
			<!-- CONTAINER FORM UNAUTHORIZED -->
				<div class="boxgrey">
					<table width="460" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td class="padform"><img src="<?=base_url()?>assets/site/images/tks_tosharevideo.gif" /></td>
						</tr>
						<tr>
						  <td class="padform">
						  <p>Only users that have Permission Authorized for this application may join. </p><br />
							<p class="goldthick">Please "Allow Pop Up" from this application to authorize the application.</p></td>
					  </tr>
						<tr>
						  <td class="padform_center">
						  <?php echo authorizeButton();?>
						  </td>
					  </tr>
						<tr>
						  <td class="padform_center">&nbsp;</td>
					  </tr>
					</table>
				</div>
			<!-- /CONTAINER FORM UNAUTHORIZED -->

	<?php else: ?>
	
	<!-- CONTAINER FORM AUTHORIZED -->
    <div class="boxgrey">
        	<table width="460" border="0" cellspacing="0" cellpadding="0">
  				<tr>
   				  <td>&nbsp;</td>
  				</tr>
  				<tr>
  				  <td class="padform">
                  		<div class="boxtext_lft"><img src="<?=base_url()?>assets/site/images/tks_howtoshare.gif"/></div>
                    	<div class="boxtext_rgt2"><p><a href="mekanisme.html">Mekanisme lengkap</a></p></div>
                  </td>
			  </tr>
  				<tr>
  				  <td><img src="<?=base_url()?>assets/site/images/howtoshare.gif" /></td>
			  </tr>
  				<tr>
  				  <td class="padform">&nbsp;</td>
			  </tr>
  				<tr>
  				  <td class="padform"><img src="<?=base_url()?>assets/site/images/tks_sharelinkkamu.gif" /></td>
			  </tr>

  				<tr>
  				  <td class="padform">
				  <?php if($html_form_upload):?>
				   <?=$html_form_upload?>
				  <?php else:?>
				   <?=implode("<br/>",$notification)?>
				  <?php endif;?>
				   
					</td>
				</tr>
  				<tr>
  				  <td class="padform">&nbsp;</td>
			  </tr>
			</table>
	</div>
    <!-- /CONTAINER FORM AUTHORIZED -->
	
	<?php endif;?>


  <?php $this->load->view('site/footer');//End HTML ?>
