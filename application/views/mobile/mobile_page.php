<?php echo $this->load->view('mobile/mobile_header',$campaign,true); //Begin HTML ?>
<ul class="breadcrumb">
  	<li><a href="<?=mobile_menu_url()?>">Home</a></li>
</ul>
<div class="main" >
	<div class="box box-l">
	  <div class="inner">
		<?php echo $page_body?>
	  </div>
	</div>
</div>
<?php echo $this->load->view('mobile/mobile_footer',$campaign,true); //End HTML ?>