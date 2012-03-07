<?php echo $this->load->view('site/header',$campaign,true); //Begin HTML ?>

<div class="main">
<div class="text-format">
<h2>Terms & Conditions</h2>
<?php echo nl2br($rules);?>
</div>
</div>

<?php echo $this->load->view('site/footer',$campaign,true);//End HTML ?>