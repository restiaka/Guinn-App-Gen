<?php $this->load->view('site/header'); //Begin HTML ?>

<?php $this->load->view('site/menu'); //menu HTML ?>

    

  <div class="topimage">
	<img src="<?=base_url()?>assets/site/images/logo.jpg" width="400" height="204" alt="Guinness World Series of Pool 2011" />
  </div>

   <div class="cnt_vid_home">
        	<p class="gold">
			Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer eleifend lobortis velit ac sagittis. Curabitur at neque et ante bibendum tempor. Integer consequat ipsum dui, at varius purus. Suspendisse enim metus, dignissim sed placerat eget, condimentum et ante. Aliquam erat volutpat. Mauris eget nisl a ante egestas ullamcorper. Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
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
	
	<?php echo $html_form_register?>
	
	<?php else: ?>
	<!-- CONTAINER FORM AUTHORIZED -->
    <div class="boxgrey">
        	<table width="460" border="0" cellspacing="0" cellpadding="0">
  				<tr>
  				  <td class="padform">&nbsp;</td>
			  </tr>
  				<tr>
  				  <td class="padform">Share Yours Here!</td>
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
