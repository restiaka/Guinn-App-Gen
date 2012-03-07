<?php echo $this->load->view('site/header',$campaign,true); ?>
<div class="main">
	<div class="main-banner">
	 <div style="margin-top:20px;text-align:center;">
	   Only Fans of <?php echo $fbpage['name']?> may join the Contest<br/><br/>
		<?php echo fblike($fbpage['link'],"show_faces='false' width='430' font=''"); ?>
	 </div>		
	</div>
</div>
<script>
	fbEnsureInit(function(){
		FB.Event.subscribe('edge.create',
			function(response) {
				window.top.location.href = '<?php echo $redirectURL?>'
			}
		);
	});
</script>
<?php echo $this->load->view('site/footer',$campaign,true);//End HTML ?>