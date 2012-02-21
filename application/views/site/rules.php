<?php $this->load->view('site/header'); //Begin HTML ?>
<div id="main">

<div class="main-header">
	<h2>Rules</h2>
</div>

<div class="text">
<?=nl2br($rules)?>
</div>

</div>
	
<?php $this->load->view('site/footer');//End HTML ?>