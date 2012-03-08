<?php echo $this->load->view('site/header',null,true); ?>
<div class="main">
	<div class="main-banner">
	 <div style="margin-top:20px;text-align:center;">
	   Only Authorized user can use this application.<br/><br/>
		<?php echo authorizeButton("Please Login/Authorize App",$redirectURL);?>	
	 </div>		
	</div>
</div>
<?php echo $this->load->view('site/footer',null,true);//End HTML ?>