<?php $this->load->view('site/header'); //Begin HTML ?>

<?php $this->load->view('site/menu'); //menu HTML ?>

<?php $this->load->view('site/header_image'); //header image HTML ?>

   <div class="cnt_vid_home">
        	<p class="gold">
			<?=nl2br($campaign_info['description'])?>
			</p>
    </div>
  
    <?php if(!$is_authorized):?>
		
			<!-- CONTAINER FORM UNAUTHORIZED -->
				<div class="boxgrey">
					<table width="460" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td class="padform">Share Yours Here!</td>
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

	<?php elseif(!$customer_registered): ?>
	
    <div class="boxgrey">
        	<table width="460" border="0" cellspacing="0" cellpadding="0">
  				
			 <tr>
  				  <td class="padform"><span style="font-weight:bold;font-size:23px;color:#D9BB75">Register Here!</span></td>
			  </tr>

  				<tr>
  				  <td class="padform">
				  <?php if($html_form_register):?>
				   <?=$html_form_register?>
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

	<?php else: ?>
	<!-- CONTAINER FORM AUTHORIZED -->
    <div class="boxgrey">
        	<table width="460" border="0" cellspacing="0" cellpadding="0">
  				
			 <tr>
  				  <td class="padform"><span style="font-weight:bold;font-size:23px;color:#D9BB75">Share Yours Here!</span></td>
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
