<?php extract(load::c('home_c')->getOutput()); //Load and extract controller variables ?>

<?php load::v('header'); //Begin HTML ?>

	  <?php $already = false;//$db->get_var("SELECT video_id from video WHERE fbid = ".$facebook->getUser());?>
		<?php if((count($notify)<=0 && isset($post['submitvideo'])) || $already):?>
		 <!-- TEXT TOP -->
    	<div class="toptext">
       	  <h3 class="gold_h3">Terima kasih kamu sudah menshare video ini !</h3>
		  <h4 class="greywhite2">Cari vote sebanyak-banyaknya dan share video kamu ke teman-teman kamu</h4>
        	<br /><br />
        	<h4 class="greywhite2">Klik link ini untuk mulai men-share ke teman-teman kamu</h4><br />
            <p><a href="#" style="color:#CFB177;font-weight:bold;" onclick="fbContestShare('<?php echo PAGE_CANVAS_URL?>','<?=APP_CALLBACK_URL?>images/GWSOPsmall100.jpg'); return false;">Lets Share The Trick!</a></p>
		</div>
    <!-- /TEXT TOP -->
		<?php else:?>
		  <!-- TEXT TOP -->
			<div class="toptext"><h3 class="greywhite">Ayo share trick permainan billiard kamu. Kamu upload ke <strong>You Tube</strong>,  masukin link videonya disini, cari vote yang banyak dari temen-temen kamu. Video yang votenya banyak dan juga sesuai dengan penilaian juri bakalan dapetin hadiah dari Guinness.</h3></div>
		<!-- /TEXT TOP -->
		<?php endif;?>
  

    <!-- CONTAINER VIDEO -->
<div class="cnt_vid_home">

        	<p class="gold">Contoh video Trick</p><br/>
        	<!-- VID LEFT -->
            	<div class="cnt_vidhome_left"><img src="<?=themeUrl()?>images/vid_1.jpg" /></div>
            <!-- /VID LEFT -->
            
            <!-- VID RIGHT -->
            	<div class="cnt_vidhome_rgt"><img src="<?=themeUrl()?>images/vid_2.jpg" /></div>
            <!-- /VID RIGHT -->
		<div class="clear"></div>
        </div>
    <!-- /CONTAINER VIDEO -->
    <?php if(!load::l('facebook')->getUser() || !isExtPermsAllowed()):?>
		
			<!-- CONTAINER FORM UNAUTHORIZED -->
				<div class="boxgrey">
					<table width="460" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td class="padform"><img src="<?=themeUrl()?>images/tks_tosharevideo.gif" /></td>
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
	<?php else:?>
	 
	 <?=load::m('form_m')->customer_register();?>

	
	
	<?php endif;?>
    
   

  <?php load::v('footer');//End HTML ?>