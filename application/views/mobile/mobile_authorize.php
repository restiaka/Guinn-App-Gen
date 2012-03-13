<?php echo $this->load->view('mobile/mobile_header',$campaign,true); //Begin HTML ?>
<div class="main">
	<div class="main-banner">
	 <div style="margin-top:20px;text-align:center;">
	   Only Authorized user can use this application.<br/><br/>
		<?php echo authorizeButton("Please Login/Authorize App",$redirectURL);?>	
	 </div>		
	</div>
</div>
<?php echo $this->load->view('mobile/mobile_footer',$campaign,true); //Begin HTML ?>