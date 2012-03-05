<?php echo $this->load->view('site/header',null,true); //Begin HTML ?>
<?php echo $this->load->view('site/header_main_navigation',null,true); //Begin HTML ?>
<style>.big-img img{width:480px;}</style>	
<div id="main">

<div class="main-header">
	<h2>Contest Gallery</h2>
</div>

<div>
<?php echo $content?>
</div>

</div>	
	
	
<?php echo $this->load->view('site/footer',null,true);//End HTML ?>