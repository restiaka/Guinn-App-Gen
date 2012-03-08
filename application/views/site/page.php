<?php echo $this->load->view('site/header',$campaign,true); //Begin HTML ?>
<div class="main" >
	<div class="box box-l">
	  <div class="inner">
		<?php echo $page_body?>
	  </div>
	</div>
</div>
<?php echo $this->load->view('site/footer',$campaign,true);//End HTML ?>