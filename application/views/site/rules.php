<?php echo $this->load->view('site/header',null,true); //Begin HTML ?>
<?php echo $this->load->view('site/header_main_navigation',null,true); //Begin HTML ?>
<div id="main">

<div class="main-header">
	<h2>Rules</h2>
</div>

<div class="text">
<?=nl2br($rules)?>
</div>

</div>

<?php echo $this->load->view('site/footer',null,true);//End HTML ?>