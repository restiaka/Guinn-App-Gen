<?php echo $this->load->view('mobile/mobile_header',$campaign,true); //Begin HTML ?>

<ul class="breadcrumb">
  <li><a href="<?=mobile_menu_url()?>" data-prefetch>Home</a></li>
  <li>Upload</li>
</ul>

<div class="main">
<div class="text-format">
<h2>Terms & Conditions</h2>
<?php echo nl2br($rules);?>
</div>
</div>

<?php echo $this->load->view('mobile/mobile_footer',$campaign,true); //End HTML ?>